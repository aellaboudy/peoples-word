<?php
require_once( dirname(__FILE__) . '/admin/cheezcap.php');
require_once( dirname(__FILE__) . '/core/loader.php');

/** Tell WordPress to run x2_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'x2_setup' );
if ( ! function_exists( 'x2_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * To override x2_setup() in a child theme, add your own x2_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_theme_support('custom-background') To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 * @uses $content_width To set a content width according to the sidebars.
 * @uses BP_DISABLE_ADMIN_BAR To disable the admin bar if set to disabled in the themesettings.
 *
 */
function x2_setup() {
    global $cap, $content_width;

    // This theme styles the visual editor with editor-style.css to match the theme style.
    add_editor_style();

    // This theme uses post thumbnails

	add_theme_support( 'woocommerce' );
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 222, 160, true );
    add_image_size( 'slider-top-large', 1000, 250, true );
	add_image_size( 'slider-top-nivo', 1000, 320, true );
    add_image_size( 'slider-large', 990, 250, true );
    add_image_size( 'slider-middle', 756, 250, true );
    add_image_size( 'slider-thumbnail', 80, 50, true );
    add_image_size( 'post-thumbnails', 222, 160, true );
	add_image_size( 'single-post-thumbnail', 598, 372, true );


    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Make theme available for translation
    // Translations can be filed in the /languages/ directory
    load_theme_textdomain( 'x2', get_template_directory() . '/languages' );

    $locale = get_locale();
    $locale_file = get_template_directory() . "/languages/$locale.php";
    if ( is_readable( $locale_file ) )
        require_once( $locale_file );

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus( array(
        'menu_top' => __( 'Header top menu', 'x2' ),
        'primary'  => __( 'Header bottom menu', 'x2' ),
    ) );

    // This theme allows users to set a custom background
    if($cap->wp_custom_background == true){
        add_theme_support( 'custom-background' );
    }
    // Your changeable header business starts here
    define( 'HEADER_TEXTCOLOR', '888888' );

    // No CSS, just an IMG call. The %s is a placeholder for the theme template directory URI.
   // define( 'HEADER_IMAGE', '%s/images/default-header.png' );

    // The height and width of your custom header. You can hook into the theme's own filters to change these values.
    // Add a filter to x2_header_image_width and x2_header_image_height to change these values.
    define( 'HEADER_IMAGE_WIDTH', apply_filters( 'x2_header_image_width', 1000 ) );
    define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'x2_header_image_height', 233 ) );


    // Add a way for the custom header to be styled in the admin panel that controls
    // custom headers. See x2_admin_header_style(), below.
    if($cap->add_custom_image_header == true){
		$defaults = array(
            /*'default-image'          => '',
            'random-default'         => false,
            'width'                  => 0,
            'height'                 => 0,
            'flex-height'            => false,
            'flex-width'             => false,
            'default-text-color'     => '',
            'header-text'            => true,
            'uploads'                => true,*/
            'wp-head-callback'       => 'x2_admin_header_style',
            'admin-head-callback'    => 'x2_header_style',
            'admin-preview-callback' => 'x2_admin_header_image',
        );
        add_theme_support('custom-header',$defaults);
        //add_custom_image_header( 'x2_header_style', 'x2_admin_header_style', 'x2_admin_header_image' );
    }

}
endif;

if ( ! function_exists( 'x2_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 */
function x2_header_style() {
    // If no custom options for text are set, let's bail
    // get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
    if ( HEADER_TEXTCOLOR == get_header_textcolor() )
        return;
    // If we get this far, we have custom styles. Let's do this.
    ?>
    <style type="text/css">
    <?php
        // Has the text been hidden?
        if ( 'blank' == get_header_textcolor() ) :
    ?>
        #blog-description, #header h1 a, #header h4 a {
            position: absolute;
            left: -9000px;
        }
    <?php
        // If the user has set a custom color for the text use that
        else :
    ?>
        #blog-description, #header h1 a, #header h4 a {
            color: #777777;
            color: #<?php echo get_header_textcolor(); ?> !important;
        }
    <?php endif; ?>
    </style>
    <?php
}
endif;


if ( ! function_exists( 'x2_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in x2_setup().
 *
 */
function x2_admin_header_style() {
?>
    <style type="text/css">
    .appearance_page_custom-header #headimg {
        background: #<?php echo get_background_color(); ?>;
        border: none;
        text-align: center;
    }
    #headimg h1,
    #desc {
        font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
    }
    #headimg h1 {
        margin: 0;
    }
    #headimg h1 a {
        font-size: 36px;
        letter-spacing: -0.03em;
        line-height: 42px;
        text-decoration: none;
    }
    #desc {
        font-size: 18px;
        line-height: 31px;
        padding: 0 0 9px 0;
    }
    <?php
        // If the user has set a custom color for the text use that
        if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
    ?>
        #site-title a,
        #site-description {
            color: #<?php echo get_header_textcolor(); ?>;
        }
    <?php endif; ?>
    #headimg img {
        max-width: 990px;
        width: 100%;
    }
    </style>
<?php
}
endif;

