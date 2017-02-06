<?php 
$kopa_setting = kopa_get_template_setting();

if ( 'blog-5' == $kopa_setting['layout_id'] ) {
    $kopa_blog_slider_image_size = 'flexslider-image-size';
} else {
    $kopa_blog_slider_image_size = 'gallery-image-size';
}

// get options
$kopa_blog_slider_category_id = (int) get_option( 'kopa_theme_options_blog_slider_category_id' );
$kopa_theme_options_blog_slider_posts_number = (int) get_option( 'kopa_theme_options_blog_slider_posts_number', 3 );
$kopa_blog_slider_settings = array(
    'animation'       => get_option( 'kopa_theme_options_blog_slider_effect', 'slide' ),
    'slideshow_speed' => (int) get_option( 'kopa_theme_options_blog_slider_slideshow_speed', 7000 ),
    'animation_speed' => (int) get_option( 'kopa_theme_options_blog_slider_animation_speed', 600 ),
    'autoplay'        => get_option( 'kopa_theme_options_blog_slider_autoplay', 'false' )
);

// validate options
if ( $kopa_theme_options_blog_slider_posts_number <= 0 ) {
    $kopa_theme_options_blog_slider_posts_number = 3;
}

// new query posts
$kopa_blog_slider_posts = new WP_Query( array(
    'cat'            => $kopa_blog_slider_category_id,
    'posts_per_page' => $kopa_theme_options_blog_slider_posts_number,
) );

if ( $kopa_blog_slider_posts->have_posts() && 'show' == get_option( 'kopa_theme_options_display_blog_slider', 'show' ) ) { ?>
    <div class="kp-slider-widget widget">
        <div class="home-slider flexslider loading" data-animation="<?php echo $kopa_blog_slider_settings['animation']; ?>" data-slideshow_speed="<?php echo $kopa_blog_slider_settings['slideshow_speed']; ?>" data-animation_speed="<?php echo $kopa_blog_slider_settings['animation_speed']; ?>" data-autoplay="<?php echo $kopa_blog_slider_settings['autoplay']; ?>" data-direction="horizontal">
            <ul class="slides">
            <?php while ( $kopa_blog_slider_posts->have_posts() ) { 
                $kopa_blog_slider_posts->the_post(); ?>
               
                    <li>
                        <article>
							<?php if ( has_post_thumbnail() ) { ?>
                            <img src="<?php echo kopa_get_image_src(get_the_ID(), $kopa_blog_slider_image_size) ?>" alt="<?php echo get_the_title();?>">
                            <?php } else {
							?>
							<img src="http://placehold.it/720x480&text=No+Image+Available" alt="<?php echo get_the_title();?>">
							<?php
							} // endif ?>
                            <div class="flex-caption">
                                <header>
                                    <span class="entry-categories"><?php _e( 'in:', kopa_get_domain() ); ?> <?php the_category( ', '); ?></span>
                                    <span class="entry-met">&nbsp;|&nbsp;</span>
                                    <span class="entry-date"><a href="<?php the_permalink(); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a></span>
                                </header>
                                <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <?php the_excerpt(); ?>
                            </div>
                            <!-- flex-caption -->
                        </article>
                    </li>
                
            <?php } // endwhile ?>
            </ul>
        </div>
    </div>
<?php } // endif

wp_reset_postdata();
