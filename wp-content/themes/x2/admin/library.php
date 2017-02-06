<?php
//
// CheezCap - Cheezburger Custom Administration Panel
// (c) 2008 - 2010 Cheezburger Network (Pet Holdings, Inc.)
// LOL: http://cheezburger.com
// Source: http://code.google.com/p/cheezcap/
// Authors: Kyall Barrows, Toby McKes, Stefan Rusek, Scott Porad
// License: GNU General Public License, version 2 (GPL), http://www.gnu.org/licenses/gpl-2.0.html
//

class Group {
  var $name;
  var $id;
  var $options;

  function Group( $_name, $_id, $_options ) {
    $this->name = $_name;
    $this->id = "cap_$_id";
    $this->options = apply_filters('x2_cap_get_options', $_options, $_id);
  }

    function WriteHtml() {

    echo '<div class="x2_accordion">';
    for ( $i=0; $i < count( $this->options ); $i++ ) {
      echo '<span class="' . $this->options[$i]->id . '">';
        $this->options[$i]->WriteHtml();
      echo '</span>';

    }
    echo '</div>';

  }
}

class Option {
  var $name;
  var $desc;
  var $id;
  var $_key;
  var $std;
  var $accordion;
  var $accordion_name;

  function Option( $_name, $_desc, $_id, $_std  ) {
    $this->name = $_name;
    $this->desc = $_desc;
    $this->id = "cap_$_id";
    $this->_key = $_id;
    $this->std = $_std;
  }

  function WriteHtml() {
    echo '';
  }

  function Reset( $ignored ) {
    update_option( $this->id, $this->std );
  }

  function Import( $data ) {
    if ( array_key_exists( $this->id, $data->dict ) )
        $cap = get_option('x2_theme_options');
        $cap[$this->id] = isset($data->dict[$this->id]) ? $data->dict[$this->id] : '';
        update_option( 'x2_theme_options', $cap );
  }

  function Export( $data ) {
    $cap = get_option('x2_theme_options');
    $data->dict[$this->id] = $cap[$this->id];
  }

  function get() {
    $value = get_option('x2_theme_options');
    return isset($value[$this->id]) ? $value[$this->id] : '';
  }
}

class TextOption extends Option {
  var $useTextArea;

  function TextOption( $_name, $_desc, $_id, $_std = '', $_useTextArea = false, $_accordion = 'on', $_accordion_name = "off"  ) {
    $this->Option( $_name, $_desc, $_id, $_std );
    $this->useTextArea = $_useTextArea;
    $this->accordion = $_accordion;
    $this->accordion_name = $_accordion_name;
  }

  function WriteHtml() {

    $stdText = $this->std;
    $value = get_option('x2_theme_options');
      if ( isset($value[$this->id]) && $value[$this->id] != "" )
            $stdText =  $value[$this->id];

      if($this->accordion == 'on' || $this->accordion == 'start'){ ?>
        <?php if($this->accordion_name != 'off' && $this->accordion_name != __('off','x2') ) { ?>
          <h3 class="option-title"><a href="#"><?php echo $this->accordion_name; ?></a></h3>
          <div>
          <p class="option-title"><b><?php echo $this->name; ?></b></p>
        <?php } else {?>
          <h3 class="option-title"><a href="#"><?php echo $this->name; ?></a></h3>
          <div>
        <?php }?>
      <?php } else { ?>
        <p class="option-title"><b><?php echo $this->name; ?></b></p>
      <?php } ?>
      <p class="desc"><?php echo $this->desc; ?></p>
      <?php $commentWidth = 2;
      if ( $this->useTextArea ) :
        $commentWidth = 1;
        ?>
                <textarea class="text_option_teaxarea" name="x2_theme_options[<?php echo $this->id; ?>]" id="<?php echo $this->id; ?>"><?php echo esc_attr( stripcslashes($stdText) ); ?></textarea>
        <?php
      else :
        ?>
        <input name="x2_theme_options[<?php echo $this->id; ?>]" id="<?php echo $this->id; ?>" type="text" value="<?php echo esc_attr( stripcslashes($stdText) ); ?>" size="40" />
        <?php
      endif;

      if($this->accordion == 'on' || $this->accordion == 'end'){ ?>
        </div>
      <?php } ?>
  <?php
  }

