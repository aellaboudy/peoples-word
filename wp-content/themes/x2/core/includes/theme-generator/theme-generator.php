<?php
class x2_Theme_Generator{

	var $detect;

	/**
	 * PHP 5 constructor
	 *
	 * @package x2
	 * @since 1.0
	 */
	function __construct() {
		global $bp;

		$this->detect = new x2_Detect();
        $this->component = explode('-',$this->detect->tk_get_page_type());

		// load predefined constants first
        add_action( 'bp_head',    array( $this, 'load_constants' ), 2 );
        add_filter( 'body_class', array( $this, 'add_bubble'), 10 );
        add_filter( 'body_class', array( $this, 'add_home_class'), 10 );
        add_action( 'init',       array( $this, 'slider_init'));

    }


	function load_constants(){
		global $cap, $post;

		if(	$cap->menu_x == "" ) $cap->menu_x = 'left';
		if( $cap->font_style == "" ) $cap->font_style = '"Ubuntu", Arial, tahoma, verdana, sans-serif';
		if( $cap->font_size == "" ) $cap->font_size = "13";
		if( $cap->font_alt_size == "" ) $cap->font_alt_size = "11";

		$component = explode('-',$this->detect->tk_get_page_type());

		if($cap->sidebar_position == ''){
			$cap->sidebar_position      = 'right';
			$cap->enable_x2_slideshow   = 'home';
			$cap->header_text           = 'off';
			$cap->preview               = true;
		}

		$sidebar_position = $cap->sidebar_position;

        if(!$cap->bp_profile_sidebars) {
            $cap->bp_profile_sidebars = "default";
        }

        if(!empty($component[2])){
			if($component[2] == 'groups' && !empty($component[3]) && $cap->bp_groups_sidebars != 'default') {
				$sidebar_position = $cap->bp_groups_sidebars;
			} elseif($component[2] == 'profile' && !empty($component[3]) && $cap->bp_profile_sidebars != 'default') {
				$sidebar_position = $cap->bp_profile_sidebars;
			}
		}

		$leftsidebar_width  = $cap->leftsidebar_width;
		$rightsidebar_width = $cap->rightsidebar_width;

		switch ($sidebar_position) {
			case 'left': $cap->rightsidebar_width = 0; break;
			case 'right': $cap->leftsidebar_width = 0; break;
			case 'none': $cap->leftsidebar_width = 0; $cap->rightsidebar_width = 0; break;
			case 'full-width': $cap->leftsidebar_width = 0; $cap->rightsidebar_width = 0; break;
		}

		if(!empty($post)){
            $tmp = get_post_meta( $post->ID, '_wp_page_template', true );

            switch ($tmp) {
                case '_pro/left-sidebar.php': $cap->leftsidebar_width = $leftsidebar_width; $cap->rightsidebar_width = 0; break;
                case '_pro/right-sidebar.php': $cap->leftsidebar_width = 0; $cap->rightsidebar_width = $rightsidebar_width; break;
                case '_pro/left-and-right-sidebar.php': $cap->leftsidebar_width = $leftsidebar_width; $cap->rightsidebar_width = $rightsidebar_width; break;

                case 'left-sidebar.php': $cap->leftsidebar_width = $leftsidebar_width; $cap->rightsidebar_width = 0; break;
                case 'right-sidebar.php': $cap->leftsidebar_width = 0; $cap->rightsidebar_width = $rightsidebar_width; break;
                case 'left-and-right-sidebar.php': $cap->leftsidebar_width = $leftsidebar_width; $cap->rightsidebar_width = $rightsidebar_width; break;

                case 'full-width.php': $cap->leftsidebar_width = 0; $cap->rightsidebar_width = 0; break;
                case 'home-magazine.php': $cap->leftsidebar_width = 0; $cap->rightsidebar_width = 0; break;
            }
        }

	}

	/**
	 * header: add badge in the upper left corner
	 *
	 * located: header.php do_action( 'bp_before_header' )
	 *
	 * @package x2
	 * @since 0.1
	 */
	function body_badge(){
		global $cap;
		if(!defined('is_pro')):
			return;
		else:
			if( !$cap->logo ) {
				echo '<a class="body_badge_link" href="'.home_url('/').'">' . __("This is your custom badge text! :)", "x2") . '</a><div class="badge_body"><span class="badge_text"></span></div></a>';
			}
		endif;
	}

	/**
	 * header: add div class 'inner' inside the header if the header is set to full width
	 *
	 * located: header.php - do_action( 'bp_first_inside_header' )
	 *
	 * @package x2
	 * @since 1.0
	 */
	function div_inner_start_inside_header(){
		global $cap;

		if ($cap->header_width == "full-width") {
			echo '<div class="inner">';
		}
	}

	/**
	 * header: add div end for class 'inner' inside the header if the header is set to full width
	 *
	 * located: header.php - do_action( 'bp_last_inside_header' )
	 *
	 * @package x2
	 * @since 1.0
	 */
	function div_inner_end_inside_header(){
		global $cap;

		if ($cap->header_width == "full-width") {
			echo '</div><!-- .inner -->';
		}
	}

	/**
	 * header: add div 'innerrim' before header if the header is not set to full width
	 *
	 * located: header.php - do_action( 'bp_before_header' )
	 *
	 * @package x2
	 * @since 1.0
	 */
	function innerrim_before_header(){
		global $cap;

		if ($cap->header_width != "full-width") {
			echo '<div id="innerrim">';
		}
	}

	/**
	 * header: add div 'innerrim' after header if the header is set to full width
	 *
	 * located: header.php do_action( 'bp_after_header' ) on line 84
	 *
	 * @package x2
	 * @since 1.0
	 */
	function innerrim_after_header(){
		global $cap;

		if ($cap->header_width == "full-width") {
			echo '<div id="innerrim">';
		}
	}

	/**
	 * header: add a search field in the header
	 *
	 * located: header.php do_action( 'bp_after_header_nav' )
	 *
	 * @package x2
	 * @since 1.0
	 */

