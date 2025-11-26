<?php
/**
 * Configuration Snapshots - Save and Restore Build Configurations
 * 
 * @package kaziki
 */

if (!defined('ABSPATH')) {
    exit;
}

class Kaziki_Config_Snapshots {
    
    /**
     * Save current build configuration as a snapshot
     * 
     * @param int $build_id Build post ID
     * @param string $name Snapshot name
     * @param string $description Optional description
     * @return array Result with success status and snapshot ID
     */
    public static function save_snapshot($build_id, $name, $description = '') {
        if (!$build_id || !get_post($build_id)) {
            return array(
                'success' => false,
                'message' => 'Invalid build ID'
            );
        }
        
        if (empty($name)) {
            return array(
                'success' => false,
                'message' => 'Snapshot name is required'
            );
        }
        
        // Get all ACF fields for this build
        $acf_fields = array(
            // Build Settings
            'build_template' => get_field('build_template', $build_id),
            'build_domain' => get_field('build_domain', $build_id),
            'deployment_type' => get_field('deployment_type', $build_id),
            
            // SEO Settings
            'title_page' => get_field('title_page', $build_id),
            'description_page' => get_field('description_page', $build_id),
            'canonical' => get_field('canonical', $build_id),
            
            // Hero Section
            'hero' => get_field('hero', $build_id),
            
            // Category List
            'category_list' => get_field('category_list', $build_id),
            
            // Casino List
            'casino_list' => get_field('casino_list', $build_id),
            
            // Main Content
            'content_text' => get_field('content_text', $build_id),
            
            // FAQ Items
            'faq_items' => get_field('faq_items', $build_id),
        );
        
        // Get appearance settings from options
        $appearance_settings = array(
            'logo' => get_field('logo', 'option'),
            'favicon' => get_field('favicon', 'option'),
            'og_image' => get_field('og_image', 'option'),
            'ref_link' => get_field('ref_link', 'option'),
            'alt_text' => get_field('alt_text', 'option'),
            
            // Colors
            'main_color' => get_field('main_color', 'option'),
            'second_color' => get_field('second_color', 'option'),
            'text_color' => get_field('text_color', 'option'),
            'bg_color' => get_field('bg_color', 'option'),
            'link_color' => get_field('link_color', 'option'),
            'border-colors' => get_field('border-colors', 'option'),
            
            // Buttons
            'btn_color' => get_field('btn_color', 'option'),
            'btn_color_text' => get_field('btn_color_text', 'option'),
            'btn2_color' => get_field('btn2_color', 'option'),
            'btn2_color_text' => get_field('btn2_color_text', 'option'),
            
            // Tables
            'table_odd' => get_field('table_odd', 'option'),
            'table_even' => get_field('table_even', 'option'),
            
            // Menu and Buttons
            'menu' => get_field('menu', 'option'),
            'buttons' => get_field('buttons', 'option'),
            'menu_footer' => get_field('menu_footer', 'option'),
            
            // Footer
            'payments' => get_field('payments', 'option'),
            'parthners' => get_field('parthners', 'option'),
            'all_right' => get_field('all_right', 'option'),
        );
        
        // Create snapshot data
        $snapshot = array(
            'id' => uniqid('snapshot_'),
            'name' => sanitize_text_field($name),
            'description' => sanitize_textarea_field($description),
            'timestamp' => current_time('timestamp'),
            'acf_fields' => $acf_fields,
            'appearance_settings' => $appearance_settings,
        );
        
        // Get existing snapshots
        $snapshots = get_post_meta($build_id, '_config_snapshots', true);
        if (!is_array($snapshots)) {
            $snapshots = array();
        }
        
        // Add new snapshot
        $snapshots[] = $snapshot;
        
        // Save to post meta
        update_post_meta($build_id, '_config_snapshots', $snapshots);
        
        return array(
            'success' => true,
            'message' => 'Configuration snapshot saved successfully',
            'snapshot_id' => $snapshot['id'],
            'snapshot' => $snapshot
        );
    }
    
    /**
     * Restore a configuration snapshot
     * 
     * @param int $build_id Build post ID
     * @param string $snapshot_id Snapshot ID to restore
     * @return array Result with success status
     */
    public static function restore_snapshot($build_id, $snapshot_id) {
        if (!$build_id || !get_post($build_id)) {
            return array(
                'success' => false,
                'message' => 'Invalid build ID'
            );
        }
        
        // Get snapshots
        $snapshots = get_post_meta($build_id, '_config_snapshots', true);
        if (!is_array($snapshots)) {
            return array(
                'success' => false,
                'message' => 'No snapshots found'
            );
        }
        
        // Find the snapshot
        $snapshot = null;
        foreach ($snapshots as $s) {
            if ($s['id'] === $snapshot_id) {
                $snapshot = $s;
                break;
            }
        }
        
        if (!$snapshot) {
            return array(
                'success' => false,
                'message' => 'Snapshot not found'
            );
        }
        
        // Restore ACF fields
        if (isset($snapshot['acf_fields']) && is_array($snapshot['acf_fields'])) {
            foreach ($snapshot['acf_fields'] as $field_name => $field_value) {
                update_field($field_name, $field_value, $build_id);
            }
        }
        
        // Restore appearance settings
        if (isset($snapshot['appearance_settings']) && is_array($snapshot['appearance_settings'])) {
            foreach ($snapshot['appearance_settings'] as $field_name => $field_value) {
                update_field($field_name, $field_value, 'option');
            }
        }
        
        return array(
            'success' => true,
            'message' => 'Configuration restored successfully',
            'snapshot' => $snapshot
        );
    }
    
