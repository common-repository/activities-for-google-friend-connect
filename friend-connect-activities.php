<?php
/*
Plugin Name: Activities for Google Friend Connect
Plugin URI: http://chungyc.org/software/friend-connect-activities
Description: Post activities to Google Friend Connect without requiring a matching local account.
Version: 1.0
Author: Yoo Chung
Author URI: http://chungyc.org/
*/
?>
<?php
/*  Copyright 2009  Yoo Chung

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php

// End-points for RESTful APIs
define('GFCA_PEOPLE', 'http://www.google.com/friendconnect/api/people/');
define('GFCA_ACTIVITIES', 'http://www.google.com/friendconnect/api/activities/');

if (is_admin())
	include dirname(__FILE__) . '/admin.php';

/**
 * Prepare Friend Connect data
 *
 * Collect Friend Connect data that would be useful for posting
 * activities.  Some of the data collected here is required to use
 * {@link gfca_post_activity()} properly.
 *
 * It collects the Friend Connect site identifier, the Friend Connect
 * authentication cookie, the display name for the user, and other
 * information about the user as returned by Friend Connect.  The data
 * is returned as an array with the previous fields indexed by 'id',
 * 'fcauth', 'displayName', and 'viewer', respectively.
 *
 * If the site is not set up to use Google Friend Connect or if the
 * user has not signed into the site with Friend Connect, then NULL is
 * returned.
 *
 * @return array Friend Connect data, or NULL if unavailable
 */
function gfca_setup() {
	$site_ID = get_option('gfca_site_ID');
	if (!$site_ID)
		return NULL;

	$cookie_key = 'fcauth' . $site_ID;
	if (array_key_exists($cookie_key, $_COOKIE))
		$fcauth = urlencode($_COOKIE[$cookie_key]);
	else
		return NULL;

	/* Contact Google Friend Connect to obtain information about user. */
	$url = GFCA_PEOPLE . '@me/@self?fcauth=' . $fcauth;
	$response = wp_remote_get($url);

	/* Extract information about user. */
	$body = wp_remote_retrieve_body($response);
	if ($body != '') {
		$data = json_decode($body, true);
		if ($data != NULL) {
			$viewer = $data['entry'];
			$name = $viewer['displayName'];
		} else {
			$viewer = NULL;
			$name = NULL;
		}
	}

	return array(
		'id' => $site_ID,
		'fcauth' => $fcauth,
		'displayName' => $name,
		'viewer' => $viewer
        );
}

/**
 * Post an activity
 *
 * Post an activity to Google Friend Connect.  The activity is a
 * message that can include only text and a few HTML elements, namely
 * b, i, a, and span.
 *
 * While the Friend Connect authentication cookie could be obtained
 * manually, it can also be obtained using {@link gfca_setup()}.
 *
 * @param string $fcauth Friend Connect authentication cookie
 * @param string $activity message representing activity
 */
function gfca_post_activity($fcauth, $activity) {
	$url = GFCA_ACTIVITIES . '@me/@self?fcauth=' . $fcauth;
	$body = json_encode(array( 'title' => $activity ));
	wp_remote_post($url, 
		array(
			'body' => $body,
			'headers' => array( 'Content-Type' => 'application/json' )
		)
	);	
}

/**
 * Post the publishing of a post as an activity
 *
 * @param int post ID
 */
function gfca_post_publish_activity($id) {
	$data = gfca_setup();
	if ($data == NULL)
		return;

	$title = get_the_title($id);
	$link = get_permalink($id);
	$name = $data['displayName'];

	if ($name != NULL)
		$format = __('%1$s posted <a href="%2$s">%3$s</a>.', 'gfca');
	else
		$format = __('Posted <a href="%2$s">%3$s</a>.', 'gfca');

	$msg = sprintf($format, $name, $link, $title);
	gfca_post_activity($data['fcauth'], $msg);
}

/**
 * Post commenting activity
 *
 * @param int comment ID
 */
function gfca_post_comment_activity($id) {
	$data = gfca_setup();
	if ($data == NULL)
		return;

	$title = get_the_title(get_comment($id)->comment_post_ID);
	$link = get_comment_link($id);
	$name = $data['displayName'];

	if ($name != NULL)
		$format = __('%1$s commented on <a href="%2$s">%3$s</a>.', 'gfca');
	else
		$format = __('Commented on <a href="%2$s">%3$s</a>.', 'gfca');

	$msg = sprintf($format, $name, $link, $title);
	gfca_post_activity($data['fcauth'], $msg);
}

/**
 * Post link addition activity
 *
 * @param int link ID
 */
function gfca_post_link_activity($id) {
	$data = gfca_setup();
	if ($data == NULL)
		return;

	$bookmark = get_bookmark($id);
	$title = $bookmark->link_name;
	$link = $bookmark->link_url;
	$name = $data['displayName'];

	if ($name != NULL)
		$format = __('%1$s added link to <a href="%2$s">%3$s</a>.', 'gfca');
	else
		$format = __('Added link to <a href="%2$s">%3$s</a>.', 'gfca');

	$msg = sprintf($format, $name, $link, $title);
	gfca_post_activity($data['fcauth'], $msg);
}

/**
 * Load localization strings
 */
function gfca_textdomain() {
	$lang_dir = basename(dirname(__FILE__)) . '/lang';
	load_plugin_textdomain('gfca', NULL, $lang_dir);
}

add_action('publish_post', 'gfca_post_publish_activity');
add_action('comment_post', 'gfca_post_comment_activity');
add_action('add_link', 'gfca_post_link_activity');
add_action('init', 'gfca_textdomain');

?>
