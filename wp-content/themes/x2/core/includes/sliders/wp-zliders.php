<?php

class wp_zliders{

    // Animation settings
    var $id;

    var $effect;
    var $animation_speed;
    var $pause_time;
    var $auto_play;
    var $show_caption;

    var $navi;
    var $navi_position;

    var $width;
    var $height;
    var $size_unit;

    var $css_class;
    var $disable_slider_css;

    var $activate_in_theme;
    var $activate_in_admin;

    // Array with all effects
    var $effects;

    // Animation content
    var $slider_content;

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
            'effect'          => '',
            'animation_speed' => 500, // Milliseconds
            'pause_time'      => 3000, // Milliseconds
            'auto_play'       => TRUE,
            'show_caption'    => TRUE,

            // Navi
            'navi'          => TRUE, // true, false
            'navi_position' => 'outside', // outside, inside, none

            // Slider size
            'width'     => '1000',
            'height'    => '320',
            'size_unit' => 'px',

            // Styles
            'css_class'          => '',
            'disable_slider_css' => FALSE,

            // Activation
            'activate_in_theme' => TRUE,
            'activate_in_admin' => FALSE,

        );

        $args = wp_parse_args( $args, $defaults);
        extract( $args , EXTR_SKIP );

        $this->constants();

        $this->id = $id;

        $this->effect          = $effect;
        $this->animation_speed = $animation_speed;
        $this->auto_play       = $auto_play;
        $this->pause_time      = $pause_time;
        $this->show_caption    = $show_caption;

        $this->width     = $width;
        $this->height    = $height;
        $this->size_unit = $size_unit;

        $this->navi          = $navi;
        $this->navi_position = $navi_position;

        $this->css_class          = $css_class;
        $this->disable_slider_css = $disable_slider_css;

        $this->effects = array();

        $this->slider_content = array();

        // Enqueing Scripts
        if( $activate_in_theme ):
            add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
            if( !$this->disable_slider_css )
                add_action( 'wp_head', array( $this, 'css' ) );
        endif;

        if( $activate_in_admin ):
            add_action( 'admin_head', array( $this, 'load_scripts' ) );
            if( !$this->disable_slider_css )
                add_action( 'admin_head', array( $this, 'css' ) );
        endif;

    }

    /**
    * Load Scripts function -> Dummy function as template for child classes
    *
    * @package WP Zliders
    * @since 0.1.0
    *
    */
    public function load_scripts(){
        // NOTHING
    }

    /**
    * Adds needed CSS code for slider -> Dummy function as template for child classes
    *
    * @package WP Zliders
    * @since 0.1.0
    *
    */
    public function css(){
        // NOTHING
    }

    /**
    * Loading Constants
    *
    * @package WP Zliders
    * @since 0.1.0
    *
    */
    private function constants(){
        if(!defined('WP_ZLIDERS_FOLDER')){
            define( 'WP_ZLIDERS_FOLDER',     get_template_directory() . '/core/includes/sliders' );
        }
        if(!defined('WP_ZLIDERS_URLPATH')){
            define( 'WP_ZLIDERS_URLPATH',    get_template_directory_uri() . '/core/includes/sliders' );
        }
    }

    /**
    * Adding a slide to the slideshow
    *
    * @package Themekraft Framework
    * @since 0.1.0
    *
    * @param string $img_src The URL to the image
    * @param string $content Content within the slide
    * @param string $url An URL where the slide is linking to
    * @param string $effect An individual effect for the slide
    *
    */
    function add_slide( $img_url, $caption = FALSE, $url = FALSE, $effect = FALSE, $thumb_url = FALSE ){
        array_push( $this->slider_content, array( 'img_url' => $img_url, 'caption' => $caption, 'url' => $url, 'effect' => $effect, 'thumb_url' => $thumb_url ) );
    }

    /**
    * Get HTML function -> Dummy function as template for child classes
    *
    * @package WP Zliders
    * @since 0.1.0
    *
    */
    function get_html(){
        // NOTHING
    }
}
