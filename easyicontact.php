<?php
/*
Plugin Name: Easy iContact

Description: Makes seamless integration with iContact point and click.
Version: 0.3.1
Author: Ben McFadden
Author URI: http://benmcfadden.com
URI: https://github.com/mcfadden/Easy-iContact---WordPress-plugin

*/
?>
<?php
//Enable Shortcodes in widgets:
add_filter('widget_text', 'do_shortcode');

function easy_icontact_menu() {
	add_options_page('Easy iContact Options', 'Easy iContact', 'manage_options', 'easyicontact', 'easy_icontact_options_page');
}
add_action('admin_menu', 'easy_icontact_menu');

function easy_icontact_options_page() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

?>
<div class="wrap">
  <h2>East iContact</h2>
  Easy iContact configuration.
<form action="options.php" method="post">
<?php settings_fields('easy_icontact_options'); ?>
<?php do_settings_sections('easy_icontact'); ?>
	<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
	</form>
<h3>Shortcode settings</h3>
  <p>Shortcode: [easyicontact]</p>
  <p>Shortcode optons:<br />
  0 == false<br />
  1 == true</p>
  <ul>
    <li><strong>confirm_email</strong> (1 or 0) default: <em>true (1)</em></li>
    <li><strong>first_name</strong> (1 or 0) default: <em>true (1)</em></li>
    <li><strong>last_name</strong> (1 or 0) default: <em>true (1)</em></li>
    <!--<li><strong>table</strong> put the items in a table (1 or 0) default: <em>true (1)</em> NOTE: Not yet implemented</li>-->
    <li><strong>ajax</strong> (1 or 0) default: <em>true (1)</em> NOTE: while this setting is implemented, when not using AJAX to process requests, it may respond a bit strange. This feature may be removed in future versions.</li>
    <li><strong>validation</strong> (1 or 0) default: <em>true (1)</em></li>
    <li><strong>label_type</strong> ('label' or 'value') Create HTML labels or insert the lable as the default value of a field. If value is chosen, upon click, the default value is removed. default: <em>&quot;label&quot;</em></li>
    <li><strong>submit_image</strong> path/URL - If set to a value other than false, it will be used as the path/URL to a submit button image. Relative paths are relative from the <a href="http://codex.wordpress.org/Function_Reference/bloginfo" target="_blank">stylesheet_directory</a> (<em><?php bloginfo('stylesheet_directory'); ?></em>). Absolute paths and URLs are used as-is. URLs must begin with "http://" or "https://".  If submit_image is set, submit_text is used as the alt text. Default: <em>false (0)</em></li>
    <li><strong>submit_text</strong> text - Will show on the submit button if submit_image is false. If submit_image is used, submit_text is used as the alt text for submit_image. Default: &quot;Sign up!&quot;</li>
	<li><strong>callback_function</strong> function name - This JavaScript function will be called upon successful submit of the form. It is called immediately after the success message is displayed. Checks to make sure the function is defined.</li>
  </ul>
  <p>Example Shortcode:</p>
  <pre>[easyicontact confirm_email='0' last_name='0' 'submit_text='Sign me up!' label_type='value' ]</pre>
  <h3>Example CSS</h3>
  <p>You'll have to add this to your template's CSS file. In future versions, I may enable a custom CSS option, but for now you have to put it in your template's CSS file and edit there.</p>
  <pre>
    /* EasyiContact */

/* Wrapper div around the form element */
div#easyicontact_wrapper{

}
/* Text input fields */
div#easyicontact_wrapper input[type=text]{
  background-color: #FFFFFF;
  border: 1px solid #CCCCCC;
  width: 140px;
  height: 15px;
  color: #000000;
  padding: 2px;
}
/* Text input field that failed validation */
div#easyicontact_wrapper input.validation-error{
  border: 1px solid #FF0000;
}
/* Text input field with default text (when label_type='value' ) */
div#easyicontact_wrapper input.default{
  color: #CCCCCC;
}
/* Image submit */
div#easyicontact_wrapper input.submit-image{
  background-color: transparent;
  color: #000000;
}
/* Button submit */
div#easyicontact_wrapper input.submit-button{
  color: #000000;
}
  </pre>
