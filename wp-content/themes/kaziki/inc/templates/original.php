/* Вот так будут казино выводится */

/**
 * Шорткод [author_box]
 */
add_shortcode( 'author_box', 'sc_author_box_shortcode' );

function sc_author_box_shortcode( $atts ) {
    global $post;
    if ( ! $post ) return '';

    $author_id    = $post->post_author;
    $author_name  = get_the_author_meta( 'display_name', $author_id );
    $author_url   = get_author_posts_url( $author_id );
    $last_updated = get_the_modified_date( 'j F Y', $post->ID );

    // ACF поле avatar
    $avatar_url = function_exists('get_field') ? get_field( 'avatar', 'user_' . $author_id ) : '';

    ob_start();
    ?>
    <div class="sc-author-box" style="cursor: pointer;background:<?php the_field( 'author_color', 'option' ); ?>!important;"
         onclick="location.href='<?php echo esc_url( $author_url ); ?>'">
        <?php if ( $avatar_url ) : ?>
            <img src="<?php echo esc_url( $avatar_url ); ?>" alt="<?php echo esc_attr( $author_name ); ?>" class="sc-author-avatar">
        <?php endif; ?>
        <div class="sc-author-info">
            <div><strong>Author:</strong> <a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( $author_name ); ?></a></div>
            <?php
            $old_locale = setlocale(LC_TIME, 0); // зберігаємо поточну локаль
            setlocale(LC_TIME, 'en_US.UTF-8'); // встановлюємо англійську локаль

            $last_updated = strftime('%e %B %Y', get_post_modified_time( 'U', true, $post->ID ));

            setlocale(LC_TIME, $old_locale); // відновлюємо стару локаль
            ?>
            <div><strong>Last updated:</strong> <?php echo esc_html( $last_updated ); ?></div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Стилі для шорткода
 */
add_action( 'wp_enqueue_scripts', function() {
    wp_add_inline_style( 'wp-block-library', "
        .sc-author-box {
            background: #f3f3f3;
            border-radius: 8px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 20px 0;
        }
        .sc-author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        .sc-author-info a {
            text-decoration: none;
            color: #000;
        }
        .sc-author-info a:hover {
            text-decoration: underline;
        }
    " );
});


// Шорткод [casinos] или [casinos id=1]
add_shortcode( 'casinos', function( $atts ) {
    $atts = shortcode_atts( [
        'id' => ''
    ], $atts, 'casinos' );

    ob_start();

    if ( have_rows( 'casino_items', 'option' ) ) :
        $index = 1;
        echo '<div class="casinos-list">';
        while ( have_rows( 'casino_items', 'option' ) ) : the_row();

            // Если указан id и он не совпадает с текущим индексом — пропускаем
            if ( $atts['id'] && intval( $atts['id'] ) !== $index ) {
                $index++;
                continue;
            }

            $logo   = get_sub_field( 'logo' );
            $name   = get_sub_field( 'name' );
            $bonus  = get_sub_field( 'bonus' );
            $list   = get_sub_field( 'list' ); // Можешь сделать repeater или textarea с <li>
            $rating = get_sub_field( 'rating' );
            $link   = get_sub_field( 'link' );
            $border = get_sub_field( 'border' ); // true/false

            $border_class = $border ? ' casino-border' : '';

            ?>
            <div class="casino-item<?php echo esc_attr( $border_class ); ?>">
                <div class="casino-logo">
                    <?php if ( $logo ) : ?>
                        <img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( $name ); ?>">
                    <?php endif; ?>
                    <div class="casino-name"><?php echo esc_html( $name ); ?></div>
                </div>
                <div class="casino-bonus">
                    <strong><?php echo esc_html( $bonus ); ?></strong>
                </div>
                <div class="casino-list">
                    <?php if ( $list ) : ?>
                            <?php
                            // Если $list как repeater
                            if ( is_array( $list ) ) {
                                foreach ( $list as $item ) {
                                    echo '<li>' . esc_html( $item['text'] ) . '</li>';
                                }
                            } else {
                                // Если textarea с <li>
                                echo wp_kses_post( $list );
                            }
                            ?>

                    <?php endif; ?>
                </div>
                <div class="casino-rating">
                    <?php
                    $max_stars = 5; // максимум звёзд
                    $rating_10 = floatval($rating); // рейтинг 0-10
                    $rating_5 = ($rating_10 / 10) * $max_stars; // переводим в 5-звёздную шкалу

                    $full_stars = floor($rating_5);
                    $half_star  = ($rating_5 - $full_stars >= 0.5) ? 1 : 0;
                    $empty_stars = $max_stars - $full_stars - $half_star;

                    echo '<span class="rating-number">' . esc_html($rating_10) . '/10</span>';
                    echo '<span class="rating-stars">';

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


                <div class="casino-btn">
                    <?php if ( $link ) : ?>
                        <a href="<?php echo esc_url( $link ); ?>" class="btn-play" target="_blank">PLAY NOW</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php

            // Если нужно вывести только один элемент
            if ( $atts['id'] && intval( $atts['id'] ) === $index ) {
                break;
            }

            $index++;
        endwhile;
        echo '</div>';
    endif;

    return ob_get_clean();
});

перед the_content


вот head и header
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
    <style>
        body{
            background: <?php the_field( 'body_color', 'option' ); ?>!important;
			font-family:<?php the_field( 'font', 'option' ); ?>!important;
        }
		body#fixed{
			padding-top:120px;
		}
		header.fixed{
			background: #d3d3d3;
    position: fixed;
    width: 100%;
    top: 0;
		}
        /* Анимация бургера */
        .burger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            width: 25px;
            height: 20px;
            position: relative;
            z-index: 1001;
        }

        .burger span {
            width: 100%;
            height: 3px;
            background: #000;
            transition: all 0.3s ease;
        }

        /* Бургер → крестик */
        .burger.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .burger.active span:nth-child(2) {
            opacity: 0;
        }

        .burger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }
        /* Основной контейнер */
        header .container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: center;
        }

        header .logo img {
            max-width: 193px;
        }

        header {
            box-shadow: 0 4px 15px rgba(0, 0, 0, .35) !important;
            padding: 15px 0;
            background: #fff;
            position: relative;
            z-index: 1000;
        }

        /* Меню */
        .main-navigation .menu {
            display: flex;
            list-style: none;
            gap: 20px;
            justify-content: end;
        }

        .main-navigation .menu > li {
            position: relative;
        }

        .main-navigation .menu > li > a {
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            color: #000;
            font-weight: bold;
        }

        /* Подменю */
        .main-navigation .sub-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: #fff;
            list-style: none;
            min-width: 200px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 100;
        }

        .main-navigation .sub-menu li a {
            padding: 10px;
            display: block;
            color: #000;
            margin-left: 10px;
        }

        .main-navigation .menu > li:hover > .sub-menu {
            display: block;
        }

        /* Бургер */
        .burger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
        }

        .burger span {
            width: 25px;
            height: 3px;
            background: #000;
        }

        /* Мобильная версия */
        @media (max-width: 768px) {
            .burger {
                display: flex;
            }
            .main-navigation {
                position: absolute;
                top: 100%;
                right: 0;
                width: 100%;
                background: #fff;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease;
            }
            .main-navigation.open {
                max-height: 500px; /* достаточно для всех пунктов */
            }
            .main-navigation .menu {
                flex-direction: column;
                padding: 10px 15px;
                justify-content: end;
            }
            .main-navigation .menu > li {
                border-bottom: 1px solid #eee;
            }
            .main-navigation .sub-menu {
                position: static;
                box-shadow: none;
                display: none;
            }
            .main-navigation .menu li.open > .sub-menu {
                display: block;
            }
        }











        ul, ol {
            margin-left: 0!important;
        }
        table tr:nth-child(even) td {
            background-color: <?php the_field( 'table_even', 'option' ); ?>!important
        }
		        table tr:nth-child(odd) td {
            background-color:<?php the_field( 'table_odd', 'option' ); ?>!important
        }
         table {
            width: 100%!important;
            margin: 0!important;
            border-collapse: collapse!important;
            font-size: 1em!important;
            border: 1px solid #ccc!important;
            table-layout: auto!important
        }

         table th {
            text-align: center!important;
            padding: 1em!important;
            font-size: 1em!important;
            font-weight: 700!important;
            color: #333!important;
            border: 1px solid #ccc!important;
            background: #f0f0f0!important
        }

         table td {
            padding: .5em 1em!important;
            border: 1px solid #ccc!important;
            background: #fff!important
        }






        .casinos-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 1100px;
            margin: 0 auto;
            margin: 20px 0;
        }

        .casino-item {
            display: grid;
            grid-template-columns: 150px .7fr .7fr 100px 140px;
            gap: 20px;
            align-items: center;
            background: #fff;
            border-radius: 10px;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border: 2px solid transparent;
        }
        .casino-item ul{
            margin-bottom: 0!important;
        }
        .casino-item ul {
            list-style: none; /* убираем стандартные точки */
            padding-left: 0;
            margin: 0;
        }

        .casino-item ul li {
            position: relative;
            padding-left: 28px; /* отступ под галочку */
            margin-bottom: 8px;
        }

        .casino-item ul li::before {
            content: "✔"; /* галочка */
            position: absolute;
            left: 0;
            top: 0;
            color: #28a745; /* зелёный цвет галочки */
            font-weight: bold;
            font-size: 16px;
            line-height: 1.2;
        }

        .casino-item.casino-border {
            border-color: #FFD54F; /* Желтая рамка */
        }

        .casino-logo {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .casino-logo img {
            max-height: 50px;
            margin-bottom: 5px;
        }

        .casino-name {
            font-size: 14px;
            color: #888;
        }

        .casino-bonus {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            line-height: 120%;
            margin-bottom: 0;
            letter-spacing: -0.02em;
            text-transform: capitalize;
            color: #1C2642;
        }

        .casino-bonus ul {
            margin: 5px 0 0;
            padding-left: 18px;
            font-weight: normal;
            font-size: 14px;
            color: #333;
        }

        .casino-bonus li {
            margin-bottom: 3px;
        }

        .casino-rating {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
        }

        .casino-rating .rating-stars {
            display: block;
            font-size: 14px;
            color: #f5c518;
        }

        .casino-btn {
            text-align: right;
        }

        .btn-play {
            display: inline-block;
            padding: 10px 20px;
            background: <?php the_field( 'cs_bg', 'option' ); ?>;
            color:<?php the_field( 'cs_color', 'option' ); ?> !important;
            font-weight: bold;
            border-radius: 30px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-play:hover {
			transition:.4s;
           transform:scale(1.05);
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
                background: #f3f3f3;
                border-radius: 8px;
                padding: 10px 15px;
                display: flex
            ;
                align-items: center;
                gap: 15px;
                margin: 20px 0;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
        }












        .container {
            padding: 0 15px;
            margin: 0 auto;
            width: 100%;
        }

        @media screen and (min-width: 576px) {
            .container {
                max-width: 540px;
            }
        }

        @media screen and (min-width: 768px) {
            .container {
                max-width: 720px;
            }
        }

        @media screen and (min-width: 992px) {
            .container {
                max-width: 960px;
            }
        }

        @media screen and (min-width: 1200px) {
            .container {
                max-width: 1140px;
            }
        }
        header .container{
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: center;
        }
        header .logo{
            max-width: 193px;
        }
        header{
            box-shadow: 0 4px 15px rgba(0, 0, 0, .35) !important;
            padding: 15px 0;
        }
        /* Стрелочка у пунктов с подменю */
        .main-navigation .menu > li.menu-item-has-children > a::after {
            content: "▼";
            font-size: 10px;
            margin-left: 5px;
            transition: transform 0.3s;
        }

        .main-navigation .menu > li.menu-item-has-children:hover > a::after {
            transform: rotate(180deg);
        }

        /* Мобильная стрелка */
        @media (max-width: 768px) {
            .main-navigation .menu > li.menu-item-has-children > a::after {
                float: right;
                transform: rotate(0deg);
            }
            .main-navigation .menu li.open > a::after {
                transform: rotate(180deg);
            }
        }
		
		
		
		
		
		
		
		
		
		
		
		footer .logo img{
			    max-width: 227px;
    margin-bottom: 12px;
		}
		
        footer .container{
             display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    padding-top: 40px;
    padding-bottom: 60px;
        }
		.menu-footer{
			display:flex;
			flex-direction:column;
			gap:15px;
		}
		.menu-footer a{
			text-decoration:none!important;
		}
		.menu-footer a:hover{
			text-decoration:underline!important;
		}
		  @media (max-width: 1008px) {
			footer .container {
    grid-template-columns: 1fr!important;
				gap:15px;
			}
		}
        body{
            margin-bottom: 0!important;
        }
    </style>
	<style>
			/* FAQ section */
.faq h2 {
    font-size: 28px;
    margin-bottom: 25px;
    font-weight: 700;
}

.faq-item {
    padding: 15px 0;
    cursor: pointer;
	    border-radius: 15px;
    background: <?php the_field( 'faq_bg'); ?>;
    padding: 16px 20px;
    font-weight: 500 !important;
	margin-bottom:20px;
}

.faq-item:last-child {
    border-bottom: none;
}

/* Question */
.faq-item .question {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    position: relative;
    padding-right: 25px;
    transition: color 0.3s ease;
}

.faq-item .question::after {
    content: "+";
    position: absolute;
    right: 0;
    top: 0;
    font-size: 20px;
    transition: transform 0.3s ease;
}

/* Active state */
.faq-item.active .question {
    color:<?php the_field( 'faq_color'); ?>;
}

.faq-item.active .question::after {
    content: "-";
    transform: rotate(180deg);
}

/* Answer */
.faq-item .answer {
    display: none;
    font-size: 16px;
    color: #555;
    margin-top: 10px;
    line-height: 1.5;
    animation: fadeIn 0.3s ease;
}

/* Smooth fade animation */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
		h1{
			font-size:<?php the_field( 'h1','option'); ?>em!important;
		}
			h2{
			font-size:<?php the_field( 'h2','option'); ?>em!important;
		}
			h3{
			font-size:<?php the_field( 'h3','option'); ?>em!important;
		}
			h4{
			font-size:<?php the_field( 'h4','option'); ?>em!important;
		}
			p{
			font-size:<?php the_field( 'p','option'); ?>px!important;
		}
			li{
			font-size:<?php the_field( 'li','option'); ?>px!important;
		}
			</style>
	<?php wp_head(); ?>
</head>

<body id="<?php the_field( 'header_position', 'option' ); ?>" <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'simple-casino' ); ?></a>

    <header id="masthead" class="site-header <?php the_field( 'header_position', 'option' ); ?>" style="background: <?php the_field( 'header_color', 'option' ); ?>;">
        <div class="container">
            <div class="logo">
                <?php if (get_field('logo', 'option')) : ?>
                    <a href="<?php echo home_url(); ?>">
                        <img src="<?php the_field('logo', 'option'); ?>" alt="<?php echo get_bloginfo('name'); ?>"/>
                    </a>
                <?php endif ?>
            </div>

            <div class="burger" id="burger">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <nav id="site-navigation" class="main-navigation">
                <?php
                wp_nav_menu(array(
                    'menu' => 'Menu 1',
                    'container' => false,
                    'menu_class' => 'menu',
                    'menu_id' => 'primary-menu',
                ));
                ?>
            </nav>
        </div>
    </header>

    Вот сам шаблон 
    <?php
/*
Template Name: Home-page
*/
get_header();
$post_id = get_the_ID();
$title_casino = get_the_title();
?>
    <main id="news-<?php echo get_the_ID(); ?>" class="seo-text" style="margin: 0;padding-bottom:5em;">
        <div class="container">
            <div class="content-news">

                <h1 class="news">
                    <?php the_title(); ?>
                </h1>
                <div class="text-news">
                    <?php the_content(); ?>
                </div>

            </div>
			 <section id="faq" class="faq">
    <div class="container">
        <h2><?php the_field('faq_title'); ?></h2>

        <?php if ( have_rows('faq') ) : ?>
            <?php while ( have_rows('faq') ) : the_row(); ?>
                <div class="faq-item">
                    <h3 class="question"><?php the_sub_field('question'); ?></h3>
                    <div class="answer"><?php the_sub_field('answer'); ?></div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>

        <div class="faq-text"><?php the_field('faq_after_text'); ?></div>
    </div>
</section>

<?php if ( have_rows('faq') ) : ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    <?php
      $faq_entries = [];
      while ( have_rows('faq') ) : the_row();
          $question = get_sub_field('question');
          $answer = get_sub_field('answer');
          $faq_entries[] = '{
            "@type": "Question",
            "name": ' . json_encode($question) . ',
            "acceptedAnswer": {
              "@type": "Answer",
              "text": ' . json_encode($answer) . '
            }
          }';
      endwhile;
      echo implode(",\n", $faq_entries);
    ?>
  ]
}
</script>
<?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const faqItems = document.querySelectorAll(".faq-item");

    // По умолчанию — все открыты
    faqItems.forEach(item => {
        item.classList.add("active");
        const answer = item.querySelector(".answer");
        if (answer) answer.style.display = "block";
    });

    faqItems.forEach(item => {
        const question = item.querySelector(".question");
        
        question.addEventListener("click", () => {
            item.classList.toggle("active");
            const answer = item.querySelector(".answer");
            if (answer) {
                answer.style.display = item.classList.contains("active") ? "block" : "none";
            }
        });
    });
});
</script>

        </div>
    </main>
