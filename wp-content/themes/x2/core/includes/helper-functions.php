<?php
/*

Simple class to let themes add dependencies on plugins in ways they might find useful

Example usage:

    $test = new Theme_Plugin_Dependency( 'simple-facebook-connect', 'http://ottopress.com/wordpress-plugins/simple-facebook-connect/' );
    if ( $test->check_active() )
        echo 'SFC is installed and activated!';
    else if ( $test->check() )
        echo 'SFC is installed, but not activated. <a href="'.$test->activate_link().'">Click here to activate the plugin.</a>';
    else if ( $install_link = $test->install_link() )
        echo 'SFC is not installed. <a href="'.$install_link.'">Click here to install the plugin.</a>';
    else
        echo 'SFC is not installed and could not be found in the Plugin Directory. Please install this plugin manually.';

*/
if (!class_exists('Theme_Plugin_Dependency')) {
    class Theme_Plugin_Dependency {
        // input information from the theme
        var $slug;
        var $uri;

        // installed plugins and uris of them
        private $plugins; // holds the list of plugins and their info
        private $uris; // holds just the URIs for quick and easy searching

        // both slug and PluginURI are required for checking things
        function __construct( $slug, $uri ) {
            $this->slug = $slug;
            $this->uri = $uri;
            if ( empty( $this->plugins ) )
                $this->plugins = get_plugins();
            if ( empty( $this->uris ) )
                $this->uris = wp_list_pluck($this->plugins, 'PluginURI');
			// echo '<pre>';
			// print_r($this->uris);
			// echo '</pre>';

        }

        // return true if installed, false if not
        function check() {
            return in_array($this->uri, $this->uris);
        }

        // return true if installed and activated, false if not
        function check_active() {
            $plugin_file = $this->get_plugin_file();
            if ($plugin_file) return is_plugin_active($plugin_file);
            return false;
        }

        // gives a link to activate the plugin
        function activate_link() {
            $plugin_file = $this->get_plugin_file();
            if ($plugin_file) return wp_nonce_url(self_admin_url('plugins.php?action=activate&plugin='.$plugin_file), 'activate-plugin_'.$plugin_file);
            return false;
        }

        // return a nonced installation link for the plugin. checks wordpress.org to make sure it's there first.
        function install_link() {
            include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

            $info = plugins_api('plugin_information', array('slug' => $this->slug ));

            if ( is_wp_error( $info ) )
                return false; // plugin not available from wordpress.org

            return wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . $this->slug), 'install-plugin_' . $this->slug);
        }

        // return array key of plugin if installed, false if not, private because this isn't needed for themes, generally
        private function get_plugin_file() {
            return array_search($this->uri, $this->uris);
        }
    }
}
/**
* options "Add scripts to head" and "add scripts to footer"
*
* @package X2
* @since 1.5
*/

// add to head function - hooks the stuff to bp_head
function x2_cap_add_to_head() {
	global $cap;
	echo stripslashes($cap->add_to_head);
}
add_action('bp_head', 'x2_cap_add_to_head', 20);

// add to footer function - hooks the stuff to wp_footer
function x2_cap_add_to_footer() {
	global $cap;
	echo stripslashes($cap->add_to_footer);
}
add_action('wp_footer', 'x2_cap_add_to_footer', 20);

function back_to_top(){
    global $cap;
    //if($cap->menu_waypoints == true) { ?>
	<footer>
        <nav>
            <ul>
                <li><a class="top" href="#" title="<?php _e('Back to top', 'x2'); ?>"><?php _e('Top', 'x2'); ?></a></li>
            </ul>
        </nav>
    </footer>
    <?php //}
}


function waypoints_js(){
	global $cap;

	if($cap->menu_waypoints == true) { ?>
        <script type="text/javascript">
        jQuery(document).ready(function() {

            jQuery('.top').addClass('hidden');

            jQuery.waypoints.settings.scrollThrottle = 300;
            jQuery('#outerrim').waypoint(function(event, direction) {


                jQuery('.top').toggleClass('hidden', direction === "up");
            }, {
                offset: '-100%'
            }).find('#access').waypoint(function(event, direction) {
            	 jQuery('#access').toggleClass('sticky', direction === "down");

                	if(direction == 'up'){
        	        	jQuery('#nav-logo').animate({ opacity: 1},0, function() {
							jQuery(this).hide('200');
						});
						jQuery(".wrapcheck").removeClass("inner");

                	}
                	if(direction == 'down'){
        	        	jQuery('#nav-logo').animate({ opacity: 1},0, function() {
							jQuery(this).show('200');
						});
						jQuery(".wrapcheck").addClass("inner");
                	}

                event.stopPropagation();
            });

        });
        </script>
	<?php
	 } else { ?>
	 	<script type="text/javascript">
        jQuery(document).ready(function() {

            jQuery('.top').addClass('hidden');
            jQuery.waypoints.settings.scrollThrottle = 300;
            jQuery('#outerrim').waypoint(function(event, direction) {
                jQuery('.top').toggleClass('hidden', direction === "up");
            }, {
                offset: '-100%'
            })

        });
        </script>
        <?php
	 }
}

