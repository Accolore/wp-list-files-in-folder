<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://codecanyon.net/user/Accolore
 * @since      1.0.0
 *
 * @package    Wp_List_Files_In_Folder
 * @subpackage Wp_List_Files_In_Folder/public/partials
 */
?>

<?php _e('Click on a filename to download. Click on folder name to expand or collapse.', 'wp-list-files-in-folder'); ?>
<ul id="wplfif-tree-root">
	<?php echo $raw_html; ?>
</ul>