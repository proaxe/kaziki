<?php
/**
 * HTML Renderer - Convert PHP Template to Static HTML
 * 
 * @package kaziki
 */

if (!defined('ABSPATH')) {
    exit;
}

class Kaziki_HTML_Renderer {
    
    private $build_id;
    private $build_dir;
    private $images = array();
    
    public function __construct($build_id) {
        $this->build_id = $build_id;
        $this->build_dir = Kaziki_Build_System::get_build_dir($build_id);
    }
    
    /**
     * Generate HTML build
     */
    public function generate() {
        try {
            // Create build directory
            Kaziki_Build_System::create_build_dir($this->build_id);
            
            // Update status
            Kaziki_Build_System::update_build_status($this->build_id, 'building');
            
            // Render HTML
            $html = $this->render_template();
            
            // Download and replace images
            $html = $this->process_images($html);
            
            // Save HTML file
            $html_file = $this->build_dir . '/index.html';
            file_put_contents($html_file, $html);
            
            // Update meta
            update_post_meta($this->build_id, '_build_path', $this->build_dir);
            Kaziki_Build_System::update_build_status($this->build_id, 'ready');
            
            return array(
                'success' => true,
                'message' => 'Build generated successfully',
                'path' => $this->build_dir,
                'url' => Kaziki_Build_System::get_build_url($this->build_id) . '/index.html'
            );
            
        } catch (Exception $e) {
            Kaziki_Build_System::update_build_status($this->build_id, 'error');
            return array(
                'success' => false,
                'message' => $e->getMessage()
            );
        }
    }
    
    /**
     * Render template to HTML
     */
    private function render_template() {
        // Get ACF fields from build post
        $fields = $this->get_build_fields();
        
        // Get option fields (shared across all builds)
        // NOTE: These are loaded fresh from database on each build
        $option_fields = $this->get_option_fields();
        
        // Get selected template
        $template = $fields['build_template'] ?? 'template1';
        
        // Start output buffering
        ob_start();
        
        // Render based on selected template
        switch ($template) {
            case 'template2':
                $this->render_template2($fields, $option_fields);
                break;
            case 'template1':
            default:
                $this->render_template1($fields, $option_fields);
                break;
        }
        
        // Get content
        $html = ob_get_clean();
        
        return $html;
    }
    
    /**
     * Render Template 1 (Default Casino Template)
     */
    private function render_template1($fields, $option_fields) {
        // Extract variables
        $link = $option_fields['ref_link'] ?? '#';
        $alt = $option_fields['alt_text'] ?? 'Image';
        
        // Color variables
        $color_main = $option_fields['main_color'] ?? '#0073aa';
        $color_second = $option_fields['second_color'] ?? '#f0f0f0';
        $color_text = $option_fields['text_color'] ?? '#333';
        $bg = $option_fields['bg_color'] ?? '#fff';
        $color_link = $option_fields['link_color'] ?? '#0073aa';
        $btn_bg = $option_fields['btn_color'] ?? '#0073aa';
        $btn_text = $option_fields['btn_color_text'] ?? '#fff';
        $btn2_bg = $option_fields['btn2_color'] ?? '#f0f0f0';
        $btn2_text = $option_fields['btn2_color_text'] ?? '#333';
        $table_odd = $option_fields['table_odd'] ?? '#f9f9f9';
        $table_even = $option_fields['table_even'] ?? '#fff';
        $border_color = $option_fields['border-colors'] ?? '#ddd';
        
        // Font family from Google Fonts selector
        $font_family = $option_fields['main_google_font'] ?? "'DM Sans:100,200,300,400,500,600,700|DM Sans'";
        // Extract font name from format: 'Font Name:weights|Font Name'
        preg_match("/\|([^']+)/", $font_family, $matches);
        $font_name = $matches[1] ?? 'DM Sans';
        
        // Page fields
        $title_page = $fields['title_page'] ?? 'Page Title';
        $description_page = $fields['description_page'] ?? 'Page Description';
        $canonical = $fields['canonical'] ?? '';
        $hero = $fields['hero'] ?? '';
        $category_list = $fields['category_list'] ?? array();
        $casino_list = $fields['casino_list'] ?? array();
        $content_text = $fields['content_text'] ?? '';
        $faq_items = $fields['faq_items'] ?? array();
        $deployment_type = $fields['deployment_type'] ?? 'pages_dev';
        
        // Load global settings for fallback
        $global_settings = $this->get_global_settings();
        
        // Option fields with fallback to global settings
        $logo = $option_fields['logo'] ?? '';
        $og_image = $option_fields['og_image'] ?? '';
        $favicon = $option_fields['favicon'] ?? '';
        $buttons = $option_fields['buttons'] ?? array();
        $menu = $option_fields['menu'] ?? array();
        
        // Fallback logic: if build field is empty, use global setting
        // Casino list
        if (empty($casino_list) && !empty($global_settings['casino_slots_list'])) {
            $casino_list = $global_settings['casino_slots_list'];
        }
        
        // Category list
        if (empty($category_list) && !empty($global_settings['category_list_global'])) {
            $category_list = $global_settings['category_list_global'];
        }
        
        // Menu footer
        $menu_footer = $option_fields['menu_footer'] ?? array();
        if (empty($menu_footer) && !empty($global_settings['menu_footer_global'])) {
            $menu_footer = $global_settings['menu_footer_global'];
        }
        
        // Payments
        $payments = $option_fields['payments'] ?? array();
        if (empty($payments) && !empty($global_settings['payments_global'])) {
            $payments = $global_settings['payments_global'];
        }
        
        // Partners
        $parthners = $option_fields['parthners'] ?? array();
        if (empty($parthners) && !empty($global_settings['partners_global'])) {
            $parthners = $global_settings['partners_global'];
        }
        
        // Randomize arrays
        if (!empty($casino_list) && is_array($casino_list)) {
            shuffle($casino_list);
        }
        if (!empty($category_list) && is_array($category_list)) {
            shuffle($category_list);
        }
        if (!empty($menu_footer) && is_array($menu_footer)) {
            shuffle($menu_footer);
        }
        if (!empty($payments) && is_array($payments)) {
            shuffle($payments);
        }
        if (!empty($parthners) && is_array($parthners)) {
            shuffle($parthners);
        }
        
        $all_right = $option_fields['all_right'] ?? '';
        
        // Debug: Log what we loaded
        error_log('=== OPTION FIELDS DEBUG ===');
        error_log('Logo: ' . ($logo ? 'YES' : 'NO'));
        error_log('Buttons count: ' . (is_array($buttons) ? count($buttons) : '0'));
        error_log('Menu count: ' . (is_array($menu) ? count($menu) : '0'));
        error_log('Payments count: ' . (is_array($payments) ? count($payments) : '0'));
        error_log('Casino list count: ' . (is_array($casino_list) ? count($casino_list) : '0'));
        
        // Current URL
        $current_url = $canonical ?: '#';
        
        // Include the template file
        include(dirname(__FILE__) . '/templates/template1-html.php');
    }
    
