<?php get_header(); global $cap, $the_lp_query; $the_lp_query = '';?>

	<div id="content">
		<div class="padder">
		<?php do_action( 'bp_before_archive' ) ?>

		<div class="page" id="blog-archives">

			<?php if ( have_posts() ) : ?>

			   	<header class="page-header">
						<h2 class="pagetitle">
							<?php if ( is_day() ) : ?>
								<?php printf( __( 'Daily Archives: %s', 'x2' ), '<span>' . get_the_date() . '</span>' ); ?>
							<?php elseif ( is_month() ) : ?>
								<?php printf( __( 'Monthly Archives: %s', 'x2' ), '<span>' . get_the_date( 'F Y' ) . '</span>' ); ?>
							<?php elseif ( is_year() ) : ?>
								<?php printf( __( 'Yearly Archives: %s', 'x2' ), '<span>' . get_the_date( 'Y' ) . '</span>' ); ?>
							<?php elseif ($cap->category_pagetitle == "") : ?>
								<?php printf( __( 'You are browsing the Blog for %1$s.', 'x2' ), wp_title( false, false ) ); ?>
							<?php else: ?>
								<?php echo $cap->category_pagetitle; printf( __( ' %1$s', 'x2' ), wp_title( false, false ) ); ?>
							<?php endif; ?>
						</h3>
				</header>

				<?php x2_the_loop($cap->posts_lists_style, 'archive', 'show'); ?>

			<?php else : ?>

				<h2 class="center"><?php _e( 'Not Found', 'x2' ) ?></h2>
				<?php locate_template( array( 'searchform.php' ), true ) ?>

			<?php endif; ?>

		</div>

		<?php do_action( 'bp_after_archive' ) ?>

		</div><!-- .padder -->
		
	</div><!-- #content -->

<?php get_footer(); ?>
