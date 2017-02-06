/*
 * JS which is sued in admin area
 */
jQuery(document).ready(function(){

	jQuery('input[id="cap_category_name_all"]').bind('click', function(){
		var status = jQuery(this).is(':checked');
		jQuery('input[name="x2_theme_options[cap_category_name][]"]').attr('checked', status);
	});

	//jQuery('#cap_category_name').multipleSelect();

    var show_hide_slide_settings =  jQuery('.cap_slideshow_effect, .cap_slideshow_autoplay, \n\
                                            .cap_slideshow_pagination, .cap_slideshow_controls,\n\
                                            ._x2_page_slideshow_effect, ._x2_page_slideshow_autoplay,\n\
                                            ._x2_page_slideshow_pagination, ._x2_page_slideshow_controls');
    var inverted_show_hide_slide_settings = jQuery('.cap_slideshow_shadow, ._x2_slideshow_shadow');



	jQuery('#cap_posts_lists_style').change(function(){
      var current_value = jQuery(this).val();

      if(current_value == 'default' || current_value == 'bubble'){
          jQuery('.cap_posts_lists_hide_avatar, .cap_posts_lists_hide_date').show();
      } else {
         // jQuery('#cap_posts_lists_hide_avatar .option-title').not(':first').hide();
          jQuery('.cap_posts_lists_hide_avatar, .cap_posts_lists_hide_date').hide();
      }

	});
    jQuery('#cap_posts_lists_style').trigger('change');

    jQuery('#reset_theme_option_mask').click(function(){
      if(confirm(admin_params.reset_all_options)){
        jQuery('#reset_theme_option').trigger('click');
      }
    });


   jQuery('#_x2_page_enable_slideshow').change(function(){
      var current_value = jQuery(this).val();
      var current_slider_selected = jQuery('#_x2_page_slideshow_style').val();
      if(current_value != 'on'){
          jQuery('#x2_slideshow .cmb_metabox tr').not(':first').hide();
          show_hide_slide_settings.hide();
      } else {
          jQuery('#x2_slideshow .cmb_metabox tr').show();
          if(current_slider_selected == 'flux slider'){
              show_hide_slide_settings.show();
        } else {
              show_hide_slide_settings.hide();
        }
      }

   });


    jQuery('#cap_slideshow_style, #_x2_page_slideshow_style').change(function() {
        if (jQuery(this).val() == admin_params.flux_slider) {
            show_hide_slide_settings.show();
            inverted_show_hide_slide_settings.hide();
        } else {
             show_hide_slide_settings.hide();
             inverted_show_hide_slide_settings.show();
        }
       var amount = jQuery('._x2_page_slideshow_amount');
       var shadow = jQuery(".cap_slideshow_shadow, ._x2_page_slideshow_shadow");
        if(jQuery(this).find(':selected').text() == admin_params.default_slider){
            amount.hide();
            shadow.show(1);
        } else {
            amount.show();
            shadow.hide(1);
        }

    });
    jQuery('#cap_slideshow_show_sticky').change(function() {
        if (jQuery(this).val() == 'on') {
          jQuery('.cap_slideshow_show_page').hide();
        } else {
          jQuery('.cap_slideshow_show_page').show();
        }


    });
    jQuery('#_x2_page_enable_slideshow, #_x2_page_slideshow_sticky, #_x2_page_slideshow_style, #cap_slideshow_style, #cap_slideshow_show_sticky').trigger('change');

    //hide all for default and off values
    var current_value = jQuery('#_x2_page_enable_slideshow').val();
    if(current_value != 'on'){
        jQuery('#x2_slideshow .cmb_metabox tr').not(':first').hide();
        show_hide_slide_settings.hide();
    }
    jQuery('.dismiss-activation-message,.x2-rate-it .go-to-wordpress-repo').click(function(){
        var message_block = jQuery('.x2-rate-it');
        send_ajax_option_update(message_block, 'x2_dismiss_activation_message');
    });

    jQuery('.close').click(function(){
        var message_block = jQuery('.slideshow_info');
        send_ajax_option_update(message_block, 'x2_dismiss_info_messages');
    });
    function send_ajax_option_update(message_block, action){
        jQuery.ajax({
            url : admin_params.ajax_url,
            type: 'post',
            data : {
                'action' : action, //'dismiss_activation_message',
                'value' : 'yes'
            },
            success : function(data){
                if(data){
                   message_block.hide();
                }
            }
        });
    }

    jQuery('#cap_overwrite_css').css({
        width: '100%',
        height: '150px'
    });
    jQuery('#cap_overwrite_css').focus(function(){
        jQuery('#cap_overwrite_css').elastic();
    });

    jQuery('._x2_page_slideshow_post_type :checkbox').change(function(){
        var only_post = true;
        var category_block = jQuery('._x2_page_category_name');
        jQuery('._x2_page_slideshow_post_type :checked').each(function(){
            if(jQuery(this).val() != 'post'){
                only_post = false;
            }
        });
        if(!only_post){
            category_block.hide().find(':checkbox').attr('checked', false);
        } else {
            category_block.show();
        }
    });

   jQuery('._x2_page_featured_posts_post_type :checkbox').change(function(){
        var only_post = true;
        var category_block = jQuery('._x2_page_featured_posts_category');
        jQuery('._x2_page_featured_posts_post_type :checked').each(function(){
            if(jQuery(this).val() != 'post'){
                only_post = false;
            }
        });
        if(!only_post){
            category_block.hide().find(':checkbox').attr('checked', false);
        } else {
            category_block.show();
        }
    });

    jQuery('#x2_get_personal_help').click(function(){
        var action = jQuery(this);

        confirm('Get personal help by the theme authors and write us right from your theme options panel - as soon as you purchase the x2 Premium Pack.');

        jQuery('#x2_get_more').css('background', 'papayawhip');

    });
});

