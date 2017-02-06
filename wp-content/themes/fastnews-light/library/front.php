<?php
add_action('after_setup_theme', 'kopa_front_after_setup_theme');

function kopa_front_after_setup_theme() {
    add_theme_support('post-formats', array('gallery', 'audio', 'video'));
    add_theme_support('post-thumbnails');
    add_theme_support('loop-pagination');
    add_theme_support('automatic-feed-links');
    add_theme_support('editor_style');
    add_editor_style('editor-style.css');

    global $content_width;
    if ( ! isset( $content_width ) ) {
        $content_width = 1062;
    }

    register_nav_menus(array(
        'main-nav'   => __( 'Main Menu', kopa_get_domain() ),
        'footer-nav' => __( 'Footer Menu', kopa_get_domain() )
    ));

    if (!is_admin()) {
        add_action('wp_enqueue_scripts', 'kopa_front_enqueue_scripts');
        add_action('wp_footer', 'kopa_footer');
        add_action('wp_head', 'kopa_head');
        add_filter('widget_text', 'do_shortcode');
        add_filter('the_category', 'kopa_the_category');
        add_filter('get_the_excerpt', 'kopa_get_the_excerpt');
        
        add_filter('post_class', 'kopa_post_class');
        add_filter('body_class', 'kopa_body_class');
        // add_filter('wp_nav_menu_items', 'kopa_add_icon_home_menu', 10, 2);
        // add_filter('comment_reply_link', 'kopa_comment_reply_link');
        // add_filter('edit_comment_link', 'kopa_edit_comment_link');
        // add_filter('wp_tag_cloud', 'kopa_tag_cloud');
        add_filter('excerpt_length', 'kopa_custom_excerpt_length');
        add_filter('excerpt_more', 'kopa_custom_excerpt_more');
    } else {
        add_action('show_user_profile', 'kopa_edit_user_profile');
        add_action('edit_user_profile', 'kopa_edit_user_profile');
        add_action('personal_options_update', 'kopa_edit_user_profile_update');
        add_action('edit_user_profile_update', 'kopa_edit_user_profile_update');
        
    }

    
    
    $sizes = array(
        'flexslider-image-size'   => array(500, 500, TRUE, __('Flexslider Post Image (Kopatheme)', kopa_get_domain())),
        'article-list-image-size' => array(300, 277, TRUE, __('Article List Post Image (Kopatheme)', kopa_get_domain())),
        'article-list-sm-image-size' => array(120, 120, TRUE, __('Article List Small Post Image (Kopatheme)', kopa_get_domain())),
        'article-carousel-image-size' => array(354, 354, TRUE, __('Article Carousel Post Image (Kopatheme)', kopa_get_domain())),
        'entry-list-image-size' => array(227, 182, TRUE, __('Entry List Thumbnail Image (Kopatheme)', kopa_get_domain())),
        'blog-image-size' => array(680, 419, TRUE, __('Blog Image Size (Kopatheme)', kopa_get_domain())),
        
    );
    
    apply_filters('kopa_get_image_sizes', $sizes);

    foreach ($sizes as $slug => $details) {
        add_image_size($slug, $details[0], $details[1], $details[2]);
    }
    
}

function kopa_tag_cloud($out) {

    $matches = array();
    $pattern = '/<a[^>]*?>([\\s\\S]*?)<\/a>/';
    preg_match_all($pattern, $out, $matches);

    $htmls = $matches[0];
    $texts = $matches[1];
    $new_html = '';
    for ($index = 0; $index < count($htmls); $index++) {

        $new_html.= preg_replace('#(<a.*?(href=\'.*?\').*?>).*?(</a>)#', '<a '.'$2'.'>' . $texts[$index] . '$3' . ' ', $htmls[$index]);
    }

    return $new_html;
}

function kopa_comment_reply_link($link) {
    return str_replace('comment-reply-link', 'comment-reply-link small-button green-button', $link);
}

function kopa_edit_comment_link($link) {
    return str_replace('comment-edit-link', 'comment-edit-link small-button green-button', $link);
}

function kopa_post_class($classes) {
    if (is_single()) {
        $classes[] = 'entry-box';
        $classes[] = 'clearfix';      
    }
    return $classes;
}

function kopa_body_class($classes) {
    $template_setting = kopa_get_template_setting();

    if (is_front_page()) {
        $classes[] = 'home-page';
    } else {
        $classes[] = 'sub-page';
    }

    switch ($template_setting['layout_id']) {
        case 'blog':
            $classes[] = 'kp-categories-1';
            break;
        case 'blog-2':
            $classes[] = 'kp-categories-2';
            break;
        case 'blog-3':
            $classes[] = 'kp-categories-3';
            break;
        case 'blog-4':
            $classes[] = 'kp-categories-4';
            break;
        case 'blog-5':
            $classes[] = 'kp-categories-5';
            break;
        case 'single-right-sidebar':
            $queried_object = get_queried_object();
            if ( '' == get_post_format( $queried_object->ID ) ) {
                $classes[] = 'kp-single-standard';
            } elseif ( 'gallery' == get_post_format( $queried_object->ID ) ) {
                $classes[] = 'kp-single-gallery';
            } elseif ( 'video' == get_post_format( $queried_object->ID ) ) {
                $classes[] = 'background-red';
            }
            

            $classes[] = 'kp-single-right-sidebar';
            break;
        case 'page-right-sidebar':
            $classes[] = 'kp-page-right-sidebar';
            break;
        case 'page-fullwidth':
            $classes[] = 'kp-elements-page';
            break;
        case 'page-fullwidth-widgets':
            $classes[] = 'kp-gallery-page';
            break;
        case 'error-404':
            $classes[] = 'kp-error-page';
            break;
    }

    return $classes;
}

function kopa_footer() {
    wp_nonce_field('kopa_set_view_count', 'kopa_set_view_count_wpnonce', false);

    
}

