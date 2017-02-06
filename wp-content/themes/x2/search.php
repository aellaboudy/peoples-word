<?php get_header(); global $cap, $the_lp_query; $the_lp_query = '';?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_blog_search' ) ?>

		<div class="page" id="blog-search">

            <h2 class="pagetitle"><?php _e( 'Blog Search Results', 'x2' ) ?></h2>

            <?php x2_the_loop($cap->posts_lists_style, 'search', 'show'); ?>

		</div>

		<?php do_action( 'bp_after_blog_search' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_footer() ?>