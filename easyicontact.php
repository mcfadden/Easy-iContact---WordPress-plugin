<?php
/*
Plugin Name: Easy iContact
Plugin URI: http://getdownwithup.com
Description: Makes seamless integration with iContact point and click.
Version: 0.1
Author: Ben McFadden
Author URI: http://benmcfadden.com

*/
?>
<?php
//Enable Shortcodes in widgets:
add_filter('widget_text', 'do_shortcode');

function easy_icontact_menu() {
	add_options_page('Easy iConact Options', 'Easy iContact', 'manage_options', 'easyicontact', 'easy_icontact_options_page');
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
  add_settings_section('easy_icontact_messages', 'Response Messages', 'easy_icontact_response_section', 'easy_icontact');
  add_settings_field('easy_icontact_success_message', 'Success Message (HTML)', 'easy_icontact_setting_success_message', 'easy_icontact', 'easy_icontact_messages');
  add_settings_field('easy_icontact_error_message', 'error Message (HTML)', 'easy_icontact_setting_error_message', 'easy_icontact', 'easy_icontact_messages');
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

function easy_icontact_response_section(){
  ?>
    <p>Enter the response HTML you would like displayed.</p>
  <?php
}

function easy_icontact_setting_success_message(){
  $options = get_option('easy_icontact_options');
  if(!isset($options['success_message']) || empty($options['success_message'])){
    $options['success_message'] = "<h2>Thank you!</h2>\n<p>You have successfully signed up to receive email update</p>";
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
    'table' => true,
    'ajax' => true,
    'validation' => true,
    'submit_text' => "Sign up!"
	), $atts ) );
  //$options = get_option('easy_icontact_options');
  
  $output = '';
  
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
      if(jQuery("input#fields_email").val() == \'\'){
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
        if(jQuery("input#fields_fname").val() == \'\'){
          jQuery("input#fields_fname").addClass("validation-error");
          error = true;
        }else{
          jQuery("#fields_fname").removeClass("validation-error");
        }
        ';
      }
      
      if(true == (bool)$last_name){
        $output .= '
        if(jQuery("input#fields_lname").val() == \'\'){
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
          "", 
          jQuery("#easyicontact").serialize() + "&ajax=true",
            function(data){ 
              jQuery("div#easyicontact_wrapper").html(data);
            } 
          );
        return false;
      ';
    }
    $output .= '
        });
      });
    </script>';
  }
  
  $output .= '
  <div id="easyicontact_wrapper">
    <form name="easyicontact" id="easyicontact" method="POST" action="" />
      <label for="fields_email">Email Address</label>
      <input type="text" name="fields_email" id="fields_email" ';
      
      if(isset($_GET['fields_email'])){
        $output .= 'value="' . $_GET['fields_email'] . '" ';
      }
      
      $output .= '/>
      ';
      
      if(true == (bool)$confirm_email){
        $output .= '
          <label for="fields_confirm_email">Comfirm Email</label>
          <input type="text" name="fields_confirm_email" id="fields_confirm_email" />
        ';
      }
      
      if(true == (bool)$first_name){
        $output .= '
          <label for="fields_fname">First Name</label>
          <input type="text" name="fields_fname" id="fields_fname" />
        ';
      }
      
      if(true == (bool)$last_name){
        $output .= '
          <label for="fields_lname">Last Name</label>
          <input type="text" name="fields_lname" id="fields_lname" />
        ';
      }
      
      $output .= '
      <input type="hidden" name="easyicontact" value="true" />
      <input class="submit-button" type="submit" value="' . $submit_text . '" />
    </form>
  </div>';
  

	return $output;
}
add_shortcode( 'easyicontact', 'easyicontacttag_func' );

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


?>