function kopa_front_enqueue_scripts() {
    if (!is_admin()) {
        global $wp_styles, $is_IE;

        $dir = get_template_directory_uri();

        /* STYLESHEETs */
        wp_enqueue_style('kopa-bootstrap', $dir . '/css/bootstrap.css', array(), NULL, 'screen');
        wp_enqueue_style('kopa-fontawesome', $dir . '/css/font-awesome.css', array(), NULL);
        wp_enqueue_style('kopa-superfish', $dir . '/css/superfish.css', array(), NULL, 'screen');
        wp_enqueue_style('kopa-flexlisder', $dir . '/css/flexslider.css', array(), NULL, 'screen');
        wp_enqueue_style('kopa-prettyPhoto', $dir . '/css/prettyPhoto.css', array(), NULL, 'screen');
        wp_enqueue_style('kopa-style', get_stylesheet_uri(), array(), NULL);
        wp_enqueue_style('kopa-extra-style', $dir . '/css/extra.css', array(), NULL);
        wp_enqueue_style('kopa-responsive', $dir . '/css/responsive.css', array(), NULL);

        if ( $is_IE ) {
            wp_register_style('kopa-ie', $dir . '/css/ie.css', array(), NULL);
            $wp_styles->add_data('kopa-ie', 'conditional', 'lt IE 9');
            wp_enqueue_style('kopa-ie');
        }

        /* JAVASCRIPTs */
        wp_enqueue_script('kopa-google-api', '//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js', array(), NULL, TRUE);
        


        /*======================================================
         *================THEME OPTIONS CUSTOM FONTS============
         *======================================================*/
        $google_fonts = kopa_get_google_font_array();
        $current_heading_font = get_option('kopa_theme_options_heading_font_family');
        $current_content_font = get_option('kopa_theme_options_content_font_family');
        $current_main_nav_font = get_option('kopa_theme_options_main_nav_font_family');
        $current_wdg_sidebar_font = get_option('kopa_theme_options_wdg_sidebar_font_family');
        $load_font_array = array();

        // heading font family
        if ($current_heading_font && ! in_array($current_heading_font, $load_font_array)) {
            array_push($load_font_array, $current_heading_font);
        }

        // content font family
        if ($current_content_font && ! in_array($current_content_font, $load_font_array)) {
            array_push($load_font_array, $current_content_font);
        }

        // main menu font family
        if ($current_main_nav_font && ! in_array($current_main_nav_font, $load_font_array)) {
            array_push($load_font_array, $current_main_nav_font);
        }

        // widget title font family  
        if ($current_wdg_sidebar_font && ! in_array($current_wdg_sidebar_font, $load_font_array)) {
            array_push($load_font_array, $current_wdg_sidebar_font);
        }

        foreach ($load_font_array as $current_font) {
            if ($current_font != '') {
                $google_font_family = $google_fonts[$current_font]['family'];
                $temp_font_name = str_replace(' ', '+', $google_font_family);
                $font_url = '//fonts.googleapis.com/css?family=' . $temp_font_name . ':300,300italic,400,400italic,700,700italic&subset=latin';
                wp_enqueue_style('Google-Font-' . $temp_font_name, $font_url);
            }
        }
        /*======================================================
         *==============END THEME OPTIONS CUSTOM FONTS==========
         *======================================================*/

        
        wp_localize_script('jquery', 'kopa_front_variable', kopa_front_localize_script());
        wp_enqueue_script('kopa-superfish-js', $dir . '/js/superfish.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-retina', $dir . '/js/retina.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-bootstrap-js', $dir . '/js/bootstrap.min.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-carouFredSel', $dir . '/js/jquery.carouFredSel-6.2.1-packed.js', array('jquery'), '6.2.1', TRUE);
        wp_enqueue_script('kopa-flexlisder-js', $dir . '/js/jquery.flexslider-min.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-prettyPhoto-js', $dir . '/js/jquery.prettyPhoto.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-modernizr-transitions', $dir . '/js/modernizr-transitions.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-imagesloaded', $dir . '/js/imagesloaded.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-masonry', $dir . '/js/masonry.pkgd.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-validate', $dir . '/js/jquery.validate.min.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('jquery-form', null, array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-set-view-count', $dir . '/js/set-view-count.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-custom-js', $dir . '/js/custom.js', array('jquery'), NULL, TRUE);

        // send localization to frontend
        wp_localize_script('kopa-custom-js', 'kopa_custom_front_localization', kopa_custom_front_localization());

        if (is_single() || is_page()) {
            wp_enqueue_script('comment-reply');
        }
    }
}

function kopa_front_localize_script() {
    $kopa_variable = array(
        'ajax' => array(
            'url' => admin_url('admin-ajax.php')
        ),
        'template' => array(
            'post_id' => (is_singular()) ? get_queried_object_id() : 0
        )
    );
    return $kopa_variable;
}

/**
 * Send the translated texts to frontend
 * @package Circle
 * @since Circle 1.12
 */
function kopa_custom_front_localization() {
    $front_localization = array(
        'validate' => array(
            'form' => array(
                'submit'  => __('Submit', kopa_get_domain()),
                'sending' => __('Sending...', kopa_get_domain())
            ),
            'name' => array(
                'required'  => __('Please enter your name.', kopa_get_domain()),
                'minlength' => __('At least {0} characters required.', kopa_get_domain())
            ),
            'email' => array(
                'required' => __('Please enter your email.', kopa_get_domain()),
                'email'    => __('Please enter a valid email.', kopa_get_domain())
            ),
            'url' => array(
                'required' => __('Please enter your url.', kopa_get_domain()),
                'url'      => __('Please enter a valid url.', kopa_get_domain())
            ),
            'message' => array(
                'required'  => __('Please enter a message.', kopa_get_domain()),
                'minlength' => __('At least {0} characters required.', kopa_get_domain())
            )
        )
    );

    return $front_localization;
}

function kopa_the_category($thelist) {
    return $thelist;
}

/* FUNCTION */

function kopa_print_page_title() {
    global $page, $paged;
    wp_title('|', TRUE, 'right');
    bloginfo('name');
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && ( is_home() || is_front_page() ))
        echo " | $site_description";
    if ($paged >= 2 || $page >= 2)
        echo ' | ' . sprintf(__('Page %s', kopa_get_domain()), max($paged, $page));
}


function kopa_set_view_count($post_id) {
    $new_view_count = 0;
    $meta_key = 'kopa_' . kopa_get_domain() . '_total_view';

    $current_views = (int) get_post_meta($post_id, $meta_key, true);

    if ($current_views) {
        $new_view_count = $current_views + 1;
        update_post_meta($post_id, $meta_key, $new_view_count);
    } else {
        $new_view_count = 1;
        add_post_meta($post_id, $meta_key, $new_view_count);
    }
    return $new_view_count;
}

function kopa_get_view_count($post_id) {
    $key = 'kopa_' . kopa_get_domain() . '_total_view';
    return kopa_get_post_meta($post_id, $key, true, 'Int');
}

/**
 * Template tag: print breadcrumb
 */
