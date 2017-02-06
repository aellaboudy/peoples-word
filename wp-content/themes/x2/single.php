<?php get_header() ?>

	<div id="content">
		<div class="padder">

			<?php do_action( 'bp_before_blog_single_post' ) ?>

			<div class="page" id="blog-single">
					
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
						<?php if( $cap->single_post_hide_avatar != 'hide' ) { ?>
							<div class="author-box">
								<?php echo get_avatar( get_the_author_meta( 'user_email' ), '50' ); ?>
								<?php if(defined('BP_VERSION')){ ?>
									<p><?php printf( __( 'by %s', 'x2' ), bp_core_get_userlink( $post->post_author ) ) ?></p>
								<?php } ?>
							</div>
						<?php } ?>
						
						<div class="post-content">	
							
							<h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'x2' ) ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
							<?php if( $cap->single_post_hide_date != 'hide') { ?>
								<p class="date"><?php the_time('F j, Y') ?> <em><?php _e( 'in', 'x2' ) ?> <?php the_category(', ') ?> <?php if(defined('BP_VERSION')){  printf( __( ' by %s', 'x2' ), bp_core_get_userlink( $post->post_author ) ); }?></em></p>
							<?php } ?> 
			
							<div class="entry">
								<?php the_content( __( 'Read the rest of this entry &rarr;', 'x2' ) ); ?>
								<div class="clear"></div>
								<?php wp_link_pages(array('before' => __( '<p class="x2_pagecount"><strong>Pages:</strong> ', 'x2' ), 'after' => '</p>', 'next_or_number' => 'number')); ?>
							</div>
								
							<div class="clear"></div>
			
							<?php if( $cap->single_post_hide_tags != 'hide') { ?>
								<?php $tags = get_the_tags(); if($tags)	{  ?>
									<p class="postmetadata"><span class="tags"><?php the_tags( __( 'Tags: ', 'x2' ), ', ', '<br />'); ?></span></p>
								<?php } ?> 
							<?php } ?>	 
							
							<?php if( $cap->single_post_hide_comment_info != 'hide') { ?>
								<p class="postmetadata"><span class="comments"><?php comments_popup_link( __( 'No Comments &#187;', 'x2' ), __( '1 Comment &#187;', 'x2' ), __( '% Comments &#187;', 'x2' ) ); ?></span></p>
							<?php } ?>
									
							
							<div class="alignleft"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'x2' ) . '</span> %title' ); ?></div>
							<div class="alignright"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'x2' ) . '</span>' ); ?></div>
						</div>
					</div>
	
					<?php edit_post_link( __( 'Edit this entry.', 'x2' ), '<p>', '</p>'); ?>
	
					<?php comments_template(); ?>
		
					<?php endwhile; else: ?>
						<p><?php _e( 'Sorry, no posts matched your criteria.', 'x2' ) ?></p>
					<?php endif; ?>
					
					<?php do_action( 'x2_list_posts_on_post' ) ?>
			</div>

			<?php do_action( 'bp_after_blog_single_post' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_footer() ?>