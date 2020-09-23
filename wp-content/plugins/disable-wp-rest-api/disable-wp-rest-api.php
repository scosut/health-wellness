<?php 
/*
	Plugin Name: Disable WP REST API
	Plugin URI: https://perishablepress.com/disable-wp-rest-api/
	Description: Disables the WP REST API for visitors not logged into WordPress.
	Tags: rest, rest-api, api, json, disable, head, header, link, http
	Author: Jeff Starr
	Author URI: https://plugin-planet.com/
	Donate link: https://monzillamedia.com/donate.html
	Contributors: specialk
	Requires at least: 4.4
	Tested up to: 5.5
	Stable tag: 1.9
	Version: 1.9
	Requires PHP: 5.6.20
	Text Domain: disable-wp-rest-api
	Domain Path: /languages
	License: GPL v2 or later
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) die();

/*
	Disable REST API link in HTTP headers
	Link: <https://example.com/wp-json/>; rel="https://api.w.org/"
*/
remove_action('template_redirect', 'rest_output_link_header', 11);

/*
	Disable REST API links in HTML <head>
	<link rel='https://api.w.org/' href='https://example.com/wp-json/' />
*/
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');

/*
	Disable REST API
*/
if (version_compare(get_bloginfo('version'), '4.7', '>=')) {
	
	add_filter('rest_authentication_errors', 'disable_wp_rest_api');
	
} else {
	
	disable_wp_rest_api_legacy();
	
}

function disable_wp_rest_api($access) {
	
	if (!is_user_logged_in()) {
		
		$message = apply_filters('disable_wp_rest_api_error', __('REST API restricted to authenticated users.', 'disable-wp-rest-api'));
		
		return new WP_Error('rest_login_required', $message, array('status' => rest_authorization_required_code()));
		
	}
	
	return $access;
	
}

function disable_wp_rest_api_legacy() {
	
    // REST API 1.x
    add_filter('json_enabled', '__return_false');
    add_filter('json_jsonp_enabled', '__return_false');
	
    // REST API 2.x
    add_filter('rest_enabled', '__return_false');
    add_filter('rest_jsonp_enabled', '__return_false');
	
}

function disable_wp_rest_api_plugin_links($links, $file) {
	
	if ($file === plugin_basename(__FILE__)) {
		
		$home_href  = 'https://perishablepress.com/disable-wp-rest-api/';
		$home_title = esc_attr__('Plugin Homepage', 'disable-wp-rest-api');
		$home_text  = esc_html__('Homepage', 'disable-wp-rest-api');
		
		$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $home_href .'" title="'. $home_title .'">'. $home_text .'</a>';
		
		$rate_href  = 'https://wordpress.org/support/plugin/disable-wp-rest-api/reviews/?rate=5#new-post';
		$rate_title = esc_attr__('Please give a 5-star rating! A huge THANK YOU for your support!', 'disable-wp-rest-api');
		$rate_text  = esc_html__('Rate this plugin', 'disable-wp-rest-api') .'&nbsp;&raquo;';
		
		$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $rate_href .'" title="'. $rate_title .'">'. $rate_text .'</a>';
		
	}
	
	return $links;
	
}
add_filter('plugin_row_meta', 'disable_wp_rest_api_plugin_links', 10, 2);