function kopa_breadcrumb() {
    // get show/hide option
    $kopa_breadcrumb_status = get_option('kopa_theme_options_breadcrumb_status', 'show');

    if ( $kopa_breadcrumb_status != 'show' ) {
        return;
    }

    if (is_main_query()) {
        global $post, $wp_query;

        $prefix = '&nbsp;/&nbsp;';
        $current_class = 'current-page';
        $description = '';
        $breadcrumb_before = '<div class="breadcrumb clearfix">';
        $breadcrumb_after = '</div>';
        $breadcrumb_home = '<a href="' . esc_url(home_url()) . '">' . __('Home', kopa_get_domain()) . '</a>';
        $breadcrumb = '';
        ?>

        <?php
        if (is_home()) {
            $breadcrumb.= $breadcrumb_home;
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, __('Blog', kopa_get_domain()));
        } else if (is_post_type_archive('product') && get_option('woocommerce_shop_page_id')) {
            $breadcrumb.= $breadcrumb_home;
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, get_the_title(get_option('woocommerce_shop_page_id')));
        } else if (is_tag()) {
            $breadcrumb.= $breadcrumb_home;

            $term = get_term(get_queried_object_id(), 'post_tag');
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $term->name);
        } else if (is_category()) {
            $breadcrumb.= $breadcrumb_home;

            $category_id = get_queried_object_id();
            $terms_link = explode(',', substr(get_category_parents(get_queried_object_id(), TRUE, ','), 0, (strlen(',') * -1)));
            $n = count($terms_link);
            if ($n > 1) {
                for ($i = 0; $i < ($n - 1); $i++) {
                    $breadcrumb.= $prefix . $terms_link[$i];
                }
            }
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, get_the_category_by_ID(get_queried_object_id()));

        } else if ( is_tax('product_cat') ) {
            $breadcrumb.= $breadcrumb_home;
            $breadcrumb.= $prefix . '<a href="'.get_page_link( get_option('woocommerce_shop_page_id') ).'">'.get_the_title( get_option('woocommerce_shop_page_id') ).'</a>';
            $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

            $parents = array();
            $parent = $term->parent;
            while ($parent):
                $parents[] = $parent;
                $new_parent = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ));
                $parent = $new_parent->parent;
            endwhile;
            if( ! empty( $parents ) ):
                $parents = array_reverse($parents);
                foreach ($parents as $parent):
                    $item = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ));
                    $breadcrumb .= $prefix . '<a href="' . get_term_link( $item->slug, 'product_cat' ) . '">' . $item->name . '</a>';
                endforeach;
            endif;

            $queried_object = get_queried_object();
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $queried_object->name);
        } else if ( is_tax( 'product_tag' ) ) {
            $breadcrumb.= $breadcrumb_home;
            $breadcrumb.= $prefix . '<a href="'.get_page_link( get_option('woocommerce_shop_page_id') ).'">'.get_the_title( get_option('woocommerce_shop_page_id') ).'</a>';
            $queried_object = get_queried_object();
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $queried_object->name);
        } else if (is_single()) {
            $breadcrumb.= $breadcrumb_home;

            if ( get_post_type() === 'product' ) :

                $breadcrumb .= $prefix . '<a href="'.get_page_link( get_option('woocommerce_shop_page_id') ).'">'.get_the_title( get_option('woocommerce_shop_page_id') ).'</a>';

                if ($terms = get_the_terms( $post->ID, 'product_cat' )) :
                    $term = apply_filters( 'jigoshop_product_cat_breadcrumb_terms', current($terms), $terms);
                    $parents = array();
                    $parent = $term->parent;
                    while ($parent):
                        $parents[] = $parent;
                        $new_parent = get_term_by( 'id', $parent, 'product_cat');
                        $parent = $new_parent->parent;
                    endwhile;
                    if(!empty($parents)):
                        $parents = array_reverse($parents);
                        foreach ($parents as $parent):
                            $item = get_term_by( 'id', $parent, 'product_cat');
                            $breadcrumb .= $prefix . '<a href="' . get_term_link( $item->slug, 'product_cat' ) . '">' . $item->name . '</a>';
                        endforeach;
                    endif;
                    $breadcrumb .= $prefix . '<a href="' . get_term_link( $term->slug, 'product_cat' ) . '">' . $term->name . '</a>';
                endif;

                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, get_the_title());

            else : 

                $categories = get_the_category(get_queried_object_id());
                if ($categories) {
                    foreach ($categories as $category) {
                        $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', get_category_link($category->term_id), $category->name);
                    }
                }

                $post_id = get_queried_object_id();
                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, get_the_title($post_id));

            endif;

        } else if (is_page()) {
            if (!is_front_page()) {
                $post_id = get_queried_object_id();
                $breadcrumb.= $breadcrumb_home;
                $post_ancestors = get_post_ancestors($post);
                if ($post_ancestors) {
                    $post_ancestors = array_reverse($post_ancestors);
                    foreach ($post_ancestors as $crumb)
                        $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', get_permalink($crumb), get_the_title($crumb));
                }
                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, get_the_title(get_queried_object_id()));
            }
        } else if (is_year() || is_month() || is_day()) {
            $breadcrumb.= $breadcrumb_home;

            $date = array('y' => NULL, 'm' => NULL, 'd' => NULL);

            $date['y'] = get_the_time('Y');
            $date['m'] = get_the_time('m');
            $date['d'] = get_the_time('j');

            if (is_year()) {
                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $date['y']);
            }

            if (is_month()) {
                $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', get_year_link($date['y']), $date['y']);
                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, date('F', mktime(0, 0, 0, $date['m'])));
            }

            if (is_day()) {
                $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', get_year_link($date['y']), $date['y']);
                $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', get_month_link($date['y'], $date['m']), date('F', mktime(0, 0, 0, $date['m'])));
                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $date['d']);
            }

        } else if (is_search()) {
            $breadcrumb.= $breadcrumb_home;

            $s = get_search_query();
            $c = $wp_query->found_posts;

            $description = sprintf(__('<span class="%1$s">Your search for "%2$s"', kopa_get_domain()), $current_class, $s);
            $breadcrumb .= $prefix . $description;
        } else if (is_author()) {
            $breadcrumb.= $breadcrumb_home;
            $author_id = get_queried_object_id();
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</a>', $current_class, sprintf(__('Posts created by %1$s', kopa_get_domain()), get_the_author_meta('display_name', $author_id)));
        } else if (is_404()) {
            $breadcrumb.= $breadcrumb_home;
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, __('Error 404', kopa_get_domain()));
        }

        if ($breadcrumb)
            echo apply_filters('kopa_breadcrumb', $breadcrumb_before . $breadcrumb . $breadcrumb_after);
    }
}

