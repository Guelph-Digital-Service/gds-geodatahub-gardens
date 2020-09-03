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

/*
* Pull options and store in easily accessible variables
*/
$gdsset = get_option( 'gds_geodatahub_gardens_genset' );
define( 'GDSGARDENS_FILE', __FILE__ );
define( 'GDSGARDENS_OPTION', 'gds_geodatahub_gardens_settings' );
define( 'GARDEN_MENU_SHOW', ( isset( $gdsset['menu_show'] ) ) ? $gdsset['menu_show'] : 0 );
define( 'GARDEN_PARENT_PAGE_PATH', ( isset( $gdsset['archive_parent'] ) ) ? $gdsset['archive_parent'] : null );
define( 'GARDEN_PARENT_PAGE_ID', get_page_by_path(GARDEN_PARENT_PAGE_PATH)->ID );
define( 'GARDEN_FIRST_API_URL', ( isset( $gdsset['api_url'] ) ) ? $gdsset['api_url'] : null );
define( 'GARDEN_SECOND_API_URL', ( isset( $gdsset['second_api_url'] ) ) ? $gdsset['second_api_url'] : null );
define( 'GARDEN_FULL_WIDTH', ( isset( $gdsset['full_width'] ) ) ? $gdsset['full_width'] : 0 );

/**
* Register custom post types
*/
require_once dirname( __FILE__ ) . '/partials/gardenPostType.php';
include 'partials/populateGardenFields.php';

/**
* On plugin activations
*/
function activate_gds_geodatahub_gardens() {
	garden_custom_post_type();
	garden_custom_post_taxonomies();
	require_once dirname( __FILE__ ) . '/partials/populateGardenTypes.php';
	populate_garden_types();
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
	if ( __FILE__ != WP_UNINSTALL_PLUGIN )
		return;

	unregister_post_type( 'community-garden' ); // Unregister the post type

	$allposts= get_posts( array('post_type'=>'community-garden','numberposts'=>-1) );
	foreach ($allposts as $eachpost) {
	  wp_delete_post( $eachpost->ID, true );
	}
	delete_custom_terms('garden-type');

	delete_option( GDSGARDENS_OPTION );
	delete_option( 'gds_geodatahub_gardens_genset' );
	delete_site_option('gds_geodatahub_gardens_genset');


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
