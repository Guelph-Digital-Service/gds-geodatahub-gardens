<?php
function pull_gardens_from_geodatahub($api, $attach_baseurl){
  $request = wp_remote_get($api);
  if( is_wp_error( $request ) ) {	return false;}
  $body = wp_remote_retrieve_body( $request );
  $data = json_decode( $body );
  foreach ( $data->features as $feature ) {

    $post_title = ucwords(strtolower($feature->attributes->Name));
    $post_desc = ucwords(strtolower($feature->attributes->Description));

    $garden_post = array(
         'post_title'  => $post_title,
         'post_parent'  => GARDEN_PARENT_PAGE_ID,
         'post_excerpt' => $post_desc,
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



    if ($attach_baseurl){
      $image_url = $attach_baseurl . $feature->attributes->OBJECTID . '/attachments/';

      for ($i=0; $i<31; $i++){
        if (@getimagesize($image_url . $i)) {

          $desc = sanitize_title($post_title) . '-photo' . $i;
          $wp_image_url = upload_image($image_url . $i, $feature->attributes->OBJECTID, $new_garden_post_id, $desc);
          $attach_id = pippin_get_image_id($wp_image_url);

          $array = get_field('field_5f3fd9e0fb951', $new_garden_post_id, false);
          if (!is_array($array)) {
            $array = array();
          }
          $array[] = $attach_id;
          update_field('field_5f3fd9e0fb951', $array, $new_garden_post_id );
        }
      }
    }
  }
}


function checkRemoteFile( $url ) {
  $headers=get_headers($url);
  return stripos($headers[0],"200 OK")?true:false;
}

function upload_image($url, $garden_id, $post_id, $desc) {
    $image = "";
    if($url != "") {

        $image_tmp =  download_url($url);

        if (is_wp_error($image_tmp)) {
            var_dump( $image_tmp->get_error_messages( ) );
        } else {
            $image_size = filesize($image_tmp);
            $image_name = "garden-api-attachment-" . $garden_id . "-" . basename($url) . ".jpg"; // .jpg optional

            //Download complete now upload in your project
            $file = array(
               'name' => $image_name, // ex: wp-header-logo.png
               'type' => 'image/jpg',
               'tmp_name' => $image_tmp,
               'error' => 0,
               'size' => $image_size,
            );

            $upload_dir = wp_upload_dir();
            $dup_attach_id = attachment_url_to_postid($upload_dir['url'] . '/' . $image_name);

            if ($dup_attach_id != 0 ){
              wp_delete_attachment( $dup_attach_id, true );
            }
            $attachmentId = media_handle_sideload($file, $post_id, $desc);
            update_post_meta($attachmentId, '_wp_attachment_image_alt', $desc);

            if ( is_wp_error($attachmentId) ) {
                @unlink($file['tmp_name']);
                var_dump( $attachmentId->get_error_messages( ) );
            } else {
                $image = wp_get_attachment_url( $attachmentId );
            }

        }
    }
    return $image;
}

// retrieves the attachment ID from the file URL
function pippin_get_image_id($image_url) {
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
        return $attachment[0];
}