  function get() {
    $value = get_option('x2_theme_options');
    $value = isset($value[$this->id]) ? $value[$this->id] : '';

    if ( empty( $value ) )
      return $this->std;
    return $value;
  }
}

class CheckboxGroupOptions extends Option{
    public $options;

    function CheckboxGroupOptions($_name, $_desc, $_id, $_options, $_stdIndex = 0, $_accordion = 'on', $_accordion_name = "off"){
        $this->Option( $_name, $_desc, $_id, $_stdIndex );
    $this->options = $_options;
    $this->accordion = $_accordion;
    $this->accordion_name = $_accordion_name;
    }
    function WriteHtml() {
        if($this->accordion == 'on' || $this->accordion == 'start'){ ?>
        <?php if($this->accordion_name != 'off') { ?>
          <h3 class="option-title"><a href="#"><?php echo $this->accordion_name; ?></a></h3>
          <div>
          <p class="option-title"><b><?php echo $this->name; ?></b></p>
        <?php } else {?>
          <h3 class="option-title"><a href="#"><?php echo $this->name; ?></a></h3>
          <div>
        <?php }?>
      <?php } else { ?>
        <p class="option-title"><b><?php echo $this->name; ?></b></p>
      <?php } ?>
        <p class="desc"><?php echo $this->desc; ?></p>

        <div class="checkboxgroupoptions">
            <?php

                    $value = get_option('x2_theme_options');
                    $value = (isset($value[$this->id]) && is_serialized($value[$this->id])) ? unserialize($value[$this->id]) : array($value[$this->id]);

            $args = array(
              'descendants_and_self'  => 0,
              'selected_cats'         => $value,
              'popular_cats'          => false,
              'taxonomy'              => 'category',
              'checked_ontop'         => true,
            );
            ob_start();

              echo '<ul>
                  <li>
                    <label class="selectit">
                      <input value="all" type="checkbox" name="'.$this->id.'_all" id="'.$this->id.'_all"> ' . __("All categories", "x2") . '
                    </label>
                  </li>';


                  wp_terms_checklist('', $args);
              echo '</ul>';

              $wp_terms_checklist = ob_get_contents();

            ob_clean();
            $wp_terms_checklist = str_replace( "post_category[]", "x2_theme_options[".$this->id."][]", $wp_terms_checklist );
            echo $wp_terms_checklist;
            ?>
          </div>

      <?php if( $this->accordion == 'on' || $this->accordion == 'end'){ ?>
        </div>
      <?php }
    }

    function get(){
        $value = get_option('x2_theme_options');
        if(isset($value[$this->id]) && is_serialized($value[$this->id])){
            return unserialize($value[$this->id]);
        } else if(isset($value[$this->id]) && !is_serialized($value[$this->id])){
            return array($value[$this->id]);
        } else {
            return  array();
        }
    }
}

class DropdownOption extends Option {
  var $options;

  function DropdownOption( $_name, $_desc, $_id, $_options, $_stdIndex = 0, $_accordion = 'on', $_accordion_name = "off" ) {
    $this->Option( $_name, $_desc, $_id, $_stdIndex );
    $this->options = $_options;
    $this->accordion = $_accordion;
    $this->accordion_name = $_accordion_name;
  }

  function WriteHtml() {

      if($this->accordion == 'on' || $this->accordion == 'start'){ ?>
        <?php if($this->accordion_name != 'off') { ?>
          <h3 class="option-title"><a href="#"><?php echo $this->accordion_name; ?></a></h3>
          <div>
          <p class="option-title"><b><?php echo $this->name; ?></b></p>
        <?php } else {?>
          <h3 class="option-title"><a href="#"><?php echo $this->name; ?></a></h3>
          <div>
        <?php }?>
      <?php } else { ?>
        <p class="option-title"><b><?php echo $this->name; ?></b></p>
      <?php } ?>
        <p class="desc"><?php echo $this->desc; ?></p>
        <select name="x2_theme_options[<?php echo $this->id; ?>]" id="<?php echo $this->id; ?>">
        <?php

        foreach( $this->options as $key=>$option ) :
          // If standard value is given

          $value = get_option('x2_theme_options');
          $value = (isset($value[$this->id]))? $value[$this->id] : '';
          if( $this->std != "" ){
            ?>
                        <option <?php if(!is_numeric($key)) echo 'value="'.$key.'"'?> <?php if ( $value == $key || $value == $option || ( ! $value && (isset($this->options[ $this->std ]))? $this->options[ $this->std ] : '' == $option )) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
            <?php
          }else{
            ?>
            <option <?php if(!is_numeric($key)) echo 'value="'.$key.'"'?> <?php if ($value == $key || $value == $option ) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
          <?php }
        endforeach;
        ?>
        </select>
      <?php if( $this->accordion == 'on' || $this->accordion == 'end'){ ?>
        </div>
      <?php } ?>
      <?php
  }