</div>
<?php
}

add_action('admin_init', 'easy_icontact_admin_init');
function easy_icontact_admin_init(){
  wp_enqueue_script('jquery');

  register_setting( 'easy_icontact_options', 'easy_icontact_options', 'easy_icontact_options_validate' );

  add_settings_section('easy_icontact_main', 'iContact Account Settings', 'easy_icontact_section_text', 'easy_icontact');
  add_settings_field('easy_icontact_listid', 'List ID', 'easy_icontact_setting_listid', 'easy_icontact', 'easy_icontact_main');
  add_settings_field('easy_icontact_specialid', 'Special ID<br />(Should be the same as ListID)', 'easy_icontact_setting_specialid', 'easy_icontact', 'easy_icontact_main');
  add_settings_field('easy_icontact_specialidvalue', 'Special ID Value', 'easy_icontact_setting_specialidvalue', 'easy_icontact', 'easy_icontact_main');
  add_settings_field('easy_icontact_clientid', 'Client ID', 'easy_icontact_setting_clientid', 'easy_icontact', 'easy_icontact_main');
  add_settings_field('easy_icontact_formid', 'Form ID', 'easy_icontact_setting_formid', 'easy_icontact', 'easy_icontact_main');

  add_settings_section('easy_icontact_fields', 'Field Labels', 'easy_icontact_fields_section', 'easy_icontact');
  add_settings_field('easy_icontact_fields_fname_label', 'First Name:', 'easy_icontact_setting_fields_fname_label', 'easy_icontact', 'easy_icontact_fields');
  add_settings_field('easy_icontact_fields_lname_label', 'Last Name:', 'easy_icontact_setting_fields_lname_label', 'easy_icontact', 'easy_icontact_fields');
  add_settings_field('easy_icontact_fields_email_label', 'Email Address:', 'easy_icontact_setting_fields_email_label', 'easy_icontact', 'easy_icontact_fields');
  add_settings_field('easy_icontact_fields_confirm_email_label', 'Confirm Email Address:', 'easy_icontact_setting_fields_confirm_email_label', 'easy_icontact', 'easy_icontact_fields');

  add_settings_section('easy_icontact_messages', 'Response Messages', 'easy_icontact_response_section', 'easy_icontact');
  add_settings_field('easy_icontact_success_message', 'Success Message (HTML)', 'easy_icontact_setting_success_message', 'easy_icontact', 'easy_icontact_messages');
  add_settings_field('easy_icontact_error_message', 'Error Message (HTML)', 'easy_icontact_setting_error_message', 'easy_icontact', 'easy_icontact_messages');
}

function easy_icontact_section_text() {
  ?>
  <script type="text/javascript">
    function parse_html(){
      html = jQuery('textarea#raw_html').val();

      listid = html.split('"listid" value="', 2);
      if(listid == html){
        alert("Error: listid not found in provided HTML");
        return;
      }
      if(listid[1].split('">', 1) == listid){
        alert("Error: invalid listid found in provided HTML");
        return;
      }
      jQuery('input#easy_icontact_listid').val(listid[1].split('">', 1));

      specialid = html.split('name="specialid:', 2);
      if(specialid == html){
        alert("Error: specialid not found in provided HTML");
        return;
      }
      if(specialid[1].split('" value="', 1) == specialid){
        alert("Error: invalid specialid found in provided HTML");
        return;
      }
      jQuery('input#easy_icontact_specialid').val(specialid[1].split('" value="', 1));

      specialidvalue = html.split('name="specialid:', 2);
      if(specialidvalue == html){
        alert("Error: specialidvalue not found in provided HTML");
        return;
      }
      if(specialidvalue[1].split('" value="', 2) == specialidvalue){
        alert("Error: invalid specialidvalue found in provided HTML");
        return;
      }
      specialidvalue = specialidvalue[1].split('" value="', 2);
      if(specialidvalue[1].split('">', 1) == specialidvalue){
        alert("Error: invalid specialidvalue found in provided HTML");
        return;
      }
      jQuery('input#easy_icontact_specialidvalue').val(specialidvalue[1].split('">', 1));

      clientid = html.split('"clientid" value="', 2);
      if(clientid == html){
        alert("Error: clientid not found in provided HTML");
        return;
      }
      if(clientid[1].split('">', 1) == clientid){
        alert("Error: invalid clientid found in provided HTML");
        return;
      }
      jQuery('input#easy_icontact_clientid').val(clientid[1].split('">', 1));

      formid = html.split('"formid" value="', 2);
      if(formid == html){
        alert("Error: formid not found in provided HTML");
        return;
      }
      if(formid[1].split('">', 1) == formid){
        alert("Error: invalid formid found in provided HTML");
        return;
      }
      jQuery('input#easy_icontact_formid').val(formid[1].split('">', 1));




    }
  </script>
  <p>Please enter in the details from your sign-up form, or paste the generated form in the textarea below:</p>
  <textarea id="raw_html" name="raw_html" cols="80" rows="5">Paste the HTML code from iContact into this box, and then click "Parse Code" below. Be sure to click "Save Changes" when you are done.</textarea><br />
  <input type="button" name="parse" value="Parse Code" onClick="parse_html()" />
  <?php

  }