function kopa_get_related_articles() {
    if (is_single()) {
        $get_by = get_option('kopa_theme_options_post_related_get_by', 'post_tag');
        if ('hide' != $get_by) {
            $limit = (int) get_option('kopa_theme_options_post_related_limit', 4);
            if ($limit > 0) {
                global $post;
                $taxs = array();
                if ('category' == $get_by) {
                    $cats = get_the_category(($post->ID));
                    if ($cats) {
                        $ids = array();
                        foreach ($cats as $cat) {
                            $ids[] = $cat->term_id;
                        }
                        $taxs [] = array(
                            'taxonomy' => 'category',
                            'field' => 'id',
                            'terms' => $ids
                        );
                    }
                } else {
                    $tags = get_the_tags($post->ID);
                    if ($tags) {
                        $ids = array();
                        foreach ($tags as $tag) {
                            $ids[] = $tag->term_id;
                        }
                        $taxs [] = array(
                            'taxonomy' => 'post_tag',
                            'field' => 'id',
                            'terms' => $ids
                        );
                    }
                }

                if ($taxs) {
                    $related_args = array(
                        'tax_query' => $taxs,
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => $limit
                    );
                    $related_posts = new WP_Query( $related_args );
                    if ( $related_posts->have_posts() ) {
                        $post_index = 1;
                        ?>
                        <div class="kopa-related-post">
                            <h3><?php _e( 'Related Articles', kopa_get_domain() ); ?></h3>
                            <ul class="clearfix">
                                <?php while ( $related_posts->have_posts() ) {
                                    $related_posts->the_post(); ?> 
                                    <li>
                                        <article class="entry-item clearfix">
                                            <?php if ( has_post_thumbnail() ) { ?>
                                                <div class="entry-thumb">
                                                    <a href="<?php the_permalink(); ?>"><img src="<?php echo kopa_get_image_src(get_the_ID(), 'article-list-sm-image-size'); ?>" alt="<?php echo get_the_title(); ?>" ></a>
                                                </div>
                                            <?php } // endif ?>
                                            <div class="entry-content">
                                                <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                                <span class="entry-date"><span class="kopa-minus"></span> <?php the_time( get_option( 'date_format' ) ); ?></span>
                                            </div>
                                        </article>
                                    </li>
                                    <?php 
                                    if ( $post_index % 2 == 0 ) {
                                        echo '<div class="clear"></div>';
                                    }

                                    $post_index++;
                                    ?>
                                <?php } // endwhile ?>
                            </ul>
                        </div><!--kopa-related-post-->
                        <?php
                    } // endif
                    wp_reset_postdata();
                }
            }
        }
    }
}



function kopa_get_about_author() {
    if ('show' == get_option('kopa_theme_options_post_about_author', 'show')) { ?>

        <div class="about-author clearfix">
            <h3><?php _e( 'About author', kopa_get_domain() ); ?></h3>

            <div class="about-author-detail clearfix">
                <a class="avatar-thumb" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_avatar( get_the_author_meta('ID'), 74 ); ?></a>
                <div class="author-content">
                    <header>                              
                        <a class="author-name" href="<?php echo get_author_posts_url( get_the_author_meta('ID') ); ?>"><?php the_author_meta('display_name'); ?></a>
                    </header>
                    <p><?php the_author_meta( 'description' ); ?></p>

                    <?php 
                    $author_facebook_url = get_the_author_meta( 'facebook' );
                    $author_twitter_url  = get_the_author_meta( 'twitter' );
                    $author_feed_url     = get_the_author_meta( 'feedurl' );
                    $author_gplus_url    = get_the_author_meta( 'google-plus' );
                    $author_flickr_url   = get_the_author_meta( 'flickr' );

                    if ( $author_facebook_url || $author_twitter_url || $author_feed_url || $author_gplus_url || $author_flickr_url ) {
                    ?>
                    <ul class="social-link clearfix">
                        <!-- facebook -->
                        <?php if ( ! empty( $author_facebook_url ) ) { ?>
                        <li><a href="<?php echo esc_url( $author_facebook_url ); ?>"><?php echo KopaIcon::getIcon('facebook'); ?></a></li>
                        <?php } // endif ?>

                        <!-- twitter -->
                        <?php if ( ! empty( $author_twitter_url ) ) { ?>
                        <li><a href="<?php echo esc_url( $author_twitter_url ); ?>"><?php echo KopaIcon::getIcon('twitter'); ?></a></li>
                        <?php } // endif ?>
                        
                        <!-- feed -->
                        <?php if ( ! empty( $author_feed_url ) ) { ?>
                        <li><a href="<?php echo esc_url( $author_feed_url ); ?>"><?php echo KopaIcon::getIcon('rss'); ?></a></li>
                        <?php } // endif ?>
                        
                        <!-- google plus -->
                        <?php if ( ! empty( $author_gplus_url ) ) { ?>
                        <li><a href="<?php echo esc_url( $author_gplus_url ); ?>"><?php echo KopaIcon::getIcon('google-plus'); ?></a></li>
                        <?php } // endif ?>
                        
                        <!-- flickr -->
                        <?php if ( ! empty( $author_flickr_url ) ) { ?>
                        <li><a href="<?php echo esc_url( $author_flickr_url ); ?>"><?php echo KopaIcon::getIcon('flickr'); ?></a></li>
                        <?php } // endif ?>
                    </ul>
                    <?php } // endif ?>
                    <!-- social-link -->
                </div><!--author-content-->
            </div>
        </div><!--about-author-->

        <?php
    }
}

function kopa_edit_user_profile($user) {
    ?>   
    <table class="form-table">
        <tr>
            <th><label for="facebook"><?php _e('Facebook', kopa_get_domain()); ?></label></th>
            <td>
                <input type="url" name="facebook" id="facebook" value="<?php echo esc_attr(get_the_author_meta('facebook', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your Facebook URL', kopa_get_domain()); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="twitter"><?php _e('Twitter', kopa_get_domain()); ?></label></th>
            <td>
                <input type="url" name="twitter" id="twitter" value="<?php echo esc_attr(get_the_author_meta('twitter', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your Twitter URL', kopa_get_domain()); ?></span>
            </td>
        </tr>  
        <tr>
            <th><label for="feedurl"><?php _e('Feed URL', kopa_get_domain()); ?></label></th>
            <td>
                <input type="url" name="feedurl" id="feedurl" value="<?php echo esc_attr(get_the_author_meta('feedurl', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your Feed URL', kopa_get_domain()); ?></span>
            </td>
        </tr>      
        <tr>
            <th><label for="google-plus"><?php _e('Google Plus', kopa_get_domain()); ?></label></th>
            <td>
                <input type="url" name="google-plus" id="google-plus" value="<?php echo esc_attr(get_the_author_meta('google-plus', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your Google Plus URL', kopa_get_domain()); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="flickr"><?php _e('Flickr', kopa_get_domain()); ?></label></th>
            <td>
                <input type="url" name="flickr" id="flickr" value="<?php echo esc_attr(get_the_author_meta('flickr', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your Flickr URL', kopa_get_domain()); ?></span>
            </td>
        </tr>
    </table>
    <?php
}

