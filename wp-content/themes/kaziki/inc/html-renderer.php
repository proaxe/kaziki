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
            case 'template4':
                $this->render_template4($fields, $option_fields);
                break;
            case 'template3':
                $this->render_template3($fields, $option_fields);
                break;
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
        $link = $option_fields['t1_ref_link'] ?? '#';
        $alt = $option_fields['t1_alt_text'] ?? 'Image';
        
        // Color variables
        $color_main = $option_fields['t1_main_color'] ?? '#0073aa';
        $color_second = $option_fields['t1_second_color'] ?? '#f0f0f0';
        $color_text = $option_fields['t1_text_color'] ?? '#333';
        $bg = $option_fields['t1_bg_color'] ?? '#fff';
        $color_link = $option_fields['t1_link_color'] ?? '#0073aa';
        $btn_bg = $option_fields['t1_btn_color'] ?? '#0073aa';
        $btn_text = $option_fields['t1_btn_color_text'] ?? '#fff';
        $btn2_bg = $option_fields['t1_btn2_color'] ?? '#f0f0f0';
        $btn2_text = $option_fields['t1_btn2_color_text'] ?? '#333';
        $table_odd = $option_fields['t1_table_odd'] ?? '#f9f9f9';
        $table_even = $option_fields['t1_table_even'] ?? '#fff';
        $border_color = $option_fields['t1_border_colors'] ?? '#ddd';
        
        // Font family from Google Fonts selector
        $font_family = $option_fields['t1_main_google_font'] ?? "'DM Sans:100,200,300,400,500,600,700|DM Sans'";
        // Extract font name from format: 'Font Name:weights|Font Name'
        preg_match("/\\|([^']+)/", $font_family, $matches);
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
        $logo = $option_fields['t1_logo'] ?? '';
        $og_image = $option_fields['t1_og_image'] ?? '';
        $favicon = $option_fields['t1_favicon'] ?? '';
        $buttons = $option_fields['t1_buttons'] ?? array();
        $menu = $option_fields['t1_menu'] ?? array();
        $header_main = $option_fields['t1_header_main'] ?? '';
        $html_lang = $fields['html_lang'] ?? '';
        
        // Fallback logic: if build field is empty, use global setting
        // Casino list
        if (empty($casino_list) && !empty($global_settings['casino_list_global'])) {
            $casino_list = $global_settings['casino_list_global'];
        }
        
        // Category list
        if (empty($category_list) && !empty($global_settings['category_list_global'])) {
            $category_list = $global_settings['category_list_global'];
        }
        
        // Menu footer
        $menu_footer = $option_fields['t1_menu_footer'] ?? array();
        if (empty($menu_footer) && !empty($global_settings['menu_footer_global'])) {
            $menu_footer = $global_settings['menu_footer_global'];
        }
        
        // Payments
        $payments = $option_fields['t1_payments'] ?? array();
        if (empty($payments) && !empty($global_settings['payments_global'])) {
            $payments = $global_settings['payments_global'];
        }
        
        // Partners
        $parthners = $option_fields['t1_parthners'] ?? array();
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
        
        $all_right = $option_fields['t1_all_right'] ?? '';
        
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
        $link = $option_fields['t2_ref_link'] ?? '#';
        $alt = $option_fields['t2_alt_text'] ?? 'Image';
        
        // Color variables
        $color_main = $option_fields['t2_main_color'] ?? '#0073aa';
        $color_second = $option_fields['t2_second_color'] ?? '#f0f0f0';
        $color_text = $option_fields['t2_text_color'] ?? '#333';
        $bg = $option_fields['t2_bg_color'] ?? '#fff';
        $color_link = $option_fields['t2_link_color'] ?? '#0073aa';
        $btn_bg = $option_fields['t2_btn_color'] ?? '#0073aa';
        $btn_text = $option_fields['t2_btn_color_text'] ?? '#fff';
        $btn2_bg = $option_fields['t2_btn2_color'] ?? '#f0f0f0';
        $btn2_text = $option_fields['t2_btn2_color_text'] ?? '#333';
        $table_odd = $option_fields['t2_table_odd'] ?? '#f9f9f9';
        $table_even = $option_fields['t2_table_even'] ?? '#fff';
        $border_color = $option_fields['t2_border_colors'] ?? '#ddd';
        
        // Font family from Google Fonts selector
        $font_family = $option_fields['t2_main_google_font'] ?? "'DM Sans:100,200,300,400,500,600,700|DM Sans'";
        // Extract font name from format: 'Font Name:weights|Font Name'
        preg_match("/\\|([^']+)/", $font_family, $matches);
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
        $logo = $option_fields['t2_logo'] ?? '';
        $og_image = $option_fields['t2_og_image'] ?? '';
        $favicon = $option_fields['t2_favicon'] ?? '';
        $buttons = $option_fields['t2_buttons'] ?? array();
        $menu = $option_fields['t2_menu'] ?? array();
        $header_main = $option_fields['t2_header_main'] ?? '';
        $html_lang = $fields['html_lang'] ?? '';
        
        // Fallback logic: if build field is empty, use global setting
        // Casino list (slots for template 2)
        if (empty($casino_list) && !empty($global_settings['slot_list_global'])) {
            $casino_list = $global_settings['slot_list_global'];
        }
        
        // Category list
        if (empty($category_list) && !empty($global_settings['category_list_global'])) {
            $category_list = $global_settings['category_list_global'];
        }
        
        // Menu footer
        $menu_footer = $option_fields['t2_menu_footer'] ?? array();
        if (empty($menu_footer) && !empty($global_settings['menu_footer_global'])) {
            $menu_footer = $global_settings['menu_footer_global'];
        }
        
        // Payments
        $payments = $option_fields['t2_payments'] ?? array();
        if (empty($payments) && !empty($global_settings['payments_global'])) {
            $payments = $global_settings['payments_global'];
        }
        
        // Partners
        $parthners = $option_fields['t2_parthners'] ?? array();
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
        
        $all_right = $option_fields['t2_all_right'] ?? '';
        
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
     * Render Template 3
     */
    private function render_template3($fields, $option_fields) {
        // Extract variables
        $link = $option_fields['t3_ref_link'] ?? '#';
        $alt = $option_fields['t3_alt_text'] ?? 'Image';
        
        // Color variables
        $color_main = $option_fields['t3_main_color'] ?? '#0073aa';
        $color_second = $option_fields['t3_second_color'] ?? '#f0f0f0';
        $color_text = $option_fields['t3_text_color'] ?? '#333';
        $bg = $option_fields['t3_bg_color'] ?? '#fff';
        $color_link = $option_fields['t3_link_color'] ?? '#0073aa';
        $btn_bg = $option_fields['t3_btn_color'] ?? '#0073aa';
        $btn_text = $option_fields['t3_btn_color_text'] ?? '#fff';
        $btn2_bg = $option_fields['t3_btn2_color'] ?? '#f0f0f0';
        $btn2_text = $option_fields['t3_btn2_color_text'] ?? '#333';
        $table_odd = $option_fields['t3_table_odd'] ?? '#f9f9f9';
        $table_even = $option_fields['t3_table_even'] ?? '#fff';
        $border_color = $option_fields['t3_border_colors'] ?? '#ddd';
        $color_title = $option_fields['t3_color_title'] ?? $color_text;
        $position_title = $option_fields['t3_position_title'] ?? 'left';
        
        // Font family from Google Fonts selector
        $font_family = $option_fields['t3_main_google_font'] ?? "'DM Sans:100,200,300,400,500,600,700|DM Sans'";
        // Extract font name from format: 'Font Name:weights|Font Name'
        preg_match("/\\|([^']+)/", $font_family, $matches);
        $font_name = $matches[1] ?? 'DM Sans';
        
        // Page fields
        $title_page = $fields['title_page'] ?? 'Page Title';
        $description_page = $fields['description_page'] ?? 'Page Description';
        $canonical = $fields['canonical'] ?? '';
        $hero = $fields['hero'] ?? '';
        $hero_title = $fields['hero_title'] ?? '';
        $hero_subtitle = $fields['hero_subtitle'] ?? '';
        $hero_rating = $fields['hero_rating'] ?? '';
        $category_list = $fields['category_list'] ?? array();
        $casino_list = $fields['casino_list'] ?? array();
        $content_text = $fields['content_text'] ?? '';
        $main_content = $fields['main_content'] ?? array();
        $faq_items = $fields['faq_items'] ?? array();
        $deployment_type = $fields['deployment_type'] ?? 'pages_dev';
        
        // Load global settings for fallback
        $global_settings = $this->get_global_settings();
        
        // Option fields with fallback to global settings
        $logo = $option_fields['t3_logo'] ?? '';
        $og_image = $option_fields['t3_og_image'] ?? '';
        $favicon = $option_fields['t3_favicon'] ?? '';
        $buttons = $option_fields['t3_buttons'] ?? array();
        $menu = $option_fields['t3_menu'] ?? array();
        $header_main = $option_fields['t3_header_main'] ?? '';
        $html_lang = $fields['html_lang'] ?? '';
        $hero_button_text = $option_fields['t3_hero_button_text'] ?? '';
        
        // Fallback logic: if build field is empty, use global setting
        // Casino list (slots for template 3)
        if (empty($casino_list) && !empty($global_settings['slot_list_global'])) {
            $casino_list = $global_settings['slot_list_global'];
        }
        
        // Category list
        if (empty($category_list) && !empty($global_settings['category_list_global'])) {
            $category_list = $global_settings['category_list_global'];
        }
        
        // Menu footer
        $menu_footer = $option_fields['t3_menu_footer'] ?? array();
        if (empty($menu_footer) && !empty($global_settings['menu_footer_global'])) {
            $menu_footer = $global_settings['menu_footer_global'];
        }
        
        // Payments
        $payments = $option_fields['t3_payments'] ?? array();
        if (empty($payments) && !empty($global_settings['payments_global'])) {
            $payments = $global_settings['payments_global'];
        }
        
        // Partners
        $parthners = $option_fields['t3_parthners'] ?? array();
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
        
        $all_right = $option_fields['t3_all_right'] ?? '';
        
        // Debug: Log what we loaded
        error_log('=== TEMPLATE 3 OPTION FIELDS DEBUG ===');
        error_log('Logo: ' . ($logo ? 'YES' : 'NO'));
        error_log('Buttons count: ' . (is_array($buttons) ? count($buttons) : '0'));
        error_log('Menu count: ' . (is_array($menu) ? count($menu) : '0'));
        error_log('Payments count: ' . (is_array($payments) ? count($payments) : '0'));
        error_log('Casino list count: ' . (is_array($casino_list) ? count($casino_list) : '0'));
        error_log('Hero title: ' . ($hero_title ? 'YES' : 'NO'));
        error_log('Hero subtitle: ' . ($hero_subtitle ? 'YES' : 'NO'));
        error_log('Hero rating: ' . ($hero_rating ? 'YES' : 'NO'));
        error_log('Main content blocks: ' . (is_array($main_content) ? count($main_content) : '0'));
        
        // Current URL
        $current_url = $canonical ?: '#';
        
        // Include the template file
        include(dirname(__FILE__) . '/templates/template3-html.php');
    }

    /**
     * Render Template 4
     */
    private function render_template4($fields, $option_fields) {
        // Extract variables
        $link = $option_fields['t4_ref_link'] ?? '#';
        $alt = $option_fields['t4_alt_text'] ?? 'Image';
        
        // Color variables
        $color_main = $option_fields['t4_main_color'] ?? '#0073aa';
        $color_second = $option_fields['t4_second_color'] ?? '#f0f0f0';
        $color_text = $option_fields['t4_text_color'] ?? '#333';
        $bg = $option_fields['t4_bg_color'] ?? '#fff';
        $color_link = $option_fields['t4_link_color'] ?? '#0073aa';
        $btn_bg = $option_fields['t4_btn_color'] ?? '#0073aa';
        $btn_text = $option_fields['t4_btn_color_text'] ?? '#fff';
        $btn2_bg = $option_fields['t4_btn2_color'] ?? '#f0f0f0';
        $btn2_text = $option_fields['t4_btn2_color_text'] ?? '#333';
        $table_odd = $option_fields['t4_table_odd'] ?? '#f9f9f9';
        $table_even = $option_fields['t4_table_even'] ?? '#fff';
        $border_color = $option_fields['t4_border_colors'] ?? '#ddd';
        
        // Font family from Google Fonts selector
        $font_family = $option_fields['t4_main_google_font'] ?? "'DM Sans:100,200,300,400,500,600,700|DM Sans'";
        // Extract font name from format: 'Font Name:weights|Font Name'
        preg_match("/\\|([^']+)/", $font_family, $matches);
        $font_name = $matches[1] ?? 'DM Sans';
        
        // Page fields
        $title_page = $fields['title_page'] ?? 'Page Title';
        $description_page = $fields['description_page'] ?? 'Page Description';
        $canonical = $fields['canonical'] ?? '';
        $content_text = $fields['content_text'] ?? '';
        $faq_items = $fields['faq_items'] ?? array();
        $deployment_type = $fields['deployment_type'] ?? 'pages_dev';
        
        // Load global settings for casino items
        $global_settings = $this->get_global_settings();
        
        // Option fields from Template 4 Settings
        $logo = $option_fields['t4_logo'] ?? '';
        $og_image = $option_fields['t4_og_image'] ?? '';
        $favicon = $option_fields['t4_favicon'] ?? '';
        $menu = $option_fields['t4_menu'] ?? array();
        $menu_items = is_array($menu) ? $menu : array();
        $header_main = $option_fields['t4_header_main'] ?? '';
        $html_lang = $fields['html_lang'] ?? '';
        
        // Colors (Redundant re-assignment removed, using the ones above)
        $cs_bg = $option_fields['cs_bg'] ?? '#28a745';
        $cs_color = $option_fields['cs_color'] ?? '#ffffff';
        $casino_text_color = $option_fields['t4_casino_text_color'] ?? '#333';
        $casino_card_bg = $option_fields['t4_casino_card_bg'] ?? '#ffffff';
        $table_odd = $option_fields['t4_table_odd'] ?? '#f9f9f9';
        $table_even = $option_fields['t4_table_even'] ?? '#ffffff';
        
        // Fonts
        $h1 = $option_fields['h1'] ?? '2';
        $h1_size = $option_fields['h1'] ?? '2';
        $h2_size = $option_fields['h2'] ?? '1.5';
        $h3_size = $option_fields['h3'] ?? '1.25';
        $h4_size = $option_fields['h4'] ?? '1';
        $p_size = $option_fields['p'] ?? '16';
        $li_size = $option_fields['li'] ?? '16';
        
        // Footer
        $footer_text = $option_fields['footer_text'] ?? '';
        $footer_color = $option_fields['footer_color'] ?? '#333333';
        $footer_color_text = $option_fields['footer_color_text'] ?? '#ffffff';
        $footer_menu1 = $option_fields['footer_menu1'] ?? array();
        $footer_menu2 = $option_fields['footer_menu2'] ?? array();
        $all_right = $option_fields['all_right'] ?? '';
        
        // Fixed Casino Desktop
        $enable_disable = $option_fields['enable_disable'] ?? array();
        $fixed_logo = $option_fields['fixed_logo'] ?? '';
        $bg_fixed = $option_fields['bg_fixed'] ?? '#1a1a1a';
        $fixed_title = $option_fields['fixed_title'] ?? '';
        $fixed_subtitle = $option_fields['fixed_subtitle'] ?? '';
        $fixed_link = $option_fields['fixed_link'] ?? '';
        $btn_name = $option_fields['btn_name'] ?? 'Play Now';
        $fixed_btn_bg = $option_fields['fixed_btn_bg'] ?? '#28a745';
        
        // Fixed Casino Mobile
        $enable_disable_mobile = $option_fields['enable_disable_mobile'] ?? array();
        $fixed_logo_mobile = $option_fields['fixed_logo_mobile'] ?? '';
        $bg_fixed_mobile = $option_fields['bg_fixed_mobile'] ?? '#1a1a1a';
        $fixed_title_mobile = $option_fields['fixed_title_mobile'] ?? '';
        $fixed_subtitle_mobile = $option_fields['fixed_subtitle_mobile'] ?? '';
        $fixed_link_mobile = $option_fields['fixed_link_mobile'] ?? '';
        $btn_name_mobile = $option_fields['btn_name_mobile'] ?? 'Play Now';
        $fixed_btn_bg_mobile = $option_fields['fixed_btn_bg_mobile'] ?? '#28a745';
        
        // FAQ
        $faq_color = $option_fields['faq_color'] ?? '#007bff';
        $faq_bg = $option_fields['faq_bg'] ?? '#eeeff4';
        
        // Author
        $author_name = $option_fields['author_name'] ?? '';
        $author_avatar = $option_fields['author_avatar'] ?? '';
        $author_color = $option_fields['author_color'] ?? '#f3f3f3';
        
        // FAQ List (from build fields)
        $faq_list = $faq_items;
        
        // IMPORTANT: Template 4 now loads casino items from its own settings tab
        // This replaces the previous dependency on Main Settings
        $casino_list = $option_fields['t4_casino_list'] ?? array();
        
        // Fallback to global settings if local list is empty (optional, but good for transition)
        if (empty($casino_list)) {
             $casino_list = $global_settings['casino_slots_list'] ?? array();
        }
        
        // Menu footer (fallback to global if empty)
        $menu_footer = $option_fields['menu_footer'] ?? array();
        if (empty($menu_footer) && !empty($global_settings['menu_footer_global'])) {
            $menu_footer = $global_settings['menu_footer_global'];
        }
        
        // Randomize arrays
        if (!empty($casino_list) && is_array($casino_list)) {
            shuffle($casino_list);
        }
        if (!empty($menu_footer) && is_array($menu_footer)) {
            shuffle($menu_footer);
        }
        
        // Debug: Log what we loaded
        error_log('=== TEMPLATE 4 OPTION FIELDS DEBUG ===');
        error_log('Logo: ' . ($logo ? 'YES' : 'NO'));
        error_log('Menu count: ' . (is_array($menu) ? count($menu) : '0'));
        error_log('Casino list count (from Main Settings): ' . (is_array($casino_list) ? count($casino_list) : '0'));
        error_log('Fixed casino desktop enabled: ' . (is_array($enable_disable) && in_array('Enable', $enable_disable) ? 'YES' : 'NO'));
        error_log('Fixed casino mobile enabled: ' . (is_array($enable_disable_mobile) && in_array('Enable', $enable_disable_mobile) ? 'YES' : 'NO'));
        
        // Current URL
        $current_url = $canonical ?: '#';
        
        // Include the template file
        include(dirname(__FILE__) . '/templates/template4-html.php');
    }


    
    /**
     * Get build fields
     */
    private function get_build_fields() {
        $fields = array(
            'build_template' => get_field('build_template', $this->build_id) ?: 'template1',
            'deployment_type' => get_field('deployment_type', $this->build_id) ?: 'pages_dev',
            'html_lang' => get_field('html_lang', $this->build_id),
            'title_page' => get_field('title_page', $this->build_id),
            'description_page' => get_field('description_page', $this->build_id),
            'canonical' => get_field('canonical', $this->build_id),
            'hero' => get_field('hero', $this->build_id),
            'hero_title' => get_field('hero_title', $this->build_id),
            'hero_subtitle' => get_field('hero_subtitle', $this->build_id),
            'hero_rating' => get_field('hero_rating', $this->build_id),
            'category_list' => get_field('category_list', $this->build_id),
            'casino_list' => get_field('casino_list', $this->build_id),
            'content_text' => get_field('content_text', $this->build_id),
            'main_content' => get_field('main_content', $this->build_id),
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
            'slot_list_global' => get_field('slot_list_global', $post_id),
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
            'header-main' => get_field('header-main', $post_id),
            'color-title' => get_field('color-title', $post_id),
            'position-title' => get_field('position-title', $post_id),
            'hero_button_text' => get_field('hero_button_text', $post_id),
        
        // Template 4 Specific Fields
        'header_position' => get_field('t4_header_position', $post_id),
        'header_color' => get_field('t4_header_color', $post_id),
        'header_main' => get_field('t4_header_main', $post_id),
        't4_logo' => get_field('t4_logo', $post_id),
        't4_favicon' => get_field('t4_favicon', $post_id),
        't4_og_image' => get_field('t4_og_image', $post_id),
        't4_menu' => get_field('t4_menu', $post_id),
        
        // T4 Colors
        'cs_bg' => get_field('cs_bg', $post_id),
        'cs_color' => get_field('cs_color', $post_id),
        't4_main_color' => get_field('t4_main_color', $post_id),
        't4_second_color' => get_field('t4_second_color', $post_id),
        't4_btn_color' => get_field('t4_btn_color', $post_id),
        't4_btn_color_text' => get_field('t4_btn_color_text', $post_id),
        't4_btn2_color' => get_field('t4_btn2_color', $post_id),
        't4_btn2_color_text' => get_field('t4_btn2_color_text', $post_id),
        't4_bg_color' => get_field('t4_bg_color', $post_id),
        't4_border_colors' => get_field('t4_border_colors', $post_id),
        't4_text_color' => get_field('t4_text_color', $post_id),
        't4_link_color' => get_field('t4_link_color', $post_id),
        't4_table_odd' => get_field('t4_table_odd', $post_id),
        't4_table_even' => get_field('t4_table_even', $post_id),
        
        // T4 Fonts
        't4_main_google_font' => get_field('t4_main_google_font', $post_id),
        'h1' => get_field('t4_h1', $post_id),
        'h2' => get_field('t4_h2', $post_id),
        'h3' => get_field('t4_h3', $post_id),
        'h4' => get_field('t4_h4', $post_id),
        'p' => get_field('t4_p', $post_id),
        'li' => get_field('t4_li', $post_id),
        
        // T4 Footer
        'footer_text' => get_field('t4_footer_text', $post_id),
        'footer_color' => get_field('t4_footer_color', $post_id),
        'footer_color_text' => get_field('t4_footer_color_text', $post_id),
        'footer_menu1' => get_field('t4_footer_menu1', $post_id),
        'footer_menu2' => get_field('t4_footer_menu2', $post_id),
        'all_right' => get_field('t4_all_right', $post_id),
        
        // T4 Fixed Casino Desktop
        'enable_disable' => get_field('t4_enable_disable', $post_id),
        'fixed_logo' => get_field('t4_fixed_logo', $post_id),
        'bg_fixed' => get_field('t4_bg_fixed', $post_id),
        'fixed_title' => get_field('t4_fixed_title', $post_id),
        'fixed_subtitle' => get_field('t4_fixed_subtitle', $post_id),
        'fixed_link' => get_field('t4_fixed_link', $post_id),
        'btn_name' => get_field('t4_btn_name', $post_id),
        'fixed_btn_bg' => get_field('t4_fixed_btn_bg', $post_id),
        
        // T4 Fixed Casino Mobile
        'enable_disable_mobile' => get_field('t4_enable_disable_mobile', $post_id),
        'fixed_logo_mobile' => get_field('t4_fixed_logo_mobile', $post_id),
        'bg_fixed_mobile' => get_field('t4_bg_fixed_mobile', $post_id),
        'fixed_title_mobile' => get_field('t4_fixed_title_mobile', $post_id),
        'fixed_subtitle_mobile' => get_field('t4_fixed_subtitle_mobile', $post_id),
        'fixed_link_mobile' => get_field('t4_fixed_link_mobile', $post_id),
        'btn_name_mobile' => get_field('t4_btn_name_mobile', $post_id),
        'fixed_btn_bg_mobile' => get_field('t4_fixed_btn_bg_mobile', $post_id),
        
        // T4 FAQ
        'faq_color' => get_field('t4_faq_color', $post_id),
        'faq_bg' => get_field('t4_faq_bg', $post_id),
        
        // T4 Author
        'author_name' => get_field('t4_author_name', $post_id),
        'author_avatar' => get_field('t4_author_avatar', $post_id),
        'author_color' => get_field('t4_author_color', $post_id),
        
        // T4 Main
        'ref_link' => get_field('t4_ref_link', $post_id),
        'alt_text' => get_field('t4_alt_text', $post_id),
        
        // T4 Casinos
        't4_casino_list' => get_field('t4_casino_list', $post_id),
        
        // Template 3 Specific Fields
        't3_header_main' => get_field('t3_header_main', $post_id),
        't3_logo' => get_field('t3_logo', $post_id),
        't3_favicon' => get_field('t3_favicon', $post_id),
        't3_og_image' => get_field('t3_og_image', $post_id),
        't3_hero_button_text' => get_field('t3_hero_button_text', $post_id),
        't3_menu' => get_field('t3_menu', $post_id),
        't3_buttons' => get_field('t3_buttons', $post_id),
        
        // T3 Colors
        't3_main_color' => get_field('t3_main_color', $post_id),
        't3_second_color' => get_field('t3_second_color', $post_id),
        't3_btn_color' => get_field('t3_btn_color', $post_id),
        't3_btn_color_text' => get_field('t3_btn_color_text', $post_id),
        't3_btn2_color' => get_field('t3_btn2_color', $post_id),
        't3_btn2_color_text' => get_field('t3_btn2_color_text', $post_id),
        't3_bg_color' => get_field('t3_bg_color', $post_id),
        't3_btn_hover' => get_field('t3_btn_hover', $post_id),
        't3_border_colors' => get_field('t3_border_colors', $post_id),
        't3_text_color' => get_field('t3_text_color', $post_id),
        't3_link_color' => get_field('t3_link_color', $post_id),
        't3_table_odd' => get_field('t3_table_odd', $post_id),
        't3_table_even' => get_field('t3_table_even', $post_id),
        't3_color_title' => get_field('t3_color_title', $post_id),
        't3_position_title' => get_field('t3_position_title', $post_id),
        
        // T3 Fonts
        't3_main_google_font' => get_field('t3_main_google_font', $post_id),
        
        // T3 Main
        't3_ref_link' => get_field('t3_ref_link', $post_id),
        't3_alt_text' => get_field('t3_alt_text', $post_id),
        
        // T3 Footer
        't3_payments' => get_field('t3_payments', $post_id),
        't3_parthners' => get_field('t3_parthners', $post_id),
        't3_menu_footer' => get_field('t3_menu_footer', $post_id),
        't3_all_right' => get_field('t3_all_right', $post_id),
        
        // Template 1 Specific Fields
        't1_header_main' => get_field('t1_header_main', $post_id),
        't1_logo' => get_field('t1_logo', $post_id),
        't1_favicon' => get_field('t1_favicon', $post_id),
        't1_og_image' => get_field('t1_og_image', $post_id),
        't1_menu' => get_field('t1_menu', $post_id),
        't1_buttons' => get_field('t1_buttons', $post_id),
        
        // T1 Colors
        't1_main_color' => get_field('t1_main_color', $post_id),
        't1_second_color' => get_field('t1_second_color', $post_id),
        't1_btn_color' => get_field('t1_btn_color', $post_id),
        't1_btn_color_text' => get_field('t1_btn_color_text', $post_id),
        't1_btn2_color' => get_field('t1_btn2_color', $post_id),
        't1_btn2_color_text' => get_field('t1_btn2_color_text', $post_id),
        't1_bg_color' => get_field('t1_bg_color', $post_id),
        't1_btn_hover' => get_field('t1_btn_hover', $post_id),
        't1_border_colors' => get_field('t1_border_colors', $post_id),
        't1_text_color' => get_field('t1_text_color', $post_id),
        't1_link_color' => get_field('t1_link_color', $post_id),
        't1_table_odd' => get_field('t1_table_odd', $post_id),
        't1_table_even' => get_field('t1_table_even', $post_id),
        
        // T1 Fonts
        't1_main_google_font' => get_field('t1_main_google_font', $post_id),
        
        // T1 Main
        't1_ref_link' => get_field('t1_ref_link', $post_id),
        't1_alt_text' => get_field('t1_alt_text', $post_id),
        
        // T1 Footer
        't1_payments' => get_field('t1_payments', $post_id),
        't1_parthners' => get_field('t1_parthners', $post_id),
        't1_menu_footer' => get_field('t1_menu_footer', $post_id),
        't1_all_right' => get_field('t1_all_right', $post_id),
        
        // Template 2 Specific Fields
        't2_header_main' => get_field('t2_header_main', $post_id),
        't2_logo' => get_field('t2_logo', $post_id),
        't2_favicon' => get_field('t2_favicon', $post_id),
        't2_og_image' => get_field('t2_og_image', $post_id),
        't2_menu' => get_field('t2_menu', $post_id),
        't2_buttons' => get_field('t2_buttons', $post_id),
        
        // T2 Colors
        't2_main_color' => get_field('t2_main_color', $post_id),
        't2_second_color' => get_field('t2_second_color', $post_id),
        't2_btn_color' => get_field('t2_btn_color', $post_id),
        't2_btn_color_text' => get_field('t2_btn_color_text', $post_id),
        't2_btn2_color' => get_field('t2_btn2_color', $post_id),
        't2_btn2_color_text' => get_field('t2_btn2_color_text', $post_id),
        't2_bg_color' => get_field('t2_bg_color', $post_id),
        't2_btn_hover' => get_field('t2_btn_hover', $post_id),
        't2_border_colors' => get_field('t2_border_colors', $post_id),
        't2_text_color' => get_field('t2_text_color', $post_id),
        't2_link_color' => get_field('t2_link_color', $post_id),
        't2_table_odd' => get_field('t2_table_odd', $post_id),
        't2_table_even' => get_field('t2_table_even', $post_id),
        
        // T2 Fonts
        't2_main_google_font' => get_field('t2_main_google_font', $post_id),
        
        // T2 Main
        't2_ref_link' => get_field('t2_ref_link', $post_id),
        't2_alt_text' => get_field('t2_alt_text', $post_id),
        
        // T2 Footer
        't2_payments' => get_field('t2_payments', $post_id),
        't2_parthners' => get_field('t2_parthners', $post_id),
        't2_menu_footer' => get_field('t2_menu_footer', $post_id),
        't2_all_right' => get_field('t2_all_right', $post_id),
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