  function get() {
    $value = get_option('x2_theme_options');
        $value = isset($value[$this->id]) ? $value[$this->id] : '';

        if ( strtolower( $value ) == 'disabled' ){
            return false;
        }
    return $value;
  }
}

class DropdownCatOption extends Option {
  var $options;

  function DropdownCatOption( $_name, $_desc, $_id, $_options, $_stdIndex = 0, $_accordion = 'on', $_accordion_name = "off" ) {
    $this->Option( $_name, $_desc, $_id, $_stdIndex );
    $this->options = $_options;
    $this->accordion = $_accordion;
    $this->accordion_name = $_accordion_name;
  }

  function WriteHtml() {

      if($this->accordion == 'on' || $this->accordion == 'start'){ ?>
        <?php if($this->accordion_name != 'off') { ?>
          <h3 class="option-title"><a href="#"><?php echo $this->accordion_name; ?></a></h3>
          <div>
          <p class="option-title"><b><?php echo $this->name; ?></b></p>
        <?php } else {?>
          <h3 class="option-title"><a href="#"><?php echo $this->name; ?></a></h3>
          <div>
        <?php }?>
      <?php } else { ?>
                <div class="option-item <?php echo $this->id; ?>">
                    <p class="option-title"><b><?php echo $this->name; ?></b></p>
                <?php } ?>
                    <p class="desc"><?php echo $this->desc; ?></p>
                    <select name="x2_theme_options[<?php echo $this->id; ?>]" id="<?php echo $this->id; ?>">
                    <?php

                    foreach( $this->options as $option ) :
                        // If standard value is given

                        $value = get_option('x2_theme_options');
                        $value = (isset($value[$this->id]))? $value[$this->id] : '';
                        if( $this->std != "" ){
                            ?>
                            <option<?php if ( $value == $option['slug'] || ( ! $value && $this->options[ $this->std ] == $option['slug'] )) { echo ' selected="selected"'; } ?> value="<?php echo $option['slug'] ?>"><?php echo $option['name']; ?></option>
                            <?php
                        }else{
                            ?>
                            <option<?php if ( $value == $option['slug'] ) { echo ' selected="selected"'; } ?> value="<?php echo $option['slug'] ?>"><?php echo $option['name']; ?></option>
                        <?php }
                    endforeach;
                    ?>
                    </select>
      <?php if( $this->accordion == 'on' || $this->accordion == 'end'){ ?>
        </div>
      <?php } else {?>
                </div>
            <?php } ?>
      <?php
  }

  function get() {
    $value = get_option('x2_theme_options');
    $value = isset($value[$this->id]) ? $value[$this->id] : '';
    //echo $value;
        if ( strtolower( $value ) == 'disabled' )
      return false;
    return $value;
  }
}


class BooleanOption extends DropdownOption {
  var $default;

  function BooleanOption( $_name, $_desc, $_id, $_default = false, $_accordion = 'on', $_accordion_name = "off"   ) {
    $this->default = $_default;
    $this->DropdownOption( $_name, $_desc, $_id, array( 'Disabled', 'Enabled' ), $this->default );
    $this->accordion = $_accordion;
    $this->accordion_name = $_accordion_name;
  }


}

class ColorOption extends Option
{

  function ColorOption( $_name, $_desc, $_id, $_std = "", $_accordion = 'on', $_accordion_name = "off"   )
  {
        $this->Option( $_name, $_desc, $_id, $_std );
        $this->accordion = $_accordion;
    $this->accordion_name = $_accordion_name;
  }

