<?php
/**
 * Cloudflare Pages Deployment via Wrangler CLI
 * 
 * @package kaziki
 */

if (!defined('ABSPATH')) {
    exit;
}

class Kaziki_Cloudflare_Deploy {
    
    private $api_token;
    private $account_id;
    private $project_name;
    
    public function __construct() {
        // Cloudflare credentials
        $this->api_token = '5YA9j_EauFJP772qcvl_Rzsktgk2Bpr3oSiLjQkX';
        $this->account_id = '925262faebbd07d9f3636c6a04c61b6d';
        $this->project_name = 'burky-cz';
    }
    
    /**
     * Deploy build to Cloudflare Pages using Direct Upload API
     * This method uses native PHP and does not require Wrangler/Node.js
     */
    public function deploy($build_id) {
        try {
            // Get build directory
            $build_dir = Kaziki_Build_System::get_build_dir($build_id);
            
            if (!file_exists($build_dir . '/index.html')) {
                throw new Exception('Build not found. Please generate build first.');
            }
            
            // Get domain from build settings
            $domain = get_field('build_domain', $build_id);
            if (!$domain) {
                $domain = $this->project_name;
            }
            
            // 1. Collect all files
            $files = $this->get_recursive_files($build_dir);
            
            if (empty($files)) {
                throw new Exception('No files found to deploy.');
            }
            
            // 2. Prepare multipart boundary
            // Use only alphanumeric characters for boundary to avoid parsing issues
            $boundary = '----CloudflareDeployBoundary' . md5(time());
            $headers = array(
                'Authorization' => 'Bearer ' . $this->api_token,
                'Content-Type' => 'multipart/form-data; boundary=' . $boundary,
            );
            
            // 3. Build body content manually
            $body = '';
            $file_count = 0;
            $manifest = array();
            
            // Calculate hashes first
            foreach ($files as $rel_path => $abs_path) {
                $content = file_get_contents($abs_path);
                $deploy_path = '/' . ltrim($rel_path, '/');
                $hash = hash('sha256', $content);
                $manifest[$deploy_path] = $hash;
            }
            
            // Add manifest FIRST (to ensure it's found)
            $body .= "--" . $boundary . "\r\n";
            $body .= 'Content-Disposition: form-data; name="manifest"' . "\r\n";
            $body .= "Content-Type: application/json\r\n\r\n";
            $body .= json_encode($manifest) . "\r\n";
            
            // Add files
            foreach ($files as $rel_path => $abs_path) {
                $content = file_get_contents($abs_path);
                $deploy_path = '/' . ltrim($rel_path, '/');
                
                // Detect MIME type
                $ext = strtolower(pathinfo($abs_path, PATHINFO_EXTENSION));
                $mime_type = 'application/octet-stream';
                
                switch ($ext) {
                    case 'html':
                    case 'htm':
                        $mime_type = 'text/html';
                        break;
                    case 'css':
                        $mime_type = 'text/css';
                        break;
                    case 'js':
                        $mime_type = 'application/javascript';
                        break;
                    case 'json':
                        $mime_type = 'application/json';
                        break;
                    case 'png':
                        $mime_type = 'image/png';
                        break;
                    case 'jpg':
                    case 'jpeg':
                        $mime_type = 'image/jpeg';
                        break;
                    case 'gif':
                        $mime_type = 'image/gif';
                        break;
                    case 'svg':
                        $mime_type = 'image/svg+xml';
                        break;
                    case 'ico':
                        $mime_type = 'image/x-icon';
                        break;
                    case 'webp':
                        $mime_type = 'image/webp';
                        break;
                    case 'txt':
                        $mime_type = 'text/plain';
                        break;
                    case 'xml':
                        $mime_type = 'application/xml';
                        break;
                }
                
                $body .= "--" . $boundary . "\r\n";
                $body .= 'Content-Disposition: form-data; name="files[' . $deploy_path . ']"; filename="' . basename($abs_path) . '"' . "\r\n";
                $body .= "Content-Type: " . $mime_type . "\r\n\r\n";
                $body .= $content . "\r\n";
                
                $file_count++;
            }
            
            $body .= "--" . $boundary . "--\r\n";
            
            // 4. Send Request
            $url = "https://api.cloudflare.com/client/v4/accounts/{$this->account_id}/pages/projects/{$this->project_name}/deployments";
            
            // Increase timeout for upload
            $args = array(
                'headers' => $headers,
                'body' => $body,
                'method' => 'POST',
                'timeout' => 120, // 2 minutes
                'redirection' => 5,
                'blocking' => true,
            );
            
            $response = wp_remote_post($url, $args);
            
            if (is_wp_error($response)) {
                throw new Exception('API Request Failed: ' . $response->get_error_message());
            }
            
            $response_code = wp_remote_retrieve_response_code($response);
            $response_body = wp_remote_retrieve_body($response);
            $result = json_decode($response_body, true);
            
            if ($response_code !== 200 || empty($result['success'])) {
                $error_msg = $result['errors'][0]['message'] ?? 'Unknown API error';
                throw new Exception('Cloudflare API Error (' . $response_code . '): ' . $error_msg);
            }
            
            // 5. Success - Parse details
            $deployment_info = $result['result'];
            $deployment_url = $deployment_info['url'];
            $deployment_id = $deployment_info['id'];
            
            // Update build meta
            update_post_meta($build_id, '_cloudflare_deployment_id', $deployment_id);
            update_post_meta($build_id, '_cloudflare_deployment_url', $deployment_url);
            Kaziki_Build_System::update_build_status($build_id, 'deployed');
            
            return array(
                'success' => true,
                'message' => "Successfully deployed {$file_count} files to Cloudflare Pages!",
                'url' => $deployment_url,
                'deployment_id' => $deployment_id,
                'output' => 'API Upload Complete'
            );
            
        } catch (Exception $e) {
            error_log('Deployment error: ' . $e->getMessage());
            return array(
                'success' => false,
                'message' => $e->getMessage()
            );
        }
    }
    
    /**
     * Helper to get all files recursively
     */
    private function get_recursive_files($dir) {
        $files = array();
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                // Get relative path
                $abs_path = $file->getPathname();
                $rel_path = substr($abs_path, strlen($dir));
                // Normalize slashes
                $rel_path = str_replace('\\', '/', $rel_path);
                $files[$rel_path] = $abs_path;
            }
        }
        
        return $files;
    }

    /**
     * Verify API token
     */
    public function verify_token() {
        $url = 'https://api.cloudflare.com/client/v4/user/tokens/verify';
        
        $response = wp_remote_get($url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->api_token,
            ),
            'timeout' => 15,
        ));
        
        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'message' => $response->get_error_message()
            );
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body['success']) && $body['success']) {
            return array(
                'success' => true,
                'message' => 'Token is valid',
                'user' => $body['result'] ?? null
            );
        }
        
        return array(
            'success' => false,
            'message' => 'Invalid token'
        );
    }
}

