<?php
/**
 * GitHub API Deploy - Simplified version
 * Updates files directly in main branch instead of creating new branches
 * 
 * @package kaziki
 */

if (!defined('ABSPATH')) {
    exit;
}

class Kaziki_GitHub_API_Deploy {
    
    private $github_token;
    private $github_repo;
    private $github_owner;
    
    public function __construct() {
        // Load secrets if not already defined
        if (!defined('KAZIKI_GITHUB_TOKEN')) {
            $secrets_file = get_template_directory() . '/inc/secrets.php';
            if (file_exists($secrets_file)) {
                require_once $secrets_file;
            }
        }

        // GitHub credentials
        $this->github_token = defined('KAZIKI_GITHUB_TOKEN') ? KAZIKI_GITHUB_TOKEN : '';
        $this->github_owner = 'proaxe';
        $this->github_repo = 'kaziki';
        
        if (empty($this->github_token)) {
            error_log('GitHub Token is missing! Please check inc/secrets.php');
        }
    }
    
    /**
     * Deploy build to GitHub via API
     * Pushes to main branch with wrangler.toml for each build version
     */
    public function deploy_build($build_id) {
        try {
            $build_dir = Kaziki_Build_System::get_build_dir($build_id);
            
            if (!file_exists($build_dir . '/index.html')) {
                throw new Exception('Build not found. Please generate build first.');
            }
            
            // Get all files from build directory
            $files = $this->get_build_files($build_dir, $build_id);
            
            if (empty($files)) {
                throw new Exception('No files found in build directory.');
            }
            
            error_log("Found " . count($files) . " files to deploy");
            
            // Get main branch SHA
            $main_sha = $this->get_branch_sha('main');
            error_log("Main branch SHA: {$main_sha}");
            
            // Create tree with all files
            $tree_sha = $this->create_tree_with_files($files, $main_sha);
            error_log("Created tree SHA: {$tree_sha}");
            
            // Create commit
            $commit_message = "Deploy build {$build_id} - " . date('Y-m-d H:i:s');
            $commit_sha = $this->create_commit($tree_sha, $main_sha, $commit_message);
            error_log("Created commit SHA: {$commit_sha}");
            
            // Update main branch
            $this->update_branch('main', $commit_sha);
            error_log("Updated main branch");
            
            // Save deployment info
            update_post_meta($build_id, '_git_branch', 'main');
            update_post_meta($build_id, '_git_commit_hash', substr($commit_sha, 0, 7));
            update_post_meta($build_id, '_git_push_time', current_time('timestamp'));
            
            Kaziki_Build_System::update_build_status($build_id, 'pushed');
            
            return array(
                'success' => true,
                'message' => 'Build pushed to GitHub successfully!',
                'branch' => 'main',
                'commit_hash' => substr($commit_sha, 0, 7),
                'files_count' => count($files)
            );
            
        } catch (Exception $e) {
            error_log('GitHub API deployment error: ' . $e->getMessage());
            return array(
                'success' => false,
                'message' => $e->getMessage()
            );
        }
    }
    
    /**
     * Get all files from build directory
     */
    private function get_build_files($build_dir, $build_id) {
        $files = array();
        $base_path = 'wp-content/uploads/builds/version-' . $build_id;
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($build_dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $abs_path = $file->getPathname();
                $rel_path = substr($abs_path, strlen($build_dir) + 1);
                $rel_path = str_replace('\\', '/', $rel_path);
                
                $github_path = $base_path . '/' . $rel_path;
                
                $content = file_get_contents($abs_path);
                $files[$github_path] = $content;
            }
        }
        
