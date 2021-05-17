<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://codecanyon.net/user/Accolore
 * @since      1.0.0
 *
 * @package    Wp_List_Files_In_Folder
 * @subpackage Wp_List_Files_In_Folder/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_List_Files_In_Folder
 * @subpackage Wp_List_Files_In_Folder/public
 * @author     Lorenzo Accorinti <support@accolore.com>
 */
class Wp_List_Files_In_Folder_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The allowed mime types
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $allowed_mime_types    The allowed mime types array
	 */
	private $allowed_mime_types;	

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$options = get_option('wplfif_options');

		$this->allowed_mime_types = array_map('trim', explode("\n", $options['allowed_mime_types']));
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-list-files-in-folder-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'dashicons' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-list-files-in-folder-public.js', array(), $this->version, true );
	}

	/**
	 * Plugin shortcode
	 *
	 * @since    1.0.0
	 * @param    array    $atts 	The shortcode attributes array
	 * @param    string   $content	The shortcode content
	 * @return   mixed    $result 	The shortcode output
	 */
	public function list_files_shortcode( $atts,  $content = null ) {
		/*
		extract(shortcode_atts( array(
			'subfolder' => '',
		), $atts ));
		*/
		$raw_html = '';
		$raw_html .= $this->_generate_html();

		ob_start();
		include 'partials/shortcode.php';
		$result = ob_get_clean();
	
		return $result;
	}

	/**
	 * Return the path or the url of the file folder
	 *
	 * @since    1.0.0
	 * @return   string    $result 	The string with the path or the url
	 */
	private function _get_base_folder($need_url = false) {
		$upload_dir = wp_upload_dir(); 
		$file_root  = '/wp-lfif-folder/';
		$result     = $upload_dir['basedir'] . $file_root;

		if ($need_url) {
			$result = $upload_dir['baseurl'] . $file_root;
		} 

		return $result;
	}

	/**
	 * Generate the HTML for the list to display
	 *
	 * @since    1.0.0
	 * @return   string    $raw_html 	The raw html of the file list
	 */
	private function _generate_html() {
		$path = $this->_get_base_folder(false);
		$upload_dir = wp_upload_dir(); 
		$file_root  = '/wp-lfif-folder/';

		if( !file_exists($path) ) {
			$raw_html = '<p>' . sprintf(__('Error: unable to find %s folder', $this->plugin_name), $file_root) . '</p>';
		} else {
			if ( $this->_is_folder_empty($path) ) {

				$raw_html = '<p>' . __('No files or folders available.', $this->plugin_name) . '</p>';
			} else {
				$raw_html = $this->_generate_html_recursive($path);
			}
		}
		return $raw_html;
	}

	/**
	 * Recursive function for the file list generation
	 *
	 * @since    1.0.0
	 * @return   <array>    $result 	The array with the file list
	 */
	private function _generate_html_recursive($dir) {
		$files = scandir($dir);
		$results = '';

		foreach ( $files as $key => $value ) {
			$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
			$mime_type = mime_content_type($path);
			if( !is_dir($path) ) {
				if ( $this->_is_mime_type_allowed($mime_type) ) {
					$size = '(' . $this->_get_human_filesize($path) . ')';
					$icon = $this->_get_file_icon($mime_type);
					$url  = $this->_get_url_from_path($path);
					$results .= sprintf($this->_get_file_string(), $url, $icon, $value, $size);
				}
			} else if( $value != "." && $value != ".." ) {
				$results .= sprintf($this->_get_folder_start_string(), $value);
				$results .= $this->_generate_html_recursive($path);
				$results .= sprintf($this->_get_folder_end_string(), $value);
			}
		}
		return $results;
	}

	/**
	 * Return the file url given the file path
	 *
	 * @since    1.0.0
	 * @return   string    The url of the file
	 */
	private function _get_url_from_path($path) {
		$basepath = $this->_get_base_folder(false);
		$baseurl  = $this->_get_base_folder(true);

		// use the same folder separatore for comparation
		$path = str_replace("\\", "/", $path);
		$basepath = str_replace("\\", "/", $basepath);
	
		return str_replace($basepath, $baseurl, $path);
	}

	/**
	 * Return the string for the html markup for the file element
	 *
	 * @since    1.0.0
	 * @return   string    The html markup for the file element
	 */
	private function _get_file_string() {
		return '<li><a class="file" href="%1s" download><i class="dashicons %2s"></i> <span>%3s</span> <span class="right">%4s</span></a></li>';
	}

	/**
	 * Return the string for the html markup for the start of the folder element
	 * @since    1.0.0
	 * @return   string    The html markup for the start of the folder element
	 */
	private function _get_folder_start_string() {
		return '<li><a class="folder" href="#"><i class="dashicons dashicons-category"></i> <span>%1s</span> <i class="right dashicons dashicons-arrow-left"></i></a><ul>';
	}

	/**
	 * Return the string for the html markup for the end of the folder element
	 * @since    1.0.0
	 * @return   string    The html markup for the end of the folder element
	 */
	private function _get_folder_end_string() {
		return '</ul></li>';
	}

	/**
	 * Return ti file size in human format
	 *
	 * @since    1.0.0
	 * @return   string    The filesize
	 */
	function _get_human_filesize($path) {
		$decimals = 2;
		$bytes    = filesize($path);
		$sz       = 'BKMGTP';
		$factor   = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
	}

	/**
	 * Return true if $path is empty, false otherwise
	 *
	 * @since    1.0.0
	 * @param    string 	$path 		The path to check
	 * @return   boolean     			True or false
	 */
	private function _is_folder_empty( $path ) {
		return (count(scandir($path)) == 2);
	}

	/**
	 * Return true if the $mime_type string contain one of the $types element
	 *
	 * @since    1.0.0
	 * @param    string 	$mime_type 		The mime type of the file
	 * @param 	 <array> 	$types 		The array with the mime types to check
	 * @return   boolean     			True or false
	 */
	private function _check_mime_string($mime_type, $types) {
		foreach ($types as $type) {
			if (strpos($mime_type, $type) !== false) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Return true if the $mime type is in the allowed list, false otherwise
	 *
	 * @since    1.0.0
	 * @param    string 	$mime_type 	The mime type of the file
	 * @return   boolean 				True or false
	 */
	private function _is_mime_type_allowed($mime_type) {
		return in_array($mime_type, $this->allowed_mime_types);
	}

	/**
	 * Return the dashicon icon to display based on file mime type
	 *
	 * @since    1.0.0
	 * @param    string 	$mime_type 	The mime type of the file
	 * @return   string 	$icon 		The string of the dashicon
	 */
	private function _get_file_icon($mime_type) {
		//$mime_type = mime_content_type($path);

		$icon = 'dashicons-media-default';

		if ($this->_check_mime_string($mime_type, array('text/', 'word', 'pdf')) != false) {
			$icon = 'dashicons-media-text';
		}
		if ($this->_check_mime_string($mime_type, array('audio/')) != false) {
			$icon = 'dashicons-media-audio';
		}
		if ($this->_check_mime_string($mime_type, array('video/')) != false) {
			$icon = 'dashicons-media-video';
		}
		if ($this->_check_mime_string($mime_type, array('image/')) != false) {
			$icon = 'dashicons-format-image';
		}
		if ($this->_check_mime_string($mime_type, array('zip', '7z')) != false) {
			$icon = 'dashicons-format-archive';
		}

		return $icon;
	}
}