<?php get_footer();

вот футер 


	<footer id="colophon" class="site-footer" style="background: <?php the_field( 'footer_color', 'option' ); ?>">
        <div class="container" style="text-align: center">
           <div class="left">
			    <div class="logo">
                <?php if (get_field('logo', 'option')) : ?>
                    <a href="<?php echo home_url(); ?>">
                        <img src="<?php the_field('logo', 'option'); ?>" alt="<?php echo get_bloginfo('name'); ?>"/>
                    </a>
                <?php endif ?>
            </div>
			    <p style="margin: 0!important;color: <?php the_field( 'footer_color_text', 'option' ); ?>!important;"><?php the_field( 'footer_text', 'option' ); ?></p>
			</div>
			<div class="menu-footer">
				<?php if ( have_rows( 'footer_menu1', 'option' ) ) : ?>
	<?php while ( have_rows( 'footer_menu1', 'option' ) ) : the_row(); ?>
				<a style="margin: 0!important;color: <?php the_field( 'footer_color_text', 'option' ); ?>!important;" href="<?php the_sub_field( 'link' ); ?>"><?php the_sub_field( 'name' ); ?></a>
		
		
	<?php endwhile; ?>

<?php endif; ?>
			</div>
			<div class="menu-footer">
				<?php if ( have_rows( 'footer_menu2', 'option' ) ) : ?>
	<?php while ( have_rows( 'footer_menu2', 'option' ) ) : the_row(); ?>
				<a style="margin: 0!important;color: <?php the_field( 'footer_color_text', 'option' ); ?>!important;" href="<?php the_sub_field( 'link' ); ?>"><?php the_sub_field( 'name' ); ?></a>
		
		
	<?php endwhile; ?>
