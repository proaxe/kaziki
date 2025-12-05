<?php
/**
 * ACF Template 3 Settings - Field Groups
 * 
 * @package kaziki
 */

if (!defined('ABSPATH')) {
    exit;
}

if (function_exists('acf_add_local_field_group')) {
    
    acf_add_local_field_group(array(
        'key' => 'group_template3_settings',
        'title' => 'Template 3 Settings',
        'fields' => array(
            // Header Tab
            array(
                'key' => 'field_t3_header_tab',
                'label' => 'Header',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_t3_header_main',
                'label' => 'Header HTML main',
                'name' => 't3_header_main',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_t3_logo',
                'label' => 'Logo',
                'name' => 't3_logo',
                'type' => 'image',
                'return_format' => 'url',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_favicon',
                'label' => 'Favicon',
                'name' => 't3_favicon',
                'type' => 'image',
                'return_format' => 'url',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_og_image',
                'label' => 'OG image',
                'name' => 't3_og_image',
                'type' => 'image',
                'return_format' => 'url',
                'preview_size' => 'medium',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_hero_button_text',
                'label' => 'Hero Button Text',
                'name' => 't3_hero_button_text',
                'type' => 'text',
                'instructions' => 'Text for button in hero section (Template 3 only)',
                'placeholder' => 'Play Now',
                'wrapper' => array('width' => '33'),
            ),
            
            // Menu
            array(
                'key' => 'field_t3_menu',
                'label' => 'Menu',
                'name' => 't3_menu',
                'type' => 'repeater',
                'layout' => 'table',
                'sub_fields' => array(
                    array(
                        'key' => 'field_t3_menu_name',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_t3_menu_icon',
                        'label' => 'Icon',
                        'name' => 'icon',
                        'type' => 'image',
                        'return_format' => 'url',
                    ),
                ),
            ),
            array(
                'key' => 'field_t3_buttons',
                'label' => 'Buttons',
                'name' => 't3_buttons',
                'type' => 'repeater',
                'layout' => 'table',
                'sub_fields' => array(
                    array(
                        'key' => 'field_t3_button_name',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                ),
            ),
            
            // Colors Tab
            array(
                'key' => 'field_t3_colors_tab',
                'label' => 'Colors',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_t3_main_color',
                'label' => 'Main color',
                'name' => 't3_main_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_second_color',
                'label' => 'Second color',
                'name' => 't3_second_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_btn_color',
                'label' => 'Button color',
                'name' => 't3_btn_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_btn_color_text',
                'label' => 'Button color text',
                'name' => 't3_btn_color_text',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_btn2_color',
                'label' => 'Button2 color',
                'name' => 't3_btn2_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_btn2_color_text',
                'label' => 'Button2 color text',
                'name' => 't3_btn2_color_text',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_bg_color',
                'label' => 'Background color',
                'name' => 't3_bg_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_btn_hover',
                'label' => 'Button hover',
                'name' => 't3_btn_hover',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_border_colors',
                'label' => 'Border colors',
                'name' => 't3_border_colors',
                'type' => 'color_picker',
                'default_value' => '#7cae8d',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_text_color',
                'label' => 'Text color',
                'name' => 't3_text_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_link_color',
                'label' => 'Link color',
                'name' => 't3_link_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_table_odd',
                'label' => 'Table odd',
                'name' => 't3_table_odd',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_table_even',
                'label' => 'Table even',
                'name' => 't3_table_even',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_color_title',
                'label' => 'Title color (h1-h5)',
                'name' => 't3_color_title',
                'type' => 'color_picker',
                'instructions' => 'Color for all headings (h1, h2, h3, h4, h5)',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_position_title',
                'label' => 'Title alignment',
                'name' => 't3_position_title',
                'type' => 'select',
                'instructions' => 'Text alignment for all headings',
                'choices' => array(
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                ),
                'default_value' => 'left',
                'wrapper' => array('width' => '33'),
            ),
            
            // Main Tab
            array(
                'key' => 'field_t3_main_tab',
                'label' => 'Main',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_t3_main_google_font',
                'label' => 'Font family',
                'name' => 't3_main_google_font',
                'type' => 'select',
                'choices' => array(
                    "'Inter:100,200,300,400,500,600,700,800,900|Inter'" => "'Inter:100,200,300,400,500,600,700,800,900|Inter'",
                    "'Roboto:100,300,400,500,700,900|Roboto'" => "'Roboto:100,300,400,500,700,900|Roboto'",
                    "'Open Sans:300,400,500,600,700,800|Open Sans'" => "'Open Sans:300,400,500,600,700,800|Open Sans'",
                    "'Montserrat:100,200,300,400,500,600,700,800,900|Montserrat'" => "'Montserrat:100,200,300,400,500,600,700,800,900|Montserrat'",
                    "'Lato:100,300,400,700,900|Lato'" => "'Lato:100,300,400,700,900|Lato'",
                    "'Poppins:100,200,300,400,500,600,700,800,900|Poppins'" => "'Poppins:100,200,300,400,500,600,700,800,900|Poppins'",
                    "'DM Sans:100,200,300,400,500,600,700|DM Sans'" => "'DM Sans:100,200,300,400,500,600,700|DM Sans'",
                    "'Raleway:100,200,300,400,500,600,700,800,900|Raleway'" => "'Raleway:100,200,300,400,500,600,700,800,900|Raleway'",
                    "'Nunito:200,300,400,500,600,700,800,900|Nunito'" => "'Nunito:200,300,400,500,600,700,800,900|Nunito'",
                    "'Ubuntu:300,400,500,700|Ubuntu'" => "'Ubuntu:300,400,500,700|Ubuntu'",
                ),
                'default_value' => array("'Inter:100,200,300,400,500,600,700,800,900|Inter'"),
            ),
            array(
                'key' => 'field_t3_ref_link',
                'label' => 'Ref link',
                'name' => 't3_ref_link',
                'type' => 'url',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t3_alt_text',
                'label' => 'Alt text',
                'name' => 't3_alt_text',
                'type' => 'text',
                'wrapper' => array('width' => '33'),
            ),
            
            // Footer Tab
            array(
                'key' => 'field_t3_footer_tab',
                'label' => 'Footer',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_t3_payments',
                'label' => 'Payments',
                'name' => 't3_payments',
                'type' => 'repeater',
                'layout' => 'table',
                'wrapper' => array('width' => '35'),
                'sub_fields' => array(
                    array(
                        'key' => 'field_t3_payment_image',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'url',
                    ),
                ),
            ),
            array(
                'key' => 'field_t3_parthners',
                'label' => 'Partners',
                'name' => 't3_parthners',
                'type' => 'repeater',
                'layout' => 'table',
                'wrapper' => array('width' => '35'),
                'sub_fields' => array(
                    array(
                        'key' => 'field_t3_partner_image',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'url',
                    ),
                ),
            ),
            array(
                'key' => 'field_t3_menu_footer',
                'label' => 'Menu footer',
                'name' => 't3_menu_footer',
                'type' => 'repeater',
                'layout' => 'table',
                'sub_fields' => array(
                    array(
                        'key' => 'field_t3_menu_footer_name',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                ),
            ),
            array(
                'key' => 'field_t3_all_right',
                'label' => 'All right',
                'name' => 't3_all_right',
                'type' => 'textarea',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'template3-settings',
                ),
            ),
        ),
    ));
}
