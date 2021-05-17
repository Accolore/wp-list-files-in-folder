<?php

/**
 *
 * @link              http://codecanyon.net/user/Accolore
 * @since             1.0.0
 * @package           Wp_List_Files_In_Folder
 *
 * @wordpress-plugin
 * Plugin Name:       WP List Files In Folder
 * Plugin URI:        #
 * Description: This plugin provide a shortcode, "[wp-list-files-in-folder]", that display a list (with links) of the files in a folder (wp-content/wp_lfif_folder). The user can download the files by clicking on the link and can navigate in the subfolders.
 * Version:           1.0.0
 * Author:            Lorenzo Accorinti
 * Author URI:        http://codecanyon.net/user/Accolore
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-list-files-in-folder
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'WPLFIF_VERSION' ) ) {
	define( 'WPLFIF_VERSION', '1.0.0' );
}
if ( ! defined( 'WPLFIF_NAME' ) ) {
	define( 'WPLFIF_NAME', 'wp-list-files-in-folder' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-list-files-in-folder-activator.php
 */
function activate_wp_list_files_in_folder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-list-files-in-folder-activator.php';
	Wp_List_Files_In_Folder_Activator::activate( WPLFIF_NAME, WPLFIF_VERSION );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-list-files-in-folder-deactivator.php
 */
function deactivate_wp_list_files_in_folder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-list-files-in-folder-deactivator.php';
	Wp_List_Files_In_Folder_Deactivator::deactivate( WPLFIF_NAME, WPLFIF_VERSION );
}

register_activation_hook( __FILE__, 'activate_wp_list_files_in_folder' );
register_deactivation_hook( __FILE__, 'deactivate_wp_list_files_in_folder' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-list-files-in-folder.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_list_files_in_folder() {

	$plugin = new Wp_List_Files_In_Folder( WPLFIF_NAME, WPLFIF_VERSION );
	$plugin->run();

}
run_wp_list_files_in_folder();
