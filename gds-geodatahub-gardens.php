<?php
/**
* Plugin Name: GDS - Community Gardens GeoDataHub Integration
* Description: Creates a custom-post-type with appropriate fields for Guelph's Community Gardens and Pollinators and generates posts from the Guelph GeoDataHub API.
* Author: Nic Durish
* Author URI: http://www.nicdurish.ca
* Version: 1.0.0
*
* Copyright 2020 Nic Durish (nic.durish@guelph.ca)
* @author Nic Durish
* @version 1.0.0
*/

global $wp_version;

if ( ! defined( 'WPINC' ) ) { // Prevent direct file access.
	die();
}
define( 'GDSGARDENS_FILE', __FILE__ );
define( 'GDSGARDENS_OPTION', 'gds_geodatahub_gardens_settings' );

/**
* Register custom post types
*/
require_once dirname( __FILE__ ) . '/partials/gardenPostType.php';

/**
* On plugin activations
*/
function activate_gds_geodatahub_gardens() {
	garden_custom_post_type(); // Trigger function that registers the custom post type.
	garden_custom_post_taxonomies();
	require_once dirname( __FILE__ ) . '/partials/populateGardenTypes.php';
	garden_type_categories();
	flush_rewrite_rules(); // Clear the permalinks
}
register_activation_hook( __FILE__, 'activate_gds_geodatahub_gardens' );


/**
* On plugin deactivations
*/
function deactivate_gds_geodatahub_gardens() {
	unregister_post_type( 'community-garden' ); // Unregister the post type
	flush_rewrite_rules(); // Clear the permalinks
}
register_deactivation_hook( __FILE__, 'deactivate_gds_geodatahub_gardens' );

/**
* On plugin uninstall
*/
function gds_geodatahub_gardens_uninstall() {
	delete_custom_terms('garden-type');
  delete_option( GDSGARDENS_OPTION );
	unregister_post_type( 'community-garden' ); // Unregister the post type
	flush_rewrite_rules(); // Clear the permalinks
}
register_uninstall_hook( __FILE__, 'gds_geodatahub_gardens_uninstall' );

/**
* If admin is not logged in load public.php, if admin load admin.php
*/
if ( ! is_admin() ) {
	// require_once dirname( __FILE__ ) . '/public/gds-geodatahub-gardens_public.php';
} elseif ( ! defined( 'DOING_AJAX' ) ) {
	require_once dirname( __FILE__ ) . '/admin/gds-geodatahub-gardens-admin.php';
}





//TESTING
$reddit_data = wp_remote_get( 'https://www.reddit.com/r/Wordpress/.json' );

$reddit_data_decode = json_decode( $reddit_data['body'] );

foreach ( $reddit_data_decode->data->children as $item ) {
     $post_title    = $item->data->title; // post title
     $reddit_author = $item->data->author; // author
     $up_votes      = $item->data->ups; // up votes
}
