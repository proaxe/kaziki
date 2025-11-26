<?php
/**
 * Template 1 HTML - Casino/Slots Template
 * 
 * Variables available:
 * - All $fields and $option_fields variables are extracted and available
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
    'bg', 'text', 'link', 'border',  // CSS variables
    'mobile_header', 'hamburger', 'mobile_logo', 'desktop_header',
    'sidebar', 'brand_logo', 'action_btns', 'btn_primary', 'btn_secondary',
    'nav_list', 'nav_icon', 'main_wrapper', 'content_area', 'hero_section',
    'category_tabs', 'game_grid', 'game_card', 'game_overlay', 'game_title',
    'container', 'faq_section', 'faq_item', 'faq_question', 'faq_answer',
    'payments_bar', 'partners_bar', 'footer_nav', 'copyright'
);

foreach ($elements as $element) {
    $prefix = $prefixes[array_rand($prefixes)];
    $random = substr(md5(uniqid($element . time(), true)), 0, 6);
    $c[$element] = $prefix . '-' . $random;
}
?>
<!DOCTYPE html>
<html>

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
            --kz-faq: #fff;
            --<?php echo $c['bg']; ?>-faq: #13212d;
            --<?php echo $c['link']; ?>: <?php echo $color_link; ?>;
            --<?php echo $c['link']; ?>-hover: #ecbe15;
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
            line-height: 1;
            margin: 0;
            outline: 0;
            text-align: left;
            top: 0 !important;
            position: relative;
            scroll-behavior: smooth;
            text-rendering: optimizeSpeed;
        }

        h1, h2, h3, h4, h5, h6 {
            letter-spacing: .03em;
            font-weight: 700;
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

        h4 {
            font-size: 18px;
            line-height: 22px;
        }

        h5 {
            font-size: 16px;
            line-height: 20px;
        }

        p {
            line-height: 150%;
            font-weight: 500;
        }
        li{
            margin-bottom:.9em;
        }
        a {
            color: <?php echo $color_link; ?>;
        }

        a:active, a:focus, a:hover {
            outline: 0;
        }

        table {
            border-collapse: collapse;
            margin: 1rem auto;
            padding-bottom: 1rem;
            text-align: center;
            display:table;
            overflow-x: auto;
            white-space: nowrap;
            padding: 11px 15px;
            border: 2px solid var(--<?php echo $c['border']; ?>);
            border-radius: 18px;
            width: 100%;
        }

        table td {
            padding: 11px 15px;
            border: 2px solid var(--<?php echo $c['border']; ?>);
            border-style: none solid solid none;
            background: <?php echo $color_second; ?>;
            white-space: normal;
        }

        table tr:nth-child(odd) td {
            background: <?php echo $table_odd; ?>;
        }

        table tr:nth-child(even) td {
            background: <?php echo $table_even; ?>;
        }

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

        header.<?php echo $c['desktop_header']; ?> {
            display: none!important;
        }

        .<?php echo $c['sidebar']; ?> {
            position: fixed;
            padding: 30px;
            display: flex;
            height: 100vh;
            flex-direction: column;
            gap: 20px;
            align-items: center;
            justify-content: start;
            max-width: 300px;
            width: 100%;
            border-right: 1px solid var(--<?php echo $c['border']; ?>);
            background: var(--<?php echo $c['bg']; ?>);
            z-index: 999;
            overflow-y: auto;
        }

        .<?php echo $c['brand_logo']; ?> {
            margin: 15px 0;
        }

        .<?php echo $c['brand_logo']; ?> img {
            max-width: 100%;
            height: auto;
        }

        .<?php echo $c['action_btns']; ?> {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
        }

        .<?php echo $c['btn_primary']; ?> {
            color: <?php echo $btn_text; ?> !important;
            background: <?php echo $btn_bg; ?>;
            border: 1px solid var(--<?php echo $c['border']; ?>);
            text-decoration: none;
            max-height: 48px;
            padding: 12px 20px;
            border-radius: 40px;
            font-size: 16px;
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
            width: 100%;
            transition: .6s;
            font-weight: bold;
            width: -webkit-fill-available;
        }

        .<?php echo $c['btn_secondary']; ?> {
            color: <?php echo $btn2_text; ?>;
            background: <?php echo $btn2_bg; ?>;
            border: 1px solid var(--<?php echo $c['border']; ?>);
            text-decoration: none;
            max-height: 48px;
            padding: 12px 20px;
            border-radius: 40px;
            font-size: 16px;
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
            width: -webkit-fill-available;
            transition: .6s;
            font-weight: bold;
        }

        .<?php echo $c['btn_primary']; ?>:hover, .<?php echo $c['btn_secondary']; ?>:hover {
            transition: .6s;
            transform: scale(1.05);
        }

        .button {
            color: <?php echo $btn_text; ?> !important;
            background: <?php echo $btn_bg; ?>;
            border: 1px solid var(--<?php echo $c['border']; ?>);
            text-decoration: none;
            max-height: 48px;
            padding: 12px 20px;
            border-radius: 40px;
            font-size: 16px;
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
            width: 100%;
            transition: .6s;
            font-weight: bold;
                width: fit-content;
    min-width: 320px;
    margin:auto;
        }

        .btn {
            color: <?php echo $btn2_text; ?>;
            background: <?php echo $btn2_bg; ?>;
            border: 1px solid var(--<?php echo $c['border']; ?>);
            text-decoration: none;
            max-height: 48px;
            padding: 12px 20px;
            border-radius: 40px;
            font-size: 16px;
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
                width: fit-content;
    min-width: 320px;
            transition: .6s;
            font-weight: bold;
             margin:auto;
        }

        .button:hover, .btn:hover {
            transition: .6s;
            transform: scale(1.05);
        }

        .<?php echo $c['nav_list']; ?> {
            list-style: none;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
        }

        .<?php echo $c['container']; ?> .<?php echo $c['nav_list']; ?> {
            list-style: inherit;
            padding-left: 40px;
        }

        .<?php echo $c['nav_list']; ?> li a {
            display: flex;
            gap: 12px;
            align-items: center;
            text-decoration: none;
            justify-content: center;
        }

        .<?php echo $c['nav_list']; ?> li a:hover {
            text-decoration: underline;
        }

        .<?php echo $c['nav_icon']; ?> {
            width: 24px;
            height: 24px;
            background: <?php echo $color_link; ?>;
            -webkit-mask-size: contain;
            mask-size: contain;
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            display: inline-block;
        }

        .<?php echo $c['main_wrapper']; ?> {
            padding: 20px 0px 0px 300px;
        }

       

        .<?php echo $c['content_area']; ?> {
            padding: 0 24px;
        }

        .<?php echo $c['content_area']; ?> img {
            width: 100%;
            border-radius: 18px;
        }

        .<?php echo $c['content_area']; ?> p img {
            margin: auto;
            width: 100%;
            max-height: 500px;
            width: fit-content;
            margin: 0 auto;
            display: flex;
        }

        .<?php echo $c['category_tabs']; ?> {
            display: flex;
            gap: 20px;
            flex-direction: row;
            overflow-x: auto;
            padding: 10px 0;
            list-style: none;
        }

        .<?php echo $c['category_tabs']; ?> a {
            border: 2px solid <?php echo $color_main; ?>;
            color: <?php echo $color_main; ?>;
            font-weight: bold;
            border-radius: 40px;
            padding: 5px 10px;
            text-decoration: none;
            transition: .5s;
            text-transform: uppercase;
            white-space: nowrap;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .<?php echo $c['category_tabs']; ?> a:hover {
            text-decoration: none;
            transition: .5s;
            filter: hue-rotate(45deg);
        }

        .<?php echo $c['category_tabs']; ?> .<?php echo $c['nav_icon']; ?> {
            width: 20px;
            height: 20px;
        }

        .<?php echo $c['game_grid']; ?> {
            display: flex;
            gap: 12px;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .<?php echo $c['game_card']; ?> {
            width: 15%;
            position: relative;
            border-radius: 18px;
            overflow: hidden;
        }

        .<?php echo $c['game_overlay']; ?> {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            background: #080808ad;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            opacity: 0;
            transition: .6s;
        }

        .<?php echo $c['game_overlay']; ?> .<?php echo $c['btn_secondary']; ?>, .<?php echo $c['game_overlay']; ?> .<?php echo $c['btn_primary']; ?> {
            width: fit-content;
        }

        .<?php echo $c['game_card']; ?>:hover .<?php echo $c['game_overlay']; ?> {
            opacity: 1;
            transition: .6s;
        }

        .<?php echo $c['game_card']; ?> .<?php echo $c['game_title']; ?> {
            position: absolute;
            bottom: 0;
            width: 100%;
            right: 0;
            padding: 3px;
            color: <?php echo $btn2_text; ?>;
            background: <?php echo $btn2_bg; ?>;
            font-size: 14px;
            text-align: center;
        }

        .<?php echo $c['faq_section']; ?> {
            width: 100%;
            margin: 0 auto;
        }

        .<?php echo $c['faq_item']; ?> {
            border: 1px solid var(--<?php echo $c['border']; ?>);
            border-radius: 18px;
            margin-bottom: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .<?php echo $c['faq_question']; ?> {
            background: <?php echo $color_second; ?>;
            padding: 15px 20px;
            margin: 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            position: relative;
            transition: all 0.3s ease;
        }

        .<?php echo $c['faq_question']; ?>:hover {
            background: <?php echo $color_second; ?>;
        }

        .<?php echo $c['faq_question']; ?>::after {
            content: '+';
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            font-weight: 300;
            transition: all 0.3s ease;
        }

        .<?php echo $c['faq_item']; ?>.active .<?php echo $c['faq_question']; ?>::after {
            content: '−';
            transform: translateY(-50%) rotate(0deg);
        }

        .<?php echo $c['faq_answer']; ?> {
            max-height: 0;
            padding: 0 20px;
            margin: 0;
            overflow: hidden;
            background: <?php echo $color_second; ?>;
            transition: all 0.4s ease;
            opacity: 0;
            line-height: 1.6;
        }

        .<?php echo $c['faq_item']; ?>.active .<?php echo $c['faq_answer']; ?> {
            max-height: 500px;
            padding: 15px 20px;
            opacity: 1;
        }

        .<?php echo $c['faq_item']; ?>.active {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .<?php echo $c['payments_bar']; ?> {
            background: <?php echo $color_second; ?>;
            display: flex;
            justify-content: center;
            padding: 40px;
            gap: 20px 40px;
            flex-direction: row;
            flex-wrap: wrap;
            align-items: center;
            list-style:none;
            margin: 0;
            border-bottom: 1px solid #C4C9DA;
        }

        .<?php echo $c['payments_bar']; ?> img, .<?php echo $c['partners_bar']; ?> img {
            max-height: 60px;
        }

        .<?php echo $c['partners_bar']; ?> {
            display: flex;
            justify-content: center;
            padding: 40px;
             list-style:none;
            gap: 20px 40px;
            flex-direction: row;
            flex-wrap: wrap;
            align-items: center;
            margin: 0;
            border-bottom: 1px solid #C4C9DA;
        }

        .<?php echo $c['footer_nav']; ?> {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            padding: 40px 20px;
            margin: 0;
            background: <?php echo $color_second; ?>;
            list-style: none;
            text-align:center;
        }

        .<?php echo $c['footer_nav']; ?> a {
            text-decoration: underline;
            text-align: center;
            justify-content: center;
        }

        .<?php echo $c['copyright']; ?> {
            padding: 20px;
            margin: 0;
            text-align: center;
            border-top: 1px solid #C4C9DA;
        }


        .<?php echo $c['mobile_header']; ?>-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .<?php echo $c['mobile_header']; ?>-overlay.active {
            display: block;
            opacity: 1;
             z-index: 1001;
        }

 
        @media (max-width: 991px) {
            .<?php echo $c['mobile_header']; ?> {
                display: flex;
            }

            .<?php echo $c['sidebar']; ?> {
                left: -100%;
                transition: left 0.3s ease;
                top: 0;
                box-shadow: 2px 0 10px rgba(0,0,0,0.1);
                z-index: 1001;
            }

            .<?php echo $c['sidebar']; ?>.active {
                left: 0;
            }

            .<?php echo $c['main_wrapper']; ?> {
                padding: 80px 0 0 0;
            }

            .<?php echo $c['game_card']; ?> {
                width:100%;
            }

            .<?php echo $c['footer_nav']; ?> {
                grid-template-columns: 1fr;
            }
                    .<?php echo $c['content_area']; ?> p img {
            margin: auto;
            max-height: 500px;
            width: 100%;
            margin: 0 auto;
            display: flex;
        }
          .<?php echo $c['game_grid']; ?> {
            display: grid;
            gap: 12px;
           grid-template-columns:1fr 1fr;
        }
         .<?php echo $c['content_area']; ?> {
            padding: 0 15px;
        }
                .<?php echo $c['payments_bar']; ?> img, .<?php echo $c['partners_bar']; ?> img {
            max-height: 30px;
        }
         .<?php echo $c['sidebar']; ?> {
            position: fixed;
            padding: 20px;
            display: flex;
            height: 100vh;
            flex-direction: column;
            gap: 20px;
            align-items: center;
            justify-content: start;
            max-width: 250px;
            width: 100%;
            border-right: 1px solid var(--<?php echo $c['border']; ?>);
            background: var(--<?php echo $c['bg']; ?>);
            z-index: 1005;
            overflow-y: auto;
        }
         table {
            border-collapse: collapse;
            margin: 1rem auto;
            padding-bottom: 1rem;
            text-align: center;
            display: block;
            overflow-x: auto;
            white-space: nowrap;
            padding:0;
            border: 2px solid var(--<?php echo $c['border']; ?>);
            border-radius: 18px;
            width: 100%;
        }
        }

        @media (min-width: 992px) {
            .<?php echo $c['desktop_header']; ?> {
                display: block!important;
            }
          
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.<?php echo $c['faq_item']; ?>');

            faqItems.forEach(item => {
                const question = item.querySelector('.<?php echo $c['faq_question']; ?>');

                question.addEventListener('click', function() {
                    faqItems.forEach(otherItem => {
                        if (otherItem !== item && otherItem.classList.contains('active')) {
                            otherItem.classList.remove('active');
                        }
                    });
                    item.classList.toggle('active');
                });
            });

            const hamburger = document.querySelector('.<?php echo $c['hamburger']; ?>');
            const sidebar = document.querySelector('.<?php echo $c['sidebar']; ?>');
            const overlay = document.querySelector('.<?php echo $c['mobile_header']; ?>-overlay');

            if (hamburger && sidebar && overlay) {
                hamburger.addEventListener('click', function() {
                    hamburger.classList.toggle('active');
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });

                overlay.addEventListener('click', function() {
                    hamburger.classList.remove('active');
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });

                document.addEventListener('click', function(event) {
                    if (!sidebar.contains(event.target) && !hamburger.contains(event.target) && sidebar.classList.contains('active')) {
                        hamburger.classList.remove('active');
                        sidebar.classList.remove('active');
                        overlay.classList.remove('active');
                    }
                });
            }
        });
    </script>
</head>

<body>
    <div class="<?php echo $c['mobile_header']; ?>-overlay"></div>
    
    <header class="<?php echo $c['mobile_header']; ?>">
        
        <?php if ($logo): ?>
            <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($alt); ?>" class="<?php echo $c['mobile_logo']; ?>">
        <?php endif; ?>
        <div class="<?php echo $c['hamburger']; ?>">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </header>

    <header class="<?php echo $c['desktop_header']; ?>">
        <div class="<?php echo $c['brand_logo']; ?>">
            <?php if ($logo): ?>
                <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($alt); ?>">
            <?php endif; ?>
        </div>
    </header>
    
    <aside class="<?php echo $c['sidebar']; ?>">
        <a class="<?php echo $c['brand_logo']; ?>" href="<?php echo esc_url($link); ?>">
            <?php if ($logo): ?>
                <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($alt); ?>">
            <?php endif; ?>
        </a>

        <div class="<?php echo $c['action_btns']; ?>">
            <?php if (!empty($buttons) && is_array($buttons)): ?>
                <?php foreach ($buttons as $index => $button): ?>
                    <?php $class = ($index == 1) ? $c['btn_secondary'] : $c['btn_primary']; ?>
                    <a class="<?php echo esc_attr($class); ?>" href="<?php echo esc_url($link); ?>">
                        <?php echo esc_html($button['name'] ?? ''); ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <ul class="<?php echo $c['nav_list']; ?>">
            <?php if (!empty($menu) && is_array($menu)): ?>
                <?php foreach ($menu as $menu_item): ?>
                    <li>
                        <a href="<?php echo esc_url($link); ?>">
                            <?php if (!empty($menu_item['icon'])): ?>
                                <span class="<?php echo $c['nav_icon']; ?>" style="mask-image: url('<?php echo esc_url($menu_item['icon']); ?>');"></span>
                            <?php endif; ?>
                            <span><?php echo esc_html($menu_item['name'] ?? ''); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </aside>
    
    <main>
        <div class="<?php echo $c['main_wrapper']; ?>">
            <div class="<?php echo $c['content_area']; ?>">
                <div class="<?php echo $c['hero_section']; ?>">
                    <?php if ($hero): ?>
                        <img src="<?php echo esc_url($hero); ?>" alt="<?php echo esc_attr($alt); ?>">
                    <?php endif; ?>
                </div>
                
                <ul class="<?php echo $c['category_tabs']; ?>">
                    <?php if (!empty($category_list) && is_array($category_list)): ?>
                        <?php foreach ($category_list as $category): ?>
                            <li>
                                <a href="<?php echo esc_url($link); ?>">
                                    <?php if (!empty($category['image'])): ?>
                                        <span class="<?php echo $c['nav_icon']; ?>" style="mask-image: url('<?php echo esc_url($category['image']); ?>');"></span>
                                    <?php endif; ?>
                                    <?php if (!empty($category['name'])): ?>
                                        <span><?php echo esc_html($category['name']); ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
                
                <div class="<?php echo $c['game_grid']; ?>">
                    <?php if (!empty($casino_list) && is_array($casino_list)): ?>
                        <?php foreach ($casino_list as $casino): ?>
                            <div class="<?php echo $c['game_card']; ?>">
                                <?php if (!empty($casino['image'])): ?>
                                    <img src="<?php echo esc_url($casino['image']); ?>" alt="<?php echo esc_attr($casino['name'] ?? $alt); ?>">
                                    <span class="<?php echo $c['game_title']; ?>"><?php echo esc_html($casino['name'] ?? ''); ?></span>
                                <?php endif; ?>
                                <div class="<?php echo $c['game_overlay']; ?>">
                                    <a class="<?php echo $c['btn_primary']; ?>" href="<?php echo esc_url($link); ?>">Play</a>
                                    <a class="<?php echo $c['btn_secondary']; ?>" href="<?php echo esc_url($link); ?>">Demo</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <div class="<?php echo $c['container']; ?>">
                    <?php echo $content_text; ?>
                </div>

                <div class="<?php echo $c['faq_section']; ?>">
                    <?php if (!empty($faq_items) && is_array($faq_items)): ?>
                        <?php foreach ($faq_items as $faq): ?>
                            <div class="<?php echo $c['faq_item']; ?>">
                                <p class="<?php echo $c['faq_question']; ?>"><?php echo esc_html($faq['question'] ?? ''); ?></p>
                                <p class="<?php echo $c['faq_answer']; ?>"><?php echo esc_html($faq['answer'] ?? ''); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($faq_items) && is_array($faq_items)): ?>
                    <?php
                    $faq_schema = array(
                        "@context" => "https://schema.org",
                        "@type" => "FAQPage",
                        "mainEntity" => array()
                    );
                    
                    foreach ($faq_items as $faq) {
                        if (!empty($faq['question']) && !empty($faq['answer'])) {
                            $faq_schema["mainEntity"][] = array(
                                "@type" => "Question",
                                "name" => strip_tags($faq['question']),
                                "acceptedAnswer" => array(
                                    "@type" => "Answer",
                                    "text" => strip_tags($faq['answer'])
                                )
                            );
                        }
                    }
                    ?>
                    <script type="application/ld+json">
                        <?php echo json_encode($faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
                    </script>
                <?php endif; ?>

            </div>
            
            <footer>
                <ul class="<?php echo $c['payments_bar']; ?>">
                    <?php if (!empty($payments) && is_array($payments)): ?>
                        <?php foreach ($payments as $payment): ?>
                            <li>
                                <a href="<?php echo esc_url($link); ?>">
                                    <?php if (!empty($payment['image'])): ?>
                                        <img src="<?php echo esc_url($payment['image']); ?>" alt="<?php echo esc_attr($alt); ?>">
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
                
                <ul class="<?php echo $c['partners_bar']; ?>">
                    <?php if (!empty($parthners) && is_array($parthners)): ?>
                        <?php foreach ($parthners as $partner): ?>
                            <li>
                                <a href="<?php echo esc_url($link); ?>">
                                    <?php if (!empty($partner['image'])): ?>
                                        <img src="<?php echo esc_url($partner['image']); ?>" alt="<?php echo esc_attr($alt); ?>">
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
                
                <ul class="<?php echo $c['footer_nav']; ?>">
                    <?php if (!empty($menu_footer) && is_array($menu_footer)): ?>
                        <?php foreach ($menu_footer as $footer_item): ?>
                            <li>
                                <a href="<?php echo esc_url($link); ?>">
                                    <?php echo esc_html($footer_item['name'] ?? ''); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
                
                <p class="<?php echo $c['copyright']; ?>">
                    <?php echo $all_right; ?>
                </p>
            </footer>

        </div>

    </main>
</body>

</html>
