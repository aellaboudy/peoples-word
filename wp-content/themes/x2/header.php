<!DOCTYPE html>

<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width">

        <?php do_action('favicon') ?>

        <title><?php wp_title( '|', true, 'right' );?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

        <?php do_action( 'bp_head' ) ?>
        <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/style.css" type="text/css" media="screen" >

        <?php wp_head(); ?>

    </head>

<body <?php body_class() ?> id='x2'>

 <div id="outerrim">

      <?php do_action( 'bp_before_header' ) ?>

    <div id="header">

        <?php do_action( 'bp_first_inside_header' ) ?>


        <?php if ( has_nav_menu( 'menu_top' ) ) { ?>

            <?php div_inner_end_inside_header(); // adding an inner wrap in case header is set to full width ?>

             <div id="access-top">

                <?php do_action( 'x2_first_inside_menu_top' ) ?>

                <?php do_action('x2_menu_top') ?>
                <?php wp_nav_menu( array( 'container_class' => 'menu menu-top', 'theme_location' => 'menu_top','container' => 'div', 'fallback_cb' => false ) ); ?>

                <?php do_action( 'x2_last_inside_menu_top' ) ?>

            </div>

            <?php div_inner_start_inside_header(); // closing the inner wrap in case the header is set to full width ?>

        <?php } ?>

        <?php do_action( 'x2_before_widgetareas' ) ?>

        <?php global $cap; // is needed here to check some theme options ?>

        <?php if (is_active_sidebar('headerfullwidth') || $cap->header_title_position == "full-width"){ ?>
            <div class="clear"></div>
            <div class="widgetarea fullwidth">
                <?php do_action( 'x2_widgetarea_headerfullwidth' ) ?>
                <?php dynamic_sidebar( 'headerfullwidth' )?>
            </div>
        <?php } ?>

        <?php // important check if any of the following three sidebars will be occupied, to set a clear before, in case the logo is coming before the widget areas. ;)
        if (is_active_sidebar('headerleft') || $cap->header_title_position == "left" || is_active_sidebar('headercenter') || $cap->header_title_position == "center" || is_active_sidebar('headerright') || $cap->header_title_position == "right"){ ?>
            <div class="clear"></div>
        <?php } ?>

        <?php if (is_active_sidebar('headerleft') || $cap->header_title_position == "left"){ ?>
            <div class="widgetarea cc-widget">
                <?php do_action( 'x2_widgetarea_headerleft' ) ?>
                <?php dynamic_sidebar( 'headerleft' )?>
            </div>
        <?php } ?>

          <?php if (is_active_sidebar('headercenter') || $cap->header_title_position == "center"){ ?>
            <div <?php if(!is_active_sidebar('headerleft') && $cap->header_title_position != "left") { echo 'style="margin-left:33.833% !important;"'; } ?> class="widgetarea cc-widget">
                <?php do_action( 'x2_widgetarea_headercenter' ) ?>
                <?php dynamic_sidebar( 'headercenter' ) ?>
            </div>
          <?php } ?>

          <?php if (is_active_sidebar('headerright') || $cap->header_title_position == "right"){ ?>
            <div class="widgetarea cc-widget right">
                <?php do_action( 'x2_widgetarea_headerright' ) ?>
                <?php dynamic_sidebar( 'headerright' ) ?>
              </div>
          <?php } ?>

          <div class="clear"></div>

        <?php do_action( 'bp_before_access')?>
        <?php div_inner_end_inside_header(); ?>

        <?php if ( has_nav_menu( 'primary' ) || !has_nav_menu( 'menu_top' ) ) { ?>
            <div class="clear"></div>
            <div class="waypoints_wrap">
                <div id="access">
                    <div class="wrapcheck">

                        <?php div_inner_start_inside_header(); ?>
                        <div class="menu">
                            <?php do_action('x2_bottom_menu') ?>
                            <?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
                            <?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary','container' => '' ) ); ?>

                        </div>
                        <?php div_inner_end_inside_header(); ?>

                    </div>
                </div>
            </div>

        <?php } ?>

        <?php do_action( 'bp_after_header_nav' ) ?>

        <div class="clear"></div>

        <?php do_action( 'bp_last_inside_header' ) ?>

    </div><!-- #header -->

    <?php do_action( 'bp_after_header' ) ?>
    <?php do_action( 'bp_before_container' ) ?>

    <div id="container">

    <?php do_action('sidebar_left');?>