	function menu_enable_search(){
		global $cap;

		if(defined('BP_VERSION')){
			if($cap->menu_enable_search){?>
			<div id="search-bar" role="search">
				<div class="padder">

					<form action="<?php echo bp_search_form_action() ?>" method="post" id="search-form">

					    <div class="input-append">
                              <input class="span2" id="search-terms" name="s" value="<?php echo isset( $_REQUEST['s'] ) ? esc_attr( $_REQUEST['s'] ) : ''; ?>" size="16" type="text"><button class="btn" type="submit"><i class="icon-search"></i></button>
                        </div>
						<?php echo bp_search_form_type_select() ?>

						<?php wp_nonce_field( 'bp_search_form' ) ?>

					</form><!-- #search-form -->

				<?php do_action( 'bp_search_login_bar' ) ?>

				</div><!-- .padder -->
			</div><!-- #search-bar -->
			<?php
			}
		}
	}

	/**
	 * header: add a header logo in the header
	 *
	 * located: header.php do_action( 'bp_before_access' )
	 *
	 * @package x2
	 * @since 1.0
	 */
	function header_logo(){
		global $cap; ?>

		<div class="widget header_logo">

			<?php if( $cap->logo ){ ?>
				<a class="header_logo" href="<?php echo home_url() ?>" title="<?php _e( 'Home', 'x2' ) ?>"><img src="<?php echo $cap->logo?>" alt="<?php if(defined('BP_VERSION')){ bp_site_name(); } else { bloginfo('name'); } ?>"></a>
			<?php }
			$site_title = defined('BP_VERSION') ? bp_get_site_name() : get_bloginfo('name');
	        //$site_title = mb_substr($site_title, 0, 30);

			if(is_home()): ?>
				<div id="logo">
					<h1><a href="<?php echo home_url() ?>" title="<?php _e( 'Home', 'x2' ) ?>"><?php echo $site_title ?></a></h1>
					<div id="blog-description"><?php bloginfo('description'); ?></div>
				</div>
			<?php else: ?>
				<div id="logo">
					<h4><a href="<?php echo home_url() ?>" title="<?php _e( 'Home', 'x2' ) ?>"><?php echo $site_title; ?></a></h4>
					<div id="blog-description"><?php bloginfo('description'); ?></div>
				</div>
			<?php endif; ?>

		</div><?php
	}

