<?php function get_pro() {

	if( defined('is_pro') ):
		return;

	else: ?>
		 <div id="cap_getpro">
			<div class="getpro_content">
				<p style="font-size: 15px; line-height: 160%; color: #999;"><em>
					<?php _e('Get more theme options and personal support directly by the developers. <br>Order the x2 Premium Pack and support this open source project.', 'x2'); ?>
				</em></p>
				<br>
				<h1 style="margin: 0 0 20px 0; line-height: 120%;"><?php _e('What you get with x2 Premium Pack', 'x2'); ?></h1>
		    	<h3 style="font-weight: normal;"><?php _e('+ More theme options', 'x2'); ?></h3>
		    	<h3 style="font-weight: normal;"><?php _e('+ More single page options', 'x2');?></h3>
		    	<h3 style="font-weight: normal;"><?php _e('+ More page templates', 'x2'); ?></h3>
		    	<h3 style="font-weight: normal;"><?php _e('+ Prepared child theme to start customizing right away!', 'x2'); ?></h3>
		    	<h3 style="font-weight: normal;"><?php _e('+ 1-click support right from your dashboard', 'x2'); ?><h3>
		    	<h3 style="font-weight: normal;"><?php _e('+ Personal help <em>directly by the developers!</em>', 'x2'); ?></h3>
		    	<p style="margin: 25px 0;"><a href="http://themekraft.com/store/x2-premium-pack/" style="font-size: 18px; padding: 10px 25px; line-height: 100%; height: auto;" class="button button-primary" target="_new"><?php _e('Get x2 Premium Pack'); ?></a></p>
		    	<p style="margin-bottom: 100px; font-size: 15px;"><?php _e('&raquo; Start making your site awesome today!', 'x2'); ?></p>
			</div>
		</div>

		<div class="spacer"></div>
	<?php endif;

} ?>