<?php endif; ?>
			</div>
        </div>
	</footer><!-- #colophon -->
</div><!-- #page -->
<?php 
$enable_disable_checked_values = get_field( 'enable_disable', 'option' );
$enable_disable_checked_values_mobile = get_field( 'enable_disable_mobile', 'option' );
?>

<?php if ( $enable_disable_checked_values && in_array( 'Enable', $enable_disable_checked_values ) ) : ?>
    <!-- Десктопная версия -->
    <div class="fixed-sidebar desktop" style="background:<?php the_field( 'bg_fixed', 'option' ); ?>;">
        <div class="container">
            <!-- Крестик закрытия -->
            <span class="fixed-close">&times;</span>

            <div class="logo-fixed">
                <?php if ( get_field( 'fixed_logo', 'option' ) ) : ?>
                    <img src="<?php the_field( 'fixed_logo', 'option' ); ?>" />
                <?php endif ?>
                <div class="fixed-content">
                    <p class="head"><?php the_field( 'fixed_title', 'option' ); ?></p>
                    <p class="bottom"><?php the_field( 'fixed_subtitle', 'option' ); ?></p>
                </div>
            </div>

            <a href="<?php the_field( 'fixed_link', 'option' ); ?>">
                <?php the_field( 'btn_name', 'option' ); ?>
            </a>
        </div>
    </div>
