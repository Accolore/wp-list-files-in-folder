<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://codecanyon.net/user/Accolore
 * @since      1.0.0
 *
 * @package    Wp_List_Files_In_Folder
 * @subpackage Wp_List_Files_In_Folder/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_List_Files_In_Folder
 * @subpackage Wp_List_Files_In_Folder/admin
 * @author     Lorenzo Accorinti <support@accolore.com>
 */
class Wp_List_Files_In_Folder_Admin {

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
	 * The notices handler class responsible to handle all administration notices for the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @param    WP_List_Files_In_Folder_Notices    $notices    Maintains and registers all notices for the plugin.
	 */
	protected $notices;	

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->notices     = Wp_List_Files_In_Folder_Notices::get_instance();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-list-files-in-folder-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-list-files-in-folder-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Display plugin notices
	 *
	 * @since    1.0.0
	 */
	public function display_notices () {
		$upload_dir = wp_upload_dir(); 
		$file_root =  $upload_dir['basedir'] . '/wp-lfif-folder/';		

		if ( is_admin() ) {
			if (get_option( 'Activated_Plugin' ) == $this->plugin_name ) {
				delete_option( 'Activated_Plugin' );			
			}
			echo $this->notices->generate_output();
		}
	}

	/**
	 * Perform after activation initialization operations
	 *
	 * @since    1.0.0
	 */
	public function activation_init () {	
		$upload_dir = wp_upload_dir(); 
		$file_root =  $upload_dir['basedir'] . '/wp-lfif-folder/';
		
		if ( ! file_exists($file_root) ) {
			if ( !mkdir($file_root, 0755, true) ) {
				$this->notices->enqueue_notice(
					'<strong>' . sprintf(__('Error: unable to create %s folder', $this->plugin_name), $file_root) . '</strong>',
					'notice-error'
				);
			}
		}

		if ( !get_option('wplfif_options') ) {
			$options = array(
				'allowed_mime_types' => 'text/csv
text/plain
image/bmp
image/png
image/jpeg',
			);

			add_option('wplfif_options', $options);	
		}	
	}	

	/**
	 * Add plugin configuration page
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_page()  {
		// This page will be under "Settings"
		add_options_page(
			'WP List Files In Folders Settings', 
			'WP List Files In Folders Settings', 
			'manage_options', 
			'wplfif-settings', 
			array( $this, 'create_admin_page' )
		);
	}
 
 	/**
     * Options page callback
     *
	 * @since    1.0.0
	 */
    public function create_admin_page() {
		$this->options = get_option( 'wplfif_options' );
		?>
		<div class="wrap">
		<h1>Settings</h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'my_option_group' );
			do_settings_sections( 'wplfif-settings' );
			submit_button();
			?>
		</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 *
	 * @since    1.0.0
	 */
	public function page_init() {
		register_setting(
			'my_option_group',
			'wplfif_options',
			array( $this, 'sanitize' )
		);

		add_settings_section(
			'wplfif_section_1',
			__('Settings', 'wp-list-files-in-folder'),
			array( $this, 'print_section_info' ),
			'wplfif-settings'
		);  

		add_settings_field(
			'allowed_mime_types',
			__('Allowed Mime Types', 'wp-list-files-in-folder'),
			array( $this, 'allowed_mime_types_callback' ),
			'wplfif-settings',
			'wplfif_section_1'
			);
	}

	/**
	* Sanitize each setting field as needed
	* @since    1.0.0
	* @param array $input Contains all the inputs to be sanitized
	*/
	public function sanitize($input) {

		$new_input = array();
		if( isset( $input['allowed_mime_types'] ) )
			$new_input['allowed_mime_types'] = sanitize_textarea_field( $input['allowed_mime_types'] );

		return $new_input;
	}

	/** 
	* Print the Section text
	* @since    1.0.0
	*/
	public function print_section_info() {
		echo 'Enter your settings below:';
	}

	/** 
	* Get the settings option array and print one of its values
	* @since    1.0.0
	*/
	public function allowed_mime_types_callback() {
		?>
		<textarea rows="10" cols="50" id="allowed_mime_types" name="wplfif_options[allowed_mime_types]"><?php echo isset( $this->options['allowed_mime_types'] ) ? esc_attr( $this->options['allowed_mime_types']) : '' ?></textarea>
		<p><?php _e('This is the list of the supported mime types for the listed files. Insert one mime type for row. If a mime type is not in the list the file will not be displayed.', 'wp-list-files-in-folder') ?></p>
		<h3><?php _e('Select a file and press the button to add the mime type to the allowed list.', 'wp-list-files-in-folder') ?></h3>
		<p><input type="file" id="file_obj" multiple></p>
		<p><a href="#" id="add_mime_type" class="button button-secondary"><?php _e('Add mime type to list', 'wp-list-files-in-folder') ?></a></p>
		<p><span id="response_message"></span></p>
		<script>
			var control    = document.getElementById("file_obj");
			var button     = document.getElementById("add_mime_type");
			var response   = document.getElementById("response_message");
			var mime_types = [];
			var flag       = true;

			control.addEventListener("change", function(event) {
				var files = control.files;
				for (var i = 0; i < files.length; i++) {
					if (files[i].type != '') {
						mime_types.push(files[i].type);
					} else {
						flag = false;
					}
				}
				if (flag) {
					console.log('flag true');
					response.classList.add('label-success');
					response.innerHtml = '<?php _e('Mime type successfully added to list', 'wp-list-files-in-folder') ?>';
				} else {
					console.log('flag false');
					response.classList.add('label-error');
					response.innerHtml = '<?php _e('Mime type not valid.', 'wp-list-files-in-folder') ?>';
				}
			}, false);

			button.addEventListener("click", function(event) {
				event.preventDefault();
				for (var i = 0; i < mime_types.length; i++) {
					document.getElementById("allowed_mime_types").value += '\r\n' + mime_types[i];
				}
				mime_types = [];
				control.value = null;

			}, false);
		</script>
		<?php
	}
}
