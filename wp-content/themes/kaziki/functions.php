<?php
/**
 * kaziki functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package kaziki
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function kaziki_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on kaziki, use a find and replace
		* to change 'kaziki' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'kaziki', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'kaziki' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'kaziki_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'kaziki_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function kaziki_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'kaziki_content_width', 640 );
}
add_action( 'after_setup_theme', 'kaziki_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function kaziki_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'kaziki' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'kaziki' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'kaziki_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function kaziki_scripts() {
	wp_enqueue_style( 'kaziki-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'kaziki-style', 'rtl', 'replace' );

	wp_enqueue_script( 'kaziki-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'kaziki_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Build System - HTML Static Build and Cloudflare Deployment
 */
require get_template_directory() . '/inc/build-system.php';
require get_template_directory() . '/inc/acf-build-fields.php';
require get_template_directory() . '/inc/acf-template1-settings.php';
require get_template_directory() . '/inc/acf-template2-settings.php';
require get_template_directory() . '/inc/acf-template3-settings.php';
require get_template_directory() . '/inc/acf-template4-settings.php';
require get_template_directory() . '/inc/acf/fields_main_settings.php';
require get_template_directory() . '/inc/html-renderer.php';
require get_template_directory() . '/inc/cloudflare-deploy.php';
require get_template_directory() . '/inc/config-snapshots.php';
require get_template_directory() . '/inc/ajax-handlers.php';
require get_template_directory() . '/inc/admin-build-interface.php';



/*Option pages */

if( function_exists('acf_add_options_page') ) {

    // Template 1 Settings
    acf_add_options_page(array(
        'page_title'    => 'Template 1 Settings',
        'menu_title'    => 'Template 1',
        'menu_slug'     => 'template1-settings',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'themes.php',
        'icon_url'      => 'dashicons-admin-appearance',
        'redirect'      => false
    ));
    
    // Template 2 Settings
    acf_add_options_page(array(
        'page_title'    => 'Template 2 Settings',
        'menu_title'    => 'Template 2',
        'menu_slug'     => 'template2-settings',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'themes.php',
        'icon_url'      => 'dashicons-admin-appearance',
        'redirect'      => false
    ));
    
    // Template 3 Settings
    acf_add_options_page(array(
        'page_title'    => 'Template 3 Settings',
        'menu_title'    => 'Template 3',
        'menu_slug'     => 'template3-settings',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'themes.php',
        'icon_url'      => 'dashicons-admin-appearance',
        'redirect'      => false
    ));
    
    // Template 4 Settings
    acf_add_options_page(array(
        'page_title'    => 'Template 4 Settings',
        'menu_title'    => 'Template 4',
        'menu_slug'     => 'template4-settings',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'themes.php',
        'icon_url'      => 'dashicons-admin-appearance',
        'redirect'      => false
    ));
    
    // Main Settings (Global)
    acf_add_options_page(array(
        'page_title'    => 'Main Settings',
        'menu_title'    => 'Main Settings',
        'menu_slug'     => 'main-settings',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'themes.php',
        'icon_url'      => 'dashicons-admin-settings',
        'redirect'      => false
    ));

}

/* Generate random name photo */
function random_string_wp( $length = 10 ) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';
    $max = strlen($characters) - 1;

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $max)];
    }

    return $randomString;
}

// Переименовываем файл перед загрузкой
add_filter( 'sanitize_file_name', 'randomize_uploaded_filename', 10, 2 );
function randomize_uploaded_filename( $filename, $filename_raw ) {
    $info = pathinfo( $filename );

    $ext = isset( $info['extension'] ) ? '.' . $info['extension'] : '';
    
    // Случайная длина от 10 до 25 символов
    $rand_length = random_int(10, 25);

    // Генерируем новое имя
    $new_name = random_string_wp( $rand_length ) . $ext;

    return $new_name;
}

/* ==== FULL CLEAN wp_head ==== */

// /* 1. Удаляем RSS Feeds */
// remove_action('wp_head', 'feed_links', 2);
// remove_action('wp_head', 'feed_links_extra', 3);

// /* 2. Удаляем canonical */
// remove_action('wp_head', 'rel_canonical');

