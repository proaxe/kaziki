<?php
/**
 * ACF Template 2 Settings - Field Groups
 * 
 * @package kaziki
 */

if (!defined('ABSPATH')) {
    exit;
}

if (function_exists('acf_add_local_field_group')) {
    
    acf_add_local_field_group(array(
        'key' => 'group_template2_settings',
        'title' => 'Template 2 Settings',
        'fields' => array(
            // Header Tab
            array(
                'key' => 'field_t2_header_tab',
                'label' => 'Header',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_t2_header_main',
                'label' => 'Header HTML main',
                'name' => 'header-main',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_t2_logo',
                'label' => 'Logo',
                'name' => 'logo',
                'type' => 'image',
                'return_format' => 'url',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t2_favicon',
                'label' => 'Favicon',
                'name' => 'favicon',
                'type' => 'image',
                'return_format' => 'url',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t2_og_image',
                'label' => 'OG image',
                'name' => 'og_image',
                'type' => 'image',
                'return_format' => 'url',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t2_menu',
                'label' => 'Menu',
                'name' => 'menu',
                'type' => 'repeater',
                'layout' => 'table',
                'sub_fields' => array(
                    array(
                        'key' => 'field_t2_menu_name',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_t2_menu_icon',
                        'label' => 'Icon',
                        'name' => 'icon',
                        'type' => 'image',
                        'return_format' => 'url',
                    ),
                ),
            ),
            array(
                'key' => 'field_t2_buttons',
                'label' => 'Buttons',
                'name' => 'buttons',
                'type' => 'repeater',
                'layout' => 'table',
                'sub_fields' => array(
                    array(
                        'key' => 'field_t2_button_name',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                ),
            ),
            
            // Colors Tab
            array(
                'key' => 'field_t2_colors_tab',
                'label' => 'Colors',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_t2_main_color',
                'label' => 'Main color',
                'name' => 'main_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t2_second_color',
                'label' => 'Second color',
                'name' => 'second_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t2_btn_color',
                'label' => 'Button color',
                'name' => 'btn_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t2_btn_color_text',
                'label' => 'Button color text',
                'name' => 'btn_color_text',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t2_btn2_color',
                'label' => 'Button2 color',
                'name' => 'btn2_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t2_btn2_color_text',
                'label' => 'Button2 color text',
                'name' => 'btn2_color_text',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t2_bg_color',
                'label' => 'Background color',
                'name' => 'bg_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t2_btn_hover',
                'label' => 'Button hover',
                'name' => 'btn_hover',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t2_border_colors',
                'label' => 'Border colors',
                'name' => 'border-colors',
                'type' => 'color_picker',
                'default_value' => '#7cae8d',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t2_text_color',
                'label' => 'Text color',
                'name' => 'text_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t2_link_color',
                'label' => 'Link color',
                'name' => 'link_color',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t2_table_odd',
                'label' => 'Table odd',
                'name' => 'table_odd',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t2_table_even',
                'label' => 'Table even',
                'name' => 'table_even',
                'type' => 'color_picker',
                'wrapper' => array('width' => '33'),
            ),
            
            // Main Tab
            array(
                'key' => 'field_t2_main_tab',
                'label' => 'Main',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_t2_main_google_font',
                'label' => 'Font family',
                'name' => 'main_google_font',
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
                'key' => 'field_t2_ref_link',
                'label' => 'Ref link',
                'name' => 'ref_link',
                'type' => 'url',
                'wrapper' => array('width' => '33'),
            ),
            array(
                'key' => 'field_t2_alt_text',
                'label' => 'Alt text',
                'name' => 'alt_text',
                'type' => 'text',
                'wrapper' => array('width' => '33'),
            ),
            
            // Footer Tab
            array(
                'key' => 'field_t2_footer_tab',
                'label' => 'Footer',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ),
            array(
                'key' => 'field_t2_payments',
                'label' => 'Payments',
                'name' => 'payments',
                'type' => 'repeater',
                'layout' => 'table',
                'wrapper' => array('width' => '35'),
                'sub_fields' => array(
                    array(
                        'key' => 'field_t2_payment_image',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'url',
                    ),
                ),
            ),
            array(
                'key' => 'field_t2_parthners',
                'label' => 'Partners',
                'name' => 'parthners',
                'type' => 'repeater',
                'layout' => 'table',
                'wrapper' => array('width' => '35'),
                'sub_fields' => array(
                    array(
                        'key' => 'field_t2_partner_image',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'url',
                    ),
                ),
            ),
            array(
                'key' => 'field_t2_menu_footer',
                'label' => 'Menu footer',
                'name' => 'menu_footer',
                'type' => 'repeater',
                'layout' => 'table',
                'sub_fields' => array(
                    array(
                        'key' => 'field_t2_menu_footer_name',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                ),
            ),
            array(
                'key' => 'field_t2_all_right',
                'label' => 'All right',
                'name' => 'all_right',
                'type' => 'textarea',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'template2-settings',
                ),
            ),
        ),
    ));
}