function kopa_edit_user_profile_update($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    update_user_meta($user_id, 'facebook', $_POST['facebook']);
    update_user_meta($user_id, 'twitter', $_POST['twitter']);
    update_user_meta($user_id, 'feedurl', $_POST['feedurl']);
    update_user_meta($user_id, 'google-plus', $_POST['google-plus']);
    update_user_meta($user_id, 'flickr', $_POST['flickr']);
}

function kopa_get_the_excerpt($excerpt) {
    if (is_main_query()) {
        if (is_category() || is_tag()) {
            $limit = get_option('gs_excerpt_max_length', 100);
            if (strlen($excerpt) > $limit) {
                $break_pos = strpos($excerpt, ' ', $limit);
                $visible = substr($excerpt, 0, $break_pos);
                return balanceTags($visible);
            } else {
                return $excerpt;
            }
        } else if (is_search()) {
            $keys = implode('|', explode(' ', get_search_query()));
            return preg_replace('/(' . $keys . ')/iu', '<span class="kopa-search-keyword">\0</span>', $excerpt);
        } else {
            return $excerpt;
        }
    }
}
function kopa_search_title(){           // highlight the post title in search pages
    $title = get_the_title();
    $keys = implode('|', explode(' ', get_search_query()));
    $title = preg_replace('/(' . $keys .')/iu', '<span class="kopa-search-keyword">\0</span>', $title);
    return $title;
}




function kopa_get_template_setting() {
    global $KOPA_SETTING;
    $setting = array();

    if (is_home()) {
        $setting = $KOPA_SETTING['home'];
    } else if (is_post_type_archive('product')) {
        $setting = $KOPA_SETTING['shop'];
    } else if (is_archive()) {
        if (is_category() || is_tag()) {
            $setting = get_option("kopa_category_setting_" . get_queried_object_id(), $KOPA_SETTING['taxonomy']);
        } else if (is_tax('product_cat') || is_tax('product_tag')) {
            $setting = $KOPA_SETTING['shop'];
        } else {
            $setting = get_option("kopa_category_setting_" . get_queried_object_id(), $KOPA_SETTING['archive']);
        }
    } else if (is_singular()) {
        if (is_singular('post')) {
            $setting = get_option("kopa_post_setting_" . get_queried_object_id(), $KOPA_SETTING['post']);
        } else if (is_singular('product')) {
            $setting = $KOPA_SETTING['single-product'];
        } else if (is_page()) {

            $setting = get_option("kopa_page_setting_" . get_queried_object_id());
            if (!$setting) {
                if (is_front_page()) {
                    $setting = $KOPA_SETTING['home'];
                } else {
                    $setting = $KOPA_SETTING['page'];
                }
            }
        } else {
            $setting = $KOPA_SETTING['post'];
        }
    } else if (is_404()) {
        $setting = $KOPA_SETTING['_404'];
    } else if (is_search()) {
        $setting = $KOPA_SETTING['search'];
    } else{
        $setting = $KOPA_SETTING['home'];
    }     
    return $setting;
}

function kopa_content_get_gallery($content, $enable_multi = false) {
    return kopa_content_get_media($content, $enable_multi, array('gallery'));
}

function kopa_content_get_video($content, $enable_multi = false) {
    return kopa_content_get_media($content, $enable_multi, array('vimeo', 'youtube'));
}

function kopa_content_get_audio($content, $enable_multi = false) {
    return kopa_content_get_media($content, $enable_multi, array('audio', 'soundcloud'));
}

function kopa_content_get_media($content, $enable_multi = false, $media_types = array()) {
    $media = array();
    $regex_matches = '';
    $regex_pattern = get_shortcode_regex();
    preg_match_all('/' . $regex_pattern . '/s', $content, $regex_matches);
    foreach ($regex_matches[0] as $shortcode) {
        $regex_matches_new = '';
        preg_match('/' . $regex_pattern . '/s', $shortcode, $regex_matches_new);

        if (in_array($regex_matches_new[2], $media_types)) :
            $media[] = array(
                'shortcode' => $regex_matches_new[0],
                'type' => $regex_matches_new[2],
                'url' => $regex_matches_new[5]
            );
            if (false == $enable_multi) {
                break;
            }
        endif;
    }

    return $media;
}


function kopa_get_client_IP() {
    $IP = NULL;

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //check if IP is from shared Internet
        $IP = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //check if IP is passed from proxy
        $ip_array = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
        $IP = trim($ip_array[count($ip_array) - 1]);
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        //standard IP check
        $IP = $_SERVER['REMOTE_ADDR'];
    }
    return $IP;
}

function kopa_get_post_meta($pid, $key = '', $single = false, $type = 'String', $default = '') {
    $data = get_post_meta($pid, $key, $single);
    switch ($type) {
        case 'Int':
            $data = (int) $data;
            return ($data >= 0) ? $data : $default;
            break;
        default:
            return ($data) ? $data : $default;
            break;
    }
}

function kopa_get_like_permission($pid) {
    $permission = 'disable';

    $key = 'kopa_' . kopa_get_domain() . '_like_by_' . kopa_get_client_IP();
    $is_voted = kopa_get_post_meta($pid, $key, true, 'Int');

    if (!$is_voted)
        $permission = 'enable';

    return $permission;
}

function kopa_get_like_count($pid) {
    $key = 'kopa_' . kopa_get_domain() . '_total_like';
    return kopa_get_post_meta($pid, $key, true, 'Int');
}

function kopa_total_post_count_by_month($month, $year) {
    $args = array(
        'monthnum' => (int) $month,
        'year' => (int) $year,
    );
    $the_query = new WP_Query($args);
    return $the_query->post_count;
    ;
}

