<?php
/**
 * ACF Build Fields - Field Groups for HTML Builds
 * 
 * @package kaziki
 */

if (!defined('ABSPATH')) {
    exit;
}

if (function_exists('acf_add_local_field_group')) {
    
    // Main Build Settings
    acf_add_local_field_group(array(
        'key' => 'group_build_settings',
        'title' => 'Build Settings',
        'fields' => array(
            array(
                'key' => 'field_build_template',
                'label' => 'Template',
                'name' => 'build_template',
                'type' => 'select',
                'instructions' => 'Select which template to use for this build',
                'required' => 1,
                'choices' => array(
                    'template1' => 'Template 1 (Default)',
                    'template2' => 'Template 2',
                    'template3' => 'Template 3',
                    'template4' => 'Template 4',
                ),
                'default_value' => 'template1',
                'allow_null' => 0,
                'multiple' => 0,
            ),
            array(
                'key' => 'field_build_domain',
                'label' => 'Domain for Deployment',
                'name' => 'build_domain',
                'type' => 'text',
                'instructions' => 'Enter the domain name for Cloudflare Pages deployment (e.g., burky-cz)',
                'required' => 0,
                'placeholder' => 'burky-cz',
            ),
            array(
                'key' => 'field_build_deployment_type',
                'label' => 'Deployment Type',
                'name' => 'deployment_type',
                'type' => 'select',
                'instructions' => 'Select deployment target (.pages.dev will have noindex, main domain will be indexed)',
                'required' => 0,
                'choices' => array(
                    'pages_dev' => 'Cloudflare Pages (.pages.dev) - noindex',
                    'main_domain' => 'Main Domain - index',
                ),
                'default_value' => 'pages_dev',
                'allow_null' => 0,
                'multiple' => 0,
            ),
            array(
                'key' => 'field_build_html_lang',
                'label' => 'HTML Language Code',
                'name' => 'html_lang',
                'type' => 'text',
                'instructions' => 'Enter language code for HTML lang attribute (e.g., en, ru, uk). Leave empty to omit lang attribute.',
                'required' => 0,
                'placeholder' => 'en',
                'maxlength' => 10,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'html_build',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
    ));
    
    // SEO Fields (from template1.php)
    acf_add_local_field_group(array(
        'key' => 'group_build_seo',
        'title' => 'SEO Settings',
        'fields' => array(
            array(
                'key' => 'field_build_title_page',
                'label' => 'Page Title',
                'name' => 'title_page',
                'type' => 'text',
                'required' => 1,
            ),
            array(
                'key' => 'field_build_description_page',
                'label' => 'Page Description',
                'name' => 'description_page',
                'type' => 'textarea',
                'rows' => 3,
                'required' => 1,
            ),
            array(
                'key' => 'field_build_canonical',
                'label' => 'Canonical URL',
                'name' => 'canonical',
                'type' => 'url',
                'required' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'html_build',
                ),
            ),
        ),
        'menu_order' => 1,
        'position' => 'normal',
    ));
    
    // Hero Section
    acf_add_local_field_group(array(
        'key' => 'group_build_hero',
        'title' => 'Hero Section',
        'fields' => array(
            array(
                'key' => 'field_build_hero',
                'label' => 'Hero Image',
                'name' => 'hero',
                'type' => 'image',
                'return_format' => 'url',
                'preview_size' => 'medium',
                'required' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'html_build',
                ),
            ),
        ),
        'menu_order' => 2,
        'position' => 'normal',
    ));
    
    // Category List
    acf_add_local_field_group(array(
        'key' => 'group_build_category_list',
        'title' => 'Category List',
        'fields' => array(
            array(
                'key' => 'field_build_category_list',
                'label' => 'Categories',
                'name' => 'category_list',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Add Category',
                'sub_fields' => array(
                    array(
                        'key' => 'field_build_category_name',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_build_category_image',
                        'label' => 'Icon',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'url',
                        'preview_size' => 'thumbnail',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'html_build',
                ),
            ),
        ),
        'menu_order' => 3,
        'position' => 'normal',
    ));
    
    // Casino List
    acf_add_local_field_group(array(
        'key' => 'group_build_casino_list',
        'title' => 'Casino/Slot List',
        'fields' => array(
            array(
                'key' => 'field_build_casino_list',
                'label' => 'Casino Items',
                'name' => 'casino_list',
                'type' => 'repeater',
                'layout' => 'row',
                'button_label' => 'Add Casino',
                'sub_fields' => array(
                    array(
                        'key' => 'field_build_casino_name',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_build_casino_image',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'url',
                        'preview_size' => 'medium',
                        'required' => 1,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'html_build',
                ),
            ),
        ),
        'menu_order' => 4,
        'position' => 'normal',
    ));
    
    // Main Content
    acf_add_local_field_group(array(
        'key' => 'group_build_content',
        'title' => 'Main Content',
        'fields' => array(
            array(
                'key' => 'field_build_content_text',
                'label' => 'Content',
                'name' => 'content_text',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'html_build',
                ),
            ),
        ),
        'menu_order' => 5,
        'position' => 'normal',
    ));
    
    // FAQ Section
    acf_add_local_field_group(array(
        'key' => 'group_build_faq',
        'title' => 'FAQ Section',
        'fields' => array(
            array(
                'key' => 'field_build_faq_items',
                'label' => 'FAQ Items',
                'name' => 'faq_items',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => 'Add FAQ',
                'sub_fields' => array(
                    array(
                        'key' => 'field_build_faq_question',
                        'label' => 'Question',
                        'name' => 'question',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_build_faq_answer',
                        'label' => 'Answer',
                        'name' => 'answer',
                        'type' => 'textarea',
                        'rows' => 4,
                        'required' => 1,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'html_build',
                ),
            ),
        ),
        'menu_order' => 6,
        'position' => 'normal',
    ));
}
