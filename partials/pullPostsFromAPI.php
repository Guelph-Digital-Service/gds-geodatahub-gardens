<?php
function pull_gardens_from_geodatahub($api){
  $request = wp_remote_get($api);
  if( is_wp_error( $request ) ) {	return false;}
  $body = wp_remote_retrieve_body( $request );
  $data = json_decode( $body );
  foreach ( $data->features as $feature ) {

    $post_title = ucwords(strtolower($feature->attributes->Name));

    $garden_post = array(
         'post_title'  => $post_title,
         'post_parent'  => GARDEN_PARENT_PAGE_ID,
         'post_excerpt' => $feature->attributes->Description,
         'post_status' => 'publish',
         'post_type'   => 'community-garden',
    );


    $pagecheck = get_page_by_title( $post_title, OBJECT, 'community-garden');
    if ( get_post_status($pagecheck->ID) == 'publish' ){
      $new_garden_post_id = $pagecheck->ID;
    } else {
      $new_garden_post_id = wp_insert_post( $garden_post );
    }

    wp_set_object_terms($new_garden_post_id, sanitize_title_with_dashes($feature->attributes->GardenType), 'garden-type', false);


    if (isset($feature->attributes->GlobalID)) {
      update_field( 'garden_geodatahub_global_id', $feature->attributes->GlobalID, $new_garden_post_id );
    }
    if (isset($feature->attributes->OBJECTID)) {
      update_field( 'garden_geodatahub_object_id', $feature->attributes->OBJECTID, $new_garden_post_id );
    }
    if (isset($feature->attributes->AvailablePlots)) {
      update_field( 'garden_geodatahub_available_plots', $feature->attributes->AvailablePlots, $new_garden_post_id );
    }
    if (isset($feature->attributes->Accessible)) {
      update_field( 'garden_geodatahub_accessible', ucwords(strtolower($feature->attributes->Accessible)), $new_garden_post_id );
    }
    if (isset($feature->attributes->Address)) {
      update_field( 'garden_geodatahub_address', ucwords(strtolower($feature->attributes->Address)), $new_garden_post_id );
    }
    if (isset($feature->attributes->Email)) {
      update_field( 'garden_geodatahub_email', $feature->attributes->Email, $new_garden_post_id );
    }
    if (isset($feature->attributes->Ownership)) {
      update_field( 'garden_geodatahub_ownership', ucwords(strtolower($feature->attributes->Ownership)), $new_garden_post_id );
    }
    if (isset($feature->attributes->MaintainedBy)) {
      update_field( 'garden_geodatahub_maintained_by', ucwords(strtolower($feature->attributes->MaintainedBy)), $new_garden_post_id );
    }
    if (isset($feature->attributes->Directions)) {
      update_field( 'garden_geodatahub_directions', $feature->attributes->Directions, $new_garden_post_id );
    }
    if (isset($feature->attributes->GetInvolved)) {
      update_field( 'garden_geodatahub_get_involved', $feature->attributes->GetInvolved, $new_garden_post_id );
    }
    if (isset($feature->attributes->Description)) {
      update_field( 'garden_geodatahub_description', $feature->attributes->Description, $new_garden_post_id );
    }
  }
}
