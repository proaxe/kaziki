<?php
/**
 * Template 4 HTML - Casino Template with Items from Main Settings
 * 
 * Variables available:
 * - All $fields and $option_fields variables are extracted and available
 * - Casino items loaded from Main Settings (Appearance → Main Setting)
 * 
 * @package kaziki
 */

if (!defined('ABSPATH')) {
    exit;
}

// Generate random class names for obfuscation (changes on each build)
$prefixes = array('x', 'y', 'z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w');
$c = array(); // class names array

// Generate unique random classes
$elements = array(
    'bg', 'text', 'link', 'border',
    'mobile_header', 'hamburger', 'mobile_logo', 'desktop_header',
    'sidebar', 'brand_logo', 'action_btns', 'btn_primary', 'btn_secondary',
    'nav_list', 'nav_icon', 'main_wrapper', 'content_area',
    'container', 'faq_section', 'faq_item', 'faq_question', 'faq_answer',
    'footer_wrapper', 'footer_nav', 'copyright', 
    'casinos_list', 'casino_item', 'casino_logo', 'casino_name',
    'casino_bonus', 'casino_list', 'casino_rating', 'rating_number', 'rating_stars', 'casino_btn', 'btn_play',
    'author_box', 'author_avatar', 'author_info',
    'fixed_sidebar', 'fixed_close', 'logo_fixed', 'fixed_content',
    'sc_author_box', 'sc_author_avatar', 'sc_author_info'
);

