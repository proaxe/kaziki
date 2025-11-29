<?php
/**
 * Git Auto-Commit and Push for Builds
 * 
 * @package kaziki
 */

if (!defined('ABSPATH')) {
    exit;
}

class Kaziki_Git_Deploy {
    
    private $repo_path;
    private $git_executable;
    
    public function __construct() {
        $this->repo_path = ABSPATH;
        // Try to find git executable
        $this->git_executable = $this->find_git();
    }
    
    /**
     * Find git executable
     */
    private function find_git() {
        // Common paths for git (Windows paths first for OSPanel)
        $possible_paths = array(
            'C:\\Program Files\\Git\\bin\\git.exe',
            'C:\\Program Files (x86)\\Git\\bin\\git.exe',
            'git', // If in PATH
            '/usr/bin/git',
            '/usr/local/bin/git',
        );
        
        foreach ($possible_paths as $path) {
            // For Windows paths, check if file exists
            if (strpos($path, ':') !== false) {
                if (file_exists($path)) {
                    return $path;
                }
            } else {
                // For PATH commands, try to execute
                $test = @shell_exec($path . ' --version 2>&1');
                if ($test && strpos($test, 'git version') !== false) {
                    return $path;
                }
            }
        }
        
        return 'git'; // Default fallback
    }
    
    /**
     * Execute git command
     */
    private function exec_git($command) {
        $full_command = 'cd "' . $this->repo_path . '" && ' . $this->git_executable . ' ' . $command . ' 2>&1';
        $output = shell_exec($full_command);
        return $output;
    }
    
    /**
     * Commit and push build to GitHub on separate branch
     */
    public function commit_and_push_build($build_id) {
        try {
            $build_dir = Kaziki_Build_System::get_build_dir($build_id);
            
            if (!file_exists($build_dir . '/index.html')) {
                throw new Exception('Build not found. Please generate build first.');
            }
            
            // Get relative path from repo root
            $rel_build_dir = str_replace($this->repo_path, '', $build_dir);
            $rel_build_dir = str_replace('\\', '/', trim($rel_build_dir, '/\\'));
            
            // Create branch name from build ID
            $branch_name = 'build-' . $build_id;
            
            // 1. Checkout to new branch (create if doesn't exist)
            $current_branch = trim($this->exec_git('rev-parse --abbrev-ref HEAD'));
            
            // Check if branch exists
            $branch_exists = $this->exec_git('rev-parse --verify ' . $branch_name . ' 2>&1');
            
            if (strpos($branch_exists, 'fatal') !== false) {
                // Branch doesn't exist, create it
                $this->exec_git('checkout -b ' . $branch_name);
            } else {
                // Branch exists, switch to it
                $this->exec_git('checkout ' . $branch_name);
            }
            
            // 2. Add build files to git
            $add_output = $this->exec_git('add "' . $rel_build_dir . '/"');
            
            // 3. Check if there are changes to commit
            $status = $this->exec_git('status --porcelain');
            
            if (empty(trim($status))) {
                // Switch back to original branch
                $this->exec_git('checkout ' . $current_branch);
                
                return array(
                    'success' => true,
                    'message' => 'No changes to commit. Build already up to date.',
                    'skipped' => true,
                    'branch' => $branch_name
                );
            }
            
            // 4. Commit
            $commit_message = 'Deploy build ' . $build_id . ' - ' . date('Y-m-d H:i:s');
            $commit_output = $this->exec_git('commit -m "' . $commit_message . '"');
            
            // 5. Push to origin with branch name
            $push_output = $this->exec_git('push origin ' . $branch_name . ' --force');
            
            // Check for errors
            if (strpos($push_output, 'error') !== false || strpos($push_output, 'fatal') !== false) {
                throw new Exception('Git push failed: ' . $push_output);
            }
            
            // 6. Get commit hash
            $commit_hash = trim($this->exec_git('rev-parse HEAD'));
            
            // 7. Switch back to original branch
            $this->exec_git('checkout ' . $current_branch);
            
            // 8. Save deployment info
            update_post_meta($build_id, '_git_commit_hash', $commit_hash);
            update_post_meta($build_id, '_git_branch', $branch_name);
            update_post_meta($build_id, '_git_push_time', current_time('timestamp'));
            
            // 9. Generate Cloudflare Pages preview URL
            $preview_url = 'https://' . $branch_name . '.burky-cz.pages.dev';
            update_post_meta($build_id, '_cloudflare_deployment_url', $preview_url);
            
            return array(
                'success' => true,
                'message' => 'Build committed and pushed to GitHub successfully!',
                'branch' => $branch_name,
                'commit_hash' => substr($commit_hash, 0, 7),
                'preview_url' => $preview_url,
                'output' => $push_output
            );
            
        } catch (Exception $e) {
            error_log('Git deployment error: ' . $e->getMessage());
            return array(
                'success' => false,
                'message' => $e->getMessage()
            );
        }
    }
    
    /**
     * Get GitHub Actions workflow status
     */
    public function get_workflow_status($build_id) {
        $commit_hash = get_post_meta($build_id, '_git_commit_hash', true);
        
        if (!$commit_hash) {
            return array(
                'success' => false,
                'message' => 'No deployment found'
            );
        }
        
        $deployment_url = get_post_meta($build_id, '_cloudflare_deployment_url', true);
        
        return array(
            'success' => true,
            'commit_hash' => substr($commit_hash, 0, 7),
            'deployment_url' => $deployment_url,
            'status' => $deployment_url ? 'deployed' : 'pending'
        );
    }
    
    /**
     * Check if git is available
     */
    public function is_git_available() {
        $version = $this->exec_git('--version');
        return strpos($version, 'git version') !== false;
    }
}