    /**
     * Render Template 2
     */
    private function render_template2($fields, $option_fields) {
        // Extract variables
        $link = $option_fields['ref_link'] ?? '#';
        $alt = $option_fields['alt_text'] ?? 'Image';
        
        // Color variables
        $color_main = $option_fields['main_color'] ?? '#0073aa';
        $color_second = $option_fields['second_color'] ?? '#f0f0f0';
        $color_text = $option_fields['text_color'] ?? '#333';
        $bg = $option_fields['bg_color'] ?? '#fff';
        $color_link = $option_fields['link_color'] ?? '#0073aa';
        $btn_bg = $option_fields['btn_color'] ?? '#0073aa';
        $btn_text = $option_fields['btn_color_text'] ?? '#fff';
        $btn2_bg = $option_fields['btn2_color'] ?? '#f0f0f0';
        $btn2_text = $option_fields['btn2_color_text'] ?? '#333';
        $table_odd = $option_fields['table_odd'] ?? '#f9f9f9';
        $table_even = $option_fields['table_even'] ?? '#fff';
        $border_color = $option_fields['border-colors'] ?? '#ddd';
        
        // Font family from Google Fonts selector
        $font_family = $option_fields['main_google_font'] ?? "'DM Sans:100,200,300,400,500,600,700|DM Sans'";
        // Extract font name from format: 'Font Name:weights|Font Name'
        preg_match("/\|([^']+)/", $font_family, $matches);
        $font_name = $matches[1] ?? 'DM Sans';
        
        // Page fields
        $title_page = $fields['title_page'] ?? 'Page Title';
        $description_page = $fields['description_page'] ?? 'Page Description';
        $canonical = $fields['canonical'] ?? '';
        $hero = $fields['hero'] ?? '';
        $category_list = $fields['category_list'] ?? array();
        $casino_list = $fields['casino_list'] ?? array();
        $content_text = $fields['content_text'] ?? '';
        $faq_items = $fields['faq_items'] ?? array();
        $deployment_type = $fields['deployment_type'] ?? 'pages_dev';
        
        // Load global settings for fallback
        $global_settings = $this->get_global_settings();
        
        // Option fields with fallback to global settings
        $logo = $option_fields['logo'] ?? '';
        $og_image = $option_fields['og_image'] ?? '';
        $favicon = $option_fields['favicon'] ?? '';
        $buttons = $option_fields['buttons'] ?? array();
        $menu = $option_fields['menu'] ?? array();
        
        // Fallback logic: if build field is empty, use global setting
        // Casino list
        if (empty($casino_list) && !empty($global_settings['casino_slots_list'])) {
            $casino_list = $global_settings['casino_slots_list'];
        }
        
        // Category list
        if (empty($category_list) && !empty($global_settings['category_list_global'])) {
            $category_list = $global_settings['category_list_global'];
        }
        
        // Menu footer
        $menu_footer = $option_fields['menu_footer'] ?? array();
        if (empty($menu_footer) && !empty($global_settings['menu_footer_global'])) {
            $menu_footer = $global_settings['menu_footer_global'];
        }
        
        // Payments
        $payments = $option_fields['payments'] ?? array();
        if (empty($payments) && !empty($global_settings['payments_global'])) {
            $payments = $global_settings['payments_global'];
        }
        
        // Partners
        $parthners = $option_fields['parthners'] ?? array();
        if (empty($parthners) && !empty($global_settings['partners_global'])) {
            $parthners = $global_settings['partners_global'];
        }
        
        // Randomize arrays
        if (!empty($casino_list) && is_array($casino_list)) {
            shuffle($casino_list);
        }
        if (!empty($category_list) && is_array($category_list)) {
            shuffle($category_list);
        }
        if (!empty($menu_footer) && is_array($menu_footer)) {
            shuffle($menu_footer);
        }
        if (!empty($payments) && is_array($payments)) {
            shuffle($payments);
        }
        if (!empty($parthners) && is_array($parthners)) {
            shuffle($parthners);
        }
        
        $all_right = $option_fields['all_right'] ?? '';
        
        // Debug: Log what we loaded
        error_log('=== TEMPLATE 2 OPTION FIELDS DEBUG ===');
        error_log('Logo: ' . ($logo ? 'YES' : 'NO'));
        error_log('Buttons count: ' . (is_array($buttons) ? count($buttons) : '0'));
        error_log('Menu count: ' . (is_array($menu) ? count($menu) : '0'));
        error_log('Payments count: ' . (is_array($payments) ? count($payments) : '0'));
        error_log('Casino list count: ' . (is_array($casino_list) ? count($casino_list) : '0'));
        
        // Current URL
        $current_url = $canonical ?: '#';
        
        // Include the template file
        include(dirname(__FILE__) . '/templates/template2-html.php');
    }
    