foreach ($elements as $element) {
    $prefix = $prefixes[array_rand($prefixes)];
    $random = substr(md5(uniqid($element . time(), true)), 0, 6);
    $c[$element] = $prefix . '-' . $random;
}
?>
<!DOCTYPE html>
<html<?php if (!empty($html_lang)): ?> lang="<?php echo esc_attr($html_lang); ?>"<?php endif; ?>>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo esc_html($title_page); ?></title>

    <meta name="title" content="<?php echo esc_attr($title_page); ?>">
    <meta name="description" content="<?php echo esc_attr($description_page); ?>">

    <meta property="og:title" content="<?php echo esc_attr($title_page); ?>">
    <meta property="og:description" content="<?php echo esc_attr($description_page); ?>">
    <meta property="og:url" content="<?php echo esc_url($current_url); ?>">
    <?php if ($og_image): ?>
        <meta property="og:image" content="<?php echo esc_url($og_image); ?>">
        <meta property="og:image:secure_url" content="<?php echo esc_url($og_image); ?>">
    <?php endif; ?>

    <meta name="twitter:title" content="<?php echo esc_attr($title_page); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($description_page); ?>">
    <?php if ($og_image): ?>
        <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>">
    <?php endif; ?>

    <?php if ($favicon): ?>
        <link type="image/png" href="<?php echo esc_url($favicon); ?>" rel="icon" sizes="16x16">
        <link type="image/png" href="<?php echo esc_url($favicon); ?>" rel="icon" sizes="32x32">
        <link type="image/x-icon" href="<?php echo esc_url($favicon); ?>" rel="icon">
    <?php endif; ?>

    <link href="<?php echo esc_url($current_url); ?>" rel="canonical">

    <?php if ($deployment_type === 'pages_dev'): ?>
        <meta name="robots" content="noindex, nofollow">
    <?php else: ?>
        <meta name="robots" content="index, follow">
    <?php endif; ?>
    <?php if ($font_family): ?>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode(str_replace("'", '', explode('|', $font_family)[0])); ?>&display=swap" rel="stylesheet">
    <?php endif; ?>

    <style>
        :root {
            --<?php echo $c['bg']; ?>: <?php echo $bg; ?>;
            --<?php echo $c['text']; ?>: <?php echo $color_text; ?>;
            --<?php echo $c['link']; ?>: <?php echo $color_link; ?>;
            --<?php echo $c['border']; ?>: <?php echo $border_color; ?>;
        }

        * {
            box-sizing: border-box;
        }

        body {
            -webkit-font-smoothing: antialiased;
            background-color: var(--<?php echo $c['bg']; ?>);
            color: var(--<?php echo $c['text']; ?>);
            font-family: "<?php echo $font_name; ?>", sans-serif;
            font-size: 16px;
            height: auto;
            line-height: 1.5;
            margin: 0;
            outline: 0;
            text-align: left;
            top: 0 !important;
            position: relative;
            scroll-behavior: smooth;
            text-rendering: optimizeSpeed;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            letter-spacing: .03em;
            font-weight: 700;
            color: var(--<?php echo $c['text']; ?>);
        }

        h1 {
            margin-bottom: 16px;
            line-height: 36px;
            font-size: 32px;
        }

        h2 {
            font-size: 24px;
            line-height: 28px;
        }

        h3 {
            font-size: 20px;
            line-height: 24px;
        }

        p {
            line-height: 150%;
            font-weight: 500;
        }

        a {
            color: var(--<?php echo $c['link']; ?>);
        }

        a:active, a:focus, a:hover {
            outline: 0;
        }

        .<?php echo $c['container']; ?> {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Mobile Header */
        .<?php echo $c['mobile_header']; ?> {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: var(--<?php echo $c['bg']; ?>);
            border-bottom: 1px solid var(--<?php echo $c['border']; ?>);
            padding: 15px 20px;
            z-index: 1000;
            align-items: center;
            justify-content: space-between;
        }

        .<?php echo $c['hamburger']; ?> {
            width: 30px;
            height: 25px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            cursor: pointer;
            z-index: 1001;
        }

        .<?php echo $c['hamburger']; ?> span {
            width: 100%;
            height: 3px;
            background: var(--<?php echo $c['text']; ?>);
            transition: all 0.3s;
            border-radius: 2px;
        }

        .<?php echo $c['hamburger']; ?>.active span:nth-child(1) {
            transform: rotate(45deg) translate(8px, 8px);
        }

        .<?php echo $c['hamburger']; ?>.active span:nth-child(2) {
            opacity: 0;
        }

        .<?php echo $c['hamburger']; ?>.active span:nth-child(3) {
            transform: rotate(-45deg) translate(8px, -8px);
        }

        .<?php echo $c['mobile_logo']; ?> {
            max-height: 40px;
        }
        
        .<?php echo $c['mobile_logo']; ?> img {
            max-height: 40px;
        }
        
        /* Mobile Menu Overlay */
        .<?php echo $c['nav_list']; ?>.mobile-menu {
            flex-direction: column;
            gap: 0px;
            position: fixed;
            top: 0;
            left: -100%;
            width: 80%;
            max-width: 300px;
            height: 100vh;
            background: var(--<?php echo $c['bg']; ?>);
            padding: 80px 20px 20px;
            transition: left 0.3s ease;
            z-index: 999;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .<?php echo $c['nav_list']; ?>.mobile-menu.active {
            left: 0;
        }
        
        .<?php echo $c['nav_list']; ?>.mobile-menu li {
            margin-bottom: 15px;
        }
        
        .<?php echo $c['nav_list']; ?>.mobile-menu li a {
            display: block;
            padding: 10px 0;
            font-size: 18px;
        }

        /* Desktop Header */
        header.<?php echo $c['desktop_header']; ?> {
            background: var(--<?php echo $c['bg']; ?>);
            border-bottom: 1px solid var(--<?php echo $c['border']; ?>);
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        header.<?php echo $c['desktop_header']; ?> .<?php echo $c['container']; ?> {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .<?php echo $c['brand_logo']; ?> img {
            max-height: 50px;
        }

        .<?php echo $c['nav_list']; ?> {
            list-style: none;
            padding: 0;
            display: flex;
            gap: 30px;
            margin: 0;
        }

        .<?php echo $c['nav_list']; ?> li a {
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .<?php echo $c['nav_list']; ?> li a:hover {
            text-decoration: underline;
        }

        /* Main Content */
        .<?php echo $c['main_wrapper']; ?> {
            padding: 40px 0;
        }
        figure img {
    width: 100%;
    max-height: 450px;
    object-fit: contain;
}
        
        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table tr:nth-child(odd) {
            background: <?php echo esc_attr($table_odd); ?>;
        }
        table tr:nth-child(even) {
            background: <?php echo esc_attr($table_even); ?>;
        }
        table td, table th {
            padding: 10px;
            text-align: left;
            border: 1px solid var(--<?php echo $c['border']; ?>);
        }
        /* Casino Items from User Request */
        .<?php echo $c['casinos_list']; ?> {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin: 0 auto;
            margin: 20px 0;
        }

        .<?php echo $c['casino_item']; ?> {
            display: grid;
            grid-template-columns: 150px .7fr .7fr 100px 140px;
            gap: 20px;
            align-items: center;
            background: <?php echo esc_attr($casino_card_bg); ?>;
            color: <?php echo esc_attr($casino_text_color); ?>;
            border-radius: 10px;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border: 2px solid transparent;
        }
        .<?php echo $c['casino_item']; ?> ul{
            margin-bottom: 0!important;
        }
        .<?php echo $c['casino_item']; ?> ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .<?php echo $c['casino_item']; ?> ul li {
            position: relative;
            padding-left: 28px;
            margin-bottom: 8px;
        }

        .<?php echo $c['casino_item']; ?> ul li::before {
            content: "✔";
            position: absolute;
            left: 0;
            top: 0;
            color: #28a745;
            font-weight: bold;
            font-size: 16px;
            line-height: 1.2;
        }

        .<?php echo $c['casino_item']; ?>.casino-border {
            border-color: #FFD54F;
        }

        .<?php echo $c['casino_logo']; ?> {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .<?php echo $c['casino_logo']; ?> img {
            max-height: 50px;
            margin-bottom: 5px;
        }

        .<?php echo $c['casino_name']; ?> {
            font-size: 14px;
            color: #888;
        }

        .<?php echo $c['casino_bonus']; ?> {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            line-height: 120%;
            margin-bottom: 0;
            letter-spacing: -0.02em;
            text-transform: capitalize;
            color: #1C2642;
        }

        .<?php echo $c['casino_bonus']; ?> ul {
            margin: 5px 0 0;
            padding-left: 18px;
            font-weight: normal;
            font-size: 14px;
            color: #333;
        }

        .<?php echo $c['casino_bonus']; ?> li {
            margin-bottom: 3px;
        }

        .<?php echo $c['casino_rating']; ?> {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
        }

        .<?php echo $c['casino_rating']; ?> .<?php echo $c['rating_stars']; ?> {
            display: block;
            font-size: 14px;
            color: #f5c518;
        }

        .<?php echo $c['casino_btn']; ?> {
            text-align: right;
        }

        .<?php echo $c['btn_play']; ?> {
            display: inline-block;
            padding: 10px 20px;
            background: <?php echo $cs_bg; ?>;
            color: <?php echo $cs_color; ?> !important;
            font-weight: bold;
            border-radius: 30px;
            text-decoration: none;
            transition: 0.3s;
        }

        .<?php echo $c['btn_play']; ?>:hover {
            transition:.4s;
           transform:scale(1.05);
        }

        /* Author Box */
        .<?php echo $c['sc_author_box']; ?> {
            background: <?php echo $author_color; ?>;
            border-radius: 8px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 20px 0;
        }

        .<?php echo $c['sc_author_avatar']; ?> {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .<?php echo $c['sc_author_info']; ?> {
            flex: 1;
        }

        .<?php echo $c['sc_author_info']; ?> a {
            text-decoration: none;
            color: var(--<?php echo $c['text']; ?>);
            font-weight: bold;
        }

        .<?php echo $c['sc_author_info']; ?> a:hover {
            text-decoration: underline;
        }

        /* FAQ Section */
        .<?php echo $c['faq_section']; ?> {
            margin: 40px 0;
        }

        .<?php echo $c['faq_item']; ?> {
            border: 1px solid var(--<?php echo $c['border']; ?>);
            border-radius: 8px;
            margin-bottom: 15px;
            overflow: hidden;
            background: <?php echo $faq_bg; ?>;
        }

        .<?php echo $c['faq_question']; ?> {
            padding: 15px 20px;
            margin: 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            position: relative;
            transition: background 0.3s, color 0.3s;
        }

        .<?php echo $c['faq_item']; ?>.active .<?php echo $c['faq_question']; ?> {
            color: <?php echo $faq_color; ?>;
        }

        .<?php echo $c['faq_question']; ?>:hover {
            background: rgba(0,0,0,0.02);
        }

        .<?php echo $c['faq_question']; ?>::after {
            content: '+';
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            transition: transform 0.3s;
        }

        .<?php echo $c['faq_item']; ?>.active .<?php echo $c['faq_question']; ?>::after {
            content: '−';
            transform: rotate(180deg);
        }

        .<?php echo $c['faq_answer']; ?> {
            max-height: 0;
            padding: 0 20px;
            margin: 0;
            overflow: hidden;
            transition: all 0.4s ease;
            opacity: 0;
            line-height: 1.6;
        }

        .<?php echo $c['faq_item']; ?>.active .<?php echo $c['faq_answer']; ?> {
            max-height: 500px;
            padding: 15px 20px;
            opacity: 1;
        }

        /* Footer */
        footer{
                        background: <?php echo $footer_color; ?>;
        }
        .<?php echo $c['footer_nav']; ?> {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 40px 20px;
        
            list-style: none;
            margin: 0;
        }

        .<?php echo $c['footer_nav']; ?> a {
            text-decoration: none;
            text-align: center;
            color: <?php echo $footer_color_text; ?> !important;
        }

        .<?php echo $c['footer_nav']; ?> a:hover {
            text-decoration: underline;
        }

        .<?php echo $c['copyright']; ?> {
            padding: 20px;
            text-align: center;
            background: <?php echo $footer_color; ?>;
            border-top: 1px solid var(--<?php echo $c['border']; ?>);
            color: <?php echo $footer_color_text; ?>;
        }
        
        .<?php echo $c['copyright'] ?> p {
             color: <?php echo $footer_color_text; ?> !important;
        }

        /* Fixed Casino Popups */
        .<?php echo $c['fixed_sidebar']; ?> {
            color: white !important;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
            padding: 10px 0;
            z-index: 9999;
        }

        .<?php echo $c['fixed_sidebar']; ?> .head {
            font-weight: 600;
            font-size: 1.2em !important;
            margin-bottom: 0.3em;
            margin-top: 0;
        }

        .<?php echo $c['fixed_sidebar']; ?> .bottom {
            margin-bottom: 0.3em;
            margin-top: 0;
        }

        .<?php echo $c['fixed_sidebar']; ?> img {
            max-width: 150px;
        }

        .<?php echo $c['fixed_sidebar']; ?> .<?php echo $c['container']; ?> {
            display: flex;
            gap: 20px;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        .<?php echo $c['fixed_sidebar']; ?> a {
            margin-right: 2vw;
            border-radius: 16px;
            padding: 12px 20px;
            text-decoration: none !important;
            transition: 0.5s;
            color: white !important;
            font-weight: bold;
        }

        .<?php echo $c['fixed_sidebar']; ?> a:hover {
            transform: scale(1.05);
        }

        .<?php echo $c['logo_fixed']; ?> {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
        }

        .<?php echo $c['logo_fixed']; ?> p {
            margin-bottom: 0.3em;
            margin-top: 0;
        }

        .<?php echo $c['fixed_close']; ?> {
            position: absolute;
            top: -8px;
            right: 12px;
            font-size: 32px;
            color: white;
            cursor: pointer;
            line-height: 1;
            transition: 0.3s;
        }

        .<?php echo $c['fixed_close']; ?>:hover {
            transform: rotate(90deg);
        }

        /* Fixed Casino Responsive */
        .<?php echo $c['fixed_sidebar']; ?>.mobile {
            display: none;
        }

        @media screen and (max-width: 991px) {
            .<?php echo $c['fixed_sidebar']; ?>.desktop {
                display: none;
            }
            .<?php echo $c['fixed_sidebar']; ?>.mobile {
                display: block;
            }
            .<?php echo $c['fixed_sidebar']; ?>.mobile .<?php echo $c['container']; ?> {
                flex-direction: column;
            }
            .<?php echo $c['fixed_sidebar']; ?>.mobile a {
                width: 100%;
                text-align: center;
            }
        }
        figure{
            margin: 0;
        }
        figure table{
            width: 100%;
        }
         .sc-author-avatar {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                object-fit: cover;
            }
        .sc-author-box {

                border-radius: 8px;
                padding: 10px 15px;
                display: flex;
                align-items: center;
                gap: 15px;
                margin: 20px 0;
                align-items: center;
            }
            
        /* Mobile responsive */
        @media (max-width: 768px) {
            .<?php echo $c['mobile_header']; ?> {
                display: flex !important;
            }
            
            header.<?php echo $c['desktop_header']; ?> {
                display: none;
            }
            
            .<?php echo $c['main_wrapper']; ?> {
                padding-top: 80px;
            }
        }
        
        /* Адаптив */
        @media (max-width: 768px) {

            .casino-item {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .casino-logo {
                align-items: center;
            }
            .casino-btn {
                margin-top: 10px;
                text-align: center;
            }
        }

        @media (max-width: 768px) {
            .casino-item {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px 15px;
                align-items: center;
                text-align: left;
            }

            /* Логотип */
            .casino-logo {
                grid-column: 1 / 2;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .casino-logo img {
                max-height: 60px;
                width: auto;
            }

            /* Рейтинг */
            .casino-rating {
                grid-column: 2 / 2;
                justify-self: end;
                text-align: right;
                grid-row: 1;
            }
            .casino-rating .rating-stars {
                display: flex;
                gap: 2px;
                justify-content: flex-end;
            }

            /* Бонус — на всю ширину */
            .casino-bonus {
                grid-column: 1 / -1;
                font-size: 16px;
                font-weight: bold;
                text-align: center;
                margin: 5px 0;
            }

            /* Список — на всю ширину */
            .casino-list {
                grid-column: 1 / -1;
            }

            /* Кнопка — на всю ширину */
            .casino-btn {
                grid-column: 1 / -1;
                text-align: center;
                grid-row: 3;
            }

            .btn-play {
                width: 100%;
                display: inline-block;
                padding: 12px;
            }
            .casino-name{
                display: none;
            }
            .sc-author-box {

                border-radius: 8px;
                padding: 10px 15px;
                display: flex;
                align-items: center;
                gap: 15px;
                margin: 20px 0;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .sc-author-avatar {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                object-fit: cover;
            }
            .sc-author-info {
                text-align: center;
            }
            .sc-author-info a {
                text-decoration: none;
                color: #000;
            }
            .sc-author-info a:hover {
                text-decoration: underline;
            }
            
            /* Responsive tables */
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            /* Mobile Casino Item Styles */
            .<?php echo $c['casino_item']; ?> {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px 15px;
                align-items: center;
                text-align: left;
            }

            /* Logo */
            .<?php echo $c['casino_logo']; ?> {
                grid-column: 1 / 2;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .<?php echo $c['casino_logo']; ?> img {
                max-height: 60px;
                width: auto;
            }

            /* Rating */
            .<?php echo $c['casino_rating']; ?> {
                grid-column: 2 / 2;
                justify-self: end;
                text-align: right;
                grid-row: 1;
            }
            .<?php echo $c['casino_rating']; ?> .<?php echo $c['rating_stars']; ?> {
                display: flex;
                gap: 2px;
                justify-content: flex-end;
            }

            /* Bonus */
            .<?php echo $c['casino_bonus']; ?> {
                grid-column: 1 / -1;
                font-size: 16px;
                font-weight: bold;
                text-align: center;
                margin: 5px 0;
            }

            /* List */
            .<?php echo $c['casino_list']; ?> {
                grid-column: 1 / -1;
            }

            /* Button */
            .<?php echo $c['casino_btn']; ?> {
                grid-column: 1 / -1;
                text-align: center;
                grid-row: 3;
            }
            figure{
                margin: 0;
            }
            figure img{
                    width: 100%;
    min-height: 180px;
    object-fit: cover;
            }
             .<?php echo $c['footer_nav']; ?> {
            display: grid;
            grid-template-columns:  1fr;
            gap: 20px;
            padding: 40px 20px;

            list-style: none;
            margin: 0;
        }
      
        }
          footer ul{
             list-style: none;
        padding: 0;
        }
    </style>
</head>

<body>
    <!-- Mobile Header -->
    <div class="<?php echo $c['mobile_header']; ?>">
        <div class="<?php echo $c['mobile_logo']; ?>">
            <?php if (!empty($logo)): ?>
                <a href="<?php echo esc_url($current_url); ?>">
                    <img src="<?php echo esc_url($logo); ?>" alt="Logo">
                </a>
            <?php endif; ?>
        </div>
        <div class="<?php echo $c['hamburger']; ?>">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    
    <!-- Mobile Menu Overlay -->
    <ul class="<?php echo $c['nav_list']; ?> mobile-menu">
        <?php if (!empty($menu_items)): ?>
            <?php foreach ($menu_items as $item): ?>
                <li><a href="<?php echo esc_url($item['link']); ?>"><?php echo esc_html($item['name']); ?></a></li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <!-- Desktop Header -->
    <header class="<?php echo $c['desktop_header']; ?>">
        <div class="<?php echo $c['container']; ?>">
            <div class="<?php echo $c['brand_logo']; ?>">
                <?php if (!empty($logo)): ?>
                    <a href="<?php echo esc_url($current_url); ?>">
                        <img src="<?php echo esc_url($logo); ?>" alt="Logo">
                    </a>
                <?php endif; ?>
            </div>
            <ul class="<?php echo $c['nav_list']; ?>">
                <?php if (!empty($menu_items)): ?>
                    <?php foreach ($menu_items as $item): ?>
                        <li><a href="<?php echo esc_url($item['link']); ?>"><?php echo esc_html($item['name']); ?></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <!-- Main Wrapper -->
    <div class="<?php echo $c['main_wrapper']; ?>">
        <div class="<?php echo $c['container']; ?>">
            <h1><?php echo esc_html($title_page); ?></h1>
            <?php if (!empty($author_name) || !empty($author_avatar)): ?>
                <div class="<?php echo $c['sc_author_box']; ?>" style="background: <?php echo esc_attr($author_color); ?> !important;">
                    <?php if (!empty($author_avatar)): ?>
                        <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr($author_name); ?>" class="<?php echo $c['sc_author_avatar']; ?>">
                    <?php endif; ?>
                    <div class="<?php echo $c['sc_author_info']; ?>">
                        <?php if (!empty($author_name)): ?>
                            <strong>Author: <?php echo esc_html($author_name); ?></strong><br>
                        <?php endif; ?>
                        <span>Last updated: <?php echo date('d F Y'); ?></span>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (!empty($casino_list) && is_array($casino_list)): ?>
                <div class="<?php echo $c['casinos_list']; ?>">
                    <?php foreach ($casino_list as $casino): ?>
                        <?php 
                        $border_class = !empty($casino['border']) ? ' casino-border' : '';
                        $border_style = !empty($casino['border']) ? 'style="border-color: ' . esc_attr($casino['border']) . ';"' : '';
                        ?>
                        <div class="<?php echo $c['casino_item']; ?><?php echo $border_class; ?>" <?php echo $border_style; ?>>
                            <div class="<?php echo $c['casino_logo']; ?>">
                                <?php if (!empty($casino['logo'])): ?>
                                    <img src="<?php echo esc_url($casino['logo']); ?>" alt="<?php echo esc_attr($casino['name'] ?? ''); ?>">
                                <?php endif; ?>
                                <?php if (!empty($casino['name'])): ?>
                                    <div class="<?php echo $c['casino_name']; ?>"><?php echo esc_html($casino['name']); ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="<?php echo $c['casino_bonus']; ?>">
                                <?php if (!empty($casino['bonus'])): ?>
                                    <strong><?php echo esc_html($casino['bonus']); ?></strong>
                                <?php endif; ?>
                            </div>
                            
                            <div class="<?php echo $c['casino_list']; ?>">
                                <?php if (!empty($casino['list'])): ?>
                                    <?php echo wp_kses_post($casino['list']); ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="<?php echo $c['casino_rating']; ?>">
                                <?php 
                                $max_stars = 5;
                                $rating_10 = floatval($casino['rating'] ?? 0);
                                $rating_5 = ($rating_10 / 10) * $max_stars;
                                
                                $full_stars = floor($rating_5);
                                $half_star = ($rating_5 - $full_stars >= 0.5) ? 1 : 0;
                                $empty_stars = $max_stars - $full_stars - $half_star;
                                
                                echo '<span class="' . $c["rating_number"] . '">' . esc_html($rating_10) . '/10</span>';
                                echo '<span class="' . $c["rating_stars"] . '">';
                                
                                // SVG полной звезды
                                $star_full = '<svg width="20" height="20" viewBox="0 0 24 24" fill="#FFD700"><path d="M12 .587l3.668 7.431L24 9.753l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.6 0 9.753l8.332-1.735z"/></svg>';
                                // SVG половинки
                                $star_half = '<svg width="20" height="20" viewBox="0 0 24 24"><defs><linearGradient id="half"><stop offset="50%" stop-color="#FFD700"/><stop offset="50%" stop-color="#ccc"/></linearGradient></defs><path fill="url(#half)" d="M12 .587l3.668 7.431L24 9.753l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.6 0 9.753l8.332-1.735z"/></svg>';
                                // SVG пустой
                                $star_empty = '<svg width="20" height="20" viewBox="0 0 24 24" fill="#ccc"><path d="M12 .587l3.668 7.431L24 9.753l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.6 0 9.753l8.332-1.735z"/></svg>';
                                
                                // Полные
                                for ($i = 0; $i < $full_stars; $i++) {
                                    echo $star_full;
                                }
                                // Половинка
                                if ($half_star) {
                                    echo $star_half;
                                }
                                // Пустые
                                for ($i = 0; $i < $empty_stars; $i++) {
                                    echo $star_empty;
                                }
                                
                                echo '</span>';
                                ?>
                            </div>
                            
                            <div class="<?php echo $c['casino_btn']; ?>">
                                <?php if (!empty($casino['link'])): ?>
                                    <a href="<?php echo esc_url($casino['link']); ?>" class="<?php echo $c['btn_play']; ?>" target="_blank">PLAY NOW</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($content_text)): ?>
                <div class="<?php echo $c['content_area']; ?>">
                    <?php echo wp_kses_post($content_text); ?>
                </div>
            <?php endif; ?>



            <!-- FAQ Section -->
            <?php if (!empty($faq_list)): ?>
                <div class="<?php echo $c['faq_section']; ?>">
                    <?php foreach ($faq_list as $faq): ?>
                        <div class="<?php echo $c['faq_item']; ?>">
                            <h3 class="<?php echo $c['faq_question']; ?>"><?php echo esc_html($faq['question']); ?></h3>
                            <div class="<?php echo $c['faq_answer']; ?>">
                                <?php echo wp_kses_post($faq['answer']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="<?php echo $c['footer_wrapper']; ?>">
        <div class="<?php echo $c['container']; ?>">
            <div class="<?php echo $c['footer_nav']; ?>">
                <div class="footer-col">
                    <?php if (!empty($logo)): ?>
                        <img src="<?php echo esc_url($logo); ?>" alt="Logo" style="max-width: 150px; margin-bottom: 15px;">
                    <?php endif; ?>
                    <p><?php echo wp_kses_post($footer_text); ?></p>
                </div>
                <div class="footer-col">
                    <?php if (!empty($footer_menu1) && is_array($footer_menu1)): ?>
                        <ul>
                            <?php foreach ($footer_menu1 as $item): ?>
                                <li><a href="<?php echo esc_url($item['link']); ?>"><?php echo esc_html($item['name']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="footer-col">
                    <?php if (!empty($footer_menu2) && is_array($footer_menu2)): ?>
                        <ul>
                            <?php foreach ($footer_menu2 as $item): ?>
                                <li><a href="<?php echo esc_url($item['link']); ?>"><?php echo esc_html($item['name']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="<?php echo $c['copyright']; ?>">
            <p>&copy; <?php echo date('Y'); ?> <?php echo esc_html($title_page); ?>. All rights reserved.</p>
        </div>
    </footer>

    <!-- Fixed Casino Desktop -->
    <?php if (is_array($enable_disable) && in_array('Enable', $enable_disable)): ?>
        <div class="<?php echo $c['fixed_sidebar']; ?> desktop" style="background: <?php echo esc_attr($bg_fixed); ?>;">
            <div class="<?php echo $c['container']; ?>">
                <span class="<?php echo $c['fixed_close']; ?>">&times;</span>
                <div class="<?php echo $c['logo_fixed']; ?>">
                    <?php if ($fixed_logo): ?>
                        <img src="<?php echo esc_url($fixed_logo); ?>" alt="Casino">
                    <?php endif; ?>
                    <div class="<?php echo $c['fixed_content']; ?>">
                        <p class="head"><?php echo esc_html($fixed_title); ?></p>
                        <p class="bottom"><?php echo esc_html($fixed_subtitle); ?></p>
                    </div>
                </div>
                <a href="<?php echo esc_url($fixed_link); ?>" style="background: <?php echo esc_attr($fixed_btn_bg); ?> !important;">
                    <?php echo esc_html($btn_name); ?>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Fixed Casino Mobile -->
    <?php if (is_array($enable_disable_mobile) && in_array('Enable', $enable_disable_mobile)): ?>
        <div class="<?php echo $c['fixed_sidebar']; ?> mobile" style="background: <?php echo esc_attr($bg_fixed_mobile); ?>;">
            <div class="<?php echo $c['container']; ?>">
                <span class="<?php echo $c['fixed_close']; ?>">&times;</span>
                <div class="<?php echo $c['logo_fixed']; ?>">
                    <?php if ($fixed_logo_mobile): ?>
                        <img src="<?php echo esc_url($fixed_logo_mobile); ?>" alt="Casino">
                    <?php endif; ?>
                    <div class="<?php echo $c['fixed_content']; ?>">
                        <p class="head"><?php echo esc_html($fixed_title_mobile); ?></p>
                        <p class="bottom"><?php echo esc_html($fixed_subtitle_mobile); ?></p>
                    </div>
                </div>
                <a href="<?php echo esc_url($fixed_link_mobile); ?>" style="background: <?php echo esc_attr($fixed_btn_bg_mobile); ?> !important;">
                    <?php echo esc_html($btn_name_mobile); ?>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // FAQ Toggle
            const faqItems = document.querySelectorAll('.<?php echo $c['faq_item']; ?>');
            faqItems.forEach(item => {
                const question = item.querySelector('.<?php echo $c['faq_question']; ?>');
                question.addEventListener('click', function() {
                    item.classList.toggle('active');
                });
            });

            // Mobile Menu Toggle
            const hamburger = document.querySelector('.<?php echo $c['hamburger']; ?>');
            const mobileMenu = document.querySelector('.<?php echo $c['nav_list']; ?>.mobile-menu');
            
            if (hamburger && mobileMenu) {
                hamburger.addEventListener('click', function() {
                    this.classList.toggle('active');
                    mobileMenu.classList.toggle('active');
                });
                
                // Close menu when clicking on a link
                const menuLinks = mobileMenu.querySelectorAll('a');
                menuLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        hamburger.classList.remove('active');
                        mobileMenu.classList.remove('active');
                    });
                });
            }

            // Fixed Casino Close Buttons
            const closeButtons = document.querySelectorAll('.<?php echo $c['fixed_close']; ?>');
            closeButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    this.closest('.<?php echo $c['fixed_sidebar']; ?>').style.display = 'none';
                });
            });
        });
    </script>
</body>
</html>
