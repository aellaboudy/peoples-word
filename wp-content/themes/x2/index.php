<?php get_header(); global $cap, $the_lp_query; $the_lp_query = '';?>

<div id="content">
	
	<div class="padder">
	
		<?php do_action( 'bp_before_blog_home' ) ?>
		
		<div class="page" id="blog-latest">
		
			<?php x2_the_loop($cap->posts_lists_style, 'index', 'show'); ?>
		
		</div>
		
		<?php do_action( 'bp_after_blog_home' ) ?>
	
	</div><!-- .padder -->

</div><!-- #content -->
	
<?php get_footer() ?>
