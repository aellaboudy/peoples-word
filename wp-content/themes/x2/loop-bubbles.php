<?php

global $the_lp_query, $cap, $show_pagination;

if(!$the_lp_query)
	$the_lp_query = $wp_query;

if ( $the_lp_query->have_posts() ) : ?>
<div id="featured_posts_bubbles">
	<div id="list_posts_bubbles" class="loop-designer list-posts-all">

		<div class="bubbles">

	    <?php while ( $the_lp_query->have_posts() ) : $the_lp_query->the_post(); ?>

	        <?php do_action( 'bp_before_blog_post' ) ?>

	        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	            <div class="author-box">
	                <?php echo get_avatar( get_the_author_meta( 'user_email' ), '50' ); ?>
	                <?php if(defined('BP_VERSION')){ ?>
	                <p><?php printf( __( 'by %s', 'x2' ), bp_core_get_userlink( $post->post_author ) ) ?></p>
	                <?php } ?>
	            </div>

	            <div class="post-content">

	                <span class="marker"></span>

	                <h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'x2' ) ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

	                <p class="date"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'x2' ) ?> <?php the_title_attribute(); ?>"><?php the_time('F j, Y') ?></a> <em><?php _e( 'in', 'x2' ) ?> <?php the_category(', ') ?><?php if(defined('BP_VERSION')){  printf( __( ' by %s', 'x2' ), bp_core_get_userlink( $post->post_author ) );}?></em></p>

	                <div class="entry">
	                    <?php do_action('blog_post_entry')?>
	                </div>
	                <?php $tags = get_the_tags(); if($tags) {  ?>
	                    <p class="postmetadata"><span class="tags"><?php the_tags( __( 'Tags: ', 'x2' ), ', ', '<br />'); ?></span> <span class="comments"><?php comments_popup_link( __( 'No Comments &#187;', 'x2' ), __( '1 Comment &#187;', 'x2' ), __( '% Comments &#187;', 'x2' ) ); ?></span></p>
	                <?php } else {?>
	                    <p class="postmetadata"><span class="comments"><?php comments_popup_link( __( 'No Comments &#187;', 'x2' ), __( '1 Comment &#187;', 'x2' ), __( '% Comments &#187;', 'x2' ) ); ?></span></p>
	                <?php } ?>
	            </div>

	        </div>

	        <?php do_action( 'bp_after_blog_post' ) ?>

	   	 <?php endwhile; ?>

		</div>
		<div class="clear"></div>

		<?php

		// Pagination starts
		$x2_tmp = '';

		if($show_pagination != 'hide'){
			if( $cap->posts_lists_hide_pagenavi == 'show'){
			    $x2_tmp .='<div id="_bubbles" class="navigation">';
			    $x2_tmp .='<div class="alignleft">'. get_next_posts_link(__('&laquo; Older Entries','x2'), $the_lp_query->max_num_pages ) .'</div>';
			    $x2_tmp .='<div class="alignright">' . get_previous_posts_link(__('Newer Entries &raquo;','x2')) .'</div>';
			    $x2_tmp .='</div><!-- End navigation -->';
			}

			if($cap->posts_lists_hide_pagenavi == 'pagenavi' ){
			    if(function_exists('wp_pagenavi')){
			        ob_start();
			            wp_pagenavi( array( 'query' => $the_lp_query) );
			            $x2_tmp_wp_pagenavi = ob_get_clean();
			            $x2_tmp_wp_pagenavi = str_replace('class=\'wp-pagenavi\'', 'id="_bubbles" class="wp-pagenavi navigation"', $x2_tmp_wp_pagenavi);

			            $x2_tmp .= $x2_tmp_wp_pagenavi;
			    }
			}
		}
		echo $x2_tmp;
		wp_reset_postdata();?>

	</div>
</div>
<?php else : ?>

    <h2 class="center"><?php _e( 'Not Found', 'x2' ) ?></h2>
    <p class="center"><?php _e( 'Sorry, but you are looking for something that isn\'t here.', 'x2' ) ?></p>

    <?php locate_template( array( 'searchform.php' ), true ) ?>

<?php endif; ?>