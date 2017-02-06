<?php

// register all buddypress widgets if buddypress is activated

if(defined('BP_VERSION')){
	if ( function_exists('x2_login_widget') )
	    wp_register_sidebar_widget( 'x2_login_widget', __('BP Sidebar Login', 'x2'), 'x2_login_widget', '' );

    if ( function_exists('groups_header_widget') )
        wp_register_sidebar_widget( 'groups_header_widget', __('Groups Header Widget', 'x2'), 'groups_header_widget');
        wp_register_widget_control( 'groups_header_widget', __('Groups Header Control', 'x2'), 'groups_header_control', '' );

    if ( function_exists('profiles_header_widget') )
        wp_register_sidebar_widget( 'profiles_header_widget',__('Profiles Header Widget','x2'), 'profiles_header_widget');
        wp_register_widget_control( 'profiles_header_widget', __('Profiles Header Control', 'x2'), 'profiles_header_control', '' );


    if ( function_exists('forum_tags_widget') )
	    wp_register_sidebar_widget( 'forum_tags_widget', __('Forum Tags','x2'), 'forum_tags_widget', '' );
}

/**
 *  buddypress login widget
 *
 * @package x2
 * @since 1.0
 */
function x2_login_widget(){?>
	<?php global $cap;?>
		<div id="community-login" class="widget">

		<?php if ( is_user_logged_in() ) : ?>

			<h3 class="widgettitle"><?php _e( 'Welcome back, ', 'x2' ) ?><?php echo bp_core_get_username( bp_loggedin_user_id() ); ?>!</h3>
			<?php do_action( 'bp_before_sidebar_me' ) ?>
			<div id="sidebar-me">
				<a href="<?php echo bp_loggedin_user_domain() ?>"><?php bp_loggedin_user_avatar( 'type=thumb&width=40&height=40' ) ?></a>
				<h4><?php echo bp_core_get_userlink( bp_loggedin_user_id() ); ?></h4>
				<a class="btn btn-small logout" href="<?php echo wp_logout_url( bp_get_root_domain() ) ?>"><i class="icon-eject"></i>&nbsp;&nbsp;<?php _e( 'Log out', 'x2' ) ?></a>
				<?php do_action( 'bp_sidebar_me' ) ?>
			</div>
			</div>
			<?php do_action( 'bp_after_sidebar_me' ) ?>

			<?php if ( function_exists( 'bp_message_get_notices' ) ) : ?>
				<?php bp_message_get_notices(); /* Site wide notices to all users */ ?>
			<?php endif; ?>

		<?php else : ?>

			<h3 class="widgettitle"><?php _e( 'Login', 'x2' ) ?></h3>
			<?php do_action( 'bp_before_sidebar_login_form' ) ?>
			<p id="login-text">
			<?php if(!$cap->bp_login_sidebar_text) { ?>
				<?php _e( 'To start connecting please log in first.', 'x2' ) ?>
			<?php } else { ?>
				<?php echo $cap->bp_login_sidebar_text; ?>
			<?php } ?>
				<?php if ( bp_get_signup_allowed() ) : ?>
					<?php printf( __( ' You can also <a href="%s" title="Create an account">create an account</a>.', 'x2' ), home_url( BP_REGISTER_SLUG . '/' ) ) ?>
				<?php endif; ?>
			</p>

			<form name="login-form" id="sidebar-login-form" class="standard-form" action="<?php echo site_url( 'wp-login.php', 'login_post' ) ?>" method="post">

				<div class="control-group">
					<div class="controls">
						<div class="input-prepend">
					      	<span class="add-on"><i class="icon-user"></i></span>
					      	<input type="text" name="log" id="sidebar-user-login" placeholder="<?php _e('Username', 'x2')?>" class="input" value="" />
						</div>
					</div>
				</div>

				<div class="control-group">
					<div class="controls">
						<div class="input-prepend">
					      	<span class="add-on"><i class="icon-lock"></i></span>
							<input type="password" name="pwd" id="sidebar-user-pass" placeholder="<?php _e('Password', 'x2')?>" class="input" value="" />
						</div>
					</div>
				</div>

				<div class="login-options">
					<div class="lostpassword">
                        <a class="lostpassword" href="<?php echo site_url('/wp-login.php?action=lostpassword');?>">&rarr; <?php _e('Lost password?', 'x2'); ?></a>
					</div>

					<p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="sidebar-rememberme" value="forever" /> <?php _e( 'Remember Me', 'x2' ) ?></label></p>
				</div>

				<?php do_action( 'bp_sidebar_login_form' ) ?>
				<input class="btn btn-small" type="submit" name="wp-submit" id="sidebar-wp-submit" value="<?php _e('Log in','x2'); ?>" tabindex="100" />
				<input type="hidden" name="testcookie" value="1" />
			</form>
			</div>
			<?php do_action( 'bp_after_sidebar_login_form' ) ?>
		<?php endif; ?>
<?php }

