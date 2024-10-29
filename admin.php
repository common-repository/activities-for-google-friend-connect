<?php

/**
 * Action for setting up administration-related items
 */
function gfca_admin_init() {
	register_setting('gfca', 'gfca_site_ID', 'gfca_filter_site_id');
}

/**
 * Action for setting up administration menus
 */
function gfca_admin_menu() {
	add_options_page(
		__('Activities for Google Friend Connect', 'gfca'), 
		__('GFC Activities', 'gfca'), 
		'manage_options',
		'friend-connect-activities.php',
		'gfca_options_page' );
}

/**
 * The options page
 */
function gfca_options_page() {
	$id = get_option('gfca_site_ID');
	if ($id)
		$value = 'value="' . $id . '"';
	else
		$value = '';

?>
<div class="wrap">
	 <h2><?php _e('Activities for Google Friend Connect', 'gfca'); ?></h2>

<p>
<?php _e('Google Friend Connect should already be set up for this site separately.
Then setting the Friend Connect Site ID on this page will allow for activities to be posted.
This plugin posts messages to Google Friend Connect for the following activities: new comment is written, new post is published, new link is added.', 'gfca'); ?>
</p>

<form method="post" action="options.php">

<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Friend Connect Site ID:', 'gfca'); ?></th>
<td><input type='text' name='gfca_site_ID' <?php echo $value; ?> maxlength='32' /></td>
</tr>

</table>

<?php settings_fields('gfca'); ?>

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
</p>

</form>
</div>
<?php
}

/**
 * Strips out any non-numeric characters
 */
function gfca_filter_site_id($str) {
	return preg_replace('/[^0-9]/', '', $str);
}

add_action('admin_init', 'gfca_admin_init');
add_action('admin_menu', 'gfca_admin_menu');

?>