if ( ! function_exists( 'x2_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in x2_setup().
 *
 */
function x2_admin_header_image() { ?>
    <div id="headimg">
        <?php
        if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
            $style = ' style="display:none;"';
        else
            $style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
        ?>
        <h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
        <div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
        <img src="<?php esc_url ( header_image() ); ?>" alt="" />
    </div>
<?php }
endif;

add_filter('widget_text', 'do_shortcode');
add_action('widgets_init', 'x2_widgets_init');
function x2_widgets_init(){
    register_sidebars( 1,
        array(
            'name'          => __('Sidebar Right','x2'),
            'id'            => 'sidebar',
            'description'   => __('That\'s the right sidebar. It usually displays in your content area by default. You can define to show this sidebar via the x2 Theme Options or by using a Page Template.','x2'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => __('Sidebar Left','x2'),
            'id'            => 'leftsidebar',
            'description'   => __('That\'s the left sidebar that displays in your content area. You can define to show this sidebar via the x2 Theme Options or by using a Page Template.','x2'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
	register_sidebars( 1,
        array(
            'name'          => __('Home Top','x2'),
            'id'            => 'home_top',
            'description'   => __('The top and fullwidth widgetarea of your magazine homepage. Displayed in your content area as first, directly below the slideshow. (setup a magazine style homepage in Theme Options -> Home -> Homepage style)','x2'),
            'before_widget' => '<div id="%1$s" class="widget %2$s fullwidth">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle home">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => __('Home Left','x2'),
            'id'            => 'home_left',
            'description'   => __('The left sidebar widgetarea of your magazine homepage, in the content area. (setup a magazine style homepage in Theme Options -> Home -> Homepage style)','x2'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle home">',
            'after_title'   => '</h3>'
        )
    );
	register_sidebars( 1,
        array(
            'name'          => __('Home Center','x2'),
            'id'            => 'home_center',
            'description'   => __('The centered sidebar widgetarea of your magazine homepage, in the content area. (setup a magazine style homepage in Theme Options -> Home -> Homepage style)','x2'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle home">',
            'after_title'   => '</h3>'
        )
    );
	register_sidebars( 1,
        array(
            'name'          => __('Home Right','x2'),
            'id'            => 'home_right',
            'description'   => __('The right sidebar widgetarea of your magazine homepage, in the content area. (setup a magazine style homepage in Theme Options -> Home -> Homepage style)','x2'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle home">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => __('Header Full Width','x2'),
            'id'            => 'headerfullwidth',
            'description'   => __('The top and fullwidth widgetarea in your header area. (Always displayed in your header then, below the top menu, if set)','x2'),
            'before_widget' => '<div id="%1$s" class="widget %2$s fullwidth">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => __('Header Left','x2'),
            'id'            => 'headerleft',
            'description'   => __('A left aligned 1/3-width widgetarea in your header area. (Always displayed below the header fullwidth widgetarea)','x2'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => __('Header Center','x2'),
            'id'            => 'headercenter',
            'description'   => __('A centered 1/3-width widgetarea in your header area. (Always displayed below the header fullwidth widgetarea)','x2'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => __('Header Right','x2'),
            'id'            => 'headerright',
            'description'   => __('A right aligned 1/3-width widgetarea in your header area. (Always displayed below the header fullwidth widgetarea)','x2'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => __('Footer Full Width','x2'),
            'id'            => 'footerfullwidth',
            'description'   => __('A fullwidth widgetarea for your footer. Displayed in your footer area as first.','x2'),
            'before_widget' => '<div id="%1$s" class="widget %2$s fullwidth">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => __('Footer Left','x2'),
            'id'            => 'footerleft',
            'description'   => __('A left aligned 1/3-width widgetarea for your footer. (Always displayed below the footer fullwidth widgetarea)','x2'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => __('Footer Center','x2'),
            'id'            => 'footercenter',
            'description'   => __('A centered 1/3-width widgetarea for your footer. (Always displayed below the footer fullwidth widgetarea)','x2'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
    register_sidebars( 1,
        array(
            'name'          => __('Footer Right','x2'),
            'id'            => 'footerright',
            'description'   => __('A right aligned 1/3-width widgetarea for your footer. (Always displayed below the footer fullwidth widgetarea)','x2'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );
	register_sidebars( 1,
        array(
            'name'          => __('Flying Widget','x2'),
            'id'            => 'out_of_content',
            'description'   => __('A special widgetarea that always floats on the right side and follows you when scrolling. ;)','x2'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div><div class="clear"></div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>'
        )
    );

	// now registering some sidebars for buddypress sections, so checking if bp is active before..
	if ( defined( 'BP_VERSION' ) ) {

	    register_sidebars( 1,
	        array(
	            'name'          => __('Member Header Full Width','x2'),
	            'id'            => 'memberheader',
	            'description'   => __('A top and fullwidth widgetarea for your member profiles header. (Always displayed in BuddyPress member profiles INSIDE THE CONTENTAREA as first)','x2'),
	            'before_widget' => '<div id="%1$s" class="widget %2$s fullwidth">',
	            'after_widget'  => '</div><div class="clear"></div>',
	            'before_title'  => '<h3 class="widgettitle">',
	            'after_title'   => '</h3>'
	        )
	    );
	    register_sidebars( 1,
	        array(
	            'name'          => __('Member Header Left','x2'),
	            'id'            => 'memberheaderleft',
	            'description'   => __('A left aligned 1/3-width widgetarea for your member profiles header. (Always displayed in BuddyPress member profiles INSIDE THE CONTENTAREA below the Member Header Fullwidth Widgetarea)','x2'),
	            'before_widget' => '<div id="%1$s" class="widget %2$s">',
	            'after_widget'  => '</div><div class="clear"></div>',
	            'before_title'  => '<h3 class="widgettitle">',
	            'after_title'   => '</h3>'
	        )
	    );
	    register_sidebars( 1,
	        array(
	            'name'          => __('Member Header Center','x2'),
	            'id'            => 'memberheadercenter',
	            'description'   => __('A centered 1/3-width widgetarea for your member profiles header. (Always displayed in BuddyPress member profiles INSIDE THE CONTENTAREA below the Member Header Fullwidth Widgetarea)','x2'),
	            'before_widget' => '<div id="%1$s" class="widget %2$s">',
	            'after_widget'  => '</div><div class="clear"></div>',
	            'before_title'  => '<h3 class="widgettitle">',
	            'after_title'   => '</h3>'
	        )
	    );
	    register_sidebars( 1,
	        array(
	            'name'          => __('Member Header Right','x2'),
	            'id'            => 'memberheaderright',
	            'description'   => __('A right aligned 1/3-width widgetarea for your member profiles header. (Always displayed in BuddyPress member profiles INSIDE THE CONTENTAREA below the Member Header Fullwidth Widgetarea)','x2'),
	            'before_widget' => '<div id="%1$s" class="widget %2$s">',
	            'after_widget'  => '</div><div class="clear"></div>',
	            'before_title'  => '<h3 class="widgettitle">',
	            'after_title'   => '</h3>'
	        )
	    );
	    register_sidebars( 1,
	        array(
	            'name'          => __('Member Sidebar Left','x2'),
	            'id'            => 'membersidebarleft',
	            'description'   => __('A left aligned sidebar for your member profiles content. (Always displayed in BuddyPress member profiles)','x2'),
	            'before_widget' => '<div id="%1$s" class="widget %2$s">',
	            'after_widget'  => '</div><div class="clear"></div>',
	            'before_title'  => '<h3 class="widgettitle">',
	            'after_title'   => '</h3>'
	        )
	    );
	    register_sidebars( 1,
	        array(
	            'name'          => __('Member Sidebar Right','x2'),
	            'id'            => 'membersidebarright',
	            'description'   => __('A right aligned sidebar for your member profiles content. (Always displayed in BuddyPress member profiles)','x2'),
	            'before_widget' => '<div id="%1$s" class="widget %2$s">',
	            'after_widget'  => '</div><div class="clear"></div>',
	            'before_title'  => '<h3 class="widgettitle">',
	            'after_title'   => '</h3>'
	        )
	    );

		// checking if groups component is active, then also group widgetareas will be displayed.
		if ( bp_is_active( 'groups' ) ) {

		    register_sidebars( 1,
		        array(
		            'name'          => __('Group Header Full Width','x2'),
		            'id'            => 'groupheader',
		            'description'   => __('A top and fullwidth widgetarea for your group profiles header. (Always displayed in BuddyPress group profiles INSIDE THE CONTENTAREA as first)','x2'),
		            'before_widget' => '<div id="%1$s" class="widget %2$s fullwidth">',
		            'after_widget'  => '</div><div class="clear"></div>',
		            'before_title'  => '<h3 class="widgettitle">',
		            'after_title'   => '</h3>'
		        )
		    );
		    register_sidebars( 1,
		        array(
		            'name'          => __('Group Header Left','x2'),
		            'id'            => 'groupheaderleft',
		            'description'   => __('A left aligned 1/3-width widgetarea for your group profiles header. (Always displayed in BuddyPress group profiles INSIDE THE CONTENTAREA below the Group Header Fullwidth Widgetarea)','x2'),
		            'before_widget' => '<div id="%1$s" class="widget %2$s">',
		            'after_widget'  => '</div><div class="clear"></div>',
		            'before_title'  => '<h3 class="widgettitle">',
		            'after_title'   => '</h3>'
		        )
		    );
		    register_sidebars( 1,
		        array(
		            'name'          => __('Group Header Center','x2'),
		            'id'            => 'groupheadercenter',
		            'description'   => __('A centered 1/3-width widgetarea for your group profiles header. (Always displayed in BuddyPress group profiles INSIDE THE CONTENTAREA below the Group Header Fullwidth Widgetarea)','x2'),
		            'before_widget' => '<div id="%1$s" class="widget %2$s">',
		            'after_widget'  => '</div><div class="clear"></div>',
		            'before_title'  => '<h3 class="widgettitle">',
		            'after_title'   => '</h3>'
		        )
		    );
		    register_sidebars( 1,
		        array(
		            'name'          => __('Group Header Right','x2'),
		            'id'            => 'groupheaderright',
		            'description'   => __('A right aligned 1/3-width widgetarea for your group profiles header. (Always displayed in BuddyPress group profiles INSIDE THE CONTENTAREA below the Group Header Fullwidth Widgetarea)','x2'),
		            'before_widget' => '<div id="%1$s" class="widget %2$s">',
		            'after_widget'  => '</div><div class="clear"></div>',
		            'before_title'  => '<h3 class="widgettitle">',
		            'after_title'   => '</h3>'
		        )
		    );
		    register_sidebars( 1,
		        array(
		            'name'          => __('Group Sidebar Left','x2'),
		            'id'            => 'groupsidebarleft',
		            'description'   => __('A left aligned sidebar for your group profiles content. (Always displayed in BuddyPress group profiles)','x2'),
		            'before_widget' => '<div id="%1$s" class="widget %2$s">',
		            'after_widget'  => '</div><div class="clear"></div>',
		            'before_title'  => '<h3 class="widgettitle">',
		            'after_title'   => '</h3>'
		        )
		    );
		    register_sidebars( 1,
		        array(
		            'name'          => __('Group Sidebar Right','x2'),
		            'id'            => 'groupsidebarright',
		            'description'   => __('A right aligned sidebar for your group profiles content. (Always displayed in BuddyPress group profiles)','x2'),
		            'before_widget' => '<div id="%1$s" class="widget %2$s">',
		            'after_widget'  => '</div><div class="clear"></div>',
		            'before_title'  => '<h3 class="widgettitle">',
		            'after_title'   => '</h3>'
		        )
		    );

		} // end of buddypress groups component check
	} // end of buddypress active check
}

/** @var $cap autoconfig */
if($cap->buddydev_search == true && defined('BP_VERSION') && function_exists('bp_is_active')) {

    //* Add these code to your functions.php to allow Single Search page for all buddypress components*/
    // Remove Buddypress search drowpdown for selecting members etc
    add_filter("bp_search_form_type_select", "x2_remove_search_dropdown"  );
    function x2_remove_search_dropdown($select_html){
        return '';
    }

    remove_action( 'init', 'bp_core_action_search_site', 5 );//force buddypress to not process the search/redirect
    add_action( 'plugins_loaded', 'x2_bp_buddydev_search', 10 );// custom handler for the search

    function x2_bp_buddydev_search(){
    global $bp;
        if ( $bp->current_component == BP_SEARCH_SLUG )//if this is search page
            bp_core_load_template( apply_filters( 'bp_core_template_search_template', 'search-single' ) );//load the single searh template
    }
    add_action("advance-search","x2_show_search_results",1);//highest priority

    /* we just need to filter the query and change search_term=The search text*/
    function x2_show_search_results(){
        //filter the ajaxquerystring
         add_filter("bp_ajax_querystring","x2_global_search_qs",100,2);
    }

    //show the search results for member*/
    function x2_show_member_search(){ ?>
        <div class="memberss-search-result search-result">
            <h2 class="content-title"><?php _e("Members Results",'x2');?></h2>
            <?php locate_template( array( 'members/members-loop.php' ), true ) ;  ?>
            <?php global $members_template;
            if($members_template->total_member_count>1 && !empty($_REQUEST['search-terms'])):?>
                <a href="<?php echo bp_get_root_domain().'/'.BP_MEMBERS_SLUG.'/?s='.$_REQUEST['search-terms']?>" ><?php _e("View all matched Members",'x2');?></a>
            <?php endif; ?>
        </div>
        <?php
    }

    //Hook Member results to search page
    add_action("advance-search","x2_show_member_search",10); //the priority defines where in page this result will show up(the order of member search in other searchs)
    function x2_show_groups_search(){?>
        <div class="groups-search-result search-result">
            <h2 class="content-title"><?php _e("Group Search",'x2');?></h2>
            <?php locate_template( array('groups/groups-loop.php' ), true ) ;  ?>
            <?php if(!empty($_REQUEST['search-terms'])) :?>
                <a href="<?php echo bp_get_root_domain().'/'.BP_GROUPS_SLUG.'/?s='.$_REQUEST['search-terms']?>" ><?php _e("View All matched Groups",'x2');?></a>
            <?php endif;?>
        </div>
        <?php
    }

    // Hook Groups results to search page
    if ( bp_is_active( 'groups' ) ) {
        add_action( "advance-search", "x2_show_groups_search", 10 );
    }

    /**
     *
     * Show blog posts in search
     */
    function x2_show_site_blog_search(){ ?>
        <div class="blog-search-result search-result">

            <h2 class="content-title"><?php _e("Blog Search",'x2');?></h2>

            <?php locate_template( array( 'search-loop.php' ), true ) ;  ?>
            <?php if(!empty($_REQUEST['search-terms'])):?>
                <a href="<?php echo bp_get_root_domain().'/?s='.$_REQUEST['search-terms']?>" ><?php _e("View All matched Posts",'x2');?></a>
            <?php endif; ?>
        </div>
        <?php
    }

    // Hook Blog Post results to search page
    add_action( "advance-search", "x2_show_site_blog_search", 10 );

    //show forums search
    function x2_show_forums_search(){ ?>
        <div class="forums-search-result search-result">
            <h2 class="content-title"><?php _e("Forums Search",'x2');?></h2>
            <?php locate_template( array( 'forums/forums-loop.php' ), true ) ;  ?>
            <?php if(!empty($_REQUEST['search-terms'])):?>
                <a href="<?php echo bp_get_root_domain().'/'.BP_FORUMS_SLUG.'/?s='.$_REQUEST['search-terms']?>" ><?php _e("View All matched forum posts",'x2');?></a>
            <?php endif; ?>
        </div>
        <?php
    }

    //Hook Forums results to search page
    if ( bp_is_active( 'forums' ) && bp_is_active( 'groups' ) && ( function_exists( 'bp_forums_is_installed_correctly' ) ) ) {
        add_action( "advance-search", "x2_show_forums_search", 20 );
    }

    //show blogs search result
    function x2_show_blogs_search() {
        if ( ! is_multisite() ) {
            return;
        } ?>

        <div class="blogs-search-result search-result">
            <h2 class="content-title"><?php _e("Blogs Search",'x2');?></h2>
            <?php locate_template( array( 'blogs/blogs-loop.php' ), true ) ;  ?>
            <a href="<?php echo bp_get_root_domain().'/'.BP_BLOGS_SLUG.'/?s='.$_REQUEST['search-terms']?>" ><?php _e("View All matched Blogs",'x2');?></a>
        </div>
        <?php
    }

    // Hook blogs results to search page if blogs comonent is active
    if(bp_is_active( 'blogs' ) ) {
        add_action( "advance-search", "x2_show_blogs_search", 10 );
    }


    // modify the query string with the search term
    function x2_global_search_qs() {
        if ( empty( $_REQUEST[ 'search-terms' ] ) ) {
            return '';
        }

        return "search_terms=" . $_REQUEST[ 'search-terms' ];
    }

    function x2_is_advance_search() {
        global $bp;
        if ( $bp->current_component == BP_SEARCH_SLUG ) {
            return true;
        }

        return false;
    }

    remove_action( 'bp_init', 'bp_core_action_search_site', 7 );
}

// WooCommerce Compatibility
/* Remove Wrapper  */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/* Add our wrapper  */
add_action( 'woocommerce_before_main_content', create_function( '', 'echo "   <div id=\"content\"><div class=\"padder\">";' ), 10 );
add_action( 'woocommerce_after_main_content', create_function( '', 'echo "</div></div>";' ), 10 );


function remove_sidebar_left(){
    remove_action( 'sidebar_left', 'sidebar_left', 2 );
}

function remove_sidebar_right(){
    remove_action( 'sidebar_right', 'sidebar_right', 2 );
}

function x2_add_extensions_notice() {
    echo '<div class="message updated" style="border-color: #ddd; background-color: #f9f9f9; margin-bottom: 20px;"><p  style="font-size: 15px;">' . __( '&raquo; Get awesome', 'x2' ) . ' <a href="' . admin_url( 'themes.php?page=x2-extensions-options' ) . '" title="' . __( 'Extensions and supported plugins for x2', 'x2' ) . '">' . __( 'Extensions and Supported Plugins', 'x2' ) . '</a> for x2!</p></div>';
}
add_action( 'x2_after_help_buttons', 'x2_add_extensions_notice' );

/**
 * Add admin styles
 */

function x2_add_admin_styles(){
    wp_enqueue_style( 'admin_x2', get_template_directory_uri() . '/_inc/css/admin.css' );
}
add_action('admin_init', 'x2_add_admin_styles');

/**
 * Add info before tabs in Theme Options
 */

function x2_add_settins_info($tab_id){
    if('cap_slideshow' == $tab_id){
        $show = get_option('x2_dismiss_info_messages', FALSE);
        if(empty($show)){
            echo '<p class="slideshow_info">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    '.__("Slideshow settings of the single pages are stronger and will overwrite the global slideshow settings", "x2").'
                </p>';
        }
    }
}
add_action('x2_before_settings_tab', 'x2_add_settins_info');

/**
 * Add rotate function to jquery iu 1.9
 */
function x2_add_rotate_tabs(){
    wp_enqueue_script( 'x2_rotate', get_template_directory_uri() . '/_inc/js/jquery-ui-tabs-rotate.js', array('jquery','jquery-ui-tabs') );
}
add_action('wp_enqueue_scripts', 'x2_add_rotate_tabs');


// slideshow
function jquery_slider($atts, $content = null) {
    global $post, $x2_js;
    extract(shortcode_atts(array(
        'amount'                    => '4',
        'category_name'             => '0',
        'page_id'                   => '',
        'post_type'                 => 'post',
        'orderby'                   => 'DESC',
        'slider_nav'                => 'on',
        'caption'                   => 'on',
        'caption_height'            => '',
        'caption_top'               => '',
        'caption_width'             => '',
        'reflect'                   => '',
        'width'                     => '',
        'height'                    => '',
        'id'                        => '',
        'background'                => '',
        'slider_nav_color'          => '',
        'slider_nav_hover_color'    => '',
        'slider_nav_selected_color' => '',
        'slider_nav_font_color'     => '',
        'time_in_ms'                => '5000'
    ), $atts));

    if($page_id != '' && $post_type == 'post'){
        $post_type = 'page';
    }

    if($page_id != ''){
        $page_id = explode(',',$page_id);
    }

    // Get IDs of sticky posts
    $sticky_posts = get_option( 'sticky_posts' );
    $slideshow_sticky = '';

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
        'post_type'			=> $post_type,
        'category_name'		=> $category_name,
        'posts_per_page'	=> $amount,
        'amount'			=> $amount,
        'post__in'			=> $page_id
    );

    $args = $slideshow_sticky == 'on' ? $most_recent_sticky_post : $args;

    $tmp = chr(13);

    $tmp .= '<style type="text/css">'. chr(13);
    $tmp .= 'div.post img {'. chr(13);
    $tmp .= 'margin: 0 0 1px 0;'. chr(13);
    $tmp .= '}'. chr(13);

    if($slider_nav == 'off'){
        $tmp .= '#featured'.$id.' ul.ui-tabs-nav {'. chr(13);
        $tmp .= 'visibility: hidden;'. chr(13);
        $tmp .= '}'. chr(13);
        $tmp .= '#featured'.$id.' {'. chr(13);
        $tmp .= '    background: none;'. chr(13);
        $tmp .= 'padding:0;';
        $tmp .= '}'. chr(13);

    }

    if($width != ""){
        $tmp .= '#featured'.$id.' ul.ui-tabs-nav {'. chr(13);
        $tmp .= 'left:'.$width.'px;'. chr(13);
        $tmp .= '}'. chr(13);
    }

    if($caption_height != ""){
        $tmp .= '#featured'.$id.' .ui-tabs-panel .info{'. chr(13);
        $tmp .= 'height:'.$caption_height.'px;'. chr(13);
        $tmp .= '}'. chr(13);
    }

    if($caption_width != ""){
        $tmp .= '#featured'.$id.' .ui-tabs-panel .info{'. chr(13);
        $tmp .= 'width:'.$caption_width.'px;'. chr(13);
        $tmp .= '}'. chr(13);
    }

    if($caption_top != ""){
        $tmp .= '#featured'.$id.' .ui-tabs-panel .info{'. chr(13);
        $tmp .= 'top:'.$caption_top.'px;'. chr(13);
        $tmp .= '}'. chr(13);
    }

    if($background != ''){
        $tmp .= '#featured'.$id.'{'. chr(13);
        $tmp .= 'background: #'.$background.';'. chr(13);
        $tmp .= '}'. chr(13);
    }

    if($width != '' || $height != '' || $slider_nav == 'off'){
        $tmp .= '#featured'.$id.'{'. chr(13);
        $tmp .= 'width:'.$width.'px;'. chr(13);
        $tmp .= 'height:'.$height.'px;'. chr(13);
        $tmp .= '}'. chr(13);
        $tmp .= '#featured'.$id.' .ui-tabs-panel{'. chr(13);
        $tmp .= 'width:'.$width.'px; height:'.$height.'px;'. chr(13);
        $tmp .= 'background:none; position:relative;'. chr(13);
        $tmp .= '}'. chr(13);
    }

    if($slider_nav_color != '') {
        $tmp .= '#featured'.$id.' li.ui-tabs-nav-item a{'. chr(13);
        $tmp .= '    background: none repeat scroll 0 0 #'.$slider_nav_color.';'. chr(13);
        $tmp .= '}'. chr(13);
    }
    if($slider_nav_hover_color != '') {
        $tmp .= '#featured'.$id.' li.ui-tabs-nav-item a:hover{'. chr(13);
        $tmp .= '    background: none repeat scroll 0 0 #'.$slider_nav_hover_color.';'. chr(13);
        $tmp .= '}'. chr(13);
    }

    if($slider_nav_selected_color != '') {
        $tmp .= '#featured'.$id.' .ui-tabs-selected {'. chr(13);
        $tmp .= 'padding-left:0;'. chr(13);
        $tmp .= '}'. chr(13);
        $tmp .= '#featured'.$id.' .ui-tabs-selected a{'. chr(13);
        $tmp .= '    background: none repeat scroll 0 0 #'.$slider_nav_selected_color.' !important;'. chr(13);
        $tmp .= 'padding-left:0;'. chr(13);
        $tmp .= '}'. chr(13);
    }

    if($slider_nav_font_color != ''){
        $tmp .= '#featured'.$id.' ul.ui-tabs-nav li span{'. chr(13);
        $tmp .= 'color:#'.$slider_nav_font_color. chr(13);
        $tmp .= '}'. chr(13);
    }
    $tmp .= '</style>'. chr(13);


    remove_all_filters('posts_orderby');

    query_posts($args);

    if (have_posts()){
        $shortcodeclass = '';
        if ( $id == "top" )
            $shortcodeclass = "x2_slider_shortcode";

        $tmp .='<div id="x2_slider'.$id.'" class="x2_slider '.$shortcodeclass.'">'. chr(13);
        $tmp .='<div id="featured'.$id.'" class="featured">'. chr(13);

        $i = 1;
        while (have_posts()) : the_post();

            $url = get_permalink();
            $theme_fields = get_post_custom_values('my_url');
            if(isset($theme_fields[0])){
                 $url = $theme_fields[0];
            }

            $tmp .='<div id="fragment-'.$id.'-'.$i.'" class="ui-tabs-panel">'. chr(13);

            if($width != '' || $height != ''){
                if (get_the_post_thumbnail( $post->ID, array($width + 10,$height) ) == '') {
                    $ftrdimg = '<img src="'.get_template_directory_uri().'/images/slideshow/noftrdimg-1006x250.jpg" />';
                } else {
                    $ftrdimg = get_the_post_thumbnail( $post->ID, array($width + 10,$height),"class={$reflect}" );
                }
            } else {
                if (get_the_post_thumbnail( $post->ID, array(756,250),""  ) == '') {
                    $ftrdimg = '<img src="'.get_template_directory_uri().'/images/slideshow/noftrdimg.jpg" />';
                } else {
                    $ftrdimg = get_the_post_thumbnail( $post->ID, array(756,250),"class={$reflect}"  );
                }
            }

            $tmp .='    <a class="reflect" href="'.$url.'">'.$ftrdimg.'</a>'. chr(13);

            if($caption == 'on'){
                add_filter('excerpt_length', 'x2_excerpt_length');
                $tmp .=' <div class="info" >'. chr(13);
                $tmp .='    <h2><a href="'.$url.'" >'.get_the_title().'</a></h2>'. chr(13);
                $tmp .='    <p>'.get_the_excerpt().'</p>'. chr(13);
                $tmp .=' </div>'. chr(13);
            }
            $tmp .='</div>'. chr(13);
            $i++;
        endwhile;

        $tmp .='<ul class="ui-tabs-nav">'. chr(13);
        $i = 1;
        while (have_posts()) : the_post();
            if (get_the_post_thumbnail( $post->ID, 'slider-thumbnail' ) == '') { $ftrdimgs = '<img src="'.get_template_directory_uri().'/images/slideshow/noftrdimg-80x50.jpg" />'; } else { $ftrdimgs = get_the_post_thumbnail( $post->ID, 'slider-thumbnail' ); }
            $tmp .='<li class="ui-tabs-nav-item ui-tabs-selected" id="nav-fragment-'.$id.'-'.$i.'"><a href="#fragment-'.$id.'-'.$i.'">'.$ftrdimgs.'<span>'.get_the_title().'</span></a></li>'. chr(13);
            $i++;
        endwhile;
        $tmp .='</ul>'. chr(13);

        $tmp .= '</div></div>'. chr(13);
    }
    else{
        $tmp .='<div id="x2_slider_prev" class="x2_slider" style="background: #ededed;">'. chr(13);
        $tmp .='<div id="featured_prev" class="featured" style="background: #ededed;">'. chr(13);
        $tmp .='<h2 class="center" style="margin-top:50px; margin-left: 20px;">'.__( 'Empty Slideshow', 'x2' ).'</h2>'. chr(13);
        $tmp .='<p class="center" style="margin-top:20px; margin-left: 20px;">'.__( 'You have no posts selected for your slideshow! <br>Check your theme settings for the global slideshow or the page settings for page slideshows... <br>and write a post! Check the <a href="https://themekraft.zendesk.com/hc/en-us/articles/200138151-Slideshow" target="_blank">FAQ</a> for more.', 'x2' ).'</p>'. chr(13);
        $tmp .='</div></div>'. chr(13);
    }
    wp_reset_query();
    wp_reset_postdata();

    // js vars
    $x2_js['slideshow'][] = array(
        'id'         => $id,
        'time_in_ms' => $time_in_ms
    );

    return $tmp . chr(13);
}

// x2 carousel loop
function x2_carousel_loop($atts,$content = null) {
    global $post, $the_lp_query, $tmp;

    $tmp = '';
    $this_post = $post;

    extract(shortcode_atts(array(
        'featured_id'                   => '',
        'amount'                        => '12',
        'posts_per_page'                => '4',
        'category_name'                 => '0',
        'page_id'                       => '',
        'post_type'                     => 'post',
        'orderby'                       => '',
        'order'                         => '',
        'featured_posts_show_sticky'    => 'off',
        'img_position'                  => 'carousel caption',
        'height'                        => 'auto',
        'show_pagination'               => 'show',
        'pagination_ajax_effect'        => 'fadeOut_fadeIn',
        'featured_posts_image_width'    => '222',
        'featured_posts_image_height'   => '160',
        'autoplay'                      => 'off',
        'slideshow_time'                => 5000,
    ), $atts));

    if ( $featured_id == '')                                  $featured_id = substr(md5(rand()), 0, 10);
    if ( $category_name == 'all-categories')                  $category_name = '0';
    if ( $page_id != '')                                      $page_id = explode(',',$page_id);
    if ( $height != 'auto' )                                  $height = $height.'px';
    if ( $img_position == 'posts-img-between-title-content' ) $margintop = 'margin-top: 10px;';

    switch ($img_position){
        case 'image left':
            $img_position = 'posts-img-left-content-right';
        break;
        case 'image right':
            $img_position = 'posts-img-right-content-left';
        break;
        case 'image top':
            $img_position = 'posts-img-over-content';
        break;
        case 'image bottom':
            $img_position = 'posts-img-under-content';
        break;
        case 'image only':
            $img_position = 'boxgrid';
        break;
        case 'widget style - with description':
            $img_position = 'posts-img-left-content-right widget';
        break;
        case 'widget style - no description':
            $img_position = 'posts-img-left-content-right widget no-desc';
        break;
    }

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    // Get IDs of sticky posts
    $sticky_posts = get_option( 'sticky_posts' );

    // Get the sticky posts query
    $most_recent_sticky_post = array(
        'post__in'              => $sticky_posts,
        'ignore_sticky_posts'   => 1,
        'category_name'         => $category_name,
        'orderby'               => $orderby,
        'posts_per_page'        => $posts_per_page,
        'amount'                => $amount,
        'orderby'               => $orderby,
        'order'                 => $order,
        'post_type'             => $post_type,
        'paged'                 => get_query_var('paged'),

    );

    // Get the normal query
    $list_post_query_args = array(
        'amount'                => $amount,
        'posts_per_page'        => $posts_per_page,
        'orderby'               => $orderby,
        'order'                 => $order,
        'post_type'             => $post_type,
        'post__in'              => $page_id,
        'category_name'         => $category_name,
        'paged'                 => get_query_var('paged'),
        'ignore_sticky_posts'   => 1,
    );

    $list_post_query_args = $featured_posts_show_sticky == 'on' ? $most_recent_sticky_post : $list_post_query_args;

    remove_all_filters('posts_orderby');

    $list_post_query[$featured_id] = new WP_Query( $list_post_query_args );

    $the_lp_query = $list_post_query[$featured_id];

    if ($list_post_query[$featured_id]->have_posts()) :
         ob_start(); ?>

         <div id="Carousel<?php echo $featured_id ?>" class="carousel slide">
              <!-- Carousel items -->
              <div class="carousel-inner">
                <?php $i=1; while ($list_post_query[$featured_id]->have_posts()) : $list_post_query[$featured_id]->the_post();


                $no_featured_image = false;
                $url = get_permalink();
                $slider_img = get_the_post_thumbnail( $post->ID, 'slider-top-nivo' );
                $title = !empty($captions) && $captions == 'off' ? '' : get_the_title()   ;


                if ( $slider_img == '')
                    $no_featured_image = true;

                $image_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'slider-top-nivo');

               if($no_featured_image == false && $image_src[1] == 1000) {
                    $ftrdimg = get_the_post_thumbnail_src(get_the_post_thumbnail( $post->ID, 'slider-top-nivo'));
               } elseif($no_featured_image == false) {
                    $ftrdimg = get_template_directory_uri().'/images/slideshow/featured-img-too-small.jpg';
                    $url     =  get_edit_post_link($post->ID);
               } elseif($no_featured_image == true) {
                    $ftrdimg = get_template_directory_uri().'/images/slideshow/no-featured-img.jpg';
                    $url     =  get_edit_post_link($post->ID);
               }


                ?><div class="item <?php if($i == 1) echo 'active'; ?>"><?php

               switch ($img_position) {
                case 'carousel':?>
                    <img src="<?php echo $ftrdimg; ?>" alt="">
                    <?php
                break;
                case 'carousel caption':?>
                    <img src="<?php echo $ftrdimg; ?>" alt="">
                    <div class="carousel-caption">
                      <h4><?php the_title() ?></h4>
                      <p><?php the_excerpt(); ?></p>
                    </div>

                    <?php
                break;
                case 'caption':?>
                      <h4><?php the_title() ?></h4>
                      <p><?php the_excerpt(); ?></p>


                    <?php
                break;
                case 'boxgrid':

                    $thumb   = get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
                    $pattern = "/(?<=src=['|\"])[^'|\"]*?(?=['|\"])/i";
                    preg_match($pattern, $thumb, $thePath);
                    if(!isset($thePath[0])){
                        $thePath[0] = get_template_directory_uri().'/images/slideshow/noftrdimg-222x160.jpg';
                    }
                    echo '<div class="boxgrid captionfull" style="background: transparent url('.$thePath[0].') repeat scroll 0 0; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; " title="'. get_the_title().'">';
                    echo '<a href="'. get_permalink().'" title="'. get_the_title().'"><img src="'.$thePath[0].'" /></a>';
                    echo '<div class="cover boxcaption">';
                    echo '<h3><a href="'. get_permalink().'" title="'. get_the_title().'">'. get_the_title().'</a></h3>';
                    echo '<p><a href="'. get_permalink().'" title="'. get_the_title().'">'.substr(get_the_excerpt(), 0, 100).'...</a></p>';
                    echo '</div>';
                    echo '</div>';

                break;
                default:
                  echo '<a class="clickable" href="'. get_permalink() .'" title="'. get_the_title() .'">';
                    echo '<div class="listposts '.$img_position.'">';
                    if($img_position == "posts-img-left-content-right widget" || $img_position == "posts-img-left-content-right widget no-desc") {
                        echo '<span class="link">'.get_the_post_thumbnail($post->ID, 'slider-thumbnail').'</span>';
                    } elseif ( $img_position != 'posts-img-under-content' ) {
                        echo '<span class="link">'.get_the_post_thumbnail().'</span>';
                    }
                    echo '<h3><span class="link">'.get_the_title().'</span></h3>';
                    if($height != 'auto') $height = str_replace('px','',$height).'px';
                    if($img_position == 'posts-img-under-content' || $img_position == 'posts-img-over-content') $height = '48px; overflow: hidden';
                    if($img_position == 'posts-img-left-content-right widget') $height = '95px; overflow: hidden';
                    if($img_position != "posts-img-left-content-right widget no-desc") echo '<p style="height:'.$height.';">'. get_the_excerpt().'</p><span class="link more">'.__('read more','x2').'</span>';
                    if($img_position == 'posts-img-under-content') echo '<span class="link">'.get_the_post_thumbnail().'</span>';
                    echo '</div></a>';
                    if($img_position == 'posts-img-left-content-right' || $img_position == 'posts-img-right-content-left' || $img_position == 'posts-img-left-content-right widget' || $img_position == 'posts-img-left-content-right widget no-desc' ) $tmp .= '<div class="clear"></div>';
                break;
              }?>
              </div>
        <?php $i++; endwhile; ?>
        </div>
          <!-- Carousel nav -->
          <a class="carousel-control left" href="#Carousel<?php echo $featured_id ?>" data-slide="prev" >&lsaquo;</a>
          <a class="carousel-control right" href="#Carousel<?php echo $featured_id ?>" data-slide="next" >&rsaquo;</a>
        </div>

    <?php endif;

    $tmp .= ob_get_contents();
    ob_clean();

    $tmp .='<div class="clear">'.$autoplay.'</div>';

    if($autoplay == 'off'){
        $tmp .="<script>
            jQuery(document).ready(function() {
              jQuery('#Carousel".$featured_id."').carousel({
                interval: false
              })
            });
        </script>";
    }else{
        $tmp .="<script>
            jQuery(document).ready(function() {
              jQuery('#Carousel".$featured_id."').carousel({
                interval: ".$slideshow_time."
              })
            });
        </script>";
    }

    wp_reset_postdata();
    $post = $this_post;

    return $tmp;
}

/**
 * Remove all loops
 */
function remove_all_loops(){
    $return = delete_option('x2_loop_designer_options');
    die($return);
}

add_action('wp_ajax_remove_all_loops', 'remove_all_loops');

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 */
function x2_wp_title( $title, $sep ) {
    global $page, $paged;

    if ( is_feed() )
        return $title;

    // Add the blog name
    $title .= get_bloginfo( 'name' );

    // Add the blog description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
        $title .= " $sep $site_description";

    // Add a page number if necessary:
    if ( $paged >= 2 || $page >= 2 )
        $title .= " $sep " . sprintf( __( 'Page %s', 'x2' ), max( $paged, $page ) );

    return $title;
}
add_filter( 'wp_title', 'x2_wp_title', 10, 2 );

/* Theme generator output */
if ( ! function_exists( 'innerrim_before_header' ) ) :
    function innerrim_before_header(){
        global $Theme_Generator;
        /** @var $Theme_Generator x2_Theme_Generator */
        $Theme_Generator->innerrim_before_header();
    }
endif;

if ( ! function_exists( 'div_inner_start_inside_header' ) ) :
    function div_inner_start_inside_header(){
        global $Theme_Generator;
        /** @var $Theme_Generator x2_Theme_Generator */
        $Theme_Generator->div_inner_start_inside_header();
    }
endif;

if ( ! function_exists( 'div_inner_end_inside_header' ) ) :
    function div_inner_end_inside_header(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->div_inner_end_inside_header();
    }
endif;

if ( ! function_exists( 'innerrim_after_header' ) ) :
    function innerrim_after_header(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->innerrim_after_header();
    }
endif;

if ( ! function_exists( 'menu_enable_search' ) ) :
    function menu_enable_search(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->menu_enable_search();
    }
endif;

if ( ! function_exists( 'header_logo' ) ) :
    function header_logo(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->header_logo();
    }
endif;

if ( ! function_exists( 'x2_slideshow' ) ) :
    function x2_slideshow(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->x2_slideshow();
    }
endif;

if ( ! function_exists( 'favicon' ) ) :
    function favicon(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->favicon();
    }
endif;

if ( ! function_exists( 'innerrim_before_footer' ) ) :
    function innerrim_before_footer(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->innerrim_before_footer();
    }
endif;

if ( ! function_exists( 'div_inner_start_inside_footer' ) ) :
    function div_inner_start_inside_footer(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->div_inner_start_inside_footer();
    }
endif;

if ( ! function_exists( 'div_inner_end_inside_footer' ) ) :
    function div_inner_end_inside_footer(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->div_inner_end_inside_footer();
    }
endif;

if ( ! function_exists( 'innerrim_after_footer' ) ) :
    function innerrim_after_footer(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->innerrim_after_footer();
    }
endif;

if ( ! function_exists( 'footer_content' ) ) :
    function footer_content(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->footer_content();
    }
endif;

if ( ! function_exists( 'sidebar_left' ) ) :
    function sidebar_left(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->sidebar_left();
    }
endif;

if ( ! function_exists( 'sidebar_right' ) ) :
    function sidebar_right(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->sidebar_right();
    }
endif;

if ( ! function_exists( 'home_featured_posts' ) ) :
    function home_featured_posts(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        //   $Theme_Generator->home_featured_posts();
    }
endif;

if ( ! function_exists( 'add_bubble' ) ) :
    function add_bubble($classes){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->add_bubble($classes);
    }
endif;

if ( ! function_exists( 'excerpt_on' ) ) :
    function excerpt_on(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->excerpt_on();
    }
endif;

if ( ! function_exists( 'before_group_home_content' ) ) :
    function before_group_home_content(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->before_group_home_content();
    }
endif;

if ( ! function_exists( 'before_member_home_content' ) ) :
    function before_member_home_content(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->before_member_home_content();
    }
endif;

if ( ! function_exists( 'custom_login' ) ) :
    function custom_login(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->custom_login();
    }
endif;

if ( ! function_exists( 'list_posts_under_page' ) ) :
    function list_posts_under_page(){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->list_posts_under_page();
    }
endif;

if ( ! function_exists( 'x2_nivo_slider' ) ) :
    function x2_nivo_slider($atts, $content = null){
        /** @var $Theme_Generator x2_Theme_Generator */
        global $Theme_Generator;
        $Theme_Generator->x2_nivo_slider($atts,$content = null);
    }
endif;

// Fix BuddyPress 2.0 bug regarding Profile -> Settings -> Profile link and screen
function x2_fix_settings_screen_xprofile($path){
    return '/members/single/settings/profile';
}
add_filter('bp_settings_screen_xprofile', 'x2_fix_settings_screen_xprofile');

// BuddyPress Media plugin fix
function x2_fix_rtmedia_main_template_include($template, $new_rt_template) {
    global $wp_query;

    locate_template( array( 'members/single/media.php'   ), true );
}
add_filter('rtmedia_main_template_include', 'x2_fix_rtmedia_main_template_include', 10, 2);

// Woocommerce sidebar fix
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**************** NextGen gallery image uploader fix *********************/
remove_action( 'wp_footer', 'bp_core_print_generation_time');

function x2_kill_anonymous_hooks(){
    x2_remove_anonymous_object_filter(
        'wp_footer',
        'C_Photocrati_Resource_Manager',
        'print_marker'
    );
}
add_action('wp_footer', 'x2_kill_anonymous_hooks', -2);

/**
 * Remove an anonymous object filter.
 *
 * @param  string $tag    Hook name.
 * @param  string $class  Class name
 * @param  string $method Method name
 * @return void
 */
function x2_remove_anonymous_object_filter( $tag, $class, $method ) {
    $filters = $GLOBALS['wp_filter'][ $tag ];

    if ( empty ( $filters ) ) {
        return;
    }

    foreach ( $filters as $priority => $filter ) {
        foreach ( $filter as $identifier => $function ) {
            if ( is_array( $function)
                and is_a( $function['function'][0], $class )
                and $method === $function['function'][1]
            ) {
                remove_filter(
                    $tag,
                    array ( $function['function'][0], $method ),
                    $priority
                );
                 remove_action(
                    $tag,
                    array ( $function['function'][0], $method ),
                    $priority
                );
            }
        }
    }
}

define('NGG_DISABLE_RESOURCE_MANAGER', true);
/**************** END NextGen gallery image uploader fix *********************/