  function WriteHtml(){

    $stdText = $this->std;
    $value = get_option('x2_theme_options');
      if ( !empty($value[$this->id]) )
            $stdText =  $value[$this->id];

      if($this->accordion == 'on' || $this->accordion == 'start'){ ?>
        <?php if($this->accordion_name != 'off') { ?>
          <h3 class="option-title"><a href="#"><?php echo $this->accordion_name; ?></a></h3>
          <div>
          <p class="option-title"><b><?php echo $this->name; ?></b></p>
        <?php } else {?>
          <h3 class="option-title"><a href="#"><?php echo $this->name; ?></a></h3>
          <div>
        <?php }?>
      <?php } else { ?>
        <p class="option-title"><b><?php echo $this->name; ?></b></p>
      <?php } ?>
      <p class="desc"><?php echo $this->desc; ?></p>
                <input name="x2_theme_options[<?php echo $this->id; ?>]" id="<?php echo $this->id ?>" type="text" value="<?php echo htmlspecialchars(stripslashes($stdText)) ?>" size="40" />
      <?php
          if($this->accordion == 'on' || $this->accordion == 'end'){ ?>
        </div>
      <?php } ?>

      <script type="text/javascript">
        jQuery('#<?php echo $this->id ?>').ColorPicker({
          onSubmit: function(hsb, hex, rgb, el) {
          jQuery(el).val(hex);
            jQuery(el).ColorPickerHide();
          },
          onBeforeShow: function () {
            jQuery(this).ColorPickerSetColor(this.value);
          }
        })
        .bind('keyup', function(){
          jQuery(this).ColorPickerSetColor(this.value);
        });

    </script>
  <?php
  }

    function get() {
    $value = get_option('x2_theme_options');
      $value = isset($value[$this->id]) ? $value[$this->id] : '';
        if (!$value)
            return $this->std;
        return $value;
    }
}


class FileOption extends Option {

    function FileOption( $_name, $_desc, $_id, $_std = "", $_accordion = 'on', $_accordion_name = "off" ) {
        $this->Option( $_name, $_desc, $_id, $_std );
        $this->accordion      = $_accordion;
        $this->accordion_name = $_accordion_name;
    }

    function WriteHtml(){

        $stdText = $this->std;
        $value   = get_option( 'x2_theme_options' );
        if ( isset( $value[ $this->id ] ) && $value[ $this->id ] != "" ) {
            $stdText = $value[ $this->id ];
        }

        if ( $this->accordion == 'on' || $this->accordion == 'start' ){ ?>
            <?php if ($this->accordion_name != 'off') { ?>
                <h3 class="option-title"><a href="#"><?php echo $this->accordion_name; ?></a></h3>
                <div>
                    <p class="option-title"><b><?php echo $this->name; ?></b></p>
            <?php } else { ?>
                <h3 class="option-title"><a href="#"><?php echo $this->name; ?></a></h3>
                <div>
            <?php } ?>
        <?php } else { ?>
            <p class="option-title"><b><?php echo $this->name; ?></b></p>
        <?php } ?>

        <p class="desc"><?php echo $this->desc; ?></p>

        <div class="option-inputs">

            <label for="image1">
                <input id="#upload_image<?php echo $this->id ?>" type="text" size="36"
                       name="x2_theme_options[<?php echo $this->id; ?>]"
                       value="<?php echo htmlspecialchars( stripslashes( $stdText ) ) ?>"/>
                <input class="upload_image_button" type="button" value="<?php _e( 'Browse..', 'x2' ) ?>"/>
                <input class="delete_image_button" type="button" value="<?php _e( 'Delete', 'x2' ) ?>"/>
                <img class="x2_image_preview" id="image_<?php echo $this->id ?>"
                     src="<?php echo htmlspecialchars( $stdText ); ?>"/>

            </label>

        </div>
        <?php if ($this->accordion == 'on' || $this->accordion == 'end'){ ?>
            </div>
        <?php }
    }

    function get() {
        $value = get_option( 'x2_theme_options' );
        $value = isset( $value[ $this->id ] ) ? $value[ $this->id ] : '';
        if ( ! $value ) {
            return $this->std;
        }

        return $value;
    }
}

/**
 * This class is the handy short cut for accessing config options
 *
 * $cap->post_ratings is the same as get_bool_option("cap_post_ratings", false)
 */
class autoconfig {
    private $data  = false;
    private $cache = array();

    function init() {
        if ( $this->data ) {
            return;
        }

        $this->data = array();
        $options    = cap_get_options();

        foreach ( $options as $group ) {
            foreach ( $group->options as $option ) {
                $this->data[ $option->_key ] = $option;
            }
        }
    }

