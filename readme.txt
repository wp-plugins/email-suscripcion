=== Emailing Subscription ===
Contributors: seballero
Tags: subscription, e-mailing, mailing, emailing, mail, users
Requires at least: 3.4
Tested up to: 4.1
Stable tag: 1.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple WordPress plugin for e-mailing subscription list.

== Description ==

This plugin creates a list of subscribers through a simple form that can be added anywhere in the template.

Looking at your data in the WP Admin Area

This plugin provides 1 administration page

The mailing list in the admin page (http://you-site.com/wp-admin/admin.php?page=emailing_list) with option to export list in XLS format

Requirements:  
* WordPress 3.4 or later  

= Translators =

* Spanish (es_ES) - [Sebastián Orellana](http://www.seballero.com)

== Installation ==

1. Install and activate the plugin
2. Add this widget to your Sidebar.
3. [OPTIONAL] Add the form function in your template <code><?php if(function_exists('emailing_form')) { emailing_form();} ?></code>
4. [OPTIONAL] Apply custom styles
5. YOU'RE DONE!

== Frequently Asked Questions ==

= I can add the form "subscription" Anywhere on the Template? =

Yes, using the function <code><?php if(function_exists('emailing_form')) { emailing_form();} ?>.</code>

== Screenshots ==

1. The mailing list in the admin page.
2. The mailing list widget.
3. The mailing list widget in widgets section.

== Changelog ==

= 1.4.1 =
* Fixed fatal error in pagination

= 1.4 =
* Added Widget option.
* Added Delete Selected option.
* Added Date, Name and Reference fields.

= 1.3 =
* Fixed pagination bug.

= 1.2 =
* Added "Show per page" pagination filter. 
* Fixed pagination.

= 1.1 =
* Added Internationalization. 
* Translation for: Spanish, es_ES

= 1.0 =
* This is the first version of the plugin.