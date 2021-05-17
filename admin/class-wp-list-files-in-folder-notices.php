<?php

/**
 * Handle admin notices for the plugin
 *
 * @since      2.0.0
 *
 * @package    Wp_List_Files_In_Folder
 * @subpackage Wp_List_Files_In_Folder/admin
 * @author     Accolore <support@accolore.com>
 */

class Wp_List_Files_In_Folder_Notices {

	/**
	 * The singleton variable. Hold the only allowerd instance for this class.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @param    Wp_List_Files_In_Folder_Notices    $singleton    The singleton variable. Hold the only allowerd instance for this class.
	 */
	private static $singleton = null;

	/**
	 * The queue for the notices to display
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @param    string 	$notice_queue    Maintains and registers all notices for the plugin.
	 */
	protected $notice_queue;

	/**
	 * Initialize the collections used to maintain notices.
	 *
	 * @since    2.0.0
	 */
	public function __construct() {
		$this->notice_queue = false;
	}

	/**
	 * Instantiate the class into the singleton variable and return this.
	 *
	 * @since    1.0.0
	 * @return   Wp_List_Files_In_Folder_Notices	The singleton instance of the class.
	 */
	static function get_instance(){
		if (self::$singleton == null){
			self::$singleton = new Wp_List_Files_In_Folder_Notices();
		}
		return self::$singleton;
	}

	/**
	 * Enqueue a notice with display parameters
	 *
	 * @since    2.0.0
	 * @param    string    $message     The notice message
	 * @param    string    $class 		The class applied to the notice (notice-info, notice-warning, notice-error, notice-success)
	 */
	public function enqueue_notice( $message, $class = 'notice-info' ) {
		
		if ($this->notice_queue === false) {
			$this->notice_queue = array();
		}

		$this->notice_queue[] = array(
			'message' => $message,
			'class'   => $class,
		);
	}

	/**
	 * Generate the output for enqueued notices 
	 *
	 * @since    2.0.0
	 * @return 	 string 	Html code for notices
	 */
	public function generate_output() {
		$output = '';

		if ($this->notice_queue !== false) {
			foreach ($this->notice_queue as $notice) {
				ob_start();
				?>
				<div class='notice is-dismissible <?php echo $notice['class']; ?>'>
					<p><?php echo $notice['message']; ?></p>
				</div>
				<?php
				$output .= ob_get_clean();
			}
		}	
		$this->notice_queue = false;
		return $output;
	}
}