	/**
	 * header: add the top slider to the homepage, all pages, or just on specific pages
	 *
	 * located: header.php do_action( 'bp_after_header' )
	 *
	 * @package x2
	 * @since 1.0
	 */
    function x2_slideshow(){
        global $cap, $post;

        $show_me_mow                = 'false';
        $x2_page_enable_slideshow   = !empty($post) ? get_post_meta($post->ID,"_x2_page_enable_slideshow", true) : 'off';
        $component                  = explode('-',$this->detect->tk_get_page_type());

        $tmp = '';

        if(is_page() && $x2_page_enable_slideshow == 'on') {
            $show_me_mow = 'true';
        } elseif(is_page() && $x2_page_enable_slideshow == 'off'){
            return;
        }

        switch ($cap->enable_x2_slideshow) {
			case 'all':
				$show_me_mow = 'true';
			break;
            case 'home':
				if(isset($component[1]) && $component[1] == 'home' )
					$show_me_mow = 'true';
			break;
        }

        if($show_me_mow == 'false')
            return;

        $category_name        = '0' ;
        $slideshow_style      = 'nivo slideshow';
        $slideshow_caption    = 'on';
        $slideshow_amount     = '4';
        $slideshow_time       = '5000';
        $slideshow_orderby    = 'DESC';
        $slideshow_post_type  = 'post';
        $slideshow_show_pages = '';
        $slideshow_sticky     = 'off';

        $slideshow_effect = array(
            'bars',
            'blinds',
            'blocks',
            'blocks2',
            'concentric',
            'dissolve',
            'slide',
            'warp',
            'zip',
            'bars3d',
            'blinds3d',
            'cube',
            'tiles3d',
            'turn',
        );
        $slideshow_autoplay   = 'true';
        $slideshow_pagination = 'true';
        $slideshow_delay      = 4000;
        $slideshow_controls   = 'false';


        if( $x2_page_enable_slideshow == 'on' ){

            $page_category_name        = get_post_meta( $post->ID, "_x2_page_category_name", false );
            $page_slideshow_style      = get_post_meta( $post->ID, "_x2_page_slideshow_style", true );
            $page_slideshow_caption    = get_post_meta( $post->ID, "_x2_page_slideshow_caption", true );
            $page_slideshow_amount     = get_post_meta( $post->ID, "_x2_page_slideshow_amount", true );
            $page_slideshow_orderby    = get_post_meta( $post->ID, "_x2_page_slideshow_orderby", true );
            $page_slideshow_post_type  = get_post_meta( $post->ID, "_x2_page_slideshow_post_type", true );
            $page_slideshow_show_pages = get_post_meta( $post->ID, "_x2_page_slideshow_show_page", true );
            $page_slideshow_sticky     = get_post_meta( $post->ID, "_x2_page_slideshow_sticky", true );

            $page_slideshow_effect     = get_post_meta( $post->ID, "_x2_page_slideshow_effect", true );
            $page_slideshow_autoplay   = get_post_meta( $post->ID, "_x2_page_slideshow_autoplay", true );
            $page_slideshow_pagination = get_post_meta( $post->ID, "_x2_page_slideshow_pagination", true );
            $page_slideshow_delay      = get_post_meta( $post->ID, "_x2_page_slideshow_delay", true );
            $page_slideshow_controls   = get_post_meta( $post->ID, "_x2_page_slideshow_controls", true);

            if( isset($page_category_name) ){
            	$category_name = $page_category_name;
            	//$category_name = implode(',', $page_category_name);
            }
            if( !empty($page_slideshow_style) ){
                $slideshow_style = $page_slideshow_style;
            }
            if( !empty($page_slideshow_caption) ){
                $slideshow_caption = $page_slideshow_caption;
            }
            if( !empty($page_slideshow_amount) ){
                $slideshow_amount = $page_slideshow_amount;
            }
            if( !empty($page_slideshow_orderby) ){
                $slideshow_orderby = $page_slideshow_orderby;
            }
            if( !empty($page_slideshow_post_type) ){
                $slideshow_post_type = $page_slideshow_post_type;
            }
            if( !empty($page_slideshow_show_pages) ){
                $slideshow_show_pages = $page_slideshow_show_pages;
            }
            if( !empty($page_slideshow_sticky) ){
                $slideshow_sticky = $page_slideshow_sticky;
            }
            if( !empty($page_slideshow_effect) ){
                $slideshow_effect = $page_slideshow_effect;
            }
            if( !empty($page_slideshow_autoplay) ){
                $slideshow_autoplay = $page_slideshow_autoplay;
            }
            if( !empty($page_slideshow_pagination) ){
                $slideshow_pagination = $page_slideshow_pagination;
            }
            if( !empty($page_slideshow_delay) ){
                $slideshow_delay = $page_slideshow_delay;
            }
            if( !empty($page_slideshow_controls) ){
                $slideshow_controls = $page_slideshow_controls;
            }

        } else {

            if( $cap->category_name != '' ){
				$category_name = $cap->category_name;
                // $category_name = implode(',', $cap->category_name);
            }
            if( $cap->slideshow_style != '' ){
                $slideshow_style = $cap->slideshow_style;
            }
            if( $cap->slideshow_caption != '' ){
                $slideshow_caption = $cap->slideshow_caption;
            }
            if( $cap->slideshow_amount != '' ){
                $slideshow_amount = $cap->slideshow_amount;
            }
            if( $cap->slideshow_orderby != '' ){
                $slideshow_orderby = $cap->slideshow_orderby;
            }
            if( $cap->slideshow_post_type != '' ){
                $slideshow_post_type = $cap->slideshow_post_type;
            }
            if( $cap->slideshow_show_page != '' ){
                $slideshow_show_pages = $cap->slideshow_show_page;
            }
            if( $cap->slideshow_show_sticky != '' ){
                $slideshow_sticky = $cap->slideshow_show_sticky;
            }
            if( $cap->slideshow_effect != '' ){
                $slideshow_effect = $cap->slideshow_effect;
            }
            if( $cap->slideshow_autoplay != '' ){
                $slideshow_autoplay = $cap->slideshow_autoplay;
            }
            if( $cap->slideshow_pagination != '' ){
                $slideshow_pagination = $cap->slideshow_pagination;
            }
            if( $cap->slideshow_delay != '' ){
                $slideshow_delay = $cap->slideshow_delay;
            }
            if( $cap->slideshow_controls != '' ){
                $slideshow_controls = $cap->slideshow_controls;
            }

        }

 		$fp_cats = Array();
		foreach ($category_name as $key =>$cat) {
			$fp_cats[] = get_term_by('id', $cat, 'category');
		}

		$fp_cat_slugs = Array();
		foreach ($fp_cats as $key => $fp_cat) {
			if(is_object($fp_cat))
				$fp_cat_slugs[] = $fp_cat->slug;
		}

		$category_name = implode ( ', ' , $fp_cat_slugs );


        $same_atts = array(
            'category_name' => $category_name,
            'caption'       => $slideshow_caption,
            'id'            => 'slideshowtop',
            'time_in_ms'    => $slideshow_time,
            'orderby'       => $slideshow_orderby,
            'page_id'       => $slideshow_show_pages,
            'post_type'     => $slideshow_post_type
		);

        if( $slideshow_style == 'nivo slider' ){
			$atts = array(
                'effect'            => $slideshow_effect,
                'autoplay'          => $slideshow_autoplay,
                'pagination'        => $slideshow_pagination,
                'delay'             => $slideshow_delay,
                'controls'          => $slideshow_controls,
                'captions'          => $slideshow_caption,

                'slideshow_sticky'  => $slideshow_sticky,
                'amount'            => $slideshow_amount,
                'category_name'     => $category_name,
                'id'                => 'slideshowtop',
                'orderby'           => $slideshow_orderby,
                'post_type'         => $slideshow_post_type,
                'page_id'           => $slideshow_show_pages,
                'width'             => 1000,
                'height'            => 320
            );

			x2_nivo_slider($atts,$content = null);

        }
        elseif( $slideshow_style == 'flux slider' ) {

            $slideshow_autoplay     = $slideshow_autoplay == 'off' ? 'false' : 'true';
            $slideshow_pagination   = $slideshow_pagination == 'off' ? 'false' : 'true';
            $slideshow_controls     = $slideshow_controls == 'off' ? 'false' : 'true';
            $slideshow_caption      = $slideshow_caption == 'off' ? 'false' : 'true';

            $atts = array(
                'effect'            => $slideshow_effect,
                'autoplay'          => $slideshow_autoplay,
                'pagination'        => $slideshow_pagination,
                'delay'             => $slideshow_delay,
                'controls'          => $slideshow_controls,
                'captions'          => $slideshow_caption,

                'slideshow_sticky'  => $slideshow_sticky,
                'amount'            => $slideshow_amount,
                'category_name'     => $category_name,
                'id'                => 'slideshowtop',
                'orderby'           => $slideshow_orderby,
                'post_type'         => $slideshow_post_type,
                'page_id'           => $slideshow_show_pages,
                'width'             => 1000,
                'height'            => 320
            );

            $this->x2_flux_slider($atts,$content = null);

        }
        else {

            if($slideshow_style == 'full width' || $slideshow_style == 'full-width-image' ){
                $tmp .= '<style type="text/css">';
                $tmp .= '    div#x2_slider-top div.x2_slider .featured .ui-tabs-panel{';
                $tmp .= '    width: 100%;';
                $tmp .= '    }';
                $tmp .= '</style>';

                $atts = array(
                    'amount'        => $slideshow_amount,
                    'slider_nav'    => 'off',
                    'caption_width' => '1000',
                    'width'         => '1000',
                    'height'        => '250',
                );
            } else {
                $atts = array(
                        'amount'        => '4',
                        'slider_nav'    => 'on',
                    );
            }
            $atts = array_merge($atts, $same_atts);

            $tmp .= '<div id="x2_slider-top">';
            $tmp .= jquery_slider($atts, $content = null);
            $tmp .= '</div>';
            if($cap->slideshow_shadow != "no shadow"){
                $tmp .= '<div class="slidershadow" style="margin-top:-12px; margin-bottom:-30px;">
                            <img src="'.get_template_directory_uri().'/images/slideshow/'.x2_slider_shadow().'">
                         </div>';
            }
        }

        echo $tmp;
    }

