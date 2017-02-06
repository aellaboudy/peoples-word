<?php get_header() ?>

    <div id="content">
        <div class="padder">
            
        <?php do_action( 'x2_first_inside_padder' ); ?>

        <?php do_action( 'bp_before_blog_page' ) ?>

        <div class="page" id="blog-page">

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                
                <?php do_action( 'x2_before_pagetitle' ) ?>

                <h2 class="pagetitle"><?php the_title(); ?><?php do_action( 'x2_inside_pagetitle_after_title' ) ?></h2>
                
                <?php do_action( 'x2_after_pagetitle' ) ?>

                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="entry">

                        <?php the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'x2' ) ); ?>
                        <div class="clear"></div>
                        <?php wp_link_pages( array( 'before' => __( '<p class="x2_pagecount"><strong>Pages:</strong> ', 'x2' ), 'after' => '</p>', 'next_or_number' => 'number')); ?>

                    </div>
                    <div class="clear"></div>
                </div>

            <?php endwhile; endif; ?>

        </div><!-- .page -->
        <?php do_action( 'x2_list_posts_on_page' ) ?>
        
        <div class="clear"></div>
        
        <?php do_action( 'bp_after_blog_page' ) ?>
        
        <?php edit_post_link( __( 'Edit this page.', 'x2' ), '<p class="edit-link">', '</p>'); ?>
        
        <!-- instead of comment_form() we use comments_template(). If you want to fall back to wp, change this function call ;-) -->
        <?php comments_template(); ?>
        
        </div><!-- .padder -->
    </div><!-- #content -->
<?php get_footer(); ?>