    public function __get( $name ) {
        $this->init();
        if ( array_key_exists( $name, $this->cache ) ) {
            return $this->cache[ $name ];
        }

        if ( empty( $this->data[ $name ] ) ) {
            return '';
        }

        $option               = $this->data[ $name ];
        $this->cache[ $name ] = $option->get();

        return $this->cache[ $name ];
    }
}

function cap_admin_css() {
    wp_enqueue_style( 'thickbox' );

    wp_enqueue_style( 'colorpicker-css',        get_template_directory_uri().'/admin/css/colorpicker.css', false );
    wp_enqueue_style( 'x2-multiple-select-css', get_template_directory_uri().'/admin/multiple-select/multiple-select.css', false );

    wp_enqueue_style( 'jquery-ui-css',          get_template_directory_uri().'/admin/css/jquery-ui.css' );
    wp_enqueue_style('x2_admin',                get_template_directory_uri().'/_inc/css/admin.css' );
}

function cap_admin_js_libs() {
    wp_enqueue_script( 'jquery-ui' );
    wp_enqueue_script( 'jquery-ui-tabs' );
    wp_enqueue_script( 'jquery-ui-accordion' );
    wp_enqueue_script( 'jquery-ui-widget' );
    wp_enqueue_script( 'jquery-color' );

    if (function_exists('wp_enqueue_media')) {
        wp_enqueue_media();
    } else {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
    }
    wp_enqueue_script( 'x2-upload', get_template_directory_uri() . '/admin/js/uploader.js', array( 'jquery' ), true );
    wp_enqueue_script( 'x2-multiple-select-js', get_template_directory_uri() . '/admin/multiple-select/jquery.multiple.select.js', array( 'jquery' ), true );
    wp_enqueue_script( 'x2-colorpicker-js', get_template_directory_uri() . '/admin/js/colorpicker.js', array(), true );
    wp_enqueue_script( 'x2-autogrow-textarea-js', get_template_directory_uri() . '/admin/js/jquery.autogrowtextarea.min.js', array(), true );
    wp_enqueue_script( 'x2-zendesk_js', '//assets.zendesk.com/external/zenbox/v2.6/zenbox.js' );
    wp_enqueue_style( 'x2-zendesk_css', '//assets.zendesk.com/external/zenbox/v2.6/zenbox.css' );

}

function cap_admin_js() { ?>
    <script type="text/javascript">
    /* <![CDATA[ */

    var tabCookieName = "x2-tabs";
    jQuery(document).ready(function() {
        jQuery("#config-tabs").tabs({
            active : (jQuery.cookie(tabCookieName) || 0),
            activate : function( event, ui ) {
                var newIndex = ui.newTab.parent().children().index(ui.newTab);
                jQuery.cookie(tabCookieName, newIndex);
            }
        });
        jQuery(".x2_accordion").accordion({
            header:"h3",
            active:false,
            heightStyle: "content",
            collapsible: true
        });

        var slideshow_conrols = jQuery('.cap_slideshow_effect,.cap_slideshow_autoplay,.cap_slideshow_pagination.cap_slideshow_controls');
        var slideshow_shadow  = jQuery('.cap_slideshow_shadow');

        slideshow_conrols.hide();

        var cap_slideshow_style = jQuery('#cap_slideshow_style');
        if (cap_slideshow_style.val() == 'flux slider') {
            slideshow_conrols.show();
            slideshow_shadow.hide();
        }

        cap_slideshow_style.change(function() {
            var strUser = jQuery("#cap_slideshow_style").val();

            if (jQuery('#cap_slideshow_style').val() == 'flux slider') {
                slideshow_conrols.show();
                slideshow_shadow.hide();
            } else {
                slideshow_conrols.hide();
                slideshow_shadow.show();
            }
        });

    });
    /* ]]> */
    </script>
<?php
}