// /* 3. Убираем generator meta */
// remove_action('wp_head', 'wp_generator');

// /* 4. Отключаем эмоджи */
// remove_action('wp_head', 'print_emoji_detection_script', 7);
// remove_action('wp_print_styles', 'print_emoji_styles');

// /* 5. Отключаем REST API discovery links */
// remove_action('wp_head', 'rest_output_link_wp_head', 10);
// remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

// /* 6. Отключаем RSD + WLW */
// remove_action('wp_head', 'rsd_link');
// remove_action('wp_head', 'wlwmanifest_link');

// /* 7. Отключаем shortlinks */
// remove_action('wp_head', 'wp_shortlink_wp_head', 10);

// /* 8. Отключаем wp-embed */
// remove_action('wp_footer', 'wp_embed_footer_scripts');

// /* 9. Убираем jQuery Migrate */
// add_action('wp_default_scripts', function($scripts){
//     if (!empty($scripts->registered['jquery'])) {
//         $scripts->registered['jquery']->deps =
//             array_diff($scripts->registered['jquery']->deps, ['jquery-migrate']);
//     }
// });

// /* 10. Удаляем стили плагинов и темы — оставляем только нужное WP-редактору */
// add_action('wp_print_styles', function() {
//     global $wp_styles;

//     $allowed = [
//         'dashicons',
//         'common',
//         'editor-buttons',
//         'wp-editor',
//         'forms',
//         'admin-bar'
//     ];

//     foreach ($wp_styles->queue as $handle) {
//         if (!in_array($handle, $allowed)) {
//             wp_dequeue_style($handle);
//         }
//     }
// }, 100);

// /* 11. Удаляем скрипты плагинов/тем — но оставляем нужное редактору */
// add_action('wp_print_scripts', function() {
//     global $wp_scripts;

//     $allowed = [
//         'jquery',
//         'jquery-ui-core',
//         'wp-util',
//         'editor',
//         'wp-editor',
//         'quicktags',
//         'admin-bar'
//     ];

//     foreach ($wp_scripts->queue as $handle) {
//         if (!in_array($handle, $allowed)) {
//             wp_dequeue_script($handle);
//         }
//     }
// }, 100);

add_action('admin_enqueue_scripts', function() {

    // TinyMCE — ядро
    wp_enqueue_script(
        'tinymce-core',
        includes_url('js/tinymce/tinymce.min.js'),
        [],
        false,
        true
    );

    // WordPress адаптер к TinyMCE
    wp_enqueue_script(
        'wp-tinymce',
        includes_url('js/tinymce/wp-tinymce.js'),
        ['tinymce-core'],
        false,
        true
    );

    // Скрипты для WordPress editor
    wp_enqueue_script('editor');
    wp_enqueue_script('quicktags');
    wp_enqueue_script('wp-editor');
    wp_enqueue_script('utils');
    wp_enqueue_script('wp-dom');
    wp_enqueue_script('wp-hooks');
    wp_enqueue_script('wp-i18n');
    wp_enqueue_script('wp-element');

    // стили редактора
    wp_enqueue_style('editor-buttons');
});
/* change default folder images */
add_filter('upload_dir', function($dirs) {
    $dirs['path'] = ABSPATH . 'img';
    $dirs['url']  = site_url('img');
    $dirs['basedir'] = ABSPATH . 'img';
    $dirs['baseurl'] = site_url('img');

    return $dirs;
});



/* btn add shortcode */
function my_custom_button_shortcode( $atts ) {

    // атрибути
    $atts = shortcode_atts( array(
        'text' => 'Visit casino',
        'id'   => '1',
    ), $atts );

    // глобальний лінк
    $link = get_field('ref_link', 'option');
    if( !$link ) return '';

    // класи по id
    $class = ($atts['id'] == '2') ? 'button' : 'btn';

    return '<a class="' . esc_attr( $class ) . '" href="' . esc_url( $link ) . '">' 
            . esc_html( $atts['text'] ) .
        '</a>';
}
add_shortcode( 'button', 'my_custom_button_shortcode' );

// Load GitHub API Deploy class
require_once get_template_directory() . '/inc/github-api-deploy.php';
