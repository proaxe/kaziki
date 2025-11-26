<?php
/**
 * AJAX Handlers for Build System
 * 
 * @package kaziki
 */

if (!defined('ABSPATH')) {
    exit;
}

class Kaziki_Ajax_Handlers {
    
    public function __construct() {
        // AJAX actions
        add_action('wp_ajax_kaziki_generate_build', array($this, 'generate_build'));
        add_action('wp_ajax_kaziki_rebuild', array($this, 'rebuild'));
        add_action('wp_ajax_kaziki_deploy_cloudflare', array($this, 'deploy_cloudflare'));
        add_action('wp_ajax_kaziki_download_build', array($this, 'download_build'));
        add_action('wp_ajax_kaziki_delete_build', array($this, 'delete_build'));
        add_action('wp_ajax_kaziki_verify_cloudflare', array($this, 'verify_cloudflare'));
        
        // Configuration Snapshot actions
        add_action('wp_ajax_kaziki_save_snapshot', array($this, 'save_snapshot'));
        add_action('wp_ajax_kaziki_restore_snapshot', array($this, 'restore_snapshot'));
        add_action('wp_ajax_kaziki_delete_snapshot', array($this, 'delete_snapshot'));
        add_action('wp_ajax_kaziki_export_snapshot', array($this, 'export_snapshot'));
        add_action('wp_ajax_kaziki_import_snapshot', array($this, 'import_snapshot'));
    }
    
    /**
     * Generate new build
     */
    public function generate_build() {
        // Log the request
        error_log('=== BUILD GENERATION STARTED ===');
        error_log('POST data: ' . print_r($_POST, true));
        
        try {
            check_ajax_referer('kaziki_build_nonce', 'nonce');
        } catch (Exception $e) {
            error_log('Nonce check failed: ' . $e->getMessage());
            wp_send_json_error(array('message' => 'Security check failed'));
            return;
        }
        
        if (!current_user_can('edit_posts')) {
            error_log('Permission denied for user');
            wp_send_json_error(array('message' => 'Permission denied'));
            return;
        }
        
        $build_id = intval($_POST['build_id']);
        error_log('Build ID: ' . $build_id);
        
        if (!$build_id) {
            error_log('Invalid build ID');
            wp_send_json_error(array('message' => 'Invalid build ID'));
            return;
        }
        
        // Check if post exists
        $post = get_post($build_id);
        if (!$post) {
            error_log('Post not found for ID: ' . $build_id);
            wp_send_json_error(array('message' => 'Build post not found'));
            return;
        }
        
        error_log('Post found: ' . $post->post_title);
        
        // Generate build
        try {
            error_log('Creating HTML Renderer...');
            $renderer = new Kaziki_HTML_Renderer($build_id);
            
            error_log('Calling generate()...');
            $result = $renderer->generate();
            
            error_log('Generate result: ' . print_r($result, true));
            
            if ($result['success']) {
                error_log('=== BUILD GENERATION SUCCESSFUL ===');
                wp_send_json_success($result);
            } else {
                error_log('=== BUILD GENERATION FAILED ===');
                error_log('Error: ' . $result['message']);
                wp_send_json_error($result);
            }
        } catch (Exception $e) {
            error_log('=== BUILD GENERATION EXCEPTION ===');
            error_log('Exception: ' . $e->getMessage());
            error_log('Trace: ' . $e->getTraceAsString());
            wp_send_json_error(array('message' => 'Exception: ' . $e->getMessage()));
        }
    }
    
