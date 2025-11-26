<?php
/*
Template Name: Template -1 
*/


$wp_load_path = dirname(__FILE__);
for ($i = 0; $i < 8; $i++) {
    $wp_load_path = dirname($wp_load_path);
    if (file_exists($wp_load_path . '/wp-load.php')) {
        require_once $wp_load_path . '/wp-load.php';
        break;
    }
}


if (!function_exists('get_field')) {
    die('WordPress не загружен. Проверьте путь к wp-load.php');
}

global $post;
$post_id = $post ? $post->ID : get_the_ID();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php

    $title_page      = get_field('title_page') ?: get_bloginfo('name');
    $description_page = get_field('description_page') ?: get_bloginfo('description');
    $canonical_field  = get_field('canonical');

    $current_url = ($canonical_field) ? $canonical_field : home_url(add_query_arg(NULL, NULL));

    $og_image = get_field('og_image', 'option');
    $favicon  = get_field('favicon', 'option');
    ?>

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

    <meta name="robots" content="ALL">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400..700&amp;display=swap" rel="preload" as="font">
    <?php
    $link = get_field('ref_link', 'option');
    $color_main = get_field('main_color', 'option');
    $color_second = get_field('second_color', 'option');
    $color_text = get_field('text_color', 'option');
    $bg = get_field('bg_color', 'option');
    $color_link = get_field('link_color', 'option');
    $alt = get_field('alt_text', 'option');
    $btn_bg = get_field('btn_color', 'option');
    $btn_text = get_field('btn_color_text', 'option');
    $btn2_bg = get_field('btn2_color', 'option');
    $btn2_text = get_field('btn2_color_text', 'option');
    $table_odd = get_field('table_odd', 'option');
    $table_even = get_field('table_even', 'option');

    $menu = "menu";
    $btn = "btn";
    $button = "button";
    $hero = "hero";
    $main_content = "main_content";
    $faq = "faq";
    $cat_list = "category-list";
    $slot_list = "slot-list";
    ?>
    <style>
        :root {
            --color-bg: <?php the_field('bg_color', 'option'); ?>;
            --color-text: <?php the_field('text_color', 'option'); ?>;
            --color-faq: #fff;
            --color-link: <?php the_field('link_color', 'option'); ?>;
            --color-link-hover: #ecbe15;
            --border-color: <?php the_field('border-colors', 'option'); ?>;

        }

        body {
            -webkit-font-smoothing: antialiased;
            background-color: var(--color-bg);
            color: var(--color-text);
            font-family: "DM Sans", sans-serif;
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

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
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

        a {
            color: <?php echo $color_link; ?>;
        }

        a:hover {}

        a:active,
        a:focus,
        a:hover {
            outline: 0;
        }

        table {
            border-collapse: collapse;
            margin: 1rem auto;
            padding-bottom: 1rem;
            text-align: center;
            display: table;
            white-space: nowrap;
            padding: 11px 15px;
            border: 2px solid var(--border-color);
            border-radius: 18px;
            width: 100%;
        }

        table td {
            padding: 11px 15px;
            border: 2px solid var(--border-color);
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

        header {
            display: none;
        }

        aside nav {
            position: fixed;
            padding: 30px;
            display: flex;
            height: 100vh;
            flex-direction: column;
            gap: 20px;
            align-items: center;
            justify-content: start;
            max-width: 240px;
            width: 100%;
            border-right: 1px solid var(--border-color);
        }

        .logo {
            margin: 15px 0;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
        }

        .button {
            color: <?php echo $btn_text ?> !important;
            background: <?php echo $btn_bg ?>;
            border: 1px solid var(--border-color);
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
            text-decoration: none;
            transition: .6s;
            font-weight: bold;
            width: -webkit-fill-available;
        }

        .btn {
            color: <?php echo $btn2_text ?>;
            background: <?php echo $btn2_bg ?>;
            border: 1px solid var(--border-color);
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
            text-decoration: none;
            transition: .6s;
            font-weight: bold;
            width: -webkit-fill-available;
        }

        .button:hover,
        .btn:hover {
            transition: .6s;
            transform: scale(1.05);
        }

        aside nav .button {
            width: -webkit-fill-available;
        }

        ul {
            list-style: none;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .container ul {
            list-style: inherit;
            padding-left: 40px;
        }

        ul li a {
            display: flex;
            gap: 12px;
            align-items: center;
            text-decoration: none;
        }

        ul li a:hover {
            text-decoration: underline;
        }

        .icon {
            width: 24px;
            height: 24px;
            background: <?php echo $color_link; ?>;
            -webkit-mask-size: contain;
            mask-size: contain;
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
        }

        .<?php echo $menu; ?> {}

        .main_content {
            padding: 20px 0px 0px 300px;

        }

        .main_content .btn,
        .main_content .button {
            width: 320px;
            margin: 1em auto;
        }

        .main_content-container {
            padding: 0 24px;
        }

        .main_content img {
            width: 100%;
            border-radius: 18px;

        }

        .main_content p img {
            margin: auto;
            width: 100%;
            max-height: 500px;
            width: fit-content;
            margin: 0 auto;
            margin-left: auto;
            margin-right: auto;
            display: flex;
        }

        .btn-list {
            display: flex;
            gap: 20px;
            flex-direction: row;
            overflow-x: auto;
            padding: 10px 0;
        }

        .btn-list a {
            border: 2px solid <?php echo esc_url($color_main); ?>;
            color: <?php echo esc_url($color_main); ?>;
            font-weight: bold;
            border-radius: 40px;
            padding: 5px 10px;
            text-decoration: none;
            transition: .5s;
            text-transform: uppercase;
        }

        .btn-list a:hover {

            text-decoration: none;
            transition: .5s;
            filter: hue-rotate(45deg);
        }

        .btn-list .icon {
            width: 20px;
            height: 20px;
        }

        .<?php echo $slot_list ?> {
            display: flex;
            gap: 12px;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .<?php echo $slot_list ?>-item {
            width: 15%;
            position: relative;
            border-radius: 18px;
            overflow: hidden;
        }

        .<?php echo $slot_list ?>-buttons {
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

        .<?php echo $slot_list ?>-buttons .btn,
        .<?php echo $slot_list ?>-buttons .button {
            width: fit-content;
        }

        .<?php echo $slot_list ?>-item:hover .<?php echo $slot_list ?>-buttons {
            opacity: 1;
            transition: .6s;
        }

        .<?php echo $slot_list ?>span {
            position: absolute;
            bottom: 0;
            width: 100%;
            right: 0;
            padding: 3px;
            color: <?php echo $btn2_text ?>;
            background: <?php echo $btn2_bg ?>;
            font-size: 14px;
            text-align: center;
        }

        .<?php echo $slot_list ?>.btn,
        .<?php echo $slot_list ?>.button {
            width: fit-content;
        }

        .<?php echo $faq ?> {
            width: 100%;
            margin: 0 auto;
        }

        .<?php echo $faq ?>-items {
            border: 1px solid var(--border-color);
            border-radius: 18px;
            margin-bottom: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .<?php echo $faq ?>-question {
            background: <?php echo $color_second ?>;
            padding: 15px 20px;
            margin: 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            position: relative;
            transition: all 0.3s ease;
        }

        .<?php echo $faq ?>-question:hover {
            background: <?php echo $color_second ?>;
        }

        .<?php echo $faq ?>-question::after {
            content: '+';
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            font-weight: 300;
            transition: all 0.3s ease;
        }

        .<?php echo $faq ?>-items.active .<?php echo $faq ?>-question::after {
            content: '−';
            transform: translateY(-50%) rotate(0deg);
        }

        .<?php echo $faq ?>-answer {
            max-height: 0;
            padding: 0 20px;
            margin: 0;
            overflow: hidden;
            background: <?php echo $color_second ?>;
            transition: all 0.4s ease;
            opacity: 0;
            line-height: 1.6;
        }

        .<?php echo $faq ?>-items.active .<?php echo $faq ?>-answer {
            max-height: 500px;
            padding: 15px 20px;
            opacity: 1;
        }

        .<?php echo $faq ?>-items.active {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .payments {
            background: <?php echo $color_second ?>;
        }

        .payments,
        .parthners {
            display: flex;
            justify-content: center;
            padding: 40px;
            gap: 20px 40px;
            flex-direction: row;
            flex-wrap: wrap;
            align-items: center;
            margin: 0;
            border-bottom: 1px solid #C4C9DA;
        }

        .payments img,
        .parthners img {
            max-height: 60px;
        }

        .menu-footer {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            padding: 40px 0;
            margin: 0;
            background: <?php echo $color_second ?>;
        }

        .menu-footer a {
            text-decoration: underline;
            text-align: center;
            justify-content: center;
        }

        .all-right {
            padding: 20px;
            margin: 0;
            text-align: center;
            border-top: 1px solid #C4C9DA;
        }
        @media (max-width:991px){
            
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.<?php echo $faq ?>-items');

            faqItems.forEach(item => {
                const question = item.querySelector('.<?php echo $faq ?>-question');

                question.addEventListener('click', function() {
                    faqItems.forEach(otherItem => {
                        if (otherItem !== item && otherItem.classList.contains('active')) {
                            otherItem.classList.remove('active');
                        }
                    });
                    item.classList.toggle('active');
                });
            });
        });
    </script>
</head>

<body>
    <header>
        <div class="logo">
            <?php if (get_field('logo', 'option')) : ?>
                <img src="<?php the_field('logo', 'option'); ?>" alt="<?php echo $alt ?>">
            <?php endif ?>
        </div>
        <div class="toggler">
                    
        </div>
    </header>
    <aside>
        <nav>
            <a class="logo" href="<?php echo esc_url($link); ?>">
                <?php if (get_field('logo', 'option')) : ?>
                    <img src="<?php the_field('logo', 'option'); ?>" alt="<?php echo $alt ?>">
                <?php endif ?>
            </a>

            <div class="buttons">
                <?php if (have_rows('buttons', 'option')) : ?>
                    <?php while (have_rows('buttons', 'option')) : the_row(); ?>

                        <?php

                        $index = get_row_index();

                        $class = ($index == 2) ? $btn : $button;
                        ?>

                        <a class="<?php echo esc_attr($class); ?>" href="<?php echo esc_url($link); ?>">
                            <?php the_sub_field('name'); ?>
                        </a>

                    <?php endwhile; ?>
                <?php endif; ?>

            </div>
            <ul class="<?php echo $menu; ?>">
                <?php if (have_rows('menu', 'option')) : ?>
                    <?php while (have_rows('menu', 'option')) : the_row(); ?>
                        <li>
                            <a href="<?php echo esc_url($link); ?>">
                                <?php if (get_sub_field('icon')) : ?>
                                    <span class="icon" style="mask-image: url('<?php the_sub_field('icon'); ?>')">

                                    </span>
                                <?php endif ?>
                                <span>
                                    <?php the_sub_field('name'); ?>
                                </span>
                            </a>
                        </li>
                    <?php endwhile; ?>
                <?php endif; ?>
            </ul>
        </nav>
    </aside>
    <main>

        <div class="<?php echo $main_content ?>">
            <div class="<?php echo $main_content ?>-container">
                <div class="<?php echo $hero ?>">
                    <img src="<?php the_field('hero'); ?>" alt="<?php echo $alt ?>">
                </div>
                <ul class="btn-list">
                    <?php if (have_rows('category_list')) : ?>
                        <?php while (have_rows('category_list')) : the_row(); ?>
                            <li>
                                <a href="<?php echo $link ?>">
                                    <?php if (get_sub_field('image')) : ?>
                                        <span class="icon" style="mask-image: url('<?php the_sub_field('image'); ?>')">

                                        </span>
                                    <?php endif ?>
                                    <?php if (get_sub_field('name')) : ?>
                                        <span> <?php the_sub_field('name'); ?></span>
                                    <?php endif ?>

                                </a>
                            </li>
                        <?php endwhile; ?>

                    <?php endif; ?>
                </ul>
                <div class="<?php echo $slot_list ?>">
                    <?php if (have_rows('casino_list')) : ?>
                        <?php while (have_rows('casino_list')) : the_row(); ?>
                            <div class="<?php echo $slot_list ?>-item">
                                <?php if (get_sub_field('image')) : ?>
                                    <img src="<?php the_sub_field('image'); ?>" />
                                    <span><?php the_sub_field('name'); ?></span>
                                <?php endif ?>
                                <div class="<?php echo $slot_list ?>-buttons">
                                    <a class="button" href="<?php echo $link ?>">
                                        Play

                                    </a>
                                    <a class="btn" href="<?php echo $link ?>">
                                        Demo
                                    </a>
                                </div>
                            </div>


                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
                <div class="container">
                    <?php the_field('content_text'); ?>
                </div>

                <div class="<?php echo $faq ?>">
                    <?php if (have_rows('faq_items')) : ?>
                        <?php while (have_rows('faq_items')) : the_row(); ?>
                            <div class="<?php echo $faq ?>-items">

                                <p class="<?php echo $faq ?>-question">
                                    <?php the_sub_field('question'); ?>
                                </p>
                                <p class="<?php echo $faq ?>-answer">
                                    <?php the_sub_field('answer'); ?>
                                </p>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
                <?php

                if (have_rows('faq_items')) :

                    $faq_schema = array(
                        "@context" => "https://schema.org",
                        "@type" => "FAQPage",
                        "mainEntity" => array()
                    );

                    while (have_rows('faq_items')) : the_row();

                        $question = get_sub_field('question');
                        $answer   = get_sub_field('answer');

                        if ($question && $answer) {
                            $faq_schema["mainEntity"][] = array(
                                "@type" => "Question",
                                "name" => wp_strip_all_tags($question),
                                "acceptedAnswer" => array(
                                    "@type" => "Answer",
                                    "text" => wp_strip_all_tags($answer)
                                )
                            );
                        }

                    endwhile;
                ?>

                    <script type="application/ld+json">
                        <?php echo json_encode($faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
                    </script>

                <?php endif; ?>

            </div>
            <footer>
                <ul class="payments">
                    <?php if (have_rows('payments', 'option')) : ?>
                        <?php while (have_rows('payments', 'option')) : the_row(); ?>

                            <li>
                                <a href="<?php echo $link ?>">
                                    <?php if (get_sub_field('image')) : ?>
                                        <img src="<?php the_sub_field('image'); ?>" />
                                    <?php endif ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </ul>
                <ul class="parthners">
                    <?php if (have_rows('parthners', 'option')) : ?>
                        <?php while (have_rows('parthners', 'option')) : the_row(); ?>
                            <li>
                                <a href="<?php echo $link ?>">
                                    <?php if (get_sub_field('image')) : ?>
                                        <img src="<?php the_sub_field('image'); ?>" />
                                    <?php endif ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </ul>
                <ul class="menu-footer">
                    <?php if (have_rows('menu_footer', 'option')) : ?>
                        <?php while (have_rows('menu_footer', 'option')) : the_row(); ?>
                            <li>
                                <a href="<?php echo $link ?>">
                                    <?php the_sub_field('name'); ?>
                                </a>
                            </li>

                        <?php endwhile; ?>
                    <?php endif; ?>
                </ul>
                <p class="all-right">
                    <?php the_field('all_right', 'option'); ?>
                </p>
            </footer>

        </div>

    </main>
</body>

</html>