        return $files;
    }
    
    /**
     * Get or create branch for build
     */
    private function get_or_create_branch($branch_name) {
        // Try to get existing branch
        try {
            $url = "https://api.github.com/repos/{$this->github_owner}/{$this->github_repo}/git/refs/heads/{$branch_name}";
            $response = $this->github_request($url, 'GET');
            
            if (!empty($response['object']['sha'])) {
                error_log("Branch {$branch_name} already exists");
                return $response['object']['sha'];
            }
        } catch (Exception $e) {
            // Branch doesn't exist, will create it
            error_log("Branch {$branch_name} doesn't exist, creating...");
        }
        
        // Get main branch SHA to create new branch from
        $main_sha = $this->get_branch_sha('main');
        
        // Create new branch
        $url = "https://api.github.com/repos/{$this->github_owner}/{$this->github_repo}/git/refs";
        $this->github_request($url, 'POST', array(
            'ref' => 'refs/heads/' . $branch_name,
            'sha' => $main_sha
        ));
        
        error_log("Created new branch {$branch_name}");
        return $main_sha;
    }
    
    /**
     * Get branch SHA
     */
    private function get_branch_sha($branch_name) {
        $url = "https://api.github.com/repos/{$this->github_owner}/{$this->github_repo}/git/refs/heads/{$branch_name}";
        $response = $this->github_request($url, 'GET');
        
        if (empty($response['object']['sha'])) {
            throw new Exception("Could not get {$branch_name} branch SHA");
        }
        
        return $response['object']['sha'];
    }
    
    /**
     * Create tree with files
     */
    private function create_tree_with_files($files, $base_sha) {
        $tree = array();
        
        foreach ($files as $path => $content) {
            // Create blob first
            $blob_sha = $this->create_blob($content);
            
            $tree[] = array(
                'path' => $path,
                'mode' => '100644',
                'type' => 'blob',
                'sha' => $blob_sha
            );
        }
        
        $url = "https://api.github.com/repos/{$this->github_owner}/{$this->github_repo}/git/trees";
        
        $response = $this->github_request($url, 'POST', array(
            'tree' => $tree,
            'base_tree' => $base_sha
        ));
        
        if (empty($response['sha'])) {
            throw new Exception('Could not create tree');
        }
        
        return $response['sha'];
    }
    
    /**
     * Create blob
     */
    private function create_blob($content) {
        $url = "https://api.github.com/repos/{$this->github_owner}/{$this->github_repo}/git/blobs";
        
        $response = $this->github_request($url, 'POST', array(
            'content' => base64_encode($content),
            'encoding' => 'base64'
        ));
        
        if (empty($response['sha'])) {
            throw new Exception('Could not create blob');
        }
        
        return $response['sha'];
    }
    
    /**
     * Create commit
     */
    private function create_commit($tree_sha, $parent_sha, $message) {
        $url = "https://api.github.com/repos/{$this->github_owner}/{$this->github_repo}/git/commits";
        
        $response = $this->github_request($url, 'POST', array(
            'message' => $message,
            'tree' => $tree_sha,
            'parents' => array($parent_sha)
        ));
        
        if (empty($response['sha'])) {
            throw new Exception('Could not create commit');
        }
        
        return $response['sha'];
    }
    
    /**
     * Update branch reference
     */
    private function update_branch($branch_name, $commit_sha) {
        $url = "https://api.github.com/repos/{$this->github_owner}/{$this->github_repo}/git/refs/heads/{$branch_name}";
        
        $this->github_request($url, 'PATCH', array(
            'sha' => $commit_sha,
            'force' => true
        ));
    }
    
    /**
     * Make GitHub API request
     */
    private function github_request($url, $method = 'GET', $data = null) {
        $args = array(
            'method' => $method,
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->github_token,
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'Kaziki-Build-System'
            ),
            'timeout' => 60
        );
        
        if ($data !== null) {
            $args['body'] = json_encode($data);
            $args['headers']['Content-Type'] = 'application/json';
        }
        
        error_log("GitHub API Request: {$method} {$url}");
        
        $response = wp_remote_request($url, $args);
        
        if (is_wp_error($response)) {
            throw new Exception('GitHub API request failed: ' . $response->get_error_message());
        }
        
        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $result = json_decode($body, true);
        
        error_log("GitHub API Response Code: {$code}");
        
        if ($code < 200 || $code >= 300) {
            $error_msg = $result['message'] ?? 'Unknown error';
            $error_detail = isset($result['errors']) ? json_encode($result['errors']) : '';
            error_log("GitHub API Error: {$error_msg} | Details: {$error_detail}");
            throw new Exception("GitHub API error ({$code}): {$error_msg}");
        }
        
        return $result;
    }
}