function easy_icontact_setting_listid(){
  $options = get_option('easy_icontact_options');
  echo "<input id='easy_icontact_listid' name='easy_icontact_options[listid]' size='40' type='text' value='" . $options['listid'] . "' />";
}

function easy_icontact_setting_specialid(){
  $options = get_option('easy_icontact_options');
  echo "<input id='easy_icontact_specialid' name='easy_icontact_options[specialid]' size='40' type='text' value='" . $options['specialid'] . "' />";
}

function easy_icontact_setting_specialidvalue(){
  $options = get_option('easy_icontact_options');
  echo "<input id='easy_icontact_specialidvalue' name='easy_icontact_options[specialidvalue]' size='40' type='text' value='" . $options['specialidvalue'] . "' />";
}

function easy_icontact_setting_clientid(){
  $options = get_option('easy_icontact_options');
  echo "<input id='easy_icontact_clientid' name='easy_icontact_options[clientid]' size='40' type='text' value='" . $options['clientid'] . "' />";
}

function easy_icontact_setting_formid(){
  $options = get_option('easy_icontact_options');
  echo "<input id='easy_icontact_formid' name='easy_icontact_options[formid]' size='40' type='text' value='" . $options['formid'] . "' />";
}

function easy_icontact_fields_section(){
  ?>
    <p>Enter the text you would like to use to identify the fields.</p>
  <?php
}
function easy_icontact_setting_fields_fname_label(){
  $options = get_option('easy_icontact_options');
  if(!isset($options['fname_label']) || empty($options['fname_label'])){
    $options['fname_label'] = "First Name";
  }
  echo "<input id='easy_icontact_fields_fname_label' name='easy_icontact_options[fname_label]' size='40' type='text' value='" . $options['fname_label'] . "' />";
}
function easy_icontact_setting_fields_lname_label(){
  $options = get_option('easy_icontact_options');
  if(!isset($options['lname_label']) || empty($options['lname_label'])){
    $options['lname_label'] = "Last Name";
  }
  echo "<input id='easy_icontact_fields_lname_label' name='easy_icontact_options[lname_label]' size='40' type='text' value='" . $options['lname_label'] . "' />";
}
function easy_icontact_setting_fields_email_label(){
  $options = get_option('easy_icontact_options');
  if(!isset($options['email_label']) || empty($options['email_label'])){
    $options['email_label'] = "Email Address";
  }
  echo "<input id='easy_icontact_fields_email_label' name='easy_icontact_options[email_label]' size='40' type='text' value='" . $options['email_label'] . "' />";
}
function easy_icontact_setting_fields_confirm_email_label(){
  $options = get_option('easy_icontact_options');
  if(!isset($options['confirm_email_label']) || empty($options['confirm_email_label'])){
    $options['confirm_email_label'] = "Comfirm Email Address";
  }
  echo "<input id='easy_icontact_fields_confirm_email_label' name='easy_icontact_options[confirm_email_label]' size='40' type='text' value='" . $options['confirm_email_label'] . "' />";
}