function kopa_head() {
    $logo_margin_top = get_option('kopa_theme_options_logo_margin_top');
    $logo_margin_left = get_option('kopa_theme_options_logo_margin_left');
    $logo_margin_right = get_option('kopa_theme_options_logo_margin_right');
    $logo_margin_bottom = get_option('kopa_theme_options_logo_margin_bottom');
    

    echo "<style type='text/css'>
        .logo-image{
            margin-top:{$logo_margin_top}px;
            margin-left:{$logo_margin_left}px;
            margin-right:{$logo_margin_right}px;
            margin-bottom:{$logo_margin_bottom}px;
        } 
    </style>";

    /* =========================================================
      Main Colorscheme
      ============================================================ */
    

    /* ==================================================================================================
     * Custom heading color
     * ================================================================================================= */
    


    /* =========================================================
      Font family
      ============================================================ */
		$google_fonts = kopa_get_google_font_array();
    $current_heading_font = get_option('kopa_theme_options_heading_font_family');
    $current_content_font = get_option('kopa_theme_options_content_font_family');
    $current_main_nav_font = get_option('kopa_theme_options_main_nav_font_family');
    $current_wdg_sidebar_font = get_option('kopa_theme_options_wdg_sidebar_font_family');
    $load_font_array = array();

    
    if ($current_heading_font) {
        $google_font_family = $google_fonts[$current_heading_font]['family'];
        echo "<style>
         h1, h2, h3, h4, h5, h6 {
            font-family: '{$google_font_family}', sans-serif;
        }
        </style>";
    }
    if ($current_content_font) {
        $google_font_family = $google_fonts[$current_content_font]['family'];
        echo "<style>
         body,
         .pagination ul li,
         .search-box .search-form .search-text,
         .search-box .search-form .search-submit,
         .widget_categories .postform, 
         .widget_archive select,
         .list-container-3 ul li a,
         #comments-form label.required, #contact-form label.required,
         #comments-form #submit-comment, #contact-form #submit-contact,
         .error-404 .left-col p
          {
            font-family: '{$google_font_family}', sans-serif;
        }
        </style>";
    }
    if ($current_main_nav_font) {
        $google_font_family = $google_fonts[$current_main_nav_font]['family'];
        echo "<style>
        #main-menu > li > a,
        #main-menu li ul li a,
        #footer-menu li a {
            font-family: '{$google_font_family}', sans-serif;
        }
        </style>";
    }
    if ($current_wdg_sidebar_font) {
        $google_font_family = $google_fonts[$current_wdg_sidebar_font]['family'];
        echo "<style>
        .widget .widget-title,
        #main-content .widget .widget-title {
            font-family: '{$google_font_family}', sans-serif;
        }
        </style>";
    }


    /* =========================================================
      Font size
      ============================================================ */
    
    
    
    /* ================================================================================
     * Font weight
      ================================================================================ */

    

    /* ==================================================================================================
     * Custom CSS
     * ================================================================================================= */
    $kopa_theme_options_custom_css = htmlspecialchars_decode(stripslashes(get_option('kopa_theme_options_custom_css')));
    if ($kopa_theme_options_custom_css)
        echo "<style>{$kopa_theme_options_custom_css}</style>";

    /* ==================================================================================================
     * IE8 Fix CSS3
     * ================================================================================================= */
    echo "<!--[if lt IE 9]><style>
        .flex-control-paging li a,
        .carousel-nav a,
        .kp-video-widget ul li .entry-item .entry-thumb .play-icon,
        .search-box .search-form .search-text,
        .search-box .search-form .search-submit,
        .kp-gallery-slider .play-icon,
        .kp-gallery-carousel .play-icon,
        .kp-dropcap.color {
            behavior: url(".get_template_directory_uri()."/js/PIE.htc);
        }
    </style><![endif]-->";
}

/* ==============================================================================
 * Mobile Menu
  ============================================================================= */

class kopa_mobile_menu extends Walker_Nav_Menu {

    function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0) {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat("\t", $depth) : '';

        $class_names = $value = '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        
        if ($depth == 0)
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . ' clearfix"' : 'class="clearfix"';
        else 
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : 'class=""';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $value . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .=!empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .=!empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .=!empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        if ($depth == 0) {
            $item_output = $args->before;
            $item_output .= '<h3><a' . $attributes . '>';
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
            $item_output .= '</a></h3>';
            $item_output .= $args->after;
        } else {
            $item_output = $args->before;
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;
        }
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        if ($depth == 0) {
            $output .= "\n$indent<span>+</span><div class='clear'></div><div class='menu-panel clearfix'><ul>";
        } else {
            $output .= '<ul>'; // indent for level 2, 3 ...
        } 
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        if ($depth == 0) {
            $output .= "$indent</ul></div>\n";
        } else {
            $output .= '</ul>';
        } 
    }

}

function kopa_add_icon_home_menu($items, $args) {

    if ($args->theme_location == 'main-nav') {
        if ($args->menu_id == 'toggle-view-menu') {
            $homelink = '<li class="clearfix"><h3><a href="' . esc_url(home_url()) . '">' . __('Home', kopa_get_domain()) . '</a></h3></li>';
            $items = $homelink . $items;
        } else if ($args->menu_id == 'main-menu') {
            $homelink = '<li class="menu-home-item' . ( is_front_page() ? ' current-menu-item' : '') . '"><a href="' . esc_url(home_url()) . '">' . __('Home', kopa_get_domain()) . '</a></li>';
            $items = $homelink . $items;
        }
    }

    if ($args->theme_location == 'footer-nav') {
        if ($args->menu_id == 'footer-menu') {
            $homelink = '<li><a href="' . esc_url(home_url()) . '">' . __('Home', kopa_get_domain()) . '</a></li>';
            $items = $homelink . $items;
        }
    }

    return $items;
}

function kopa_custom_excerpt_length( $length ) {
$length = 20;

    return $length;
}

function kopa_custom_excerpt_more( $more ) {
    return '...';
}

/**
 * Convert Hex Color to RGB using PHP
 * @link http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
 */
function kopa_hex2rgba($hex, $alpha = false) {
    $hex = str_replace("#", "", $hex);

    if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
    }
    if ( $alpha ) {
        return array($r, $g, $b, $alpha);
    }
    
    return array($r, $g, $b);
}

/**
 * Get gallery string ids after getting matched gallery array
 * @return array of attachment ids in gallery
 * @return empty if no gallery were found
 */
function kopa_content_get_gallery_attachment_ids( $content ) {
    $gallery = kopa_content_get_gallery( $content );

    if (isset( $gallery[0] )) {
        $gallery = $gallery[0];
    } else {
        return '';
    } 

    if ( isset($gallery['shortcode']) ) {
        $shortcode = $gallery['shortcode'];
    } else {
        return '';
    } 

    // get gallery string ids
    preg_match_all('/ids=\"(?:\d+,*)+\"/', $shortcode, $gallery_string_ids);
    if ( isset( $gallery_string_ids[0][0] ) ) {
        $gallery_string_ids = $gallery_string_ids[0][0];
    } else {
        return '';
    } 

    // get array of image id
    preg_match_all('/\d+/', $gallery_string_ids, $gallery_ids);
    if ( isset( $gallery_ids[0] ) ) {
        $gallery_ids = $gallery_ids[0];
    } else {
        return '';
    } 

    return $gallery_ids;
}

/**
 * Get weather data of location automatically by ip
 * @since Gammar 1.0
 */

/**
 * Template tag: print header ticker
 * @since FastNews 1.0
 */
