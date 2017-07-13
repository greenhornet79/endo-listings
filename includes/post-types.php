<?php
/**
 * Register Listing post type.
 * 
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function endo_listings_post_type() {
	$labels = array(
		'name'               => _x( 'Listings', 'post type general name', 'endo_listing' ),
		'singular_name'      => _x( 'Listing', 'post type singular name', 'endo_listing' ),
		'menu_name'          => _x( 'Listings', 'admin menu', 'endo_listing' ),
		'name_admin_bar'     => _x( 'Listing', 'add new on admin bar', 'endo_listing' ),
		'add_new'            => _x( 'Add New', 'Listing', 'endo_listing' ),
		'add_new_item'       => __( 'Add New Listing', 'endo_listing' ),
		'new_item'           => __( 'New Listing', 'endo_listing' ),
		'edit_item'          => __( 'Edit Listing', 'endo_listing' ),
		'view_item'          => __( 'View Listing', 'endo_listing' ),
		'all_items'          => __( 'All Listings', 'endo_listing' ),
		'search_items'       => __( 'Search Listings', 'endo_listing' ),
		'parent_item_colon'  => __( 'Parent Listings:', 'endo_listing' ),
		'not_found'          => __( 'No listings found.', 'endo_listing' ),
		'not_found_in_trash' => __( 'No listings found in Trash.', 'endo_listing' )
	);

	$args = array(
		'labels' 		        =>  $labels,
		'has_archive' 	        =>  true,
		'public' 		        =>  true,
		'supports' 		        =>  array( 'title', 'thumbnail' ),
		'menu_position'        =>  5,
		'menu_icon'            => 'dashicons-clipboard'
	);

	register_post_type( 'listing', $args );
}
add_action( 'init', 'endo_listings_post_type' );
