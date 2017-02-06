<?php

// No direct access is allowed
if( ! defined( 'ABSPATH' ) ) exit;

load_template(dirname(__FILE__). '/wp-zliders.php' );

class wp_nivo_slider extends wp_zliders{

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

            // Slide Effect
            'effect'          => 'random',
            'animation_speed' => 600,
            'pause_time'      => 3000,
            'auto_play'       => TRUE,

            // Navi
            'navi' => TRUE, // true, false

            // Slider size
            'width'     => '1000',
            'height'    => '320',
            'size_unit' => 'px',

            // Styles
            'css_class'          => 'nivoSlider',
            'disable_slider_css' => FALSE,

            // Activation
            'activate_in_theme' => TRUE,
            'activate_in_admin' => FALSE,

            // Nivo Slider special values
            'navi_next_prev'     => TRUE, // true, false
            'slices'             => 15, // For slice animations
            'box_cols'           => 8,
            'box_rows'           => 4,
            'start_slide'        => 0,
            'control_nav_thumbs' => FALSE,
            'direction_nav_hide' => TRUE,
            'pause_on_hover'     => TRUE,
            'manual_advance'     => FALSE,
            'prev_text'          => 'Prev',
            'next_text'          => 'Next',
            'random_start'       => FALSE,
            'controls'           => 'false',
            'captions'           => 'false',
        );

        $args = wp_parse_args( $args, $defaults);
        extract( $args , EXTR_SKIP );

        parent::__construct( $args );

        $this->navi_next_prev     = $navi_next_prev;
        $this->slices             = $slices;
        $this->box_cols           = $box_cols;
        $this->box_rows           = $box_rows;
        $this->start_slide        = $start_slide;
        $this->control_nav_thumbs = $control_nav_thumbs;
        $this->direction_nav_hide = $direction_nav_hide;
        $this->pause_on_hover     = $pause_on_hover;
        $this->manual_advance     = $manual_advance;
        $this->prev_text          = $prev_text;
        $this->next_text          = $next_text;
        $this->controls           = $controls;
        $this->captions           = $captions;

        $this->effects = array(
                'sliceDown',
                'sliceDownLeft',
                'sliceUp',
                'sliceUpLeft',
                'sliceUpDown',
                'sliceUpDownLeft',
                'fold',
                'fade',
                'random',
                'slideInRight',
                'slideInLeft',
                'boxRandom',
                'boxRain',
                'boxRainReverse',
                'boxRainGrow',
                'boxRainGrowReverse',
            );
    }

    /**
    * Adds needed CSS code for slider
    *
    * @package WP Zliders
    * @since 0.1.0
    *
    */
    public function css(){
        global $cap;

        $img_path = get_template_directory_uri().'/core/includes/sliders/nivo-slider/themes/default/';

        // Slide Controlls
        $html = chr(13) . '<!-- WP Zliders - Nivo Slider CSS //-->' . chr(13);
        $html.= '<style type="text/css">';

        ob_start(); ?>

				.nivoSlider {
                	height: 320px; /* fixed the height for now */
                }
                .slider-wrapper .nivoSlider {
                    position:relative;
                    background:#<?php echo $cap->bg_details_color; ?> url(<?php echo $img_path ?>loading.gif) no-repeat 50% 50%;
                    -webkit-box-shadow: 0px 0px 3px rgba(0,0,0,0.6);
                    -moz-box-shadow: 0px 0px 3px rgba(0,0,0,0.6);
                    box-shadow: 0px 0px 3px rgba(0,0,0,0.6);
                }
                 .nivoSlider img {
                    position:absolute;
                    top:0px;
                    left:0px;
                    display:none;
                }
                 .nivoSlider a {
                    border:0;
                    display:block;
                }

                 .nivo-controlNav {
                    text-align: center;
                    padding: 20px 0;
                }
                 .nivo-controlNav a {
                    display:inline-block;
                    width:22px;
                    height:22px;
                    background:url(<?php echo $img_path ?>bullets.png) no-repeat;
                    text-indent:-9999px;
                    border:0;
                    margin: 0 2px;
                }
                 .nivo-controlNav a.active {
                    background-position:0 -22px;
                }

                 .nivo-directionNav a {
                    display:block;
                    width:30px;
                    height:30px;
                    background:url(<?php echo $img_path ?>arrows.png) no-repeat;
                    text-indent:-9999px;
                    border:0;
                }
                 a.nivo-nextNav {
                    background-position:-30px 0;
                    right:15px;
                }
                 a.nivo-prevNav {
                    left:15px;
                }
                .nivo-caption a {
                    color:#fff;
                    border-bottom:1px dotted #fff;
                }
                 .nivo-caption a:hover {
                    color:#fff;
                }

                 .nivo-controlNav.nivo-thumbs-enabled {
                    width: 100%;
                }
                 .nivo-controlNav.nivo-thumbs-enabled a {
                    width: auto;
                    height: auto;
                    background: none;
                    margin-bottom: 5px;
                }
                 .nivo-controlNav.nivo-thumbs-enabled img {
                    display: block;
                    width: 120px;
                    height: auto;
                }

        <?php

        $html.=  ob_get_contents();
        ob_end_clean();

        $html.= '</style>';
        $html.= chr(13) . '<!-- WP Zliders - Nivo Slider CSS //-->' . chr(13);

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
           wp_enqueue_script( 'nv_script', WP_ZLIDERS_URLPATH . '/nivo-slider/jquery.nivo.slider.pack.js');


        // CSS
        if( !$this->disable_slider_css )
            wp_enqueue_style( 'nivo-slider-css', WP_ZLIDERS_URLPATH . '/nivo-slider/nivo-slider.css' );
    }

    /**
    * Getting HTML for Slider
    *
    * @package WP Zliders
    * @since 0.1.0
    *
    */
    public function get_html(){
        global $cap;
        $html = '';
          // Standard Slider values
        $html.= '<script type="text/javascript">';
        $html.= 'jQuery(document).ready(function(jQuery) {';
        $html.= 'jQuery(\'#' . $this->id . '\').nivoSlider({';

        // Effect settings
        $html.= 'effect: \'' . $this->effect . '\',';
        $html.= 'animSpeed: ' . $this->animation_speed . ',';
        $html.= 'pauseTime: ' . $this->pause_time . ',';

        // Navigation
        if( !$this->navi )
            $html.= 'controlNav: false,';

        // Nivo Slider special values
        if( !$this->direction_nav_hide )
            $html.= 'directionNavHide: false,';


        if( !$this->navi_next_prev )
            $html.= 'directionNav: false,';

        $html.= 'slices: ' . $this->slices . ',';
        $html.= 'boxCols: ' . $this->box_cols . ',';
        $html.= 'boxRows: ' . $this->box_rows . ',';
        $html.= 'startSlide: ' . $this->start_slide . ',';

        if( $this->control_nav_nhumbs )
            $html.= 'controlNavThumbs: true,';

        if( !$this->pause_on_hover )
            $html.= 'pauseOnHover: false,';

        if( $this->manual_advance )
            $html.= 'manualAdvance: true,';

        $html.= 'prevText: \'' . $this->prev_text . '\',';
        $html.= 'nextText: \'' . $this->next_text . ',\'';

        if( $this->random_start )
            $html.= 'randomStart: true,';

        $html.= '});});</script>';

        $html .= ' <div class="slider-wrapper ' . $this->css_class . '" style="width:' . $this->width . $this->size_unit .  '; height:' . $this->height . $this->size_unit .  '"><div id="' . $this->id . '" class="' . $this->css_class . '">';

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

        if($cap->slideshow_shadow != "no shadow"){
            $html .= '<div class="slidershadow" style="margin-top:-64px; margin-bottom:-30px;"><img src="'.get_template_directory_uri().'/images/slideshow/'.x2_slider_shadow().'"></div>';
        }
        $html.= '</div>';



        return $html;
    }
}