<?php endif; ?>


<?php if ( $enable_disable_checked_values_mobile && in_array( 'Enable', $enable_disable_checked_values_mobile ) ) : ?>
    <!-- Мобильная версия -->
    <div class="fixed-sidebar mobile" style="background:<?php the_field( 'bg_fixed_mobile', 'option' ); ?>;">
        <div class="container">
            <!-- Крестик закрытия -->
            <span class="fixed-close">&times;</span>

            <div class="logo-fixed">
                <?php if ( get_field( 'fixed_logo_mobile', 'option' ) ) : ?>
                    <img src="<?php the_field( 'fixed_logo_mobile', 'option' ); ?>" />
                <?php endif ?>
                <div class="fixed-content">
                    <p class="head"><?php the_field( 'fixed_title_mobile', 'option' ); ?></p>
                    <p class="bottom"><?php the_field( 'fixed_subtitle_mobile', 'option' ); ?></p>
                </div>
            </div>

            <a href="<?php the_field( 'fixed_link_mobile', 'option' ); ?>">
                <?php the_field( 'btn_name_mobile', 'option' ); ?>
            </a>
        </div>
    </div>
<?php endif; ?>


<style>
	.fixed-sidebar{
		color:white!important;
		width:100%;
		position:fixed;
		bottom:0;
		left:0;
		padding:10px 0;
		z-index:9999;
	}

	.head{
		font-weight:600;
		font-size:1.2em!important;
	}

	.fixed-sidebar img{
		max-width:150px;
	}

	.fixed-sidebar .container{
		display: flex;
		gap:20px;
		align-items:center;
		justify-content: space-between;
		position: relative;
	}

	.fixed-sidebar a{
		margin-right:2vw;
		border-radius: 16px;
		background: <?php the_field( 'fixed_btn_bg', 'option' ); ?> !important;
		color: #0f212e !important;
		padding: 12px 20px;
		text-decoration:none!important;
		transition:.5s;
		color:white!important;
	}
	.fixed-sidebar.mobile a{
		background: <?php the_field( 'fixed_btn_bg_mobile', 'option' ); ?> !important;
	}
	.fixed-sidebar a:hover{
		transform:scale(1.05);
	}

	.logo-fixed{
		display: flex;
		justify-content: center;
		align-items: center;
		gap: 40px;
	}

	.logo-fixed p{
		margin-bottom: .3em;
		margin-top: 0;
	}

	/* Крестик */
	.fixed-close{
		position: absolute;
		top: -8px;
		right: 12px;
		font-size: 32px;
		color: white;
		cursor: pointer;
		line-height: 1;
		transition: 0.3s;
	}
	.fixed-close:hover{
		transform: rotate(90deg);
	}

	/* Показывать только нужную версию */
	.fixed-sidebar.mobile { display: none; }
	@media screen and (max-width: 991px){
		.fixed-sidebar.desktop { display: none; }
		.fixed-sidebar.mobile { display: block; }
		.fixed-sidebar.mobile .container{
		flex-direction:column;
	}
		.fixed-sidebar.mobile a {
    background: #545454 !important;
    width: 100%;
    text-align: center;
}
	}
	
</style>


<script>
	document.addEventListener("DOMContentLoaded", function(){
		const closeBtn = document.querySelector(".fixed-close");
		const sidebar = document.querySelector(".fixed-sidebar");
		if(closeBtn && sidebar){
			closeBtn.addEventListener("click", function(){
				sidebar.style.display = "none";
			});
		}
	});
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const burger = document.getElementById("burger");
        const nav = document.getElementById("site-navigation");
        const menuItems = nav.querySelectorAll(".menu-item-has-children");

        burger.addEventListener("click", () => {
            burger.classList.toggle("active"); // меняем бургер на крестик
            nav.classList.toggle("open");
        });

        // Мобильное открытие подменю
        menuItems.forEach(item => {
            item.addEventListener("click", function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    this.classList.toggle("open");
                }
            });
        });
    });


</script>
<?php wp_footer(); ?>

</body>
</html>