    function x2_flux_slider($atts, $content = null) {
        global $cap, $post, $wp_flux_slider;

        $cap_slideshow_time = $cap->slideshow_time;
        if( !empty($cap_slideshow_time) ){
            $atts['delay'] = $atts['pause_time'] = $cap_slideshow_time;
        }

        extract(shortcode_atts(array(
            'amount'                    => '4',
            'category_name'             => '0',
            'page_id'                   => '',
            'post_type'                 => 'post',
            'orderby'                   => 'DESC',
            'reflect'                   => '',
            'width'                     => '',
            'height'                    => '',
            'id'                        => '',
            'effect'                    => array(
                                             'bars',
                                             'blinds',
                                             'blocks',
                                             'blocks2',
                                             'concentric',
                                             'dissolve',
                                             'slide',
                                             'warp',
                                             'zip',
                                             'bars3d',
                                             'blinds3d',
                                             'cube',
                                             'tiles3d',
                                             'turn',
                                           ),
            'autoplay'                  => 'true',
            'pagination'                => 'false',
            'delay'                     => 4000,
            'captions'                  => 'false',
            'controls'                  => 'false',
            'slideshow_sticky'          => 'off'
        ), $atts));

        if($page_id != '' && $post_type == 'post'){
             $post_type = 'page';
        }

        if($page_id != ''){
            $page_id = explode(',',$page_id);
        }

       // Get IDs of sticky posts
        $sticky_posts = get_option( 'sticky_posts' );

        // Get the sticky posts query
        $most_recent_sticky_post = array(
            'post__in'				=> $sticky_posts,
            'ignore_sticky_posts'	=> 1,
            'category_name'  		=> $category_name,
            'orderby'				=> $orderby,
            'posts_per_page'		=> $amount,
            'amount'				=> $amount
        );

        // Get the normal query

        $args = array(
            'orderby'			=> $orderby,
            'category_name'		=> $category_name,
            'posts_per_page'	=> $amount,
            'amount'			=> $amount,
        );
        if( !empty($page_id)) {
            $args['post__in'] = $page_id;
        }

        $args['post_type'] = $post_type;

        $args = $slideshow_sticky == 'on' ? $most_recent_sticky_post : $args;

        remove_all_filters('posts_orderby');

        $flux = new WP_Query($args);

        if ( $flux->have_posts() ){
            while ($flux->have_posts()) : $flux->the_post();

                $no_featured_image = false;
                $url               = get_permalink();
                $slider_img        = get_the_post_thumbnail( $post->ID, 'slider-top-nivo' );

                if ( $slider_img == '')
                    $no_featured_image = true;

                $image_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'slider-top-nivo');

               if($no_featured_image == false && $image_src[1] == 1000) {
                    $ftrdimg = get_the_post_thumbnail_src(get_the_post_thumbnail( $post->ID, 'slider-top-nivo'));
                    $wp_flux_slider->add_slide( $ftrdimg, get_the_title(), $url );
               } elseif($no_featured_image == false ) {
                    $ftrdimg = get_template_directory_uri().'/images/slideshow/featured-img-too-small.jpg';
                    $url     =  get_edit_post_link($post->ID);
                    $wp_flux_slider->add_slide( $ftrdimg, get_the_title(), $url );
               } elseif($no_featured_image == true) {
                    $ftrdimg = get_template_directory_uri().'/images/slideshow/no-featured-img.jpg';
                    $url     =  get_edit_post_link($post->ID);
                    $wp_flux_slider->add_slide( $ftrdimg, get_the_title(), $url );
               }

            endwhile;

            if(is_string($effect)){
                if($effect == 'random'){
                    $effect = array(
                        'bars',
                        'blinds',
                        'blocks',
                        'blocks2',
                        'concentric',
                        'dissolve',
                        'slide',
                        'warp',
                        'zip',
                        'bars3d',
                        'blinds3d',
                        'cube',
                        'tiles3d',
                        'turn'
                    );
                } else {
                    $effect = explode(',', $effect);
                }
            }

            $wp_flux_slider->effect     = $effect;
            $wp_flux_slider->autoplay   = $autoplay;
            $wp_flux_slider->pagination = $pagination;
            $wp_flux_slider->delay      = $delay;
            $wp_flux_slider->captions   = $captions;
            $wp_flux_slider->controls   = $controls;

            echo $wp_flux_slider->get_html();
        } else {
            echo '';
        }