    /**
     * Get build fields
     */
    private function get_build_fields() {
        $fields = array(
            'build_template' => get_field('build_template', $this->build_id) ?: 'template1',
            'deployment_type' => get_field('deployment_type', $this->build_id) ?: 'pages_dev',
            'title_page' => get_field('title_page', $this->build_id),
            'description_page' => get_field('description_page', $this->build_id),
            'canonical' => get_field('canonical', $this->build_id),
            'hero' => get_field('hero', $this->build_id),
            'category_list' => get_field('category_list', $this->build_id),
            'casino_list' => get_field('casino_list', $this->build_id),
            'content_text' => get_field('content_text', $this->build_id),
            'faq_items' => get_field('faq_items', $this->build_id),
        );
        
        return $fields;
    }
    
    /**
     * Get global settings from Main Settings option page
     * These serve as fallback values for all templates
     */
    private function get_global_settings() {
        $post_id = 'option';
        
        $global_settings = array(
            'casino_slots_list' => get_field('casino_slots_list', $post_id),
            'category_list_global' => get_field('category_list_global', $post_id),
            'menu_footer_global' => get_field('menu_footer_global', $post_id),
            'payments_global' => get_field('payments_global', $post_id),
            'partners_global' => get_field('partners_global', $post_id),
        );
        
        return $global_settings;
    }
    
    /**
     * Get option fields (from theme settings)
     * 
     * NOTE: These fields are loaded fresh from the database on each build generation,
     * so any changes to option page fields will be reflected in new builds automatically.
     * 
     * Fields are loaded from template-specific option pages (template1-settings, template2-settings, etc.)
     */
    private function get_option_fields() {
        // Get selected template to determine which option page to use
        $build_fields = $this->get_build_fields();
        $template = $build_fields['build_template'] ?? 'template1';
        
        // IMPORTANT: ACF stores ALL option pages data under 'option' key
        // Even though we have separate Template 1 and Template 2 settings pages,
        // ACF uses the same 'option' post_id for all of them
        // The location rules only control WHERE fields are displayed in admin,
        // but the data is stored together under 'option'
        
        // This means Template 1 and Template 2 currently SHARE the same data
        // To fix this properly, we need to rename fields in ACF to be unique per template
        // For now, we load from 'option' which will work but both templates use same data
        
        $post_id = 'option';
        
        $fields = array(
            'ref_link' => get_field('ref_link', $post_id),
            'main_color' => get_field('main_color', $post_id),
            'second_color' => get_field('second_color', $post_id),
            'text_color' => get_field('text_color', $post_id),
            'bg_color' => get_field('bg_color', $post_id),
            'link_color' => get_field('link_color', $post_id),
            'alt_text' => get_field('alt_text', $post_id),
            'btn_color' => get_field('btn_color', $post_id),
            'btn_color_text' => get_field('btn_color_text', $post_id),
            'btn2_color' => get_field('btn2_color', $post_id),
            'btn2_color_text' => get_field('btn2_color_text', $post_id),
            'table_odd' => get_field('table_odd', $post_id),
            'table_even' => get_field('table_even', $post_id),
            'logo' => get_field('logo', $post_id),
            'og_image' => get_field('og_image', $post_id),
            'favicon' => get_field('favicon', $post_id),
            'buttons' => get_field('buttons', $post_id),
            'menu' => get_field('menu', $post_id),
            'payments' => get_field('payments', $post_id),
            'parthners' => get_field('parthners', $post_id),
            'menu_footer' => get_field('menu_footer', $post_id),
            'all_right' => get_field('all_right', $post_id),
            'border-colors' => get_field('border-colors', $post_id),
            'main_google_font' => get_field('main_google_font', $post_id),
        );
        
        return $fields;
    }
    
