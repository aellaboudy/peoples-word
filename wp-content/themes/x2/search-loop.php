<?php /* this search loop shows your blog posts in the unified search 
you may modify it as you want, It is a copy from my theme 

*/

do_action( 'bp_before_blog_search' );

global $wp_query;
$wp_query->is_search = true;

$raw_search_string   = !empty($_REQUEST['search-terms']) ? htmlspecialchars(strip_tags($_REQUEST['search-terms'])) : FALSE;
$raw_search_string_2 = !empty($_REQUEST['s']) ? htmlspecialchars(strip_tags($_REQUEST['s'])) : FALSE;

if(!empty($raw_search_string)){
	$search_term = $raw_search_string;
} elseif(!empty($raw_search_string_2)) {
	$search_term = $raw_search_string_2;
}
	
if(!empty($search_term)){
	$wp_query->query("s=".$search_term);

if ( have_posts() ) : ?>
<?php while(have_posts()):the_post(); global $post;?>
<?php do_action( 'bp_before_blog_post' ) ?>
    <div class="post"> <!-- Post goes here... -->
    	<div class="post-content"> 
        	<h3 class="post-title"><?php the_title();?></h3>
            <div>
            	<?php the_excerpt();?>                           
            </div>                       
            <div class="clear"> </div>
        </div>
        <div class="postmetadata"> 
        	<span><?php the_time('F j, Y') ?>  | <?php the_category(', ') ?> | <?php comments_popup_link( __( 'No Comments &#187;', 'x2' ), __( '1 Comment &#187;', 'x2' ), __( '% Comments &#187;', 'x2' ) ); ?></span>
            <div class="readmore"><a href="<?php the_permalink();?>"><?php _e("Read more...",'x2');?></a></div>
        </div>
		
    </div><!-- Post ends here... -->
	<?php do_action( 'bp_after_blog_post' ) ?>
    <?php endwhile;?>
	<?php if(!x2_is_advance_search()):?>
	<div class="navigation">
		<?php if(function_exists("wp_pagenavi"))wp_pagenavi();else{ ?>
		<div class="alignleft"><?php next_posts_link( __( '&larr; Previous Entries', 'x2' ) ) ?></div>
		<div class="alignright"><?php previous_posts_link( __( 'Next Entries &rarr;', 'x2' ) ) ?></div>
	<?php }?>
	</div>
	<?php endif;?>
	<?php else : ?>
	<div class="post">
		<div class="post-content 404">
		<?php echo sprintf(__("We are sorry, but we could not find anything for the search term '%s'",'x2'),$search_term);?>

	<?php locate_template( array( 'searchform.php' ), true ) ?>
	</div>
	</div>
	

	<?php endif; ?>
	<?php } ?>
<?php do_action( 'bp_after_blog_search' ) ?>        