// this function adds the needed class "icon-white" if color scheme is dark or black..
function white_icon_class() {
	if ( x2_get_color_scheme() == 'dark' || x2_get_color_scheme() == 'black' ) {
		echo 'icon-white';
	}
}

function add_home_to_nav(){ ?>
	    <div id="nav-logo">
	         <ul>
				<li id="nav-home"<?php if ( is_home() ) : ?> class="page_item current-menu-item"<?php endif; ?>>
					<a href="<?php echo home_url('/') ?>" title="<?php _e( 'Home', 'x2' ) ?>"><i class="icon-home <?php white_icon_class() ?>"></i></a>
				</li>
			</ul>
	    </div>
<?php }

// opens the inner wrap in the top menu - if needed
function open_inner_wrap_in_menu_top() {
	global $cap;
	if( $cap->header_width == "full-width" || $cap->menu_top_stay_on_top == true )
		echo "<div class=\"inner\">";
}

// closes the inner wrap in the top menu - if needed
function close_inner_wrap_in_menu_top() {
	global $cap;
	if( $cap->header_width == "full-width" || $cap->menu_top_stay_on_top == true )
		echo "</div>";
}

// the flying widget!
function out_of_site_widget(){
    global $cap;

    if($cap->out_of_content_widget == false) { ?>

        <script>
            var name = "#out_of_site_widget";
            var menuYloc = null;
            jQuery(document).ready(function(){
                    menuYloc = parseInt(jQuery(name).css("top").substring(0,jQuery(name).css("top").indexOf("px")))
                    jQuery(window).scroll(function () {
                        offset = menuYloc+jQuery(document).scrollTop()+"px";
                            jQuery(name).animate({top:offset},{duration:1000,queue:false});
                        });
                });
        </script>

    <?php } ?>
    <div id="out_of_site_widget" class="visible-desktop">
        <?php dynamic_sidebar( 'out_of_content' )?>
    </div>
<?php }



// body badge function, if the option is set
function body_badge(){
    global $cap;

    if ( $cap->body_badge_show != "hide" ) {

        // add a link around if a url is set
        if ( $cap->body_badge_link != "" ) {
            ?><a class="body_badge_link" href="<?php echo $cap->body_badge_link; ?>"><?php
        }

        // only the badge body will be added anyway
        ?><div class="badge_body"><?php

        // add the text only if something != "just my image" is set
        if ( $cap->body_badge_show != "just my image" ) {
            ?><div class="badge_text"><?php echo $cap->body_badge_text; ?></div><?php
        }

        // close the badge body anyway
        ?></div><?php

        // close the link around if a url was set
        if ( $cap->body_badge_link != "" ) {
        ?></a><?php
        }
    }
}

function get_the_post_thumbnail_src($img)
{
  return (preg_match('~\bsrc="([^"]++)"~', $img, $matches)) ? $matches[1] : '';
}

/**
 * check if it's a child theme or parent theme and return the correct path
 *
 * @package x2
 * @since 1.0
 */
function x2_require_path($path){
	x2::require_path($path);
}

/**
 * get the right img for the slideshow shadow
 *
 * @package x2
 * @since 1.0
 */
function x2_slider_shadow() {
	global $cap;
	if ($cap->slideshow_shadow == "shadow") {
		return "slider-shadow.png";
	} else {
		return "slider-shadow-sharp.png";
	}
}

/**
 *  define new excerpt length
 *
 * @package x2
 * @since 1.0
 */
function x2_excerpt_length() {
	global $cap;
	$excerpt_length = 30;
	if($cap->excerpt_length){
		$excerpt_length = $cap->excerpt_length;
	}
	return $excerpt_length;
}

/**
 * change the profile tab order
 *
 * @package x2
 * @since 1.0
 */
