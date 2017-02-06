<?php
global $wp_query, $the_lp_query, $post, $cap, $tk_query_id, $show_pagination;

$x2_tmp = '';

if(!$the_lp_query)
	$the_lp_query = $wp_query;

    $x2_tmp .= '<script type="text/javascript">'. chr(13);
    $x2_tmp .= 'jQuery(document).ready(function(){'. chr(13);
	$x2_tmp .= 'boxgrid();'. chr(13);
    $x2_tmp .= '        function boxgrid(){'. chr(13);
    $x2_tmp .= '    jQuery(\'.boxgrid.captionfull\').hover(function(){'. chr(13);
    $x2_tmp .= '        jQuery(\'.cover\', this).stop().animate({top:\'-90px\'},{queue:false,duration:160});'. chr(13);
    $x2_tmp .= '    }, function() {'. chr(13);
    $x2_tmp .= '        jQuery(".cover", this).stop().animate({top:"2px"},{queue:false,duration:160});'. chr(13);
    $x2_tmp .= '    });'. chr(13);
    $x2_tmp .= '}'. chr(13);
	$x2_tmp .= '});'. chr(13);
    $x2_tmp .= '</script>'. chr(13);

if ($the_lp_query->have_posts()) : while ($the_lp_query->have_posts()) : $the_lp_query->the_post();

	$thumb = get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
	$pattern= "/(?<=src=['|\"])[^'|\"]*?(?=['|\"])/i";
	preg_match($pattern, $thumb, $thePath);
	if(!isset($thePath[0])){
	$thePath[0] = get_template_directory_uri().'/images/slideshow/noftrdimg-222x160.jpg';
	}
	$x2_tmp .= '<a href="'. get_permalink().'" title="'. get_the_title().'"><div class="boxgrid captionfull" onclick="document.location.href=\''. get_permalink().'\'" style="cursor:pointer;background: transparent url('.$thePath[0].') repeat scroll 0 0; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; " title="'. get_the_title().'">';
	$x2_tmp .= '<div class="cover boxcaption">';
	$x2_tmp .= '<h3>'. get_the_title().'</h3>';
	$x2_tmp .= '<p>'.substr(get_the_excerpt(), 0, 100).'</p>';
	$x2_tmp .= '</div>';
	$x2_tmp .= '</div></a>';

endwhile; endif;



// Pagination starts
if($show_pagination != 'hide'){
    $x2_tmp .='<div class="clear"></div>';
    if( $cap->posts_lists_hide_pagenavi == 'show'){
        $x2_tmp .='<div id="' . $tk_query_id . '" class="navigation">';
        $x2_tmp .='<div class="alignleft">'. get_next_posts_link(__('&laquo; Older Entries','x2'), $the_lp_query->max_num_pages ) .'</div>';
        $x2_tmp .='<div class="alignright">' . get_previous_posts_link(__('Newer Entries &raquo;','x2')) .'</div>';

        $x2_tmp .='</div><!-- End navigation -->';
    }

    if($cap->posts_lists_hide_pagenavi == 'pagenavi' ){
        if(function_exists('wp_pagenavi')){
            ob_start();
                wp_pagenavi( array( 'query' => $the_lp_query) );
                $x2_tmp_wp_pagenavi = ob_get_clean();
                $x2_tmp_wp_pagenavi = str_replace('class=\'wp-pagenavi\'', 'id="'.$tk_query_id.'" class="wp-pagenavi navigation"', $x2_tmp_wp_pagenavi);

                $x2_tmp .= $x2_tmp_wp_pagenavi;
        }
    }
}
wp_reset_postdata();

echo '<div id="featured_posts'.$tk_query_id.'"><div id="list_posts'.$tk_query_id.'" class="loop-designer list-posts-all">'.$x2_tmp.'</div></div>';


?>