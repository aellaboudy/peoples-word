<?php
/**
 * Widget Registration
 * @package FastNews
 */

add_action('widgets_init', 'kopa_widgets_init');

function kopa_widgets_init() {
    
    register_widget('Kopa_Widget_Flexslider');
    
    register_widget('Kopa_Widget_Small_Articles_List');
    register_widget('Kopa_Widget_Articles_Carousel');
    
    register_widget('Kopa_Widget_Entry_List_2');
    
    
}

add_action('admin_enqueue_scripts', 'kopa_widget_admin_enqueue_scripts');

function kopa_widget_admin_enqueue_scripts($hook) {
    if ('widgets.php' === $hook) {
        $dir = get_template_directory_uri() . '/library';
        wp_enqueue_style('kopa_widget_admin', "{$dir}/css/widget.css");
        wp_enqueue_script('kopa_widget_admin', "{$dir}/js/widget.js", array('jquery'));
    }
}

function kopa_widget_article_build_query($query_args = array()) {
    $args = array(
        'post_type' => array('post'),
        'posts_per_page' => $query_args['number_of_article']
    );

    $tax_query = array();

    if ($query_args['categories']) {
        $tax_query[] = array(
            'taxonomy' => 'category',
            'field' => 'id',
            'terms' => $query_args['categories']
        );
    }
    if ($query_args['tags']) {
        $tax_query[] = array(
            'taxonomy' => 'post_tag',
            'field' => 'id',
            'terms' => $query_args['tags']
        );
    }
    if ($query_args['relation'] && count($tax_query) == 2) {
        $tax_query['relation'] = $query_args['relation'];
    }

    if ($tax_query) {
        $args['tax_query'] = $tax_query;
    }

    switch ($query_args['orderby']) {
        case 'popular':
            $args['meta_key'] = 'kopa_' . kopa_get_domain() . '_total_view';
            $args['orderby'] = 'meta_value_num';
            break;
        case 'most_comment':
            $args['orderby'] = 'comment_count';
            break;
        case 'random':
            $args['orderby'] = 'rand';
            break;
        default:
            $args['orderby'] = 'date';
            break;
    }
    if (isset($query_args['post__not_in']) && $query_args['post__not_in']) {
        $args['post__not_in'] = $query_args['post__not_in'];
    }
    return new WP_Query($args);
}

function kopa_widget_posttype_build_query( $query_args = array() ) {
    $default_query_args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'post__not_in'   => array(),
        'ignore_sticky_posts' => 1,
        'categories'     => array(),
        'tags'           => array(),
        'relation'       => 'OR',
        'orderby'        => 'latest',
        'cat_name'       => 'category',
        'tag_name'       => 'post_tag'
    );

    $query_args = wp_parse_args( $query_args, $default_query_args );

    $args = array(
        'post_type'           => $query_args['post_type'],
        'posts_per_page'      => $query_args['posts_per_page'],
        'post__not_in'        => $query_args['post__not_in'],
        'ignore_sticky_posts' => $query_args['ignore_sticky_posts']
    );

    $tax_query = array();

    if ( $query_args['categories'] ) {
        $tax_query[] = array(
            'taxonomy' => $query_args['cat_name'],
            'field'    => 'id',
            'terms'    => $query_args['categories']
        );
    }
    if ( $query_args['tags'] ) {
        $tax_query[] = array(
            'taxonomy' => $query_args['tag_name'],
            'field'    => 'id',
            'terms'    => $query_args['tags']
        );
    }
    if ( $query_args['relation'] && count( $tax_query ) == 2 ) {
        $tax_query['relation'] = $query_args['relation'];
    }

    if ( $tax_query ) {
        $args['tax_query'] = $tax_query;
    }

    switch ( $query_args['orderby'] ) {
    case 'popular':
        $args['meta_key'] = 'kopa_' . kopa_get_domain() . '_total_view';
        $args['orderby'] = 'meta_value_num';
        break;
    case 'most_comment':
        $args['orderby'] = 'comment_count';
        break;
    case 'random':
        $args['orderby'] = 'rand';
        break;
    default:
        $args['orderby'] = 'date';
        break;
    }
	
	if ( isset($query_args['date_query']) && $query_args['date_query'] ){
        global $wp_version;
        $timestamp =  $query_args['date_query'];
        if (version_compare($wp_version, '3.7.0', '>=')) {
            if (isset($timestamp) && !empty($timestamp)) {
                $y = date('Y', strtotime($timestamp));
                $m = date('m', strtotime($timestamp));
                $d = date('d', strtotime($timestamp));
                $args['date_query'] = array(
                    array(
                        'after' => array(
                            'year' => (int) $y,
                            'month' => (int) $m,
                            'day' => (int) $d
                        )
                    )
                );
            }
        }
    }
	
    return new WP_Query( $args );
}


