<?php
/**
 * Admin Build Interface
 * 
 * @package kaziki
 */

if (!defined('ABSPATH')) {
    exit;
}

class Kaziki_Admin_Build_Interface {
    
    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_scripts($hook) {
        global $post_type;
        
        // Only load on html_build post type pages
        if ($post_type !== 'html_build') {
            return;
        }
        
        // Enqueue JavaScript
        wp_enqueue_script(
            'kaziki-admin-build',
            get_template_directory_uri() . '/js/admin-build.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        // Localize script
        wp_localize_script('kaziki-admin-build', 'kazikiBuild', array(
            'nonce' => wp_create_nonce('kaziki_build_nonce'),
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));
        
        // Enqueue CSS
        wp_enqueue_style(
            'kaziki-admin-build',
            get_template_directory_uri() . '/css/admin-build.css',
            array(),
            '1.0.0'
        );
    }
}

// Initialize
new Kaziki_Admin_Build_Interface();
