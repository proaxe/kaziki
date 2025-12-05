<?php
/**
 * Build System - Custom Post Type and Core Functions
 * 
 * @package kaziki
 */

if (!defined('ABSPATH')) {
    exit;
}

class Kaziki_Build_System {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
    }
    
    /**
     * Register Custom Post Type for HTML Builds
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => 'HTML Builds',
            'singular_name'         => 'HTML Build',
            'menu_name'             => 'HTML Builds',
            'add_new'               => 'Add New Build',
            'add_new_item'          => 'Add New Build',
            'edit_item'             => 'Edit Build',
            'new_item'              => 'New Build',
            'view_item'             => 'View Build',
            'search_items'          => 'Search Builds',
            'not_found'             => 'No builds found',
            'not_found_in_trash'    => 'No builds found in trash',
        );
        
        $args = array(
            'labels'                => $labels,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_icon'             => 'dashicons-admin-site-alt3',
            'capability_type'       => 'post',
            'hierarchical'          => false,
            'supports'              => array('title'),
            'has_archive'           => false,
            'rewrite'               => false,
            'query_var'             => false,
        );
        
        register_post_type('html_build', $args);
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'build_actions',
            'Build Actions',
            array($this, 'render_actions_meta_box'),
            'html_build',
            'side',
            'high'
        );
        
        add_meta_box(
            'build_info',
            'Build Information',
            array($this, 'render_info_meta_box'),
            'html_build',
            'side',
            'default'
        );
        
        add_meta_box(
            'config_snapshots',
            'Configuration Snapshots',
            array($this, 'render_snapshots_meta_box'),
            'html_build',
            'normal',
            'default'
        );
    }
    
    /**
     * Render actions meta box
     */
    public function render_actions_meta_box($post) {
        $build_status = get_post_meta($post->ID, '_build_status', true);
        $build_path = get_post_meta($post->ID, '_build_path', true);
        $deployment_url = get_post_meta($post->ID, '_cloudflare_deployment_url', true);
        
        ?>
        <div class="build-actions">
            <p>
                <button type="button" class="button button-primary button-large" id="generate-build" data-build-id="<?php echo esc_attr($post->ID); ?>">
                    <span class="dashicons dashicons-update"></span> Generate Build
                </button>
            </p>
            
            <?php if ($build_path && file_exists($build_path . '/index.html')): ?>
                <p>
                    <button type="button" class="button button-secondary button-large" id="rebuild-build" data-build-id="<?php echo esc_attr($post->ID); ?>">
                        <span class="dashicons dashicons-backup"></span> Rebuild
                    </button>
                </p>
                
                <p>
                    <a href="<?php echo esc_url(content_url('uploads/builds/version-' . $post->ID . '/index.html')); ?>" class="button button-large" target="_blank">
                        <span class="dashicons dashicons-visibility"></span> Preview
                    </a>
                </p>
                
                <p>
                    <button type="button" class="button button-large" id="download-build" data-build-id="<?php echo esc_attr($post->ID); ?>">
                        <span class="dashicons dashicons-download"></span> Download ZIP
                    </button>
                </p>
            <?php endif; ?>
            
            <hr>
            
            <p>
                <button type="button" class="button button-primary button-large" id="git-push-deploy" data-build-id="<?php echo esc_attr($post->ID); ?>" <?php echo (!$build_path) ? 'disabled' : ''; ?>>
                    <span class="dashicons dashicons-cloud-upload"></span> Deploy via GitHub
                </button>
            </p>
            
            <?php if ($deployment_url): ?>
                <p>
                    <a href="<?php echo esc_url($deployment_url); ?>" class="button button-large" target="_blank">
                        <span class="dashicons dashicons-external"></span> View Deployment
                    </a>
                </p>
            <?php endif; ?>
            
            <div id="build-progress" style="display:none; margin-top: 15px;">
                <p><strong>Status:</strong> <span id="build-status-text">Processing...</span></p>
                <div style="background: #f0f0f0; border-radius: 3px; overflow: hidden;">
                    <div id="build-progress-bar" style="background: #0073aa; height: 20px; width: 0%; transition: width 0.3s;"></div>
                </div>
            </div>
        </div>
        
        <style>
            .build-actions .button {
                width: 100%;
                text-align: center;
                height: auto;
                padding: 8px 12px;
            }
            .build-actions .dashicons {
                margin-top: 3px;
            }
        </style>
        <?php
    }
    
    /**
     * Render info meta box
     */
    public function render_info_meta_box($post) {
        $build_status = get_post_meta($post->ID, '_build_status', true);
        $build_date = get_post_meta($post->ID, '_build_date', true);
        $build_path = get_post_meta($post->ID, '_build_path', true);
        $deployment_id = get_post_meta($post->ID, '_cloudflare_deployment_id', true);
        
        $status_labels = array(
            'draft' => 'Draft',
            'building' => 'Building...',
            'ready' => 'Ready',
            'deployed' => 'Deployed',
            'error' => 'Error'
        );
        
        $status_colors = array(
            'draft' => '#999',
            'building' => '#0073aa',
            'ready' => '#46b450',
            'deployed' => '#00a32a',
            'error' => '#dc3232'
        );
        
        $current_status = $build_status ?: 'draft';
        ?>
        <div class="build-info">
            <p>
                <strong>Status:</strong><br>
                <span style="display: inline-block; padding: 3px 8px; background: <?php echo esc_attr($status_colors[$current_status]); ?>; color: white; border-radius: 3px; font-size: 12px;">
                    <?php echo esc_html($status_labels[$current_status]); ?>
                </span>
            </p>
            
            <?php if ($build_date): ?>
                <p>
                    <strong>Last Build:</strong><br>
                    <?php echo esc_html(date('Y-m-d H:i:s', $build_date)); ?>
                </p>
            <?php endif; ?>
            
            <?php if ($build_path): ?>
                <p>
                    <strong>Build Path:</strong><br>
                    <code style="font-size: 11px; word-break: break-all;"><?php echo esc_html($build_path); ?></code>
                </p>
            <?php endif; ?>
            
            <?php if ($deployment_id): ?>
                <p>
                    <strong>Deployment ID:</strong><br>
                    <code style="font-size: 11px;"><?php echo esc_html($deployment_id); ?></code>
                </p>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * Render configuration snapshots meta box
     */
    public function render_snapshots_meta_box($post) {
        $snapshots = Kaziki_Config_Snapshots::get_snapshots($post->ID);
        ?>
        <div class="config-snapshots">
            <!-- Save New Snapshot -->
            <div class="snapshot-save-form" style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 4px;">
                <h4 style="margin-top: 0;">Save Current Configuration</h4>
                <p>
                    <label for="snapshot-name"><strong>Snapshot Name:</strong></label><br>
                    <input type="text" id="snapshot-name" class="widefat" placeholder="e.g., Blue Theme Config">
                </p>
                <p>
                    <label for="snapshot-description"><strong>Description (Optional):</strong></label><br>
                    <textarea id="snapshot-description" class="widefat" rows="2" placeholder="Brief description of this configuration..."></textarea>
                </p>
                <p>
                    <button type="button" class="button button-primary" id="save-snapshot" data-build-id="<?php echo esc_attr($post->ID); ?>">
                        <span class="dashicons dashicons-download" style="margin-top: 3px;"></span> Save Snapshot
                    </button>
                </p>
            </div>
            
            <!-- Import Snapshot -->
            <div class="snapshot-import-form" style="margin-bottom: 20px; padding: 15px; background: #f0f8ff; border: 1px solid #b3d9ff; border-radius: 4px;">
                <h4 style="margin-top: 0;">Import Snapshot</h4>
                <p>
                    <input type="file" id="snapshot-file" accept=".json">
                </p>
                <p>
                    <button type="button" class="button" id="import-snapshot" data-build-id="<?php echo esc_attr($post->ID); ?>">
                        <span class="dashicons dashicons-upload" style="margin-top: 3px;"></span> Import from JSON
                    </button>
                </p>
            </div>
            
            <!-- Snapshots List -->
            <h4>Saved Snapshots (<?php echo count($snapshots); ?>)</h4>
            
            <?php if (empty($snapshots)): ?>
                <p style="color: #666; font-style: italic;">No snapshots saved yet. Save your first configuration above!</p>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped" style="margin-top: 10px;">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Name</th>
                            <th style="width: 30%;">Description</th>
                            <th style="width: 20%;">Date</th>
                            <th style="width: 20%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($snapshots as $snapshot): ?>
                            <tr>
                                <td><strong><?php echo esc_html($snapshot['name']); ?></strong></td>
                                <td><?php echo esc_html($snapshot['description'] ?: '—'); ?></td>
                                <td><?php echo esc_html(date('Y-m-d H:i', $snapshot['timestamp'])); ?></td>
                                <td>
                                    <button type="button" class="button button-small restore-snapshot" 
                                            data-build-id="<?php echo esc_attr($post->ID); ?>" 
                                            data-snapshot-id="<?php echo esc_attr($snapshot['id']); ?>"
                                            data-snapshot-name="<?php echo esc_attr($snapshot['name']); ?>">
                                        <span class="dashicons dashicons-backup" style="margin-top: 3px;"></span> Restore
                                    </button>
                                    
                                    <a href="<?php echo esc_url(admin_url('admin-ajax.php?action=kaziki_export_snapshot&build_id=' . $post->ID . '&snapshot_id=' . $snapshot['id'] . '&nonce=' . wp_create_nonce('kaziki_build_nonce'))); ?>" 
                                       class="button button-small">
                                        <span class="dashicons dashicons-download" style="margin-top: 3px;"></span> Export
                                    </a>
                                    
                                    <button type="button" class="button button-small delete-snapshot" 
                                            data-build-id="<?php echo esc_attr($post->ID); ?>" 
                                            data-snapshot-id="<?php echo esc_attr($snapshot['id']); ?>"
                                            data-snapshot-name="<?php echo esc_attr($snapshot['name']); ?>"
                                            style="color: #a00;">
                                        <span class="dashicons dashicons-trash" style="margin-top: 3px;"></span> Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            
            <div id="snapshot-status" style="display:none; margin-top: 15px; padding: 10px; border-radius: 4px;"></div>
        </div>
        
        <style>
            .config-snapshots .dashicons {
                font-size: 16px;
                width: 16px;
                height: 16px;
            }
            .config-snapshots .button-small {
                padding: 0 8px;
                height: 26px;
                line-height: 24px;
                font-size: 12px;
            }
            #snapshot-status.success {
                background: #d4edda;
                border: 1px solid #c3e6cb;
                color: #155724;
            }
            #snapshot-status.error {
                background: #f8d7da;
                border: 1px solid #f5c6cb;
                color: #721c24;
            }
        </style>
        <?php
    }
    
    /**
     * Get build directory path
     */
    public static function get_build_dir($build_id) {
        // Use wp-content/uploads/builds instead of the custom img directory
        $upload_base = WP_CONTENT_DIR . '/uploads/builds';
        return $upload_base . '/version-' . $build_id;
    }
    
    /**
     * Get build URL
     */
    public static function get_build_url($build_id) {
        // Use wp-content/uploads/builds URL
        $upload_base_url = content_url('uploads/builds');
        return $upload_base_url . '/version-' . $build_id;
    }
    
    /**
     * Create build directory
     */
    public static function create_build_dir($build_id) {
        $build_dir = self::get_build_dir($build_id);
        
        // Delete existing build directory to ensure fresh files (no cache)
        if (file_exists($build_dir)) {
            self::delete_build_dir($build_dir);
        }
        
        // Create fresh directories
        wp_mkdir_p($build_dir);
        wp_mkdir_p($build_dir . '/img');
        
        return $build_dir;
    }
    
    /**
     * Delete build directory recursively
     */
    private static function delete_build_dir($dir) {
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
            
            if (!self::delete_build_dir($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        
        return rmdir($dir);
    }
    
    /**
     * Update build status
     */
    public static function update_build_status($build_id, $status) {
        update_post_meta($build_id, '_build_status', $status);
        
        if ($status === 'ready' || $status === 'deployed') {
            update_post_meta($build_id, '_build_date', time());
        }
    }
}

// Initialize
Kaziki_Build_System::get_instance();