function easy_icontact_response_section(){
  ?>
    <p>Enter the response HTML you would like displayed.</p>
  <?php
}

function easy_icontact_setting_success_message(){
  $options = get_option('easy_icontact_options');
  if(!isset($options['success_message']) || empty($options['success_message'])){
    $options['success_message'] = "<h2>Thank you!</h2>\n<p>You have successfully signed up to receive email updates</p>";
  }
  echo "<textarea id='easy_icontact_success_message' name='easy_icontact_options[success_message]' cols='80' rows='5' type='text'>" . $options['success_message'] . "</textarea>";
}

function easy_icontact_setting_error_message(){
  $options = get_option('easy_icontact_options');
  if(!isset($options['error_message']) || empty($options['error_message'])){
    $options['error_message'] = "<h2>Oops!</h2>\n<p>Something went wrong! Please try again later;</p>";
  }
  echo "<textarea id='easy_icontact_error_message' name='easy_icontact_options[error_message]' cols='80' rows='5' type='text'>" . $options['error_message'] . "</textarea>";
}

function easy_icontact_options_validate($input) {
  $newinput['listid'] = trim($input['listid']);
  $newinput['specialid'] = trim($input['specialid']);
  $newinput['specialidvalue'] = trim($input['specialidvalue']);
  $newinput['clientid'] = trim($input['clientid']);
  $newinput['formid'] = trim($input['formid']);
  $newinput['fname_label'] = trim($input['fname_label']);
  $newinput['lname_label'] = trim($input['lname_label']);
  $newinput['email_label'] = trim($input['email_label']);
  $newinput['confirm_email_label'] = trim($input['confirm_email_label']);
  $newinput['success_message'] = trim($input['success_message']);
  $newinput['error_message'] = trim($input['error_message']);
  /*if(!preg_match('/^[a-z0-9]{32}$/i', $newinput['text_string'])) {
    $newinput['text_string'] = '';
  }*/
  return $newinput;
}

