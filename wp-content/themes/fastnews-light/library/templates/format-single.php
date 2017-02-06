<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        
    <?php if ( has_post_thumbnail() && 'show' == get_option( 'kopa_theme_options_featured_image_status', 'show' ) ) { ?>
        <div class="entry-thumb">
            <img src="<?php echo kopa_get_image_src(get_the_ID(), 'medium'); ?>" alt="<?php echo get_the_title(); ?>">
        </div>
    <?php } ?>
    <header>
        <h4 class="entry-title"><?php the_title(); ?></h4>
        <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
    </header>

    <div class="elements-box">
        <?php the_content(); ?>
    </div>

    <div class="kopa-pagelink clearfix">
        <?php wp_link_pages( array(
            'before'   => '<p>',
            'after'    => '</p>',
            'pagelink' => __( 'Page %', kopa_get_domain() )
        ) ); ?>
    </div> <!-- .wp-link-pages -->
    
    
    
    <footer class="clearfix">
        <?php get_template_part( 'library/templates/template', 'post-navigation' ); ?>
    </footer>
    
</div><!--entry-box-->