function top_level_settings() {
    global $themename, $cap;

    $cap = new autoconfig();
    if ( ! isset( $_REQUEST['updated'] ) ) {
        $_REQUEST[ 'updated' ] = false;
    }

    if(!empty($_POST) && !empty($_POST['x2_theme_options'])){
        $options = get_option('x2_theme_options');
        $options = array_merge((array)$options, $_POST['x2_theme_options']);
            $options['cap_category_name'] = !empty($_POST['x2_theme_options']['cap_category_name']) ? serialize($options['cap_category_name']) : serialize(array());
            $options['cap_titles_post_types'] = !empty($_POST['x2_theme_options']['cap_titles_post_types']) ? serialize($options['cap_titles_post_types']) : serialize(array());
        update_option('x2_theme_options', $options);
        $cap = new autoconfig();
        do_action('x2_after_theme_settings_saved');
        $_REQUEST['message'] = 'updated';
    }

    $message = isset( $_REQUEST[ 'message' ] ) ? $_REQUEST[ 'message' ] : '';
    switch ( $message ) {
        case 'updated':
            echo "<div id='message' class='updated fade'><p><strong>" . __( 'Theme options saved.', 'x2' ) . "</strong></p></div>";
            break;
        case 'resets':
            echo "<div id='message' class='updated fade'><p><strong>" . __( 'All settings are reset to default successfully.', 'x2' ) . "</strong></p></div>";
            break;
        case 'import_empty':
            echo "<div id='message' class='error fade'><p><strong>" . __( 'Please add file to import.', 'x2' ) . "</strong></p></div>";
            break;
        case 'import_error_type':
            echo "<div id='message' class='error fade'><p><strong>" . __( 'Please add correct file to import.', 'x2' ) . "</strong></p></div>";
            break;
        case 'import':
            echo "<div id='message' class='updated fade'><p><strong>" . __( 'All settings are imported successfully.', 'x2' ) . "</strong></p></div>";
            break;
    } ?>

    <div class="wrap">

        <div id="icon-themes" class="icon32"><br></div>
        <h2><b><?php printf(__('%s Theme Options','x2'), $themename); ?></b></h2>

        <div style="font-size: 13px; float: none; clear: both; text-align:right; width: 100%;">
            <?php printf(__('Proudly brought to you by %s.', 'x2'), '<a href="http://themekraft.com/" target="_new">Themekraft</a>'); ?>
        </div>

        <div class="message updated" style="padding: 10px 2% 20px 2%; overflow:auto; float: none; clear: both; width: 96%;">

            <div id="x2_documentation" style="float: left; overflow: auto; border-right: 1px solid #ddd; padding: 0 20px 0 0;">
                <h3><span><?php _e( 'Get support.', 'x2' ) ?></span><?php do_action( 'x2_support_add_title' ); ?></h3>

                <p>
                    <?php do_action( 'x2_support_add' ); ?>
                    <a id="x2_get_personal_help" class="button button-primary" href="#" title="<?php _e('Get personal help by the theme authors and write us right from your theme options panel - as soon as you purchase the x2 Premium Pack.', 'x2')?>" style="margin-right: 3px;">
                        <?php _e( 'Personal Help', 'x2' ) ?>
                    </a>
                    <a class="button secondary" href="http://support.themekraft.com/hc/en-us/categories/200002462-x2" target="_new">
                        <?php _e( 'Documentation', 'x2' ); ?>
                    </a>
                </p>
            </div>

            <div id="x2_ideas" style="float: left; overflow: auto; padding: 0 20px 0 20px; border-right: 1px solid #ddd;">
                <h3><?php _e( 'Contribute your ideas.', 'x2' ); ?></h3>

                <p>
                    <?php _e( 'Add ideas and vote in our', 'x2' ); ?>
                    <a class="button button-secondary" href="https://themekraft.zendesk.com/hc/communities/public/topics/200009291-x2-Ideas" target="_new">
                        <?php _e( 'Ideas Forums', 'x2' ); ?>
                    </a>
                </p>
            </div>

            <div id="x2_forums" class="wpforums" style="float: left; overflow: auto; padding: 0 20px 0 20px;">
                <h3><?php _e( 'Learn, share, discuss.', 'x2' ); ?></h3>

                <p><?php _e( 'Visit the free <a class="button button-secondary" href="http://wordpress.org/support/theme/x2" target="_new">WordPress Support Forum</a>', 'x2' ); ?> </p>
            </div>

        </div>

        <div id="x2_get_more" class="message updated premium_extension" style="margin: 10px 0 10px 0; padding: 10px 20px; border-left: 4px solid red; transition: background-color 200ms ease-out 1s; -webkit-transition: background-color 200ms ease-out 1s; -moz-transition: background-color 200ms ease-out 1s; -o-transition: background-color 200ms ease-out 1s;">
            <p style="font-size: 17px;"><em><?php _e('&raquo; Get more options and <b>personal help by the theme authors</b> with the <a style="color: #dd3333;" href="http://themekraft.com/store/x2-premium-pack/" target="_new">x2 Premium Pack</a> - from just 39$', 'x2'); ?></em></p>
        </div>


        <br><br>
        <form method="post" action="">
            <?php settings_fields( 'x2_options' ); ?>

            <div id="config-tabs">
                <ul>
                    <?php
                    $groups = cap_get_options();
                    foreach( $groups as $group ) :
                    $role_section = substr($group->id, 4) . "_min_role";
                    if(current_user_can('switch_themes') || current_user_can(strtolower($cap->$role_section))){
                        ?>
                            <li><a href='#<?php echo $group->id; ?>'><?php echo $group->name; ?></a></li>
                        <?php
                    }
                    endforeach;

                    if(!defined('is_pro') && current_user_can('switch_themes')){
                      $cap_getpro = __('More', 'x2');
                      echo " <li><a href='#cap_getpro'>$cap_getpro</a></li>";
                    }

                    ?>
                </ul>
                <?php
                foreach ( $groups as $group ) :
                    $id           = $group->id;
                    $role_section = substr( $id, 4 ) . "_min_role";
                    if ( current_user_can( 'switch_themes' ) || current_user_can( strtolower( $cap->$role_section ) ) ) {
                        ?>
                        <div id="<?php echo $id; ?>">
                            <?php
                            do_action( 'x2_before_settings_tab', $id );
                            $group->WriteHtml();
                            ?>
                        </div>
                    <?php
                    }
                endforeach;
                get_pro(); ?>
            </div>

            <p class="submit">
              <input type="submit" class="button-primary" value="<?php _e( 'Save Options','x2' ); ?>" />
            </p>

        </form>

        <?php if(current_user_can('switch_themes')): ?>
            <!--Manage options-->
            <br /><hr /><br />
            <fieldset class="import-export">
                <h2><?php _e('Import / Export Theme Options', 'x2')?></h2>
                <p><?php _e('A great way to save, share or duplicate your awesome theme options setup! And to make backups of your theme options. ;-)', 'x2')?></p>
                <form enctype="multipart/form-data" method="post" id="import_export_reset_form">
                    <p class="submit alignleft" style="padding: 20px 0;">
                        <input type="file" name="file" style="padding-bottom: 4px; background: #fff; height: 29px; padding-top: 5px; padding-left: 5px; border: 1px solid #ccc; margin-top: 0px; margin-right: 5px;" />
                    </p>
                    <p class="submit alignleft" style="padding: 20px 0;">
                        <input name="action" type="submit" value="<?php _e('Import','x2');?>" class="button button-secondary" />
                    </p>
                    <p class="submit alignleft" style="margin-left: 30px; padding: 20px 0 20px 30px; border-left: 1px solid #ccc; border-radius: 0;">
                        <input name="action" type="submit" value="<?php _e('Export','x2');?>" class="button button-secondary" />
                    </p>
                    <p class="submit alignright" style="padding: 20px 0;">
                        <input name="action" type="button" id="reset_theme_option_mask" value="<?php _e('Reset All Settings','x2');?>" class="button button-secondary" />
                        <input name="action" type="submit" style="display: none;" id="reset_theme_option" value="<?php _e('Reset All Settings','x2');?>" class="button button-secondary" />
                    </p>
                </form>
            </fieldset>
        <?php endif;?>

        <div class="clear"></div>

        <?php do_action('x2_theme_settings_screen'); ?>
  </div>
  <?php
}

class ImportData {
    var $dict = array();
}

function cap_serialize_export( $data ) {
    header( 'Content-disposition: attachment; filename=theme-export.txt' );
    echo serialize( $data );
    exit();
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function x2_theme_options_validate( $input ) {
    $cap_options = cap_get_options();

    $cap_options_types = Array();

    foreach ( $cap_options as $cap_option ) :
        $cap_option_arr = (Array) $cap_option;
        foreach ( $cap_option_arr['options'] AS $option ) {
            $cap_options_types[ $option->id ] = get_class( $option );
        }
    endforeach;

    foreach ( $input as $key => $value ) :
        if ( isset( $cap_options_types[ $key ] ) ) {
            switch ( $cap_options_types[ $key ] ) {
                case 'BooleanOption':
                    if( $input[$key] == 1 ? 1 : 0);
                    break;
                default:
                    if ( !is_string( $input[ $key ] ) ) {
                        $input[ $key ] = '';
                    }
                    break;
            }
        }
    endforeach;

    return $input;
}