// [bartag foo="foo-value"]
function easyicontacttag_func( $atts ) {
	extract( shortcode_atts( array(
    'confirm_email' => true,
    'first_name' => true,
    'last_name' => true,
    'table' => true, //not yet implemented
    'ajax' => true,
    'validation' => true,
    'label_type' => 'label',
    'submit_image' => false,
    'submit_text' => "Sign up!",
	  'callback_function' => false,
    'custom_fields' => null,
    'wrapper_div' => false
	), $atts ) );
  $options = get_option('easy_icontact_options');
  
  global $config;
  $config['label_type'] = $label_type;
  $config['confirm_email'] = $confirm_email;
  $config['first_name'] = $first_name;
  $config['last_name'] = $last_name;
  $config['table'] = $table;
  $config['ajax'] = $ajax;
  $config['validation'] = $validation;
  $config['label_type'] = $label_type;
  $config['submit_image'] = $submit_image;
  $config['submit_text'] = $submit_text;
  $config['callback_function'] = $callback_function;
  $config['custom_fields'] = $custom_fields;
  $config['wrapper_div'] = $wrapper_div;

  $output = '';
  //$output .= print_r($options, true); //DEBUG
  if(true == (bool)$validation || true == (bool)$ajax){
    global $add_jquery;
    $add_jquery = true;
    $output .= '
    <script type="text/javascript" />
      jQuery(document).ready(function(){
        jQuery("form#easyicontact").submit(function(){

          ';
    if(true == (bool)$validation){
      $output .= '
        /* Validation */
        var error = new Boolean(false);
      ';

      $output .= '
      if(jQuery("input#fields_email").val() == \'\' || jQuery("input#fields_email").val() == jQuery("input#fields_email")[0].defaultValue ){
        jQuery("input#fields_email").addClass("validation-error");
        error = true;
      }else{
        jQuery("#fields_email").removeClass("validation-error");
      }
      ';

      if(true == (bool)$confirm_email){
        $output .= '
        if(jQuery("input#fields_confirm_email").val() == \'\' || jQuery("input#fields_confirm_email").val() != jQuery("input#fields_email").val() ){
          jQuery("input#fields_confirm_email").addClass("validation-error");
          error = true;
        }else{
          jQuery("#fields_confirm_email").removeClass("validation-error");
        }
        ';
      }

      if(true == (bool)$first_name){
        $output .= '
        if(jQuery("input#fields_fname").val() == \'\' || jQuery("input#fields_fname").val() == jQuery("input#fields_fname")[0].defaultValue ){
          jQuery("input#fields_fname").addClass("validation-error");
          error = true;
        }else{
          jQuery("#fields_fname").removeClass("validation-error");
        }
        ';
      }

      if(true == (bool)$last_name){
        $output .= '
        if(jQuery("input#fields_lname").val() == \'\' || jQuery("input#fields_lname").val() == jQuery("input#fields_lname")[0].defaultValue ){
          jQuery("input#fields_lname").addClass("validation-error");
          error = true;
        }else{
          jQuery("#fields_lname").removeClass("validation-error");
        }
        ';
      }

      $output .= '
        if(false != error){
          return false;
        };
      ';

    }
    if(true == (bool)$ajax){
      $output .= '
        /* Ajax submission code here */
         //Submit form
         jQuery.post(
         "./",
         jQuery("#easyicontact").serialize() + "&ajax=true",
           function(data){
             jQuery("div#easyicontact_wrapper").html(data);';
             if(false != $callback_function){
               $callback_function = rtrim($callback_function, "(); ") . '()';
               $output .= '
               if(window.' . $callback_function . '){
                 ' . $callback_function . ';
               }
               ';
             }
   	$output .= '
           }
         );
        return false;
      ';
    }
    $output .= '
        });
        jQuery("#easyicontact input[type=text]").focus(function(){
          if (jQuery(this).val() == this.defaultValue) {
            jQuery(this).val("").removeClass("default");
          }
		  jQuery(this).removeClass("validation-error");
        });
        jQuery("#easyicontact input[type=text]").blur(function(){
          if (jQuery(this).val() == "") {
            jQuery(this).val(this.defaultValue).addClass("default");
          }
        });


      });
    </script>';
  }


  $output .= '
  <div id="easyicontact_wrapper">
    <form name="easyicontact" id="easyicontact" method="POST" action="" />';
    if(true == (bool)$first_name){
      $output .= default_field("fname", $options['fname_label']);
    }

    if(true == (bool)$last_name){
      $output .= default_field("lname", $options['lname_label']);
    }

      $output .= default_field("email", $options['email_label']);
    
    /*  if(isset($_GET['fields_email'])){
        $output .= 'value="' . $_GET['fields_email'] . '" ';
      }elseif('value' == $label_type){
        $output .= 'class="default" value="' . $options['email_label'] . '" ';
      }
      */
      
    if(true == (bool)$confirm_email){
      $output .= default_field("confirm_email", $options['confirm_email_label']);
    }

    if($custom_field_data = json_decode($custom_fields)){
      $valid_types = array('text','select','checkbox','radio','textarea');
      foreach($custom_field_data as $custom_field => $params){
        if(in_array($params->type, $valid_types)){
          $type = $params->type;
        }else{
          $type = "text";
        }
        if(in_array($type, array('select','checkbox','radio')) && 0 == count($params->options)){
          $output .= "<p>ERROR: Must include at least one option</p>";
        }else{
          if("text" == $type || "textarea" == $type){
            if('value' != $label_type && !empty($params->label)){
              $output .= '<label for="fields_' . $custom_field . '">' . $params->label . '</label>';
            }
          }
          if('text' == $type){
            $output .= '<input type="text" name="fields_' . $custom_field . '" id="fields_' . $custom_field . '"';
            if('value' == $label_type){
              $output .= 'class="default" value="' . $params->label . '" ';
            }
            $output .= ' />';
          }elseif('textarea' == $type){
            $output .= '<textarea name="fields_' . $custom_field . '" id="fields_' . $custom_field . '">';
            if('value' == $label_type){
              $output .= $params->label;
            }
            $output .= '</textarea>';
          }elseif('select' == $type){
            if(!empty($params->label)){
              $output .= '<label for="fields_' . $custom_field . '">' . $params->label . '</label>';
            }
            $output .= '<select name="fields_' . $custom_field . '" id="fields_' . $custom_field . '">';
            if("true" == $params->blank){
              $output .= '<option value=""></option>';
            }
            foreach($params->options as $display => $value){
              if(!empty($value)){
                $output .= '<option value="' . $value . '">' . $display . '</option>';
              }else{
                $output .= '<option value="' . $display . '">' . $display . '</option>';
              }
            }
            $output .= '</select>';
          }elseif('radio' == $type){
            $output .= "<p>radio type input is not yet supported. If you would like to contribute, please for this project at github: https://github.com/mcfadden/Easy-iContact---WordPress-plugin</p>";
          }elseif('checkbox' == $type){
            $output .= "<p>checkbox type input is not yet supported. If you would like to contribute, please for this project at github: https://github.com/mcfadden/Easy-iContact---WordPress-plugin</p>";
          }
        }
      }
    }


      $output .= '<input type="hidden" name="easyicontact" value="true" />';

      if(false != (bool)$submit_image){
        if(false !== stripos($submit_image, 'http://') || false !== stripos($submit_image, 'https://')){ //URL
          $output .= '<input class="submit-image" type="image" alt="' . $submit_text . '" src="' . $submit_image . '" />';
        }elseif(0 == strpos($submit_image, '/')){ //Absolute Path
          $output .= '<input class="submit-image" type="image" alt="' . $submit_text . '" src="' . $submit_image . '" />';
        }else{ //Relative Path
          $output .= '<input class="submit-image" type="image" alt="' . $submit_text . '" src="' . get_bloginfo('stylesheet_directory') . '/' . ltrim($submit_image, '/') . '" />';
        }
      }else{
        $output .= '<input class="submit-button" type="submit" value="' . $submit_text . '" />';
      }

      $output .='
    </form>
  </div>';


	return $output;
}
add_shortcode( 'easyicontact', 'easyicontacttag_func' );