add_action( 'bp_init', 'x2_change_profile_tab_order' );
function x2_change_profile_tab_order() {
	global $bp, $cap;

	if($cap->bp_profiles_nav_order == ''){
		$cap->bp_default_navigation = true;
		return;
	}
	$order = $cap->bp_profiles_nav_order;
	$order = str_replace(' ','',trim($order));
	$order = explode(",", $order);
	$i = 1;

	$bp->bp_nav = x2_filter_custom_menu($bp->bp_nav, $order);

	foreach($order as $item) {
		// check this such component actually exists
		if(!bp_is_active($item)){
			continue;
		}
		$bp->bp_nav[$item]['position'] = $i;
		$i ++;
	}
}

/**
 * change the groups tab order
 *
 * @package x2
 * @since 1.0
 */
add_action('bp_init', 'x2_change_groups_tab_order');
function x2_change_groups_tab_order() {
	global $bp, $cap;


	// In BP 1.3, bp_options_nav for groups is keyed by group slug instead of by 'groups', to
	// differentiate it from the top-level groups directories and the groups subtab of member
	// profiles
	$group_slug = isset( $bp->groups->current_group->slug ) ? $bp->groups->current_group->slug : false;


	if($cap->bp_groups_nav_order == ''){
		$cap->bp_default_navigation = true;
		return;
	}


	$order = $cap->bp_groups_nav_order;
	$order = str_replace(' ','',$order);
	$order = explode(",", $order);
	$i = 1;

	$bp->bp_options_nav[$group_slug] = x2_filter_custom_menu($bp->bp_options_nav[$group_slug], $order);
	if(!empty($bp->bp_options_nav[$group_slug])){
		foreach($order as $item) {
			if(!array_key_exists($item, $bp->bp_options_nav[$group_slug])){
				continue;
			}
			$bp->bp_options_nav[$group_slug][$item]['position'] = $i;
			$i ++;
		}
	}
}
/**
 * Remove menu items wihich not included to custom list
 * @param array $menu default menu
 * @param array $custom_items list of items
 * @return array new menu items
 */
function x2_filter_custom_menu($menu, $custom_items){
	if(is_array($custom_items) && is_array($menu)){
		return array_intersect_key($menu, array_flip($custom_items));
	}
	return $menu;
}


/**
 * This function here defines the defaults for the main theme colors - if no other specific color is set ;)
 * It's used only one time - in the beginning of style.php.
 *
 * @package x2
 * @since 0.1
 */

