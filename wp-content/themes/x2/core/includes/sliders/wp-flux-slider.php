<?php

// No direct access is allowed
if( ! defined( 'ABSPATH' ) ) exit;

load_template(dirname(__FILE__). '/wp-zliders.php' );

class wp_flux_slider extends wp_zliders{

	var $navi_next_prev;
	var $slices;
	var $box_cols;
	var $box_rows;
	var $start_slide;
	var $control_nav_nhumbs;
	var $direction_nav_hide;
	var $pause_on_hover;
	var $manual_advance;
	var $prev_text;
	var $next_text;
	var $random_start;

	/**
	* PHP 5 constructor
	*
	* @package WP Zliders
	* @since 0.1.0
	*
	*/
	function __construct( $args = array() ){

		$defaults = array(
			// Standard Slide Values
			'id' => md5( rand() ),

			'effect' => array(
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
            'autoplay'   => 'true',
            'pagination' => 'true',
            'delay'      => 4000,
            'controls'   => 'false',
            'captions'   => 'false',
            );

		$args = wp_parse_args( $args, $defaults);
		extract( $args , EXTR_SKIP );

		parent::__construct( $args );

        $this->autoplay   = $autoplay;
        $this->pagination = $pagination;
        $this->delay      = $delay;
        $this->controls   = $controls;
        $this->captions   = $captions;
        $this->effect     = $effect;
	}

	/**
	* Adds needed CSS code for slider
	*
	* @package WP Zliders
	* @since 0.1.0
	*
	*/
	public function css(){

        $img_path = get_template_directory_uri().'/core/includes/sliders/flux-slider/themes/default/';

        // Slide Controlls
        $html = chr(13) . '<!-- WP Zliders - flux Slider CSS //-->' . chr(13);
        $html.= '<style type="text/css">';

        ob_start(); ?>

        <?php

        $html.= ob_get_clean();

		$html.= '</style>';
		$html.= chr(13) . '<!-- WP Zliders - flux Slider CSS //-->' . chr(13);

		echo $html;
	}

	/**
	* Load Scripts function which loads all necessary JS and CSS into WordPress
	*
	* @package WP Zliders
	* @since 0.1.0
	*
	*/
	public function load_scripts(){
	    // JS
        wp_enqueue_script( 'wp-flux-slider', WP_ZLIDERS_URLPATH . '/flux-slider/js/flux.min.js', array('jquery'));

        // CSS
        if( !$this->disable_slider_css )
            wp_enqueue_style( 'flux-slider-css', WP_ZLIDERS_URLPATH . '/flux-slider/css/flux-slider.css' );


    }

	/**
	* Getting HTML for Slider
	*
	* @package WP Zliders
	* @since 0.1.0
	*
	*/
	public function get_html(){
         ob_start();
         $html = '';
         ?>
        <script type="text/javascript" charset="utf-8">
            jQuery(document).ready(function(){
                var a = new Array();
                <?php
                foreach ($this->effect as $key => $effect) {
                    echo 'a['.$key.'] = \''.$effect.'\', '. chr(13);
                }
                ?>

                window.f = new flux.slider('#slider', {
                    autoplay: <?php echo $this->autoplay; ?>,
                    pagination: <?php echo $this->pagination; ?>,
                    delay: <?php echo $this->delay; ?>,
                    controls: <?php echo $this->controls; ?>,
                    captions: <?php echo $this->captions; ?>,
                    transitions: a
                });

                // Setup a listener for user requested transitions
                jQuery('div#transitions').bind('click', function(event){
                    event.preventDefault();

                    window.f.next(event.target.href.split('#')[1]);
                });

            });
        </script>

        <?php
        $html.=  ob_get_contents();
        ob_end_clean();
		$html .= ' <div id="slidercontainer"><div id="slider">';

		foreach( $this->slider_content AS $slider ):

            if( $slider['url'] != FALSE )
                $html.= '<a href="' . $slider['url'] . '">';

            $html.= '<img src="' . $slider['img_url'] . '"';

            if( $slider['caption'] != FALSE )
                $html.= ' title="' . $slider['caption'] . '"';

            if( $slider['thumb_url'] != FALSE )
                $html.= ' data-thumb="' . $slider['thumb_url'] . '"';

            $html.= ' />';

            if( $slider['url'] != '' )
                $html.= '</a>';

        endforeach;
		$html.= '</div>';

		$html.= '</div>';

    	return $html;
	}
}