        wp_reset_query();
        wp_reset_postdata();
    }

	function x2_nivo_slider($atts,$content = null) {
        global $cap, $post, $wp_nivo_slider;

        extract(shortcode_atts(array(
            'amount'                    => '4',
            'category_name'             => '0',
            'page_id'                   => '',
            'post_type'                 => 'post',
            'orderby'                   => 'DESC',
            'slider_nav'                => 'on',
            'caption'                   => 'on',
            'reflect'                   => '',
            'width'                     => '',
            'height'                    => '',
            'id'                        => '',
            'time_in_ms'                => '5000',
            'captions'                  => 'false',
            'controls'                  => 'false',
            'slideshow_sticky'          => 'off'
        ), $atts));

        if($page_id != '' && $post_type == 'post'){
             $post_type = 'page';
        }

        if($page_id != ''){
            $page_id = explode(',',$page_id);
        }

        // Get IDs of sticky posts
        $sticky_posts = get_option( 'sticky_posts' );

        // Get the sticky posts query
        $most_recent_sticky_post = array(
            'post__in'				=> $sticky_posts,
            'ignore_sticky_posts'	=> 1,
            'category_name'  		=> $category_name,
            'orderby'				=> $orderby,
            'posts_per_page'		=> $amount,
            'amount'				=> $amount
        );

        // Get the normal query
        $args = array(
            'orderby'			=> $orderby,
            'category_name'		=> $category_name,
            'posts_per_page'	=> $amount,
            'amount'			=> $amount,
        );

        if( !empty($page_id) ) {
            $args['post__in'] = $page_id;
        }

        $args['post_type'] = $post_type;

        $args = $slideshow_sticky == 'on' ? $most_recent_sticky_post : $args;

        remove_all_filters('posts_orderby');

        $nivo = new WP_Query($args);

        if ($nivo->have_posts()){
            while ($nivo->have_posts()) : $nivo->the_post();

                $no_featured_image = false;
                $url               = get_permalink();
                $slider_img        = get_the_post_thumbnail( $post->ID, 'slider-top-nivo' );
                $title             = $captions == 'off' ? '' : get_the_title()   ;


                if ( $slider_img == '')
                    $no_featured_image = true;

                $image_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'slider-top-nivo');

               if($no_featured_image == false && $image_src[1] == 1000) {
                    $ftrdimg = get_the_post_thumbnail_src(get_the_post_thumbnail( $post->ID, 'slider-top-nivo'));
                    $wp_nivo_slider->add_slide( $ftrdimg, $title, $url );
               } elseif($no_featured_image == false) {
                    $ftrdimg = get_template_directory_uri().'/images/slideshow/featured-img-too-small.jpg';
                    $url     =  get_edit_post_link($post->ID);
                    $wp_nivo_slider->add_slide( $ftrdimg, $title, $url );
               } elseif($no_featured_image == true) {
                    $ftrdimg = get_template_directory_uri().'/images/slideshow/no-featured-img.jpg';
                    $url     =  get_edit_post_link($post->ID);
                    $wp_nivo_slider->add_slide( $ftrdimg, $title, $url );
               }

            endwhile;

            echo $wp_nivo_slider->get_html();
        } else {
            echo '';
        }

        wp_reset_query();
        wp_reset_postdata();
   }


    function slider_init(){
        global $wp_nivo_slider, $wp_flux_slider;

        $args = array(
        'width'           => '1000',
        'height'          => '384',
        'animation_speed' => 400,
        'navi'            => True,
        );

        global $cap;
        $cap_slideshow_time = $cap->slideshow_time;
        if( !empty($cap_slideshow_time) ){
          $args['delay'] = $cap_slideshow_time;
          $args['pause_time'] = $cap_slideshow_time;
        }

        $wp_flux_slider = new wp_flux_slider( $args );
        $wp_nivo_slider = new wp_nivo_slider( $args );

    }

	/**
	 * header: add the favicon icon to the site
	 *
	 * located: header.php do_action( 'favicon' )
	 *
	 * @package x2
	 * @since 1.0
	 */
	function favicon(){
		global $cap;

		if($cap->favicon != '') {
			echo '<link rel="shortcut icon" href="'.$cap->favicon.'" />';
		}
	}

	/**
	 * footer: add div class 'inner' inside the footer if the footer is set to full width
	 *
	 * located: footer.php - do_action( 'bp_first_inside_footer' )
	 *
	 * @package x2
	 * @since 1.0
	 */
	function div_inner_start_inside_footer(){
		global $cap;

		if ($cap->footer_width == "full-width") {
			echo '<div class="inner">';
		}
	}

	/**
	 * footer: add div end for class 'inner' inside the footer if the footer is set to full width
	 *
	 * located: header.php - do_action( 'bp_last_inside_footer' )
	 *
	 * @package x2
	 * @since 1.0
	 */
	function div_inner_end_inside_footer(){
		global $cap;

		if ($cap->footer_width == "full-width") {
			echo '</div><!-- .inner -->';
		}
	}

	/**
	 * footer: add div 'innerrim' before footer if the footer is set to full width
	 *
	 * located: footer.php do_action( 'bp_before_footer' )
	 *
	 * @package x2
	 * @since 1.0
	 */
	function innerrim_before_footer(){
		global $cap;

		if ($cap->footer_width == "full-width") {
			echo '</div><!-- #innerrim -->';
		}
	}

	/**
	 * footer: add div 'innerrim' after footer if the footer is not set to full width
	 *
	 * located: footer.php do_action( 'bp_after_footer' )
	 *
	 * @package x2
	 * @since 1.0
	 */
	function innerrim_after_footer(){
		global $cap;

		if ($cap->footer_width != "full-width") {
			echo '</div><!-- #innerrim -->';
		}
	}

	/**
	 * footer: add the sidebars and their default widgets to the footer
	 *
	 * located: footer.php do_action( 'bp_after_footer' )
	 *
	 * @package x2
	 * @since 1.0
	 */
	function footer_content(){
		global $cap;
		if( ! dynamic_sidebar( 'footerfullwidth' )) :
			if($cap->preview == true){ ?>
				<div class="widget" style="margin-bottom: 0; padding: 12px; border: 1px solid #dddddd;">
						<h3 class="widgettitle" ><?php _e('20 widget areas all over the site', 'x2'); ?></h3>
						<div><p style="font-size: 16px; line-height:170%;"><?php _e('4 header + 4 footer widget areas (2 full width and 6 columns).', 'x2'); ?> <br>
						<?php _e('6 widget areas for members + 6 for groups.', 'x2'); ?>
						</p></div>

				</div>
			<?php } ?>
		<?php endif; ?>

		<?php  if (is_active_sidebar('footerleft') || $cap->preview == true ){ ?>
		<div class="widgetarea cc-widget">
			<?php if( ! dynamic_sidebar( 'footerleft' )){ ?>
				<div class="widget">
					<h3 class="widgettitle" ><?php _e('Links', 'x2'); ?></h3>
					<ul>
						<?php wp_list_bookmarks('title_li=&categorize=0&orderby=id'); ?>
					</ul>
				</div>
			<?php } ?>
	  	</div>
		<?php  } ?>

  		<?php if (is_active_sidebar('footercenter') || $cap->preview == true){ ?>
		<div <?php if(!is_active_sidebar('footerleft') && $cap->preview != true ) { echo 'style="margin-left: 34% !important;"'; } ?> class="widgetarea cc-widget">
			<?php if( ! dynamic_sidebar( 'footercenter' )){ ?>
				<div class="widget">
					<h3 class="widgettitle" ><?php _e('Archives', 'x2'); ?></h3>
					<ul>
						<?php wp_get_archives( 'type=monthly' ); ?>
					</ul>
				</div>
			<?php } ?>
	  	</div>
  		<?php } ?>

  		<?php if (is_active_sidebar('footerright') || $cap->preview == true ){ ?>
		<div class="widgetarea cc-widget cc-widget-right">
			<?php if( ! dynamic_sidebar( 'footerright' )){ ?>
				<div class="widget">
					<h3 class="widgettitle" ><?php _e('Meta', 'x2'); ?></h3>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</div>
			<?php } ?>
	  	</div>
	  	<?php } ?>

  		<div class="clear"></div>
	  	<br />
		<?php if($cap->disable_credits_footer != false || !defined('is_pro')){ ?>
			<br />
			<div class="credits"><?php printf( __( '%s is proudly powered by <a class="credits" href="http://wordpress.org">WordPress.</a> ', 'x2' ), bloginfo('name') ); ?>
			<?php _e('<a class="credits" href="http://themekraft.com/" target="_blank" title="Wordpress Themes" alt="WordPress Themes">Themes and Plugins developed by Themekraft.</a>','x2') ?></div>
		<?php } ?>
		<?php if($cap->my_credits_footer != '' ){ ?>
			<br />
			<div class="credits"><?php echo $cap->my_credits_footer; ?></div>
		<?php } ?>
	<?php
	}


	/**
	 * left sidebar: add the sidebar and their default widgets to the left sidebar
	 *
	 * located: do_action( 'sidebar_left' )
	 *
	 * @package x2
	 * @since 1.0
	 */
	function sidebar_left(){
		global $cap, $bp, $post;

		$tmp = !empty($post) ? get_post_meta( $post->ID, '_wp_page_template', true ) : '';
		if( $tmp == 'full-width.php' || $tmp == '_pro/right-sidebar.php')
			return;

		if( $tmp == 'left-and-right-sidebar.php' || $tmp == 'left-sidebar.php' || $tmp == '_pro/left-sidebar.php' ||  $tmp == '_pro/left-and-right-sidebar.php'){
			locate_template( array( 'sidebar-left.php' ), true );
			return;
		}

        $component = explode('-',$this->detect->tk_get_page_type());
		if(!empty($component[2])){

			if($component[2] == 'groups' && !empty($component[3])) {
				if($cap->bp_groups_sidebars == 'left' || $cap->bp_groups_sidebars == 'left and right' ){
					locate_template( array( 'groups/single/group-sidebar-left.php' ), true );
				} elseif($cap->bp_groups_sidebars == "default" && $cap->sidebar_position == "left" || $cap->sidebar_position == "left and right" && $cap->bp_groups_sidebars == "default"){
					locate_template( array( 'sidebar-left.php' ), true );
				}
			} elseif($component[2] == 'profile' && !empty($component[3])) {

				if($cap->bp_profile_sidebars == 'left' || $cap->bp_profile_sidebars == 'left and right' ){
					locate_template( array( 'members/single/member-sidebar-left.php' ), true );
				} elseif( $cap->bp_profile_sidebars == "default" && $cap->sidebar_position == "left" || $cap->sidebar_position == "left and right" && $cap->bp_profile_sidebars == "default"){
					locate_template( array( 'sidebar-left.php' ), true );
				}
			} else if($cap->sidebar_position == "left" || $cap->sidebar_position == "left and right"){
				locate_template( array( 'sidebar-left.php' ), true );
			}
		} else {
			if($cap->sidebar_position == "left" || $cap->sidebar_position == "left and right"){
				locate_template( array( 'sidebar-left.php' ), true );
			}
	  	}
	}

	/**
	 * sidebar: add the sidebar and their default widgets to the right sidebar
	 *
	 * located: do_action( 'sidebar_left' )
	 *
	 * @package x2
	 * @since 1.0
	 */
	function sidebar_right(){
		global $cap, $post;

		$tmp = !empty($post) ? get_post_meta( $post->ID, '_wp_page_template', true ) : '';

		if( $tmp == 'full-width.php' || $tmp == 'left-sidebar.php'|| $tmp == '_pro/left-sidebar.php')
			return;

		if( $tmp == 'left-and-right-sidebar.php' || $tmp == 'right-sidebar.php' ||  $tmp == '_pro/left-and-right-sidebar.php' || $tmp == '_pro/right-sidebar.php'){
			locate_template( array( 'sidebar.php' ), true );
			return;
		}

		$component = explode('-',$this->detect->tk_get_page_type());
		if(!empty($component[2])){
			if($component[2] == 'groups' && !empty($component[3])) {
				if($cap->bp_groups_sidebars == 'right' || $cap->bp_groups_sidebars == 'left and right' ){
					locate_template( array( 'groups/single/group-sidebar-right.php' ), true );
				} elseif($cap->bp_groups_sidebars == "default" && $cap->sidebar_position == "right" || $cap->sidebar_position == "left and right" && $cap->bp_groups_sidebars == "default"){
					locate_template( array( 'sidebar.php' ), true );
				}
			} elseif($component[2] == 'profile' && !empty($component[3])) {
                if(!$cap->bp_profile_sidebars) $cap->bp_profile_sidebars = "default";

                if($cap->bp_profile_sidebars == 'right' || $cap->bp_profile_sidebars == 'left and right' ){
                    locate_template( array( 'members/single/member-sidebar-right.php' ), true );

                } elseif( $cap->bp_profile_sidebars == "default" && $cap->sidebar_position == "right" || $cap->sidebar_position == "left and right" && $cap->bp_profile_sidebars == "default"){
                    locate_template( array( 'sidebar.php' ), true );
                }

            } else if($cap->sidebar_position == "right" || $cap->sidebar_position == "left and right"){
                locate_template( array( 'sidebar.php' ), true );
            }
		} else {
			if($cap->sidebar_position == "right" || $cap->sidebar_position == "left and right"){
                locate_template( array( 'sidebar.php' ), true, false );
			}
		}
	}

    /**
     * x2_list_posts_on_pages
     *
     * located: index.php do_action( 'bp_before_blog_home' )
     *
     * @package x2
     * @since 1.0
     */
    function list_posts_under_page(){
        global $post, $cap, $tmp, $the_lp_query, $show_pagination, $wp_query;

        $this_post = $post;
        $show_me_mow                    = 'false';
        $x2_page_enable_featured_posts  = get_post_meta($post->ID,"_x2_page_enable_featured_posts", true);
        $component                      = explode('-',$this->detect->tk_get_page_type());

        if(is_page() && $x2_page_enable_featured_posts == 'show') {
            $show_me_mow = 'true';
        }

        if($show_me_mow == 'false')
            return;

        $tk_query_id = '';
        $template    = get_post_meta($post->ID,"_x2_page_featured_posts_style", true);
        $show_sticky = get_post_meta($post->ID,"_x2_page_featured_posts_show_sticky", true);
        $categories  = get_post_meta($post->ID,"_x2_page_featured_posts_category", false);
        $post_type   = get_post_meta($post->ID,"_x2_page_featured_posts_post_type", true);

        $posts_per_page   = get_post_meta($post->ID,"_x2_page_featured_posts_posts_per_page", true);
        $show_pages_by_id = get_post_meta($post->ID,"_x2_page_featured_posts_show_pages_by_id", true);
        $orderby          = get_post_meta($post->ID,"_x2_page_featured_posts_orderby", true);
        $show_pagination  = get_post_meta($post->ID,"_x2_page_featured_posts_show_pagination", true);
        // $pagination_ajax_effect = get_post_meta($post->ID,"_x2_page_featured_posts_pagination_ajax_effect", true);

		$fp_cats = Array();
		foreach ($categories as $key => $cat) {
			$fp_cats[] = get_term_by('id',$cat, 'category');
		}

		$fp_cat_slugs = Array();
		foreach ($fp_cats as $key => $fp_cat) {
			if(is_object($fp_cat)) {
                $fp_cat_slugs[] = $fp_cat->slug;
            }
		}

		$categories = implode ( ',' , $fp_cat_slugs );

		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	    // Get IDs of sticky posts
	    $sticky_posts = get_option( 'sticky_posts' );

		// Get the sticky posts query
	    $most_recent_sticky_post = array(
	        'post__in'              => $sticky_posts,
	        'ignore_sticky_posts'   => 1,
	        'category_name'         => $categories,
	        'orderby'               => $orderby,
	        'posts_per_page'        => $posts_per_page,
	        'post_type'             => $post_type,
	    );

        if( isset($order) && !empty($order)){
            $most_recent_sticky_post['order'] = $order;
        }

	    // Get the normal query
	    $list_post_query_args = array(
	        'posts_per_page'        => $posts_per_page,
	        'orderby'               => $orderby,
	        'category_name'         => $categories,
	        'ignore_sticky_posts'   => 1,
	    );

        if( !empty($show_pagination) && $show_pagination != 'hide' ) {
            $list_post_query_args['paged'] = $paged;
        }
        if( !empty($show_pages_by_id) ) {
            $list_post_query_args['post__in'] = $show_pages_by_id;
        }
        if( empty($page_id) ) {
            $list_post_query_args['post_type'] = $post_type;
        }
        if( isset($order) && !empty($order)){
            $list_post_query_args['order'] = $order;
        }

		remove_all_filters('posts_orderby');

    	$list_post_query_args = $show_sticky == 'on' ? $most_recent_sticky_post : $list_post_query_args;
		$list_post_query[$tk_query_id] = new WP_Query( $list_post_query_args );

		$the_lp_query = $list_post_query[$tk_query_id];

		x2_the_loop( $template, $tk_query_id, $show_pagination);

        wp_reset_query();
        $post = $this_post;
    }

	/**
	 * check if to use content or excerpt and the excerpt length
	 *
	 * located: multiple places
	 *
	 * @package x2
	 * @since 1.0
	 */
	function excerpt_on(){
		global $cap;

		if($cap->excerpt_on != 'content'){
			add_filter('excerpt_length', 'x2_excerpt_length');
			the_excerpt( __( 'Read the rest of this entry &rarr;', 'x2' ) );
		} else {
			the_content( __( 'Read the rest of this entry &rarr;', 'x2' ) );
		}
	}


	/**
	 * groups home: add the sidebars and their default widgets to the groups header
	 *
	 * located: grous/home.php do_action( 'bp_before_group_home_content' )
	 *
	 * @package x2
	 * @since 1.0
	 */
	function before_group_home_content(){
		global $cap;
		if( $cap->bp_groups_header == false || $cap->bp_groups_header == 'on'):?>
			<div id="item-header">
				<?php if( ! dynamic_sidebar( 'groupheader' )) : ?>
				 <?php locate_template( array( 'groups/single/group-header.php' ), true ) ?>
				<?php endif; ?>

				<?php if (is_active_sidebar('groupheaderleft') ){ ?>
					<div class="widgetarea cc-widget">
					<?php dynamic_sidebar( 'groupheaderleft' )?>
					</div>
				<?php } ?>
				<?php if (is_active_sidebar('groupheadercenter') ){ ?>
					<div <?php if(!is_active_sidebar('groupheaderleft')) { echo 'style="margin-left:30% !important"'; } ?> class="widgetarea cc-widget">
					<?php dynamic_sidebar( 'groupheadercenter' ) ?>
					</div>
				<?php } ?>
				<?php if (is_active_sidebar('groupheaderright') ){ ?>
					<div class="widgetarea cc-widget cc-widget-right">
					<?php dynamic_sidebar( 'groupheaderright' ) ?>
					</div>
				<?php } ?>
			</div>
		<?php else:?>
			<div id="item-header">
				<h2><a href="<?php bp_group_permalink() ?>" title="<?php bp_group_name() ?>"><?php bp_group_name() ?></a></h2>
			</div>
		<?php endif;?>
		<?php if($cap->bp_default_navigation == true){?>
			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav">
					<ul>
						<?php bp_get_options_nav() ?>

						<?php do_action( 'bp_group_options_nav' ) ?>
					</ul>
				</div>
			</div><!-- #item-nav -->
		<?php }
	}

	/**
	 * members home: add the sidebars and their default widgets to the members header
	 *
	 * located: members/home.php do_action( 'bp_before_member_home_content' )
	 *
	 * @package x2
	 * @since 1.0
	 */
	function before_member_home_content(){
		global $cap;

		if($cap->bp_profile_header == false || $cap->bp_profile_header == 'on'): ?>
			<div id="item-header">
				<?php if( ! dynamic_sidebar( 'memberheader' )) : ?>
					<?php locate_template( array( 'members/single/member-header.php' ), true ) ?>
				<?php endif; ?>

				<div class="clear"></div>

				<?php if (is_active_sidebar('memberheaderleft') ){ ?>
					<div class="widgetarea cc-widget">
					<?php dynamic_sidebar( 'memberheaderleft' )?>
					</div>
				<?php } ?>
				<?php if (is_active_sidebar('memberheadercenter') ){ ?>
					<div <?php if(!is_active_sidebar('memberheaderleft')) { echo 'style="margin-left:30% !important"'; } ?> class="widgetarea cc-widget">
					<?php dynamic_sidebar( 'memberheadercenter' ) ?>
					</div>
				<?php } ?>
				<?php if (is_active_sidebar('memberheaderright') ){ ?>
					<div class="widgetarea cc-widget cc-widget-right">
					<?php dynamic_sidebar( 'memberheaderright' ) ?>
					</div>
				<?php } ?>
			</div>
		<?php else:?>
			<div id="item-header">
				<h2 class="fn"><a href="<?php bp_user_link() ?>"><?php bp_displayed_user_fullname() ?></a> <span class="highlight">@<?php bp_displayed_user_username() ?> <span>?</span></span></h2>
			</div>
		<?php endif;?>

		<?php if($cap->bp_default_navigation == true){?>
		<div id="item-nav">
			<div class="item-list-tabs no-ajax" id="object-nav">
				<ul>
					<?php bp_get_displayed_user_nav() ?>

					<?php do_action( 'bp_member_options_nav' ) ?>
				</ul>
			</div>
		</div><!-- #item-nav -->
		<?php }
	}


	/**
	 * login page: overwrite the login css by adding it to the login_head
	 *
	 * located: login.php do_action( 'login_head' )
	 *
	 * @package x2
	 * @since 1.0
	 */
	function custom_login() {
		global $cap; ?>
		<style type="text/css">

		.login h1 a {
			<?php if($cap->bg_loginpage_img){ ?>
				background-image: url('<?php echo $cap->bg_loginpage_img; ?>') !important;
				background-repeat: no-repeat;
				height:<?php echo $cap->login_logo_height; ?>px;
			<?php } ?>
			clear: both;
		}

		<?php if($cap->bg_loginpage_body_img || $cap->bg_loginpage_body_color){ ?>
			body, body.login {
				<?php if($cap->bg_loginpage_body_img){ ?>
 					background-image: url('<?php echo $cap->bg_loginpage_body_img; ?>');
				<?php } ?>
				<?php if($cap->bg_loginpage_body_color){ ?>
					background-color: #<?php echo $cap->bg_loginpage_body_color; ?>;
				<?php } ?>
			}
		<?php } ?>

		<?php if($cap->bg_loginpage_body_color){ ?>
			body {
				color:#<?php echo $cap->bg_loginpage_body_color; ?>;
			}
		<?php } ?>
		#login{
		    margin: auto;
    		padding-top: 30px;
		}
		.login #nav a {
			color:#777 !important;
		}
		.login #nav a:hover {
			color:#777 !important;
		}
		.updated, .login #login_error, .login .message {
			background: none;
			color:#777;
			border-color:#888;
		}
		#lostpasswordform {
			border-color:#999;
		}
		</style>
	<?php
	}

	/**
	 * check if bubble style is activated and where you are right now.
	 * if needed, add class 'bubble' in the body.
	 *
	 * do_action( 'body_class' )
	 *
	 * @package x2
	 * @since 0.1
	 */
	function add_bubble($classes){
		global $cap;

		$component = explode('-',$this->detect->tk_get_page_type());

		$bubble_destination = array(
			'archive'	 => $cap->posts_lists_style,
			'home'		 => $cap->posts_lists_style,
		);

		if( isset($component[1]) && isset($bubble_destination[$component[1]]) && !empty($bubble_destination[$component[1]]) && !in_array( $bubble_destination[$component[1]], $classes ) ){
            $classes[] = $bubble_destination[$component[1]];
        }

		return $classes;

	}

	/**
	 * check if the class 'home' exists in the body_class if buddypress is activated.
	 * if not, add class 'home'
	 *
	 * do_action( 'body_class' )
	 *
	 * @package x2
	 * @since 1.0
	 */
	function add_home_class($classes){

        $component = explode('-',$this->detect->tk_get_page_type());

		if(defined('BP_VERSION')){
			if( !in_array( 'home', $classes ) ){
				if (isset($component[1]) && $component[1] == 'home' )
					$classes[] = 'home';
			}
		}

		return $classes;
	}
}
?>
