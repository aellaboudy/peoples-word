<?php
$kopa_logo_url = get_option('kopa_theme_options_logo_url');
$kopa_top_banner_code = get_option('kopa_theme_options_top_banner_code');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">                   
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php kopa_print_page_title(); ?></title>     
        <link rel="profile" href="http://gmpg.org/xfn/11">           
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

        <?php if (get_option('kopa_theme_options_favicon_url')) { ?>       
            <link rel="shortcut icon" type="image/x-icon"  href="<?php echo get_option('kopa_theme_options_favicon_url'); ?>">
        <?php } ?>

        <?php if (get_option('kopa_theme_options_apple_iphone_icon_url')) { ?>
            <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_option('kopa_theme_options_apple_iphone_icon_url'); ?>">
        <?php } ?>

        <?php if (get_option('kopa_theme_options_apple_ipad_icon_url')) { ?>
            <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_option('kopa_theme_options_apple_ipad_icon_url'); ?>">
        <?php } ?>

        <?php if (get_option('kopa_theme_options_apple_iphone_retina_icon_url')) { ?>
            <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_option('kopa_theme_options_apple_iphone_retina_icon_url'); ?>">
        <?php } ?>

        <?php if (get_option('kopa_theme_options_apple_ipad_retina_icon_url')) { ?>
            <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_option('kopa_theme_options_apple_ipad_retina_icon_url'); ?>">        
        <?php } ?>

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->

        <!--[if lt IE 9]>
            <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js"></script>
            <script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js"></script>
            <script src="<?php echo get_template_directory_uri(); ?>/js/css3-mediaqueries.js"></script>
            <script src="<?php echo get_template_directory_uri(); ?>/js/PIE_IE678.js"></script>
        <![endif]-->

        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>

        <div class="kp-page-header">
            <div id="header-top">
                <div class="wrapper clearfix">
                    <nav id="main-nav" class="pull-left">
                        <?php
                        if (has_nav_menu('main-nav')) {
                            wp_nav_menu(array(
                                'theme_location' => 'main-nav',
                                'container' => '',
                                'menu_id' => 'main-menu',
                                'items_wrap' => '<ul id="%1$s" class="%2$s clearfix">%3$s</ul>'
                            ));

                            $mobile_menu_walker = new kopa_mobile_menu();
                            wp_nav_menu(array(
                                'theme_location' => 'main-nav',
                                'container' => 'div',
                                'container_id' => 'mobile-menu',
                                'menu_id' => 'toggle-view-menu',
                                'items_wrap' => '<span>' . __('Menu', kopa_get_domain()) . '</span><ul id="%1$s">%3$s</ul>',
                                'walker' => $mobile_menu_walker
                            ));
                        }
                        ?>
                    </nav>
                    <!-- main-nav -->

                    <?php get_search_form(); ?>
                </div>
                <!-- wrapper -->
            </div>
            <!-- header-top -->
            <div id="header-middle">
                <div class="wrapper clearfix">
                    <div class="logo-image pull-left">
                        <?php if (get_header_image()) { ?>
                            <a href="<?php echo esc_url(home_url()); ?>">
                                <img src="<?php header_image(); ?>" width="217" height="70" alt="<?php bloginfo('name'); ?> <?php _e('Logo', kopa_get_domain()); ?>">
                            </a>
                        <?php } ?>
                        <h1 class="site-title"><a href="<?php echo esc_url(home_url()); ?>"><?php bloginfo('name'); ?></a></h1>
                    </div>
                    <div class="top-banner pull-right">
                        <?php echo htmlspecialchars_decode(stripslashes($kopa_top_banner_code)); ?>
                    </div>
                </div>
                <!-- wrapper -->
            </div>
            <!-- header-middle -->
            <div id="header-bottom">
                <div class="wrapper clearfix">
                    <div class="kp-headline-wrapper pull-left">
                        <?php kopa_header_ticker(); ?>                    
                    </div>
                    <!-- kp-headline-wrapper -->

                    <?php kopa_header_social_links(); ?>
                    <!-- social-link -->
                </div>
                <!-- wrapper -->
            </div>
            <!-- header-bottom -->
        </div>
        <!-- kp-page-header -->

        <div id="main-content" class="clearfix">