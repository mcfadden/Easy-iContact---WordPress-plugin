=== Easy iContact ===
Contributors: benmcfadden
Tags: iContact
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: 0.3

Easy iContact allows point-and-click (and paste the shortcode) integration with iContact

== Description ==

Easy iContact is a plugin designed to make integrating an iContact signup form into your WordPress template.. well.. easy.

Since this is an early beta release, although it has shown to be functional in my tests, it is provided as-is.

At the moment, installation and use instructions are sparse, but they are coming.

Note: You need an [iContact](http://icontact.com/ "iContact") account to utilize this plugin. They do have a [free trial](http://icontact.com/pricing "iContact pricing"), and a micro plan that is always [free](https://www.icontact.com/email-marketing-free-solution "Free iContact plan").

= Shortcode Options =
Shortcode: `[easyicontact]`
__Shortcode optons:__
0 == false
1 == true


* __confirm_email__  (1 or 0) default: _true (1)_
* __first_name__ (1 or 0) default: _true (1)_
* __last_name__ (1 or 0) default: _true (1)_
* __validation__ (1 or 0) default: _true (1)_
* __label_type__ ('label' or 'value') Create HTML labels or insert the lable as the default value of a field. If value is chosen, upon click, the default value is removed. default: _label_
* __submit_image__ path/URL - If set to a value other than false, it will be used as the path/URL to a submit button image. Relative paths are relative from the [stylesheet_directory](http://codex.wordpress.org/Function_Reference/bloginfo, "Codex: bloginfo"). Absolute paths and URLs are used as-is. URLs must begin with "http://" or "https://".  If submit_image is set, submit_text is used as the alt text. Default: _false (0)_
* __submit_text__ text - Will show on the submit button if submit_image is false. If submit_image is used, submit_text is used as the alt text for submit_image. Default: _Sign up!_
* __wrapper_div__ (1 or 0) - If set to true (1) each label and input field is wrapped with a css tagged div element. This helps with styling.  default: _false (0)_


= Advanced Shortcode Options =
* __callback_function__ - function name - This JavaScript function will be called upon successful submit of the form. It is called immediately after the success message is displayed. Checks to make sure the function is defined.
* __icontact_listid__, __icontact_specialid__, __icontact_specialid_value__, __icontact_clientid__, __icontact_formid__ - string - When these 5 shortcode attributes are used in conjunction you can overwrite which signup-form the submission will go.
* __custom_fields__ - JSON string - This is designed for advanced users. This allows you to add custom fields to your form. In order to be stored at iContact, they will also need to be created in your iContact account. Possible values for the type are currently: text, textarea, and select. An example use of this attribtue can be seen here:
`custom_fields='{"school":{"type":"text","label":"School:"},"grade":{"type":"select","options":{"3rd":"3rd","4th":"4th","5th":"5th","Other":"Other"},"blank":"true","label":"Grade:"},"city":{"type":"text","label":"City:"}}'`


**Example Shortcode:**
`[easyicontact confirm_email='0' last_name='0' 'submit_text='Sign me up!' label_type='value' ]`


= Why use this plugin? =
When using the code as it comes from iContact, the user is directed away from your website. This allows you to have 100% control over the visual feeling and messaging the user sees. Also, by using asynchronous form submission, the process is quick and seamless to the end user.

== Installation ==

Install the plugin by uploading the `easyicontact` folder to your `/wp-content/plugins` directory or the plugins page in your WordPress dashboard.

At iContact, create a sign-up-form that you will use for this plugin. Copy the manual HTML code, and paste it into the settings page for Easy i-Contact. It will automatically parse out the fields it needs.

Next, use the shortcode guide (below and on the settings page for reference) and place your shortcode in a text widget or on a page. If you would like to use it in your template file, use the WordPress `do_shortcode` function. [do_shortcode reference](http://codex.wordpress.org/Function_Reference/do_shortcode)

Use the sample CSS on the settings page as a starting point when customizing the CSS for your template.

NOTE: Because this plugin is in very early development, things may change drastically. I will do my best to document all changes carefully, and keep backwards compatibility as much as reasonable.

== Other Information ==

= Known issues, feature requests, bug reporting =

Visit [https://github.com/mcfadden/Easy-iContact---WordPress-plugin/issues](https://github.com/mcfadden/Easy-iContact---WordPress-plugin/issues) to view known issues, submit feature requests, and bug reports.

== Changelog ==

= 0.4 =
* Feature: Add shortcode option wrapper_div to optionally wrap the label and input in a div statement. This helps with styling.
* Advanced User Feature: Added ability to use multiple iContact signup lists by specifying iContact parameters in shortcode.
* Advanced User Feature: Added ability to use custom fields.
* Documentation: Added documentation for Advanced User Features.

= 0.3 =
* Feature: Added a callback_function (JavaScript) that is called when the form is successfully submitted.
* Fixed Bug: When using AJAX submitting, the form fields weren't validated.
* Fixed Bug: In IE and Firefox when submitting the form, the value of the submit button would be truncated
* Enhancement: When a form submission has failed validation, when the user clicks into a field the validation-error class is now removed.
* Readme: Updated Installation title so it appears under the correct tab on the WordPress plugin page.
* Readme: Updated instructions for reporting issues
* Readme: Added shortcode options to Description tab

= 0.2 =
* Added to the WordPress plugin directory
* Added field label customization options
* Added example CSS to the settings page
* Added example shortcode settings to the settings page
* Added label_type attribute
* Added submit_image attribute

= 0.1 =
* Added custom shortcode with attributes
