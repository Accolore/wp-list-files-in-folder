<?php

/**
 * Fired during plugin activation
 *
 * @link       http://codecanyon.net/user/Accolore
 * @since      1.0.0
 *
 * @package    Wp_List_Files_In_Folder
 * @subpackage Wp_List_Files_In_Folder/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_List_Files_In_Folder
 * @subpackage Wp_List_Files_In_Folder/includes
 * @author     Lorenzo Accorinti <support@accolore.com>
 */
class Wp_List_Files_In_Folder_Activator {

	/**
	 * Plugin activation
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name    The slug of this plugin.
	 * @param    string    $version        The current version of the plugin.
	 */
	public static function activate( $plugin_name, $version ) {
		add_option( 'Activated_Plugin', $plugin_name );
	}
}
