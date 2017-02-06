<?php 
/*
 * Template Name: Home Magazine 
 */
?>

<?php remove_sidebar_left(); ?>

<?php get_header() ?>

    <div id="content">

            <?php do_action( 'bp_before_blog_page' ) ?>
    
                <div class="page" id="homepage">
                	<?php if(is_page()){ ?>
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
			        <?php } ?>
                    <div id="sidebar_home_top" class="home-sidebar fullwidth widgetarea">
                    <?php if( ! dynamic_sidebar( 'home_top' )) : ?>    
                		
                    <?php endif; // end primary widget area ?>
                    </div>  
                    
                    <div id="sidebar_home_left" class="home-sidebar threecolumns widgetarea">
                    <?php if( ! dynamic_sidebar( 'home_left' )) : ?>    
                   
                    <?php endif; // end primary widget area ?>
                    </div>  
                    
                    <div id="sidebar_home_center" class="home-sidebar threecolumns widgetarea">
                    <?php if( ! dynamic_sidebar( 'home_center' )) : ?>    
                    
                    <?php endif; // end primary widget area ?>
                    </div>  
                    
                    <div id="sidebar_home_right" class="home-sidebar threecolumns right widgetarea">
                    <?php if( ! dynamic_sidebar( 'home_right' )) : ?>    
                    
                    <?php endif; // end primary widget area ?>
                    </div>

                </div><!-- .homepage -->
            
            <div class="clear"></div>
        
            <?php do_action( 'bp_after_blog_page' ) ?>
            
    </div><!-- #content -->

<?php remove_sidebar_right(); ?>

<?php get_footer(); ?>