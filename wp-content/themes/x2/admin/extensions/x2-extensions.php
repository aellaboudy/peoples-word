<?php

/**
 * The Theme Extensions Admin Page
 * Based on the Example of Otto @WordPress.org - Thanks Otto!
 * http://ottopress.com/2012/themeplugin-dependencies/
 *
 * ***********************************************************
 */


/**
 * Adding the Admin Page
 * @author Sven Lehnert, Konrad Sroka
 */
add_action( 'admin_menu', 'x2_extensions_admin_menu' );
function x2_extensions_admin_menu() {
    add_theme_page( __('X2 Extensions', 'x2'), __('X2 Extensions', 'x2'), 'edit_theme_options', 'x2-extensions-options', 'x2_extensions_screen' );
}


/**
 * x2 Extensions Admin Page
 * @author Sven Lehnert, Konrad Sroka
 */
function x2_extensions_screen() { ?>
	<style>
		div.wrap h2 { margin-bottom: 10px; }
		p.bordered { margin-bottom: 50px; }

		/** free extensions section **/
		table.plugins td p { padding: 5px 5px 3px 5px; }

		/** premium extensions section **/
		#themekraft-shop { margin-top: 50px; }


	</style>
    <div class="wrap">
        <div id="icon-themes" class="icon32"><br></div>
        <h2><?php _e('x2 Extensions', 'x2'); ?></h2>
        <p style="font-size: 15px; margin-bottom: 30px;"><i><?php _e('Free and Premium Extensions for your x2 Theme.', 'x2'); ?></i></p>
        <div class="clear"></div>

        <?php _e('<h2><b><i>Free</i></b> Extensions and Supported Plugins</h2>', 'x2');?>

        <form method="post" action="options.php">
        	<table class="wp-list-table widefat plugins">
        		<tbody id="the-list">
		            <?php
		            // adding each free extension - handpicked for you ;)
					add_free_extension( 'Jetpack by WordPress.com -', 'jetpack', 'http://wordpress.org/extend/plugins/jetpack/' );
					add_free_extension( 'BuddyPress - Social Network Component for WordPress -', 'buddypress', 'http://buddypress.org' );
		           	add_free_extension( 'bbPress - Forums Component for WordPress -', 'bbpress', 'http://bbpress.org/' );
					add_free_extension( 'WP-PageNavi - Page Navigation -', 'wp-pagenavi', 'http://wordpress.org/extend/plugins/wp-pagenavi/' );
					add_free_extension( 'Simple Social Icons', 'simple-social-icons', 'http://www.studiopress.com/plugins/simple-social-icons' );
					add_free_extension( 'NextGEN Gallery', 'nextgen-gallery', 'http://www.nextgen-gallery.com/' );
					add_free_extension( 'WooCommerce', 'woocommerce', 'http://www.nextgen-gallery.com/' );
					?>
				</tbody>
			</table>
        </form>

    </div><!-- end .wrap --><?php

}


/**
 * Adding a free extensions to the list
 * @author Sven Lehnert, Konrad Sroka
 */
function add_free_extension( $name, $slug, $url ) {
    $tpd = new Theme_Plugin_Dependency( $slug, $url ); ?>

    <tr class="<?php if( $tpd->check_active() ) { echo "active"; } else { echo "inactive"; } ?>">
    	<td><p>
		    <?php
		    // echo '<pre>';
		    // print_r($tpd);
		    // echo '/<pre>';
		    if( $tpd->check_active() ) {
		        printf(__('%s is installed and activated!', 'x2'), $name);
		    } else if( $tpd->check() ) {
		        printf(__('%s is installed, but not activated. <a href="%s">Click here to activate the plugin.</a>', 'x2'), $name, $tpd->activate_link());
		    } else if( $install_link = $tpd->install_link() ) {
		        printf(__('%s is not installed. <a href="%s">Click here to install the plugin.</a>', 'x2'), $name, $install_link);
		    } else {
		        printf(__('%s is not installed and could not be found in the plugin directory. Please install this plugin manually.', 'x2'), $name);
		    } ?>
		</td></p>
    </tr><?php

}


/**
 * Enqueue the extensions JS
 * @author Sven Lehnert
 */
add_action('admin_enqueue_scripts', 'x2_extensions_js');
function x2_extensions_js(){
	wp_enqueue_script( 'x2_extensions_js', get_template_directory_uri() . '/admin/extensions/js/x2-extensions.js', array('jquery') );
}

?>