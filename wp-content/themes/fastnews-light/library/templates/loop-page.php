<?php if ( have_posts() ) {
    while ( have_posts() ) {
        the_post(); ?>

    <div id="page-<?php the_ID(); ?>" <?php post_class( 'elements-box' ); ?>>
        <h2 class="element-title"><?php the_title(); ?></h2>
        <?php the_content(); ?>
    
        <div class="kopa-pagelink clearfix">
            <?php wp_link_pages( array(
                'before'   => '<p>',
                'after'    => '</p>',
                'pagelink' => __( 'Page %', kopa_get_domain() )
            ) ); ?>
        </div> <!-- .wp-link-pages -->

        <?php 
        if(comments_open() ){
        comments_template(); 
        }
        ?>
    </div>

<?php } // endwhile
} // endif
?>