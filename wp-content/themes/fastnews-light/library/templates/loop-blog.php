<?php 
if ( is_home() ) {
    get_template_part( 'library/templates/template', 'blog-slider' );
}
?>

<ul class="entry-list isotop-item clearfix">
    <?php if ( have_posts() ) {
        while ( have_posts() ) {
            the_post(); ?>

            <li class="element">
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-item clearfix' ); ?>>
                    <?php if ( has_post_thumbnail() ) { ?>
                        <div class="entry-thumb">
                            <a href="<?php the_permalink(); ?>"><img src="<?php echo kopa_get_image_src(get_the_ID(), 'medium'); ?>" alt="<?php echo get_the_title(); ?>"></a>
                        </div>
                        <!-- entry-thumb -->
                    <?php } // endif ?>
                    <div class="entry-content">
                        <header>
                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php echo (!is_search())? get_the_title(): kopa_search_title();  ?></a></h4>
                            <span class="entry-date"><a href="<?php the_permalink(); ?>">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></a></span>
                        </header>
                        <?php the_excerpt(); ?>
                        <a href="<?php the_permalink(); ?>" class="more-link"><?php _e( 'Continue Reading ...', kopa_get_domain() ); ?></a>
                    </div>
                    <!-- entry-content -->
                </article>
                <!-- entry-item -->
            </li>

        <?php } // endwhile
    } // endif ?>
</ul>

<!-- pagination -->
<?php get_template_part('library/templates/template', 'pagination'); ?>