function x2_switch_css(){
	global $cap;

		if(isset( $_GET['show_style']))
            $cap->style_css = $_GET['show_style'];

		switch ($cap->style_css){
	        case 'dark':
				if( $cap->bg_body_color == '' ) $cap->bg_body_color  =  '393939';
				if( $cap->bg_container_color == '' ) $cap->bg_container_color  =  '393939';
				if( $cap->bg_container_alt_color == '' ) $cap->bg_container_alt_color  =  '232323';
				if( $cap->bg_details_color == '' ) $cap->bg_details_color  =  '282828';
				if( $cap->bg_details_hover_color == '' ) $cap->bg_details_hover_color  =  '333333';
				if( $cap->font_color == '' ) $cap->font_color  =  'aaaaaa';
				if( $cap->font_alt_color == '' ) $cap->font_alt_color  =  '777777';
				if( $cap->link_color == '' ) $cap->link_color  =  'b7c366';
		    break;
	        case 'white':
				if( $cap->bg_body_color == '' ) $cap->bg_body_color  =  'ffffff';
				if( $cap->bg_container_color == '' ) $cap->bg_container_color  =  'ffffff';
				if( $cap->bg_container_alt_color == '' ) $cap->bg_container_alt_color  =  'e3e3e3';
				if( $cap->bg_details_color == '' ) $cap->bg_details_color  =  'f1f1f1';
				if( $cap->bg_details_hover_color == '' ) $cap->bg_details_hover_color  =  'f9f9f9';
				if( $cap->font_color == '' ) $cap->font_color  =  '777777';
				if( $cap->font_alt_color == '' ) $cap->font_alt_color  =  'aaaaaa';
				if( $cap->link_color == '' ) $cap->link_color  =  '6ba090';
			break;
	        case 'black':
				if( $cap->bg_body_color == '' ) $cap->bg_body_color  =  '040404';
				if( $cap->bg_container_color == '' ) $cap->bg_container_color  =  '040404';
				if( $cap->bg_container_alt_color == '' ) $cap->bg_container_alt_color  =  '222222';
				if( $cap->bg_details_color == '' ) $cap->bg_details_color  =  '121212';
				if( $cap->bg_details_hover_color == '' ) $cap->bg_details_hover_color  =  '181818';
				if( $cap->font_color == '' ) $cap->font_color  =  '696969';
				if( $cap->font_alt_color == '' ) $cap->font_alt_color  =  '444444';
				if( $cap->link_color == '' ) $cap->link_color  =  '2b9c83';
	        break;
			case 'light':
				if( $cap->bg_body_color == '' ) $cap->bg_body_color  =  'f9f9f9';
				if( $cap->bg_container_color == '' ) $cap->bg_container_color  =  'f9f9f9';
				if( $cap->bg_container_alt_color == '' ) $cap->bg_container_alt_color  =  'dedede';
				if( $cap->bg_details_color == '' ) $cap->bg_details_color  =  'e7e7e7';
				if( $cap->bg_details_hover_color == '' ) $cap->bg_details_hover_color  =  'f1f1f1';
				if( $cap->font_color == '' ) $cap->font_color  =  '777777';
				if( $cap->font_alt_color == '' ) $cap->font_alt_color  =  'aaaaaa';
				if( $cap->link_color == '' ) $cap->link_color  =  '74a4a3';
	        break;
			case 'natural':
				if( $cap->bg_body_color == '' ) $cap->bg_body_color  =  'e9e6d8';
				if( $cap->bg_container_color == '' ) $cap->bg_container_color  =  'e9e6d8';
				if( $cap->bg_container_alt_color == '' ) $cap->bg_container_alt_color  =  'cec7ab';
				if( $cap->bg_details_color == '' ) $cap->bg_details_color  =  'dcd6bd';
				if( $cap->bg_details_hover_color == '' ) $cap->bg_details_hover_color  =  'e3dec8';
				if( $cap->font_color == '' ) $cap->font_color  =  '79735d';
				if( $cap->font_alt_color == '' ) $cap->font_alt_color  =  '999177';
				if( $cap->link_color == '' ) $cap->link_color  =  'c3874a';
	        break;
			default:
                if( $cap->bg_body_color == '' ) $cap->bg_body_color  =  'ffffff';
                if( $cap->bg_container_color == '' ) $cap->bg_container_color  =  'ffffff';
                if( $cap->bg_container_alt_color == '' ) $cap->bg_container_alt_color  =  'e3e3e3';
                if( $cap->bg_details_color == '' ) $cap->bg_details_color  =  'f1f1f1';
                if( $cap->bg_details_hover_color == '' ) $cap->bg_details_hover_color  =  'f9f9f9';
                if( $cap->font_color == '' ) $cap->font_color  =  '777777';
                if( $cap->font_alt_color == '' ) $cap->font_alt_color  =  'aaaaaa';
                if( $cap->link_color == '' ) $cap->link_color  =  '6ba090';
	        break;
		}

	return TRUE;
}

/**
 * find out the right color scheme and create the array of css elements with the hex codes
 *
 * @package x2
 * @since 1.0
 */
function x2_color_scheme(){
	echo x2_get_color_scheme();
}
	function x2_get_color_scheme(){
		global $cap;
		if(isset( $_GET['show_style']))
			$cap->style_css = $_GET['show_style'];

		switch ($cap->style_css){
	        case 'dark':
				$color = 'dark';
	        	break;
	        case 'light':
				$color = 'light';
	        	break;
	        case 'white':
				$color = 'white';
	        	break;
	        case 'black':
				$color = 'black';
	        	break;
			case 'natural':
				$color = 'natural';
	        	break;
	        default:
				$color = 'white';
	        	break;
	        }
	        return $color;
	}

function x2_the_loop( $template, $tk_query_id_raw, $show_pagination){
    global $tk_query_id;
    if ( empty($tk_query_id_raw) ) {
        $detect = new x2_Detect();
        $tk_query_id_raw = $detect->tk_get_page_type();
    }

    $tk_query_id = $tk_query_id_raw;

    switch ($template) {

		case 'default':
			get_template_part( 'loop-default' );
			break;

		case 'bubble':
			get_template_part( 'loop-bubbles' );
			break;

		case 'image_caption':
			get_template_part( 'loop-featured-image-caption' );
			break;

		default:
			if(function_exists('tk_loop_designer_the_loop')){
				tk_loop_designer_the_loop( $template, $tk_query_id, $show_pagination );
			} else {
				get_template_part( 'loop-default' );
			}

			break;
	}
}


?>