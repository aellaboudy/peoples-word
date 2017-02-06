<?php
//
// CheezCap - Cheezburger Custom Administration Panel
// (c) 2008 - 2010 Cheezburger Network (Pet Holdings, Inc.)
// LOL: http://cheezburger.com
// Source: http://code.google.com/p/cheezcap/
// Authors: Kyall Barrows, Toby McKes, Stefan Rusek, Scott Porad
// License: GNU General Public License, version 2 (GPL), http://www.gnu.org/licenses/gpl-2.0.html
//

load_template( dirname(__FILE__) . '/get-pro.php' );
load_template( dirname(__FILE__) . '/library.php' );
load_template( dirname(__FILE__) . '/config.php' );

add_action( 'admin_init', 'x2_theme_options_init' );
function x2_theme_options_init(){
	register_setting( 'x2_options', 'x2_theme_options', 'x2_theme_options_validate' );
}

$cap = new autoconfig();

if (!defined('LOADED_CONFIG')) {
    add_action('admin_menu', 'cap_add_admin');
    define('LOADED_CONFIG', 1);
}

function cap_add_admin() {
    global $themename, $req_cap_to_edit;

	$themename = 'x2';
    $req_cap_to_edit = 'activate_plugins';

    if ( ! current_user_can ( $req_cap_to_edit ) )
		return;

    if (isset($_GET['page']) && $_GET['page'] == 'theme_settings') {
        $options = cap_get_options();
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
        $method = false;
        $done = false;
        $data = new ImportData();
        switch ($action) {
            case __('Reset All Settings', 'x2'):
                delete_option('x2_theme_options');
                cap_defaults_init();
                $method = false;
                $_REQUEST['message'] = 'resets';
                break;
            case __('Export', 'x2'):
                $method = 'Export';
                $done = 'cap_serialize_export';
                break;
            case __('Import', 'x2'):
                $method = 'Import';
                if (empty($_FILES['file']['tmp_name'])){
                    $_REQUEST['message'] = 'import_empty';
                    break;
                }else if($_FILES['file']['type'] != 'text/plain'){
                    $_REQUEST['message'] = 'import_error_type';
                    break;
                }

                $data = unserialize(implode('', file($_FILES['file']['tmp_name'])));
                break;
        }

        if ($method) {
            foreach ($options as $group) {
                foreach ($group->options as $option) {
                    call_user_func(array($option, $method), $data);
                }
            }
            if ($done)
                call_user_func($done, $data);
        }
    }

    $pgName = sprintf(__("%s Theme Options", 'x2'), $themename);
    $hook = add_theme_page($pgName, $pgName, isset($req_cap_to_edit) ? $req_cap_to_edit : 'edit_theme_options', 'theme_settings', 'top_level_settings');
    add_action("admin_print_scripts-$hook", 'cap_admin_js_libs');
    add_action("admin_print_scripts-$hook", 'x2_add_rotate_tabs');
    add_action("admin_footer-$hook", 'cap_admin_js');
    add_action("admin_print_styles-$hook", 'cap_admin_css');
}

/**
 * Create default options for the theme and save into the wp_options table
 */

add_action( 'after_switch_theme', 'cap_defaults_init_after_switch_theme' );

function  cap_defaults_init_after_switch_theme(){

    if( !get_option( 'x2_theme_options' ) ) {
        cap_defaults_init();
    }
}

function cap_defaults_init() {
    $cap_options = cap_get_options();

    $cap_options_default = Array();

    foreach ($cap_options as $cap_option) {
        $cap_option_arr = (Array) $cap_option;
        foreach ($cap_option_arr['options'] AS $option) {
			$cap_options_default[$option->id] = $option->std;
        }
    }
    update_option('x2_theme_options', $cap_options_default);

}

/**
 * Check in admin area that we have options in wp_options table.
 * If not - create with default values (almost all are set)
 */
add_action('admin_print_scripts', 'x2_activation_function');

function x2_activation_function() {

    if (!defined('is_pro')) {
        add_action('admin_notices', 'x2_add_rate_us_notice');
    }
}
/**
 * Display the rate for us message
 */
function x2_add_rate_us_notice() {
    $hide_message = get_option('x2_hide_activation_message', false);
    if (!$hide_message) {
        echo '<div class="update-nag x2-rate-it">
            ' . x2_get_add_rate_us_message() . ' <a href="#" class="dismiss-activation-message">' . __('Dismiss', 'x2') . '</a>
        </div>';
    }
}

function x2_get_add_rate_us_message() {
    return 'Please rate for <a class="go-to-wordpress-repo" href="http://wordpress.org/extend/themes/x2" target="_blank">x2 theme</a> on WordPress.org';
}

/**
 * Ajax processor for show/hide Please rate for
 */
add_action('wp_ajax_x2_dismiss_activation_message', 'x2_dismiss_activation_message');

function x2_dismiss_activation_message() {
    echo update_option('x2_hide_activation_message', $_POST['value']);
    die();
}

/**
 * Ajax processor for show/hide Please info for
 */
add_action('wp_ajax_x2_dismiss_info_messages', 'x2_dismiss_info_messages');
function x2_dismiss_info_messages() {
    echo update_option($_POST['action'], $_POST['value']);
    die();
}

function show_page_for_user() {
    global $req_cap_to_edit;
    if(current_user_can('switch_themes')){
        $req_cap_to_edit = 'switch_themes';
        return TRUE;
    }
    $cap = new autoconfig();
    $have_theme_settins_tab = FALSE;
    $groups = cap_get_options();
    foreach ($groups as $group) {
        $role_section = substr($group->id, 4) . "_min_role";
        if (current_user_can(strtolower($cap->$role_section))) {
            $have_theme_settins_tab = TRUE;
        }
    }
    if (!$have_theme_settins_tab) {
        $req_cap_to_edit = 'switch_themes';
    }
    unset($cap);
}

add_action('admin_menu', 'show_page_for_user', 9);
