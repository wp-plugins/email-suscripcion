=== E-mailing Subscription ===
Contributors: Sebastián Orellana.
Tags: subscription, e-mailing, mailing, mail, users
Requires at least: 3.4
Tested up to: 3.4.2
Stable tag: 1.0
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

== Installation ==

1. Install and activate the plugin
2. Add the form function in your template  <?php if(function_exists('emailing_form')) { emailing_form();} ?>
4. [OPTIONAL] Apply custom styles
5. YOU'RE DONE!

== Frequently Asked Questions ==

= I can add the form "subscription" Anywhere on the Template? =

Yes, using the function <?php if(function_exists('emailing_form')) { emailing_form();} ?>.

== Screenshots ==

1. The mailing list in the admin page.

== Changelog ==

= 0.1 =
* This is the first version of the plugin.

