<?php
/**
 * BuddyPress - Users Media
 */
?>

<?php get_header() ?>

    <div id="content">
        <div class="padder">

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                <?php the_content(); ?>

                <div class="clear"></div>

            <?php endwhile; endif; ?>
        
        </div><!-- .padder -->
    </div><!-- #content -->
<?php get_footer(); ?>