function default_field($field, $label){
  $this_output = "";
  global $config;
  if($config['wrapper_div']){
    $this_output .= '<div id="fields_' . $field . '_wrapper" class="field_wrapper">';
  }
  if('value' != $config['label_type']){
    $this_output .= '<label for="fields_' . $field . '">' . $label . '</label>';
  }
  $this_output .= '<input type="text" name="fields_' . $field . '" id="fields_' . $field . '" ';
  if('value' == $config['label_type']){
    $this_output .= 'class="default" value="' . $label . '" ';
  }
  $this_output .= ' />';
  if($config['wrapper_div']){
    $this_output .= '</div>';
  }
  return $this_output;
}

/* FAILED attempte to load jQuery in the footer
add_action('wp_footer', 'print_my_script');
function print_my_script() {
	global $add_jquery;

	if ( ! $add_jquery )
		return;

	wp_print_scripts('jquery');
}
/**/


add_action('template_redirect', 'add_jquery');
function add_jquery() {
	wp_enqueue_script('jquery');
}

add_action('init', 'easyicontact_process_request');
function easyicontact_process_request(){
  if(true == $_POST['easyicontact']){
    $options = get_option('easy_icontact_options');

    $url = "http://app.icontact.com/icp/signup.php";

    $post_data = array(
      'source' => $_SERVER['REQUEST_URI'],
      'listid' => $options['listid'],
      'specialid:' . $options['specialid'] => $options['specialidvalue'],
      'clientid' => $options['clientid'],
      'formid' => $options['formid'],
      'reallistid' => '1',
      'doubleopt' => '0',
      'fields_email' => $_POST['fields_email'],
      'fields_fname' => $_POST['fields_fname'],
      'fields_lname' => $_POST['fields_lname']
    );
    $response = wp_remote_post( $url, array(
        'method' => 'POST',
        'timeout' => 5,
        'redirection' => 5,
        'httpversion' => 1.0,
        'blocking' => true,
        'headers' => array(),
        'body' => $post_data,
        'cookies' => array()
      )
    );

    if( is_wp_error( $response ) ) {
      echo $options['error_message'];
    } else {
      echo $options['success_message'];
    }
    exit();
  }
}