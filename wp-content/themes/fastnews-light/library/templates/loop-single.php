<?php
if (have_posts()) {
    while (have_posts()) {
        the_post();
        get_template_part('library/templates/format-single', get_post_format());
        ?>
        <?php if (get_the_terms(get_the_ID(), 'post_tag')) { ?>
            <div class="tag-box">
                <span><?php _e('Tagged with:', kopa_get_domain()); ?></span>
                <?php the_tags('', ' ', ''); ?>
            </div><!--tag-box-->
        <?php } // endif ?>

        <?php kopa_get_about_author(); ?>

        <?php kopa_get_related_articles(); ?>
            
        <?php 
        if(comments_open() ){
        comments_template(); 
        }
        ?>

    <?php
    } // endwhile
} // endif