    /**
     * Get all snapshots for a build
     * 
     * @param int $build_id Build post ID
     * @return array List of snapshots
     */
    public static function get_snapshots($build_id) {
        $snapshots = get_post_meta($build_id, '_config_snapshots', true);
        
        if (!is_array($snapshots)) {
            return array();
        }
        
        // Sort by timestamp (newest first)
        usort($snapshots, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });
        
        return $snapshots;
    }
    
    /**
     * Delete a snapshot
     * 
     * @param int $build_id Build post ID
     * @param string $snapshot_id Snapshot ID to delete
     * @return array Result with success status
     */
    public static function delete_snapshot($build_id, $snapshot_id) {
        if (!$build_id || !get_post($build_id)) {
            return array(
                'success' => false,
                'message' => 'Invalid build ID'
            );
        }
        
        // Get snapshots
        $snapshots = get_post_meta($build_id, '_config_snapshots', true);
        if (!is_array($snapshots)) {
            return array(
                'success' => false,
                'message' => 'No snapshots found'
            );
        }
        
        // Filter out the snapshot to delete
        $updated_snapshots = array_filter($snapshots, function($s) use ($snapshot_id) {
            return $s['id'] !== $snapshot_id;
        });
        
        // Re-index array
        $updated_snapshots = array_values($updated_snapshots);
        
        // Update post meta
        update_post_meta($build_id, '_config_snapshots', $updated_snapshots);
        
        return array(
            'success' => true,
            'message' => 'Snapshot deleted successfully'
        );
    }
    
    /**
     * Export snapshot as JSON
     * 
     * @param int $build_id Build post ID
     * @param string $snapshot_id Snapshot ID to export
     * @return array Result with JSON data
     */
    public static function export_snapshot($build_id, $snapshot_id) {
        $snapshots = get_post_meta($build_id, '_config_snapshots', true);
        
        if (!is_array($snapshots)) {
            return array(
                'success' => false,
                'message' => 'No snapshots found'
            );
        }
        
        // Find the snapshot
        $snapshot = null;
        foreach ($snapshots as $s) {
            if ($s['id'] === $snapshot_id) {
                $snapshot = $s;
                break;
            }
        }
        
        if (!$snapshot) {
            return array(
                'success' => false,
                'message' => 'Snapshot not found'
            );
        }
        
        return array(
            'success' => true,
            'json' => json_encode($snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            'filename' => sanitize_file_name($snapshot['name']) . '_' . date('Y-m-d', $snapshot['timestamp']) . '.json'
        );
    }
    
    /**
     * Import snapshot from JSON
     * 
     * @param int $build_id Build post ID
     * @param string $json_data JSON string
     * @return array Result with success status
     */
    public static function import_snapshot($build_id, $json_data) {
        if (!$build_id || !get_post($build_id)) {
            return array(
                'success' => false,
                'message' => 'Invalid build ID'
            );
        }
        
        // Decode JSON
        $snapshot = json_decode($json_data, true);
        
        if (!$snapshot || !is_array($snapshot)) {
            return array(
                'success' => false,
                'message' => 'Invalid JSON data'
            );
        }
        
        // Validate snapshot structure
        if (!isset($snapshot['acf_fields']) || !isset($snapshot['appearance_settings'])) {
            return array(
                'success' => false,
                'message' => 'Invalid snapshot structure'
            );
        }
        
        // Generate new ID and timestamp
        $snapshot['id'] = uniqid('snapshot_');
        $snapshot['timestamp'] = current_time('timestamp');
        $snapshot['name'] = isset($snapshot['name']) ? $snapshot['name'] . ' (Imported)' : 'Imported Snapshot';
        
        // Get existing snapshots
        $snapshots = get_post_meta($build_id, '_config_snapshots', true);
        if (!is_array($snapshots)) {
            $snapshots = array();
        }
        
        // Add imported snapshot
        $snapshots[] = $snapshot;
        
        // Save to post meta
        update_post_meta($build_id, '_config_snapshots', $snapshots);
        
        return array(
            'success' => true,
            'message' => 'Snapshot imported successfully',
            'snapshot_id' => $snapshot['id']
        );
    }
}
