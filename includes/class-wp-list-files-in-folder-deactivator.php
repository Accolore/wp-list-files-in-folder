<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://codecanyon.net/user/Accolore
 * @since      1.0.0
 *
 * @package    Wp_List_Files_In_Folder
 * @subpackage Wp_List_Files_In_Folder/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_List_Files_In_Folder
 * @subpackage Wp_List_Files_In_Folder/includes
 * @author     Lorenzo Accorinti <support@accolore.com>
 */
class Wp_List_Files_In_Folder_Deactivator {

	/**
	 * Plugin deactivation
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name    The slug of this plugin.
	 * @param    string    $version        The current version of the plugin.	 
	 */
	public static function deactivate( $plugin_name, $version ) {
	}

}