/**
 *  buddypress default forum topic tags widget to show forum tags on the forums directory
 *
 * @package x2
 * @since 1.0
 */
function forum_tags_widget(){
 /* Show forum tags on the forums directory */
	if ( BP_FORUMS_SLUG == bp_current_component() && bp_is_directory() ) : ?>
		<div id="forum-directory-tags" class="widget tags">

			<h3 class="widgettitle"><?php _e( 'Forum Topic Tags', 'x2' ) ?></h3>
			<?php if ( function_exists('bp_forums_tag_heat_map') ) : ?>
				<div id="tag-text"><?php bp_forums_tag_heat_map(); ?></div>
			<?php endif; ?>
		</div>
<?php
	endif;
}

/**
 *  groups sidebar header widget
 *
 * @package x2
 * @since 1.0
 */
function groups_header_widget($args) {
  extract($args);

  $options = get_option("groups_header_position");
  if (!is_array( $options )) {
    $options = array(
      'groups_header_position' => 'horizontal'
    );
  }

  if($options[groups_header_position] != 'horizontal') {
  		locate_template( array( 'groups/single/group-header-sidebar.php' ), true, false );
    } else {
    if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group();
  		locate_template( array( 'groups/single/group-header.php' ), true, false );
    endwhile; endif;
  }
}

function groups_header_control() {
  $options = get_option("groups_header_position");
  if (!is_array( $options )) {
    $options = array(
      'groups_header_position' => 'horizontal'
     );
  }

  if ($_POST['groups_header_submit']){
    $options['groups_header_position'] = htmlspecialchars($_POST['groups_header_position']);
    update_option("groups_header_position", $options);
  }?>
  <p>
    <label for="groups_header_position">Widget Position: </label><br />
    Horizontal: <input type="radio" name="groups_header_position" value="horizontal" <?php if($options['groups_header_position'] == 'horizontal'){ ?> checked="checked" <?php } ?> /><br />
    Vertical: <input type="radio" name="groups_header_position" value="vertical" <?php if($options['groups_header_position'] == 'vertical'){ ?> checked="checked" <?php } ?> /><br />
    <input type="hidden" id="groups_header_submit" name="groups_header_submit" value="1" />
  </p>
<?php
}

/**
 *  members sidebar header widget
 *
 * @package x2
 * @since 1.0
 */

function profiles_header_widget($args) {
  extract($args);

  $options = get_option("profiles_header_position");
  if (!is_array( $options )) {
    $options = array(
      'profiles_header_position' => 'horizontal'
    );
  }

  if($options[profiles_header_position] != 'horizontal') {
  		locate_template( array( 'members/single/member-header-sidebar.php' ), true, false );
    } else {
    if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group();
  		locate_template( array( 'members/single/member-header.php' ), true, false );
    endwhile; endif;
  }
}

function profiles_header_control() {
  $options = get_option("profiles_header_position");
  if (!is_array( $options )) {
    $options = array(
      'profiles_header_position' => 'horizontal'
     );
  }

  if ($_POST['profiles_header_submit']){
    $options['profiles_header_position'] = htmlspecialchars($_POST['profiles_header_position']);
    update_option("profiles_header_position", $options);
  }?>
  <p>
    <label for="profiles_header_position"><?php _e('Widget Position:', 'x2')?> </label><br />
    <?php _e('Horizontal:', 'x2')?>
    <input type="radio" name="profiles_header_position" value="horizontal" <?php if($options['profiles_header_position'] == 'horizontal'){ ?> checked="checked" <?php } ?> /><br />
    <?php _e('Vertical:', 'x2')?>
    <input type="radio" name="profiles_header_position" value="vertical" <?php if($options['profiles_header_position'] == 'vertical'){ ?> checked="checked" <?php } ?> /><br />
    <input type="hidden" id="profiles_header_submit" name="profiles_header_submit" value="1" />
  </p>
<?php } ?>