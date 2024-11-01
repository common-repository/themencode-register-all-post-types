<?php
/**
 * Plugin Name: Register All Post Types by ThemeNcode
 * Plugin URI: https://themencode.com/register-all-post-types-free-wordpress-plugin/
 * Description: This plugin will check database for all the post types and create the ones that are not registered using register_post_type() function.
 * Version: 1.2
 * Author: ThemeNcode
 * Author URI: https://themencode.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package themencode-register-all-post-types
 */

/**
 * FInd and register the post types
 */
function tnc_register_unavailable_post_types() {
	global $wpdb;
	$posts_table       = $wpdb->prefix . 'posts';
	$get_types_from_db = $wpdb->get_results( "SELECT post_type FROM $posts_table", ARRAY_A );
	$db_post_types     = array();

	foreach ( $get_types_from_db as $post_id => $post_type ) {
		$db_post_types[] = $post_type['post_type'];
	}

	$unique_db_post_types = array_unique( $db_post_types );

	$registered_ptypes      = get_post_types();
	$unavailable_post_types = array_diff( $unique_db_post_types, $registered_ptypes );

	foreach ( $unavailable_post_types as $key => $post_type_key ) {
		$args = array(
			'public' => true,
			'label'  => $post_type_key,
		);

		register_post_type( $post_type_key, $args );
	}
}

add_action( 'init', 'tnc_register_unavailable_post_types', 10 );