function kopa_header_ticker() {
    // get option
    $kopa_theme_options_display_headline_status = get_option( 'kopa_theme_options_display_headline_status', 'show' );
    $kopa_theme_options_headline_title = get_option( 'kopa_theme_options_headline_title', __( 'Breaking News', kopa_get_domain() ) );
    $kopa_theme_options_headline_category_id = get_option( 'kopa_theme_options_headline_category_id', null );
    $kopa_theme_options_headline_posts_number = (int) get_option( 'kopa_theme_options_headline_posts_number', 10 );

    // validate
    if ( empty( $kopa_theme_options_headline_title ) ) {
        $kopa_theme_options_headline_title = __( 'Breaking News', kopa_get_domain() );
    } else {
        $kopa_theme_options_headline_title = strip_tags( $kopa_theme_options_headline_title );
    }
    if ( $kopa_theme_options_headline_posts_number <= 1 ) {
        $kopa_theme_options_headline_posts_number = 10;
    }

    if ( 'show' == $kopa_theme_options_display_headline_status ) {
        $args = array(
            'category'       => $kopa_theme_options_headline_category_id,
            'posts_per_page' => $kopa_theme_options_headline_posts_number,
        );

        global $post;
        $ticker_posts = get_posts( $args );
    ?>

    <span class="kp-headline-title"><?php echo $kopa_theme_options_headline_title; ?></span>
    <div class="kp-headline clearfix">                        
        <dl class="ticker-1 clearfix">
            <?php foreach ( $ticker_posts as $post ) { 
                setup_postdata( $post );
                ?>
            <dd><a href="<?php the_permalink(); ?>"><?php the_time( get_option( 'date_format' ) ); ?> - <?php the_title(); ?></a></dd>
            <?php } // end foreach 
            wp_reset_postdata();
            ?>
        </dl>
    </div>
    <!--ticker-1-->

    <?php }
}

/**
 * Template tag: print header socials link
 * @since FastNews 1.0
 */
function kopa_header_social_links() {
    $social_links = array(
        'facebook'  => array(
            'url'     => '',
            'icon'    => 'facebook',
            'display' => false,
        ),
        'twitter'   => array(
            'url'    => '',
            'icon'   => 'twitter',
            'display' => false,
        ),
        'pinterest' => array(
            'url'    => '',
            'icon'   => 'pinterest',
            'display' => false,
        ),
        'instagram' => array(
            'url'    => '',
            'icon'   => 'instagram',
            'display' => false,
        ),
        'gplus'     => array(
            'url'    => '',
            'icon'   => 'google-plus',
            'display' => false,
        ),
        'youtube'  => array(
            'url'    => '',
            'icon'   => 'youtube',
            'display' => false,
        ),
        'linkedin'  => array(
            'url'    => '',
            'icon'   => 'linkedin',
            'display' => false,
        ),
        'rss'  => array(
            'url'    => '',
            'icon'   => 'rss',
            'display' => false,
        ),
    );

    foreach( $social_links as $social_name => $social_atts ) {
        $option_name = 'kopa_theme_options_social_links_' . $social_name . '_url';
        $social_atts['url'] = get_option( $option_name, '' );

        if ( 'rss' == $social_name ) {
            if ( empty( $social_atts['url'] ) ) {
                $social_atts['url'] = get_bloginfo('rss2_url');
                $social_atts['display'] = true;
            } elseif ( $social_atts['url'] != 'HIDE' ) {
                $social_atts['url'] = esc_url( $social_atts['url'] );
                $social_atts['display'] = true;
            }
        } else {
            $social_atts['url'] = esc_url( $social_atts['url'] );
            if ( !empty( $social_atts['url'] ) ) { $social_atts['display'] = true; }
        }

        $social_links[ $social_name ] = $social_atts;
    }

    $social_link_target = get_option( 'kopa_theme_options_social_link_target', '_self' );
    ?>

    <ul class="social-link pull-right clearfix">
        <?php foreach ( $social_links as $social_name => $social_atts) { ?>
            <?php if ( $social_atts['display'] ) { ?>
        <li><a href="<?php echo $social_atts['url']; ?>" target="<?php echo $social_link_target; ?>"><?php echo KopaIcon::getIcon($social_atts['icon']); ?></a></li>
            <?php } // endif ?>
        <?php } // endforeach ?>
    </ul>

    <?php
}

/**
 * Color darken or lighten function
 * @author clearpixel
 * @link http://lab.clearpixel.com.au/2008/06/darken-or-lighten-colours-dynamically-using-php/
 * @since FastNews 1.0
 */
function kopa_color_brightness($hex, $percent) {
    // Work out if hash given
    $hash = '';
    if (stristr($hex,'#')) {
        $hex = str_replace('#','',$hex);
        $hash = '#';
    }
    /// HEX TO RGB
    $rgb = array(hexdec(substr($hex,0,2)), hexdec(substr($hex,2,2)), hexdec(substr($hex,4,2)));
    //// CALCULATE 
    for ($i=0; $i<3; $i++) {
        // See if brighter or darker
        if ($percent > 0) {
            // Lighter
            $rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1-$percent));
        } else {
            // Darker
            $positivePercent = $percent - ($percent*2);
            $rgb[$i] = round($rgb[$i] * $positivePercent) + round(0 * (1-$positivePercent));
        }
        // In case rounding up causes us to go to 256
        if ($rgb[$i] > 255) {
            $rgb[$i] = 255;
        }
    }
    //// RBG to Hex
    $hex = '';
    for($i=0; $i < 3; $i++) {
        // Convert the decimal digit to hex
        $hexDigit = dechex($rgb[$i]);
        // Add a leading zero if necessary
        if(strlen($hexDigit) == 1) {
        $hexDigit = "0" . $hexDigit;
        }
        // Append to the hex string
        $hex .= $hexDigit;
    }
    return $hash.$hex;
}

