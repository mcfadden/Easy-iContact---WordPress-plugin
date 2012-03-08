=== Easy iContact ===
Contributors: benmcfadden
Tags: iContact
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: 0.2

Easy iContact allows point-and-click (and paste the shortcode) integration with iContact

== Description ==

Easy iContact is a plugin designed to make integrating an iContact signup form into your WordPress template.. well.. easy.

Since this is an early beta release, although it has shown to be functional in my tests, it is provided as-is.

At the moment, installation and use instructions are sparse, but they are coming. Additionally, a website with an author-contact form will be around shortly.

Note: You need an [iContact](http://icontact.com/ "iContact") account to utilize this plugin. They do have a [free trial](http://icontact.com/pricing "iContact pricing"), and a micro plan that is always [free](https://www.icontact.com/email-marketing-free-solution "Free iContact plan").

== Instructions ==

Install the plugin by uploading the `easyicontact` folder to your `/wp-content/plugins` directory or the plugins page in your WordPress dashboard.

At iContact, create a sign-up-form that you will use for this plugin. Copy the manual HTML code, and paste it into the settings page for Easy i-Contact. It will automatically parse out the fields it needs.

Next, use the shortcode guide and place your shortcode in a widget. If you would like to use it in your template file, use the WordPress `do_shortcode` function. [do_shortcode reference](http://codex.wordpress.org/Function_Reference/do_shortcode)

Use the sample CSS on the settings page as a starting point when customizing the CSS for your template.

NOTE: Because this plugin is in very early development, things may change drastically. I will do my best to document all changes carefully, and keep backwards compatibility as much as reasonable.

== Changelog ==

= 0.2 =
* Added to the WordPress plugin directory
* Added field label customization options
* Added example CSS to the settings page
* Added example shortcode settings to the settings page
* Added label_type attribute
* Added submit_image attribute

= 0.1 =
* Added custom shortcode with attributes