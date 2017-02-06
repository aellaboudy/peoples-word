<?php remove_sidebar_left(); ?>

<?php get_header() ?>

	<div id="content" class="error404">
		<div class="padder">
			<?php do_action( 'bp_before_404' ); ?>
			<div id="error" class="page-404 error404 not-found" role="main">
				<a id="gohome" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php _e( 'Get back.', 'x2' ); ?>"><?php _e( '&raquo; Go home.', 'x2' ); ?></a>
				<div id="title404">404</div>

				<div class="wrong">
					<h3>
						<?php if ( $cap->errorpage_chaos_msg == "" ): ?>
							<?php _e( "Something's wrong here.", 'x2' ); ?>
						<?php else: ?>
							<?php echo $cap->errorpage_chaos_msg; ?>
						<?php endif; ?>
					</h3>
				</div>

				<div class="help404">

					<h2 class="pagetitle">
						<?php if ( $cap->errorpage_title == "" ): ?>
							<?php _e( "Page not found", 'x2' ); ?>
						<?php else: ?>
							<?php echo $cap->errorpage_title; ?>
						<?php endif; ?>
					</h2>

					<?php if ( $cap->errorpage_search_show != "hide" ): ?>
						<p>
							<?php if ( $cap->errorpage_search_msg == "" ): ?>
								<?php _e( "Maybe searching will help?", 'x2' ); ?>
							<?php else: ?>
								<?php echo $cap->errorpage_search_msg; ?>
							<?php endif; ?>
						</p>
						<?php get_search_form(); ?>
					<?php endif; ?>

				</div>

				<?php do_action( 'bp_404' ); ?>
			</div>

			<?php do_action( 'bp_after_404' ) ?>
		</div><!-- .padder -->
	</div><!-- #content -->

	</div> <!-- #container -->

	</div><!-- #outerrim -->

	<?php wp_footer(); ?>

	</body>

</html>