    /**
     * Process images - download and replace URLs
     */
    private function process_images($html) {
        error_log('=== PROCESSING IMAGES ===');
        
        // Find all image URLs - FIXED: removed space in regex
        preg_match_all('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $html, $matches);
        
        error_log('Found ' . count($matches[1]) . ' img tags');
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $image_url) {
                error_log('Processing image: ' . $image_url);
                $local_path = $this->download_image($image_url);
                if ($local_path) {
                    error_log('Downloaded to: ' . $local_path);
                    $html = str_replace($image_url, $local_path, $html);
                } else {
                    error_log('Failed to download: ' . $image_url);
                }
            }
        }
        
        // Find background images in style attributes
        preg_match_all('/url\(["\']?([^"\'()]+)["\']?\)/i', $html, $bg_matches);
        
        error_log('Found ' . count($bg_matches[1]) . ' background images');
        
        if (!empty($bg_matches[1])) {
            foreach ($bg_matches[1] as $image_url) {
                if (filter_var($image_url, FILTER_VALIDATE_URL)) {
                    error_log('Processing background: ' . $image_url);
                    $local_path = $this->download_image($image_url);
                    if ($local_path) {
                        $html = str_replace($image_url, $local_path, $html);
                    }
                }
            }
        }
        
        return $html;
    }
    
    /**
     * Download image and save locally
     */
    private function download_image($url) {
        // Skip if already downloaded
        if (isset($this->images[$url])) {
            return $this->images[$url];
        }
        
        // Skip data URLs
        if (strpos($url, 'data:') === 0) {
            return $url;
        }
        
        // Get file extension
        $path_info = pathinfo(parse_url($url, PHP_URL_PATH));
        $extension = isset($path_info['extension']) ? $path_info['extension'] : 'jpg';
        $filename_from_url = basename(parse_url($url, PHP_URL_PATH));
        
        // Generate unique filename
        $filename = md5($url) . '.' . $extension;
        $local_path = $this->build_dir . '/img/' . $filename;
        $relative_path = 'img/' . $filename;
        
        // Try to copy from local img directory first (for local development)
        $local_img_path = ABSPATH . 'img/' . $filename_from_url;
        
        if (file_exists($local_img_path)) {
            // Copy from local directory
            if (copy($local_img_path, $local_path)) {
                $this->images[$url] = $relative_path;
                error_log('Copied image from local: ' . $filename_from_url);
                return $relative_path;
            }
        }
        
        // Try to download from URL (for remote images)
        try {
            // Disable SSL verification for local development
            $context = stream_context_create(array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                )
            ));
            
            $image_data = file_get_contents($url, false, $context);
            if ($image_data !== false) {
                file_put_contents($local_path, $image_data);
                $this->images[$url] = $relative_path;
                error_log('Downloaded image from URL: ' . $url);
                return $relative_path;
            }
        } catch (Exception $e) {
            error_log('Failed to download image: ' . $url . ' - ' . $e->getMessage());
            // If download fails, keep original URL
            return $url;
        }
        
        return $url;
    }
    
    /**
     * Create ZIP archive
     */
    public function create_zip() {
        $zip_file = $this->build_dir . '.zip';
        
        if (file_exists($zip_file)) {
            unlink($zip_file);
        }
        
        $zip = new ZipArchive();
        if ($zip->open($zip_file, ZipArchive::CREATE) !== true) {
            return false;
        }
        
        // Add files to zip
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->build_dir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $file_path = $file->getRealPath();
                $relative_path = substr($file_path, strlen($this->build_dir) + 1);
                $zip->addFile($file_path, $relative_path);
            }
        }
        
        $zip->close();
        
        return $zip_file;
    }
}
