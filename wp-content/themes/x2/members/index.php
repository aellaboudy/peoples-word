<?php

/**
 * BuddyPress - Members Directory
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php get_header(); ?>

	<?php do_action( 'bp_before_directory_members_page' ); ?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_directory_members' ); ?>

		<form action="" method="post" id="members-directory-form" class="dir-form">

			<h2 class="pagetitle"><?php _e( 'Members Directory', 'x2' ); ?></h2>

			<?php do_action( 'bp_before_directory_members_content' ); ?>

			<div id="members-dir-search" class="dir-search" role="search">

				<?php $default_search_value = bp_get_search_default_text( 'members' ); ?>
				<?php $search_value         = !empty( $_REQUEST['s'] ) ? stripslashes( $_REQUEST['s'] ) : $default_search_value; ?>
			
				<form action="" method="get" id="search-members-form">
					<div class="input-append">
	            		<label for="members_search"></label>
		                <input type="text" name="s" id="members_search" placeholder="<?php echo esc_attr( $search_value ) ?>" />
		                <button type="submit" id="members_search_submit" name="members_search_submit" class="btn"><i class="icon-search"></i></button>
	            	</div>
				</form>

			</div><!-- #members-dir-search -->

			<div class="item-list-tabs" role="navigation">
				<ul>
					<li class="selected" id="members-all"><a href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_members_root_slug() ); ?>"><?php printf( __( 'All Members <span>%s</span>', 'x2' ), bp_get_total_member_count() ); ?></a></li>

					<?php if ( is_user_logged_in() && bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>

						<li id="members-personal"><a href="<?php echo bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/' ?>"><?php printf( __( 'My Friends <span>%s</span>', 'x2' ), bp_get_total_friend_count( bp_loggedin_user_id() ) ); ?></a></li>

					<?php endif; ?>

					<?php do_action( 'bp_members_directory_member_types' ); ?>

				</ul>
			</div><!-- .item-list-tabs -->

			<div class="item-list-tabs" id="subnav" role="navigation">
				<ul>

					<?php do_action( 'bp_members_directory_member_sub_types' ); ?>

					<li id="members-order-select" class="last filter">

						<label for="members-order-by"><?php _e( 'Order By:', 'x2' ); ?></label>
						<select id="members-order-by">
							<option value="active"><?php _e( 'Last Active', 'x2' ); ?></option>
							<option value="newest"><?php _e( 'Newest Registered', 'x2' ); ?></option>

							<?php if ( bp_is_active( 'xprofile' ) ) : ?>

								<option value="alphabetical"><?php _e( 'Alphabetical', 'x2' ); ?></option>

							<?php endif; ?>

							<?php do_action( 'bp_members_directory_order_options' ); ?>

						</select>
					</li>
				</ul>
			</div>

			<div id="members-dir-list" class="members dir-list">

				<?php locate_template( array( 'members/members-loop.php' ), true ); ?>

			</div><!-- #members-dir-list -->

			<?php do_action( 'bp_directory_members_content' ); ?>

			<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

			<?php do_action( 'bp_after_directory_members_content' ); ?>

		</form><!-- #members-directory-form -->

		<?php do_action( 'bp_after_directory_members' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php do_action( 'bp_after_directory_members_page' ); ?>

<?php get_footer(); ?>