/**
 * Flexslider Widget Class
 * @since FastNews 1.0
 */
class Kopa_Widget_Flexslider extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kp-slider-widget', 'description' => __('A Posts Slider Widget', kopa_get_domain()));
        $control_ops = array('width' => '500', 'height' => 'auto');
        parent::__construct('kopa_widget_flexslider', __('Kopa Flexslider', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$query_args = array();
        $query_args['categories'] = empty($instance['categories']) ? array() : $instance['categories'] ;
        $query_args['relation'] = isset($instance['relation']) ? $instance['relation'] : 'OR' ;
        $query_args['tags'] = empty($instance['tags']) ? array() : $instance['tags'] ;
        $query_args['posts_per_page'] = isset($instance['number_of_article']) ? $instance['number_of_article'] : -1 ;
        $query_args['orderby'] = isset($instance['orderby']) ? $instance['orderby'] : 'latest' ;
		if (isset($instance['kopa_timestamp'])){
			$query_args['date_query'] = $instance['kopa_timestamp'];
		}
        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $posts = kopa_widget_posttype_build_query( $query_args );

        if ( $posts->have_posts() ) { ?>
        <div class="loading flexslider home-slider" data-animation="<?php echo isset($instance['animation']) ? $instance['animation'] : 'slide' ; ?>" data-direction="<?php echo isset($instance['direction']) ? $instance['direction'] : 'horizontal' ; ?>" data-slideshow_speed="<?php echo isset($instance['slideshow_speed']) ? $instance['slideshow_speed'] : 7000 ; ?>" data-animation_speed="<?php echo isset($instance['animation_speed']) ? $instance['animation_speed'] : 600; ?>" data-autoplay="<?php echo isset($instance['is_auto_play']) ? $instance['is_auto_play'] : 'true'; ?>">
            <ul class="slides">
            <?php while ( $posts->have_posts() ) { $posts->the_post(); ?>
                <li>
                    <article>
                        <?php if(has_post_thumbnail()){ ?>
                        <a href="<?php the_permalink(); ?>"><img src="<?php echo kopa_get_image_src(get_the_ID(), 'flexslider-image-size'); ?>" alt="<?php echo get_the_title(); ?>"></a>
                        <?php } ?>
                        <div class="flex-caption">
                            <header>
                                <span class="entry-categories"><?php _e( 'in:', kopa_get_domain() ); ?> <?php the_category( ', ' ); ?></span>
                                <span class="entry-met">&nbsp;|&nbsp;</span>
                                <span class="entry-date"><?php the_time( get_option( 'date_format' ) ); ?></span>
                            </header>
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <?php the_excerpt(); ?>
                        </div>
                        <!-- flex-caption -->
                    </article>
                </li>
            <?php } // endwhile ?>
            </ul>
            <!-- slides -->
        </div>
        <?php
        } // endif $posts->have_posts()

        wp_reset_postdata();

        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'             => '',
            'categories'        => array(),
            'relation'          => 'OR',
            'tags'              => array(),
            'number_of_article' => 10,
            'orderby'           => 'latest',
            'animation'         => 'slide',
            'direction'         => 'horizontal',
            'slideshow_speed'   => '7000',
            'animation_speed'   => '600',
            'is_auto_play'      => 'true',
			'kopa_timestamp' => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = (int) $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];
        $form['animation'] = $instance['animation'];
        $form['direction'] = $instance['direction'];
        $form['slideshow_speed'] = (int) $instance['slideshow_speed'];
        $form['animation_speed'] = (int) $instance['animation_speed'];
        $form['is_auto_play'] = $instance['is_auto_play'];
		$form['kopa_timestamp'] = $instance['kopa_timestamp'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <div class="kopa-one-half">
            <p>
                <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                    <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                    <?php
                    $categories = get_categories();
                    foreach ($categories as $category) {
                        printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>

            </p>
            <p>
                <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                    <?php
                    $relation = array(
                        'AND' => __('And', kopa_get_domain()),
                        'OR' => __('Or', kopa_get_domain())
                    );
                    foreach ($relation as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                    <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                    <?php
                    $tags = get_tags();
                    foreach ($tags as $tag) {
                        printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, $form['tags'])) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
                <input class="widefat" type="number" min="2" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                    <?php
                    $orderby = array(
                        'latest' => __('Latest', kopa_get_domain()),
                        'popular' => __('Popular by View Count', kopa_get_domain()),
                        'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                        'random' => __('Random', kopa_get_domain()),
                    );
                    foreach ($orderby as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
			<?php 
			kopa_print_timeago($this->get_field_id('kopa_timestamp'), $this->get_field_name('kopa_timestamp'), $form['kopa_timestamp']);?>

        </div>
        <div class="kopa-one-half last">
            <p>
                <label for="<?php echo $this->get_field_id('animation'); ?>"><?php _e('Animation:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('animation'); ?>" name="<?php echo $this->get_field_name('animation'); ?>" autocomplete="off">
                    <?php
                    $animation = array(
                        'slide' => __('Slide', kopa_get_domain()),
                        'fade'  => __('Fade', kopa_get_domain()),
                    );
                    foreach ($animation as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['animation']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('direction'); ?>"><?php _e('Direction:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('direction'); ?>" name="<?php echo $this->get_field_name('direction'); ?>" autocomplete="off">
                    <?php
                    $direction = array(
                        'horizontal' => __('Horizontal', kopa_get_domain()),
                        'vertical'   => __('Vertical', kopa_get_domain()),
                    );
                    foreach ($direction as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['direction']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('slideshow_speed'); ?>"><?php _e('Speed of the slideshow cycling:', kopa_get_domain()); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id('slideshow_speed'); ?>" name="<?php echo $this->get_field_name('slideshow_speed'); ?>" type="number" value="<?php echo $form['slideshow_speed']; ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('animation_speed'); ?>"><?php _e('Speed of animations:', kopa_get_domain()); ?></label>                
                <input class="widefat" id="<?php echo $this->get_field_id('animation_speed'); ?>" name="<?php echo $this->get_field_name('animation_speed'); ?>" type="number" value="<?php echo $form['animation_speed']; ?>" />
            </p>

            <p>
                <input class="" id="<?php echo $this->get_field_id('is_auto_play'); ?>" name="<?php echo $this->get_field_name('is_auto_play'); ?>" type="checkbox" value="true" <?php echo ('true' === $form['is_auto_play']) ? 'checked="checked"' : ''; ?> />
                <label for="<?php echo $this->get_field_id('is_auto_play'); ?>"><?php _e('Auto Play', kopa_get_domain()); ?></label>                                
            </p>
        </div>
        <div class="kopa-clear"></div>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        if ( 0 >= $instance['number_of_article'] ) {
            $instance['number_of_article'] = 10;
        }
        $instance['orderby'] = $new_instance['orderby'];
        $instance['animation'] = $new_instance['animation'];
        $instance['direction'] = $new_instance['direction'];
        $instance['slideshow_speed'] = (int) $new_instance['slideshow_speed'];
        $instance['animation_speed'] = (int) $new_instance['animation_speed'];
        $instance['is_auto_play'] = isset($new_instance['is_auto_play']) ? $new_instance['is_auto_play'] : 'false';
		$instance['kopa_timestamp'] = $new_instance['kopa_timestamp'];

        return $instance;
    }
} // end Kopa_Widget_Flexslider

/**
 * Articles List Widget Class
 * @since FastNews 1.0
 */

/**
 * Small Articles List Widget Class
 * @since FastNews 1.0
 */
class Kopa_Widget_Small_Articles_List extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kp-small-list-widget', 'description' => __('Display Latest Articles Widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_small_articles_list', __('Kopa Small Articles List', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args = array();
		$query_args['categories'] = empty($instance['categories']) ? array() : $instance['categories'] ;
		$query_args['relation'] = isset($instance['relation']) ? $instance['relation'] : 'OR' ;
        $query_args['tags'] = empty($instance['tags']) ? array() : $instance['tags'] ;
        $query_args['posts_per_page'] = isset($instance['number_of_article']) ? $instance['number_of_article'] : -1 ;
        $query_args['orderby'] = isset($instance['orderby']) ? $instance['orderby'] : 'latest' ;
		if (isset($instance['kopa_timestamp'])){
			$query_args['date_query'] = $instance['kopa_timestamp'];
		}
        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $posts = kopa_widget_posttype_build_query( $query_args );
        ?>

        <?php if ( $posts->have_posts() ) {
            $post_index = 1; ?>

            <ul class="clearfix">
            
            <?php while ( $posts->have_posts() ) { 
                $posts->the_post();
                ?>

                <li>
                    <article class="entry-item">
                        <?php if(has_post_thumbnail()){ ?>
                        <div class="entry-thumb"><a href="<?php the_permalink(); ?>"><img src="<?php echo kopa_get_image_src(get_the_ID(), 'article-list-sm-image-size') ?>" alt="<?php echo get_the_title(); ?>"></a></div>
                        <?php } ?>
                        <div class="entry-content">
                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                </li>

                <?php
                if ( $post_index % 2 == 0 ) {
                    echo '<div class="clear"></div>';
                }

                // increases post index by 1
                $post_index++;
                ?>

            <?php } // endwhile ?>

            </ul>
            
        <?php } // endif ?>

        <?php
        wp_reset_postdata();

        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'             => '',
            'categories'        => array(),
            'relation'          => 'OR',
            'tags'              => array(),
            'number_of_article' => 3,
            'orderby'           => 'latest',
			'kopa_timestamp' => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];
		$form['kopa_timestamp'] = $instance['kopa_timestamp'];

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, $form['tags'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="1" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'latest' => __('Latest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
		<?php 
		kopa_print_timeago($this->get_field_id('kopa_timestamp'), $this->get_field_name('kopa_timestamp'), $form['kopa_timestamp']);?>

        
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        // validate number of article
        if ( 0 >= $instance['number_of_article'] ) {
            $instance['number_of_article'] = 3;
        }
        $instance['orderby'] = $new_instance['orderby'];
		$instance['kopa_timestamp'] = $new_instance['kopa_timestamp'];
        return $instance;
    }
}

/**
 * Articles Carousel Widget Class
 * @since FastNews 1.0
 */
class Kopa_Widget_Articles_Carousel extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => '', 'description' => __('Display Articles Carousel Widget', kopa_get_domain()));
        $control_ops = array('width' => '500', 'height' => 'auto');
        parent::__construct('kopa_widget_articles_carousel', __('Kopa Articles Carousel', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args = array();
		$query_args['categories'] = empty($instance['categories']) ? array() : $instance['categories'] ;
		$query_args['relation'] = isset($instance['relation']) ? $instance['relation'] : 'OR' ;
        $query_args['tags'] = empty($instance['tags']) ? array() : $instance['tags'] ;
        $query_args['posts_per_page'] = isset($instance['number_of_article']) ? $instance['number_of_article'] : -1 ;
        $query_args['orderby'] = isset($instance['orderby']) ? $instance['orderby'] : 'latest' ;
		if (isset($instance['kopa_timestamp'])){
			$query_args['date_query'] = $instance['kopa_timestamp'];
		}
        if(!isset($instance['excerpt'])){
		$instance['excerpt'] = 'false';
		}
		if($instance['excerpt']=='false'){
            echo '<div class="widget kp-full-featured-news-widget">';
        } else{
            echo '<div class="widget kp-featured-news-widget">';
        }
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }
        
        $posts = kopa_widget_posttype_build_query( $query_args );

        if ( $posts->have_posts() ) { ?>
        
            <div class="list-carousel responsive">
                <ul class="kopa-featured-news-carousel clearfix" data-next-id="#<?php echo $this->get_field_id( 'next-1' ); ?>" data-prev-id="#<?php echo $this->get_field_id( 'prev-1' ); ?>" data-scroll-items="<?php echo isset($instance['scroll_items']) ? $instance['scroll_items'] : 1; ?>" data-columns="<?php echo isset($instance['columns']) ? $instance['columns'] : 3 ; ?>" data-autoplay="<?php echo isset($instance['autoplay']) ? $instance['autoplay'] : 'false' ; ?>" data-duration="<?php echo isset($instance['duration']) ? $instance['duration'] : 500 ; ?>" data-timeout-duration="<?php echo isset($instance['timeout_duration']) ? $instance['timeout_duration'] : 2500 ; ?>">
                <?php while ( $posts->have_posts() ) { 
                    $posts->the_post(); ?>
                    <li style="width: 160px;">
                        <article class="entry-item clearfix">
                            <?php if(has_post_thumbnail()){?>
                            <div class="entry-thumb">
                                <a href="<?php the_permalink(); ?>"><img src="<?php echo kopa_get_image_src(get_the_ID(), 'article-carousel-image-size') ?>" alt="<?php echo get_the_title(); ?>"></a>
                            </div>
                            <?php } ?>
                            <div class="entry-content">
                                <header>
                                    <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    <span class="entry-date"><a href="<?php the_permalink() ?>">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></a></span>
                                </header>
                                <?php the_excerpt(); ?>
                            </div><!--entry-content-->
                        </article><!--entry-item-->
                    </li>
                <?php } // endwhile ?>  
                </ul><!--kopa-featured-news-carousel-->
                <div class="clearfix"></div>
                <div class="carousel-nav clearfix">
                    <a id="<?php echo $this->get_field_id( 'prev-1' ); ?>" class="carousel-prev" href="#" ></a>
                    <a id="<?php echo $this->get_field_id( 'next-1' ); ?>" class="carousel-next" href="#" ></a>
                </div>
            </div><!--list-carousel-->
            <?php
        } // endif $posts->have_posts()

        wp_reset_postdata();
        
        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'             => '',
            'categories'        => array(),
            'relation'          => 'OR',
            'tags'              => array(),
            'number_of_article' => 8,
            'orderby'           => 'latest',
            'scroll_items'      => 1,
            'columns'           => 3,
            'autoplay'          => 'false',
            'excerpt'           => 'false',
            'duration'          => 500,
            'timeout_duration'  => 2500,
			'kopa_timestamp' => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = (int) $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];
        $form['scroll_items'] = $instance['scroll_items'];
        $form['columns'] = $instance['columns'];
        $form['autoplay'] = $instance['autoplay'];
        $form['excerpt'] = $instance['excerpt'];
        $form['duration'] = $instance['duration'];
        $form['timeout_duration'] = $instance['timeout_duration'];
		$form['kopa_timestamp'] = $instance['kopa_timestamp'];
        ?>
        <div class="kopa-one-half">
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, $form['tags'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="2" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'latest' => __('Latest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain())
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
		<?php 
		kopa_print_timeago($this->get_field_id('kopa_timestamp'), $this->get_field_name('kopa_timestamp'), $form['kopa_timestamp']);?>

        </div>

        <div class="kopa-one-half last">
        <p>
            <label for="<?php echo $this->get_field_id('scroll_items'); ?>"><?php _e('Scroll Items:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="1" id="<?php echo $this->get_field_id('scroll_items'); ?>" name="<?php echo $this->get_field_name('scroll_items'); ?>" value="<?php echo esc_attr( $form['scroll_items'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e('Number of Columns:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id( 'columns' ); ?>" name="<?php echo $this->get_field_name( 'columns' ); ?>">
                <?php $columns = array( 3, 4, 5, 6 );
                foreach ( $columns as $value ) { ?>
                    <option value="<?php echo $value; ?>" <?php selected( $form['columns'], $value ); ?>><?php echo $value; ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <input class="" id="<?php echo $this->get_field_id('autoplay'); ?>" name="<?php echo $this->get_field_name('autoplay'); ?>" type="checkbox" value="true" <?php checked( $form['autoplay'], 'true' ); ?>>
            <label for="<?php echo $this->get_field_id('autoplay'); ?>"><?php _e('Auto Play', kopa_get_domain()); ?></label>                                
        </p>
        <p>
            <input class="" id="<?php echo $this->get_field_id('excerpt'); ?>" name="<?php echo $this->get_field_name('excerpt'); ?>" type="checkbox" value="true" <?php checked( $form['excerpt'], 'true' ); ?>>
            <label for="<?php echo $this->get_field_id('excerpt'); ?>"><?php _e('Show excerpt', kopa_get_domain()); ?></label>                                
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('duration'); ?>"><?php _e('Duration of the transition (milliseconds):', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="100" step="100" id="<?php echo $this->get_field_id('duration'); ?>" name="<?php echo $this->get_field_name('duration'); ?>" value="<?php echo esc_attr( $form['duration'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('timeout_duration'); ?>"><?php _e('The amount of milliseconds the carousel will pause:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="100" step="100" id="<?php echo $this->get_field_id('timeout_duration'); ?>" name="<?php echo $this->get_field_name('timeout_duration'); ?>" value="<?php echo esc_attr( $form['timeout_duration'] ); ?>">
        </p>
        </div>
        <div class="kopa-clear"></div>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        if ( 0 >= $instance['number_of_article'] ) {
            $instance['number_of_article'] = 8;
        }
        $instance['orderby'] = $new_instance['orderby'];
        $instance['scroll_items'] = (int) $new_instance['scroll_items'];
        if ( 0 >= $instance['scroll_items'] ) {
            $instance['scroll_items'] = 1;
        }

        $instance['columns'] = (int) $new_instance['columns'] ? (int) $new_instance['columns'] : 3;

        $instance['autoplay'] = isset( $new_instance['autoplay'] ) ? $new_instance['autoplay'] : 'false';
        $instance['excerpt'] = isset( $new_instance['excerpt'] ) ? $new_instance['excerpt'] : 'false';

        $instance['duration'] = (int) $new_instance['duration'] ? (int) $new_instance['duration'] : 500;
        $instance['timeout_duration'] = (int) $new_instance['timeout_duration'] ? (int) $new_instance['timeout_duration'] : 2500;
		$instance['kopa_timestamp'] = $new_instance['kopa_timestamp'];
        return $instance;
    }
}

/**
 * Entry List Widget Class
 * @since FastNews 1.0
 */

/**
 * Entry List Widget Class
 * Display posts in widget area 5, 6, 7, 8, 9
 * @since FastNews 1.0
 */
class Kopa_Widget_Entry_List_2 extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kp-entry-list-widget', 'description' => __('Display Featured Articles Widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_entry_list_2', __('Kopa Entry List 2', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args = array();
		$query_args['categories'] = empty($instance['categories']) ? array() : $instance['categories'] ;
		$query_args['relation'] = isset($instance['relation']) ? $instance['relation'] : 'OR' ;
        $query_args['tags'] = empty($instance['tags']) ? array() : $instance['tags'] ;
        $query_args['posts_per_page'] = isset($instance['number_of_article']) ? $instance['number_of_article'] : -1 ;
        $query_args['orderby'] = isset($instance['orderby']) ? $instance['orderby'] : 'latest' ;
		if (isset($instance['kopa_timestamp'])){
			$query_args['date_query'] = $instance['kopa_timestamp'];
		}
        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $posts = kopa_widget_posttype_build_query( $query_args );
        
        if ( $posts->have_posts() ) { ?>
            
            <?php while ( $posts->have_posts() ) { 
                $posts->the_post(); ?>

                <div class="widget kp-article-list-widget clearfix">
                    <article class="entry-item clearfix">
                        <?php if(has_post_thumbnail()){ ?>
                        <div class="entry-thumb">
                            <a href="<?php the_permalink(); ?>"><img src="<?php echo kopa_get_image_src(get_the_ID(), 'article-list-image-size') ?>" alt="<?php echo get_the_title(); ?>"></a>
                        </div>
                        <?php } ?>
                        <div class="entry-content">
                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                </div>

            <?php } // endwhile ?>

        <?php } // endif ?>

        <?php
        wp_reset_postdata();

        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'             => '',
            'categories'        => array(),
            'relation'          => 'OR',
            'tags'              => array(),
            'number_of_article' => 1,
            'orderby'           => 'latest',
			'kopa_timestamp' => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];
		$form['kopa_timestamp'] = $instance['kopa_timestamp'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, $form['tags'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="1" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'latest' => __('Latest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <?php 
		kopa_print_timeago($this->get_field_id('kopa_timestamp'), $this->get_field_name('kopa_timestamp'), $form['kopa_timestamp']);?>

        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        // validate number of article
        if ( 0 >= $instance['number_of_article'] ) {
            $instance['number_of_article'] = 4;
        }
        $instance['orderby'] = $new_instance['orderby'];
		$instance['kopa_timestamp'] = $new_instance['kopa_timestamp'];
        return $instance;
    }
}

/**
 * Advertising Widget Class
 * @since FastNews 1.0
 */

/**
 * Kopa Gallery Widget Class
 * @since FastNews 1.0
 */

/**
 * Mailchimp Subscribe Widget Class
 * @since Forceful 1.0
 */

/**
 * FeedBurner Subscribe Widget Class
 * @since Forceful 1.0
 */
