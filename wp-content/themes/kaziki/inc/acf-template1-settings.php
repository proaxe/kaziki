<?php
/**
 * ACF Template 1 Settings - Field Groups
 * 
 * @package kaziki
 */

if (!defined('ABSPATH')) {
    exit;
}

if (function_exists('acf_add_local_field_group')) {
    
    acf_add_local_field_group(array(
        'key' => 'group_template1_settings',
        'title' => 'Template 1 Settings',
        'fields' => array(
            // Header Tab
            array(
                'key' => 'field_t1_header_tab',
                'label' => 'Header',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_t1_header_main',
                'label' => 'Header HTML main',
                'name' => 't1_header_main',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_t1_logo',
                'label' => 'Logo',
                'name' => 't1_logo',
                'type' => 'image',
                'return_format' => 'url',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t1_favicon',
                'label' => 'Favicon',
                'name' => 't1_favicon',
                'type' => 'image',
                'return_format' => 'url',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t1_og_image',
                'label' => 'OG image',
                'name' => 't1_og_image',
                'type' => 'image',
                'return_format' => 'url',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t1_menu',
                'label' => 'Menu',
                'name' => 't1_menu',
                'type' => 'repeater',
                'layout' => 'table',
                'sub_fields' => array(
                    array(
                        'key' => 'field_t1_menu_name',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_t1_menu_icon',
                        'label' => 'Icon',
                        'name' => 'icon',
                        'type' => 'image',
                        'return_format' => 'url',
                    ),
                ),
            ),
            array(
                'key' => 'field_t1_buttons',
                'label' => 'Buttons',
                'name' => 't1_buttons',
                'type' => 'repeater',
                'layout' => 'table',
                'sub_fields' => array(
                    array(
                        'key' => 'field_t1_button_name',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                ),
            ),
            
            // Colors Tab
            array(
                'key' => 'field_t1_colors_tab',
                'label' => 'Colors',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_t1_main_color',
                'label' => 'Main color',
                'name' => 't1_main_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t1_second_color',
                'label' => 'Second color',
                'name' => 't1_second_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t1_btn_color',
                'label' => 'Button color',
                'name' => 't1_btn_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t1_btn_color_text',
                'label' => 'Button color text',
                'name' => 't1_btn_color_text',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t1_btn2_color',
                'label' => 'Button2 color',
                'name' => 't1_btn2_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t1_btn2_color_text',
                'label' => 'Button2 color text',
                'name' => 't1_btn2_color_text',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t1_bg_color',
                'label' => 'Background color',
                'name' => 't1_bg_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t1_btn_hover',
                'label' => 'Button hover',
                'name' => 't1_btn_hover',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t1_border_colors',
                'label' => 'Border colors',
                'name' => 't1_border_colors',
                'type' => 'color_picker',
                'default_value' => '#7cae8d',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t1_text_color',
                'label' => 'Text color',
                'name' => 't1_text_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t1_link_color',
                'label' => 'Link color',
                'name' => 't1_link_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t1_table_odd',
                'label' => 'Table odd',
                'name' => 't1_table_odd',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t1_table_even',
                'label' => 'Table even',
                'name' => 't1_table_even',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            
            // Main Tab
            array(
                'key' => 'field_t1_main_tab',
                'label' => 'Main',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_t1_main_google_font',
                'label' => 'Font family',
                'name' => 't1_main_google_font',
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
                'key' => 'field_t1_ref_link',
                'label' => 'Ref link',
                'name' => 't1_ref_link',
                'type' => 'url',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t1_alt_text',
                'label' => 'Alt text',
                'name' => 't1_alt_text',
                'type' => 'text',
                'wrapper' => array('width' => '33'),
            ),
            
            // Footer Tab
            array(
                'key' => 'field_t1_footer_tab',
                'label' => 'Footer',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_t1_payments',
                'label' => 'Payments',
                'name' => 't1_payments',
                'type' => 'repeater',
                'layout' => 'table',
                'wrapper' => array('width' => '35'),
                'sub_fields' => array(
                    array(
                        'key' => 'field_t1_payment_image',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'url',
                    ),
                ),
            ),
            array(
                'key' => 'field_t1_parthners',
                'label' => 'Partners',
                'name' => 't1_parthners',
                'type' => 'repeater',
                'layout' => 'table',
                'wrapper' => array('width' => '35'),
                'sub_fields' => array(
                    array(
                        'key' => 'field_t1_partner_image',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'url',
                    ),
                ),
            ),
            array(
                'key' => 'field_t1_menu_footer',
                'label' => 'Menu footer',
                'name' => 't1_menu_footer',
                'type' => 'repeater',
                'layout' => 'table',
                'sub_fields' => array(
                    array(
                        'key' => 'field_t1_menu_footer_name',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                ),
            ),
            array(
                'key' => 'field_t1_all_right',
                'label' => 'All right',
                'name' => 't1_all_right',
                'type' => 'textarea',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'template1-settings',
                ),
            ),
        ),
    ));
}
