<?php get_header() ?>

<div id="content">
	<div class="padder">
	<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>
		<?php do_action( 'bp_before_group_home_content' ) ?>
		
		<div id="item-body">
		<?php do_action( 'bp_before_group_body' ) ?>
		
		<?php do_action( 'bp_template_content' ) ?>

		<?php do_action( 'bp_after_group_body' ) ?>
		</div><!-- #item-body -->
		
		<?php endwhile; endif; ?>
		
	<?php do_action( 'bp_after_group_plugin_template' ) ?>
	
	</div><!-- .padder -->
</div><!-- #content -->

<?php get_footer() ?>