    /**
     * Rebuild existing build
     */
    public function rebuild() {
        check_ajax_referer('kaziki_build_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => 'Permission denied'));
        }
        
        $build_id = intval($_POST['build_id']);
        
        if (!$build_id) {
            wp_send_json_error(array('message' => 'Invalid build ID'));
        }
        
        // Clean old build
        $build_dir = Kaziki_Build_System::get_build_dir($build_id);
        if (file_exists($build_dir)) {
            $this->delete_directory($build_dir);
        }
        
        // Generate new build
        $renderer = new Kaziki_HTML_Renderer($build_id);
        $result = $renderer->generate();
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * Deploy to Cloudflare Pages
     */
    public function deploy_cloudflare() {
        check_ajax_referer('kaziki_build_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => 'Permission denied'));
        }
        
        $build_id = intval($_POST['build_id']);
        
        if (!$build_id) {
            wp_send_json_error(array('message' => 'Invalid build ID'));
        }
        
        // Deploy
        try {
            $deployer = new Kaziki_Cloudflare_Deploy();
            $result = $deployer->deploy($build_id);
            
            if ($result['success']) {
                wp_send_json_success($result);
            } else {
                wp_send_json_error($result);
            }
        } catch (Exception $e) {
            error_log('Deployment Exception: ' . $e->getMessage());
            wp_send_json_error(array('message' => $e->getMessage()));
        }
    }
    
    /**
     * Download build as ZIP
     */
    public function download_build() {
        check_ajax_referer('kaziki_build_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die('Permission denied');
        }
        
        $build_id = intval($_GET['build_id']);
        
        if (!$build_id) {
            wp_die('Invalid build ID');
        }
        
        // Create ZIP
        $renderer = new Kaziki_HTML_Renderer($build_id);
        $zip_file = $renderer->create_zip();
        
        if (!$zip_file || !file_exists($zip_file)) {
            wp_die('Failed to create ZIP file');
        }
        
        // Send file
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="build-' . $build_id . '.zip"');
        header('Content-Length: ' . filesize($zip_file));
        readfile($zip_file);
        
        // Clean up
        unlink($zip_file);
        exit;
    }
    
    /**
     * Delete build
     */
    public function delete_build() {
        check_ajax_referer('kaziki_build_nonce', 'nonce');
        
        if (!current_user_can('delete_posts')) {
            wp_send_json_error(array('message' => 'Permission denied'));
        }
        
        $build_id = intval($_POST['build_id']);
        
        if (!$build_id) {
            wp_send_json_error(array('message' => 'Invalid build ID'));
        }
        
        // Delete build directory
        $build_dir = Kaziki_Build_System::get_build_dir($build_id);
        if (file_exists($build_dir)) {
            $this->delete_directory($build_dir);
        }
        
        // Delete post
        wp_delete_post($build_id, true);
        
        wp_send_json_success(array('message' => 'Build deleted successfully'));
    }
    
    /**
     * Verify Cloudflare token
     */
    public function verify_cloudflare() {
        check_ajax_referer('kaziki_build_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Permission denied'));
        }
        
        $deployer = new Kaziki_Cloudflare_Deploy();
        $result = $deployer->verify_token();
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * Save configuration snapshot
     */
    public function save_snapshot() {
        check_ajax_referer('kaziki_build_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => 'Permission denied'));
        }
        
        $build_id = intval($_POST['build_id']);
        $name = sanitize_text_field($_POST['name']);
        $description = sanitize_textarea_field($_POST['description']);
        
        if (!$build_id) {
            wp_send_json_error(array('message' => 'Invalid build ID'));
        }
        
        if (empty($name)) {
            wp_send_json_error(array('message' => 'Snapshot name is required'));
        }
        
        $result = Kaziki_Config_Snapshots::save_snapshot($build_id, $name, $description);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * Restore configuration snapshot
     */
    public function restore_snapshot() {
        check_ajax_referer('kaziki_build_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => 'Permission denied'));
        }
        
        $build_id = intval($_POST['build_id']);
        $snapshot_id = sanitize_text_field($_POST['snapshot_id']);
        
        if (!$build_id || !$snapshot_id) {
            wp_send_json_error(array('message' => 'Invalid parameters'));
        }
        
        $result = Kaziki_Config_Snapshots::restore_snapshot($build_id, $snapshot_id);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * Delete configuration snapshot
     */
    public function delete_snapshot() {
        check_ajax_referer('kaziki_build_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => 'Permission denied'));
        }
        
        $build_id = intval($_POST['build_id']);
        $snapshot_id = sanitize_text_field($_POST['snapshot_id']);
        
        if (!$build_id || !$snapshot_id) {
            wp_send_json_error(array('message' => 'Invalid parameters'));
        }
        
        $result = Kaziki_Config_Snapshots::delete_snapshot($build_id, $snapshot_id);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * Export configuration snapshot
     */
    public function export_snapshot() {
        check_ajax_referer('kaziki_build_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die('Permission denied');
        }
        
        $build_id = intval($_GET['build_id']);
        $snapshot_id = sanitize_text_field($_GET['snapshot_id']);
        
        if (!$build_id || !$snapshot_id) {
            wp_die('Invalid parameters');
        }
        
        $result = Kaziki_Config_Snapshots::export_snapshot($build_id, $snapshot_id);
        
        if (!$result['success']) {
            wp_die($result['message']);
        }
        
        // Send JSON file
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $result['filename'] . '"');
        echo $result['json'];
        exit;
    }
    
    /**
     * Import configuration snapshot
     */
    public function import_snapshot() {
        check_ajax_referer('kaziki_build_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => 'Permission denied'));
        }
        
        $build_id = intval($_POST['build_id']);
        
        if (!$build_id) {
            wp_send_json_error(array('message' => 'Invalid build ID'));
        }
        
        if (!isset($_FILES['snapshot_file'])) {
            wp_send_json_error(array('message' => 'No file uploaded'));
        }
        
        $file = $_FILES['snapshot_file'];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            wp_send_json_error(array('message' => 'File upload error'));
        }
        
        $json_data = file_get_contents($file['tmp_name']);
        
        $result = Kaziki_Config_Snapshots::import_snapshot($build_id, $json_data);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * Delete directory recursively
     */
    private function delete_directory($dir) {
        if (!file_exists($dir)) {
            return true;
        }
        
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            
            if (!$this->delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        
        return rmdir($dir);
    }
}

// Initialize
new Kaziki_Ajax_Handlers();
