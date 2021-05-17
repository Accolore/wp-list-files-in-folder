=== Plugin Name ===
Contributors: Accolore
Donate link: https://paypal.me/accolore
Tags: files, directory, list
Requires at least: 4.8
Tested up to: 5.7.13.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

The plugin display the content of a specific folder, created by the plugin itself, into a page or a post.
To display the files list the plugin provide a shortcode, `[wp-list-files-in-folder]`.
The displayed files will be read ONLY from the folder `/wp-content/uploads/wp-lfif-folder`.
The plugin display the files and the subdirectories of the aforementioned folder in a treeview form, that allow to collapse and expand the folders and download the files by clicking on their names.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `wp-list-files-in-folder.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to your `wp-content/uploads/wp-lfif-folder/` and place the files and folders that you want to display
1. Use the `[wp-list-files-in-folder]` shortcode to display the files present in the `wp-content/uploads/wp-lfif-folder/` in your pages

== Changelog ==

= 1.0.0 =
* First release

== Upgrade Notice ==

= 1.0.0 =
First release