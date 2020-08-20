<?php

/*
* Pull options and store in easily accessible variables
*/
$gdsset = get_option( 'gds_geodatahub_gardens_genset' );
define( 'GARDEN_MENU_SHOW', ( isset( $gdsset['menu_show'] ) ) ? $gdsset['menu_show'] : 0 );
define( 'GARDEN_PARENT_PAGE_PATH', ( isset( $gdsset['archive_parent'] ) ) ? $gdsset['archive_parent'] : null );
define( 'GARDEN_PARENT_PAGE_ID', get_page_by_path(GARDEN_PARENT_PAGE_PATH)->ID );

/**
* Create Custom Post Type for Gardens
*
* @since  1.0.0
*/
function garden_custom_post_type() {
  $labels = array(
    'name'               => _x( 'Community Gardens', 'post type general name' ),
    'singular_name'      => _x( 'Community Garden', 'post type singular name' ),
    'menu_name'          => 'Community Gardens'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Guelphs Community Gardens and Pollinators',
    'public'        => true,
    'publicly_queryable' => true,
    'menu_icon'     => 'dashicons-carrot',
    'show_ui'       => false,
    // 'hierarchical' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'query_var' => true,
    'rewrite' => array(
      'slug' => GARDEN_PARENT_PAGE_PATH,
      'with_front' => false,
    ),
    'capability_type' => 'page',
    'supports'      => array( 'title', 'editor', 'excerpt', 'trackbacks', 'custom-fields', 'page-attributes' ),
    'has_archive'   => false,
    'menu_position' => null,
  );

  if ( GARDEN_MENU_SHOW ){$args['show_ui'] = true;}

  register_post_type( 'community-garden', $args );
}
add_action( 'init', 'garden_custom_post_type' );

/**
 * Add custom taxonomy for Community Gardens
 */
function garden_custom_post_taxonomies() {
  $args = array(
    'hierarchical' => true,
    'show_ui' => false,
    'has_archive' => false,
    'labels' => array(
      'name' => _x( 'Garden Types', 'taxonomy general name' ),
      'singular_name' => _x( 'Garden Type', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Garden Types' ),
      'all_items' => __( 'All Garden Types' ),
      'parent_item' => __( 'Parent Garden Type' ),
      'parent_item_colon' => __( 'Parent Garden Type:' ),
      'edit_item' => __( 'Edit Garden Type' ),
      'update_item' => __( 'Update Garden Type' ),
      'add_new_item' => __( 'Add New Garden Type' ),
      'new_item_name' => __( 'New Garden Type Name' ),
      'menu_name' => __( 'Garden Types' ),
    ),
    'rewrite' => array(
      'slug' => '', // This controls the base slug that will display before each term
      'with_front' => false, // Don't display the category base before "/locations/"
      // 'hierarchical' => false // This will allow URL's like "/locations/boston/cambridge/"
    ),
  );

  if ( GARDEN_MENU_SHOW ){ $args['show_ui'] = true; }

  register_taxonomy('garden-type', 'community-garden', $args);
}
add_action( 'init', 'garden_custom_post_taxonomies');



/**
 * saveStaffParent
 */
add_action( 'wp_insert_post_data', 'saveGardenParent', 99, 2 );
function saveGardenParent( $data, $postarr ) {
    global $post;

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return $data;

    if ( $post->post_type == "community-garden" ){
	    $data['post_parent'] = GARDEN_PARENT_PAGE_ID;
	}

    return $data;
}







function delete_custom_terms($taxonomy){
    global $wpdb;

    $query = 'SELECT t.name, t.term_id
            FROM ' . $wpdb->terms . ' AS t
            INNER JOIN ' . $wpdb->term_taxonomy . ' AS tt
            ON t.term_id = tt.term_id
            WHERE tt.taxonomy = "' . $taxonomy . '"';

    $terms = $wpdb->get_results($query);

    foreach ($terms as $term) {
        wp_delete_term( $term->term_id, $taxonomy );
    }
}
