<?php
add_action( 'acf/register_fields', 'populate_garden_types' );
function populate_garden_types() {

  // Define first level custom categories
  wp_insert_category(array(
    'cat_name'      => 'Community Gardens',
    'category_description' => '',
    'taxonomy'      => 'garden-type',
  ));
  wp_insert_category(array(
    'cat_name'      => 'Pollinator and Wildlife Gardens',
    'category_description' => '',
    'taxonomy'      => 'garden-type',
  ));


  $comcat = get_term_by( 'slug', 'community-gardens', 'garden-type');
  $comid = $comcat->term_id;
  $polcat = get_term_by( 'slug', 'pollinator-and-wildlife-gardens', 'garden-type');
  $polid = $polcat->term_id;

  wp_insert_category(array(
    'cat_name'      => 'Traditional community garden',
    'category_description' => 'A traditional community garden invites people to rent a plot to grow and harvest their own fruits, vegetables, herbs or flowers.',
    'category_parent' => $comid,
    'taxonomy'      => 'garden-type',
  ));
  wp_insert_category(array(
    'cat_name'      => 'Communal vegetable garden',
    'category_description' => 'In a communal garden, people garden as a team. They may share or donate some of the food they grow with local community groups or agencies.',
    'taxonomy'      => 'garden-type',
    'category_parent' => $comid,
  ));
  wp_insert_category(array(
    'cat_name'      => 'Orchard',
    'category_description' => 'Sometimes called a “community orchard” a food forest is a mixture of fruit trees and shrubs maintained by a group of volunteers, where everyone (including local wildlife) is welcome to enjoy the fruit.',
    'taxonomy'      => 'garden-type',
    'category_parent' => $comid,
  ));
  wp_insert_category(array(
    'cat_name'      => 'Youth farm',
    'category_description' => '',
    'taxonomy'      => 'garden-type',
    'category_parent' => $comid,
  ));


  wp_insert_category(array(
    'cat_name'      => 'Pollinator habitat',
    'category_description' => 'Pollinator garden feature plants and flowers that provide nectar and pollen resources for bees and other important insects. They can also provide food and habitat for other wildlife, or stormwater infiltration.',
    'taxonomy'      => 'garden-type',
    'category_parent' => $polid,
  ));
  wp_insert_category(array(
    'cat_name'      => 'Feature garden',
    'category_description' => '',
    'taxonomy'      => 'garden-type',
    'category_parent' => $polid,
  ));
  wp_insert_category(array(
    'cat_name'      => 'Rain garden',
    'category_description' => '',
    'taxonomy'      => 'garden-type',
    'category_parent' => $polid,
  ));
  wp_insert_category(array(
    'cat_name'      => 'Wildlife habitat-native plant garden',
    'category_description' => '',
    'taxonomy'      => 'garden-type',
    'category_parent' => $polid,
  ));

}