add_filter('kopa_icon_get_icon', 'fastnews_kopa_icon_get_icon' , 10, 3);
function fastnews_kopa_icon_get_icon($html, $icon_class, $icon_tag){
	$classes = '';
    switch ($icon_class) {
            case 'facebook':
                $classes = 'fa fa-facebook';
                break;
            case 'facebook2':
                $classes = 'fa fa-facebook-square';
                break;
            case 'twitter':
                $classes = 'fa fa-twitter';
                break;
            case 'twitter2':
                $classes = 'fa fa-twitter-square';
                break;
            case 'google-plus':
                $classes = 'fa fa-google-plus';
                break;
            case 'google-plus2':
                $classes = 'fa fa-google-plus-square';
                break;
            case 'instagram':
                $classes = 'fa fa-instagram';
                break;
            case 'youtube':
                $classes = 'fa fa-youtube';
                break;
            case 'dribbble':
                $classes = 'fa fa-dribbble';
                break;
            case 'flickr':
                $classes = 'fa fa-flickr';
                break;
            case 'rss':
                $classes = 'fa fa-rss';
                break;
            case 'linkedin':
                $classes = 'fa fa-linkedin';
                break;
            case 'pinterest':
                $classes = 'fa fa-pinterest';
                break;
            case 'email':
                $classes = 'fa fa-envelope';
                break;
            case 'pencil':
                $classes = 'fa fa-pencil';
                break;
            case 'date':
                $classes = 'fa fa-clock-o';
                break;
            case 'comment':
                $classes = 'fa fa-comment';
                break;
            case 'view':
                $classes = 'fa fa-eye';
                break;
            case 'link':
                $classes = 'fa fa-link';
                break;
            case 'film':
                $classes = 'fa fa-film';
                break;
            case 'images':
                $classes = 'fa fa-picture-o';
                break;
            case 'music':
                $classes = 'fa fa-music';
                break;
            case 'long-arrow-right':
                $classes = 'fa fa-long-arrow-right';
                break;
            case 'apple':
                $classes = 'fa fa-apple';
                break;
            case 'star':
                $classes = 'fa fa-star';
                break;
            case 'star2':
                $classes = 'fa fa-star-o';
                break;
            case 'exit':
                $classes = 'fa fa-sign-out';
                break;
            case 'folder':
                $classes = 'fa fa-folder';
                break;
            case 'video':
                $classes = 'fa fa-video-camera';
                break;
            case 'play':
                $classes = 'fa fa-play';
                break;
            case 'spinner':
                $classes = 'fa fa-spinner';
                break;
            case 'bug':
                $classes = 'fa fa-bug';
                break;
             case 'tint':
                $classes = 'fa fa-tint';
                break;
            case 'pause':
                $classes = 'fa fa-pause';
                break;
            case 'crosshairs':
                $classes = 'fa fa-crosshairs';
                break;
            case 'cog':
                $classes = 'fa fa-cog';
                break;
            case 'check-circle':
                $classes = 'fa fa-check-circle-o';
                break;
            case 'hand-right':
                $classes = 'fa fa-hand-o-right';
                break;
            case 'plus-square':
                $classes = 'fa fa-plus-square';
                break;
            case 'trash':
                $classes = 'fa fa-trash-o';
                break;
            case 'arrow-circle-up':
                $classes = 'fa fa-arrow-circle-up';
                break;
            case 'home':
                $classes = 'fa fa-home';
                break;
            case 'volume':
                $classes = 'fa fa-volume-up';
                break;
            case 'chevron-circle-right':
                $classes = 'fa fa-chevron-right';
                break;
            case 'chevron-circle-left':
                $classes = 'fa fa-chevron-left';
                break;
            
                    
        }
        return KopaIcon::createHtml($classes,$icon_tag);
}
function kopa_get_image_src($pid = 0, $size = 'thumbnail') {
    $thumb = get_the_post_thumbnail($pid, $size);
    if (!empty($thumb)) {
        $_thumb = array();
        $regex = '#<\s*img [^\>]*src\s*=\s*(["\'])(.*?)\1#im';
        preg_match($regex, $thumb, $_thumb);
        $thumb = $_thumb[2];
    }
    return $thumb;
}

/*
 * default title
 */
add_filter('the_title','kopa_get_the_title');
//add_filter('get_the_title', 'kopa_get_the_title');
function kopa_get_the_title($title){
    if($title == ''){
        $title = __('(no title)',kopa_get_domain());
    }
    return $title;
}

/*
 * Kopa get time ago
 */
function kopa_print_timeago($field_id, $field_name, $selected_timeago, $is_admin = false){
    $timeago = array(
        'label' => __('Timestamp (ago)', kopa_get_domain()),
        'options' => array(
            '' => __('-- Select --', kopa_get_domain()),
            '-1 week' => __('1 week', kopa_get_domain()),
            '-2 week' => __('2 weeks', kopa_get_domain()),
            '-3 week' => __('3 weeks', kopa_get_domain()),
            '-1 month' => __('1 months', kopa_get_domain()),
            '-2 month' => __('2 months', kopa_get_domain()),
            '-3 month' => __('3 months', kopa_get_domain()),
            '-4 month' => __('4 months', kopa_get_domain()),
            '-5 month' => __('5 months', kopa_get_domain()),
            '-6 month' => __('6 months', kopa_get_domain()),
            '-7 month' => __('7 months', kopa_get_domain()),
            '-8 month' => __('8 months', kopa_get_domain()),
            '-9 month' => __('9 months', kopa_get_domain()),
            '-10 month' => __('10 months', kopa_get_domain()),
            '-11 month' => __('11 months', kopa_get_domain()),
            '-1 year' => __('1 year', kopa_get_domain()),
            '-2 year' => __('2 years', kopa_get_domain()),
            '-3 year' => __('3 years', kopa_get_domain()),
            '-4 year' => __('4 years', kopa_get_domain()),
            '-5 year' => __('5 years', kopa_get_domain()),
            '-6 year' => __('6 years', kopa_get_domain()),
            '-7 year' => __('7 years', kopa_get_domain()),
            '-8 year' => __('8 years', kopa_get_domain()),
            '-9 year' => __('9 years', kopa_get_domain()),
            '-10 year' => __('10 years', kopa_get_domain()),
        )
    );
    if ($is_admin) {
        $str_ret = '';
        $str_ret .= '<span class="kopa-component-title">'. __($timeago['label'], kopa_get_domain()) . '</span>';
        $str_ret .= '<select class="widefat" name="' . $field_name . '" id="' . $field_id . '" class="kopa-ui-taxonomy kopa-ui-select form-control">';
        foreach ($timeago['options'] as $k => $v){
            if ($selected_timeago === $k){
                $str_ret .= '<option value="' . $k . '" selected>' . $v . '</option>';
            } else {
                $str_ret .= '<option value="' . $k . '">' . $v . '</option>';
            }

        }
        $str_ret .= '</select>';
    } else {
        $str_ret = '<p>';
        $str_ret .= '<label for="'.$field_id.'">'. __($timeago['label'], kopa_get_domain()) . '</label>';
        $str_ret .= '<select class="widefat" name="' . $field_name . '" id="' . $field_id . '">';
        foreach ($timeago['options'] as $k => $v){
            if ($selected_timeago === $k){
                $str_ret .= '<option value="' . $k . '" selected>' . $v . '</option>';
            } else {
                $str_ret .= '<option value="' . $k . '">' . $v . '</option>';
            }

        }
        $str_ret .= '</select>';
        $str_ret .= '</p>';
    }

    echo $str_ret;
}

