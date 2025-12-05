<?php
/**
 * ACF Main Settings - Global Field Groups
 * 
 * @package kaziki
 */

if (!defined('ABSPATH')) {
    exit;
}

if (function_exists('acf_add_local_field_group')) {
    
    acf_add_local_field_group(array(
        'key' => 'group_main_settings',
        'title' => 'Main Settings',
        'fields' => array(
            // Casino-slots list
            array(
                'key' => 'field_main_casino_slots',
                'label' => 'Casino items',
                'name' => 'casino_slots_list',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Add Casino',
                'sub_fields' => array(
                    array(
                        'key' => 'field_main_casino_logo',
                        'label' => 'Logo',
                        'name' => 'logo',
                        'type' => 'image',
                        'return_format' => 'url',
                        'preview_size' => 'thumbnail',
                    ),
                    array(
                        'key' => 'field_main_casino_name',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_main_casino_bonus',
                        'label' => 'Bonus',
                        'name' => 'bonus',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_main_casino_list',
                        'label' => 'List',
                        'name' => 'list',
                        'type' => 'wysiwyg',
                        'tabs' => 'all',
                        'toolbar' => 'full',
                        'media_upload' => 1,
                        'delay' => 0,
                    ),
                    array(
                        'key' => 'field_main_casino_rating',
                        'label' => 'Rating',
                        'name' => 'rating',
                        'type' => 'number',
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ),
                    array(
                        'key' => 'field_main_casino_link',
                        'label' => 'Link',
                        'name' => 'link',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_main_casino_border',
                        'label' => 'Border',
                        'name' => 'border',
                        'type' => 'color_picker',
                        'instructions' => 'Optional border color for highlighted casinos',
                    ),
                ),
            ),
            
            // Slot list (for templates 1-3)
            array(
                'key' => 'field_main_slot_list',
                'label' => 'Slot list',
                'name' => 'slot_list_global',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Add Slot',
                'instructions' => 'Slot/Game list for templates 1-3',
                'sub_fields' => array(
                    array(
                        'key' => 'field_main_slot_name',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_main_slot_image',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'url',
                        'preview_size' => 'thumbnail',
                    ),
                ),
            ),
            
            // Category list
            array(
                'key' => 'field_main_category_list',
                'label' => 'Category list',
                'name' => 'category_list_global',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Add Category',
                'sub_fields' => array(
                    array(
                        'key' => 'field_main_category_name',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_main_category_image',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'url',
                    ),
                ),
            ),
            
            // Menu footer
            array(
                'key' => 'field_main_menu_footer',
                'label' => 'Menu footer',
                'name' => 'menu_footer_global',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Add Menu Item',
                'sub_fields' => array(
                    array(
                        'key' => 'field_main_menu_footer_name',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                ),
            ),
            
            // Payments
            array(
                'key' => 'field_main_payments',
                'label' => 'Payments',
                'name' => 'payments_global',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Add Payment',
                'sub_fields' => array(
                    array(
                        'key' => 'field_main_payment_image',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'url',
                    ),
                ),
            ),
            
            // Partners
            array(
                'key' => 'field_main_partners',
                'label' => 'Partners',
                'name' => 'partners_global',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Add Partner',
                'sub_fields' => array(
                    array(
                        'key' => 'field_main_partner_image',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'url',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'main-settings',
                ),
            ),
        ),
    ));
}
