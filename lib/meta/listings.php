<?php
add_action( 'cmb2_admin_init', 'endo_listings_metaboxes' );
/**
 * Define the metabox and field configurations.
 */
function endo_listings_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_listing_';

	/**
	* Initiate the Listings Details metabox
	*/
	$cmb_listing = new_cmb2_box( array(
		'id'            => 'listing_details',
		'title'         =>  __( 'Listing Details', 'cmb2' ),
		'object_types'  =>  array( 'listing' ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    =>  true, // Show field names on the left
		'closed'        =>  false, // true to keep the metabox closed by default
	) );

	/**
	* Add the meta fields
	*/
	$cmb_listing->add_field( array(
		'name' => 'Listing Title',
		'id' => $prefix . 'title',
		'type' => 'text',
	) );

	$cmb_listing->add_field( array(
		'name' => 'Is Featured',
		'id' => $prefix . 'is_featured',
		'type' => 'checkbox',
	) );

	$cmb_listing->add_field( array(
		'name' => 'City',
		'id' => $prefix . 'city',
		'type' => 'text',
	) );

	$cmb_listing->add_field( array(
		'name' => 'State',
		'id' => $prefix . 'state',
		'type' => 'text',
	) );

	$cmb_listing->add_field( array(
		'name' => 'Lat/Lng',
		'id' => $prefix . 'lat_lng',
		'type' => 'text',
	) );

	$cmb_listing->add_field( array(
		'name' => 'Describe your hay',
		'id' => $prefix . 'description',
		'type' => 'textarea_small',
	) );

	$cmb_listing->add_field( array(
		'name' => 'How much hay do you have?',
		'id' => $prefix . 'hay_qty',
		'type' => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
	) );

	$cmb_listing->add_field( array(
		'name' => 'How do you count your hay?',
		'id' => $prefix . 'hay_qty_unit',
		'type' => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
	) );

	$cmb_listing->add_field( array(
		'name' => 'What is your hay type?',
		'id' => $prefix . 'hay_type',
		'type' => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
	) );

	$cmb_listing->add_field( array(
		'name' => 'What is the size of your hay?',
		'id' => $prefix . 'hay_size',
		'type' => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
	) );

	$cmb_listing->add_field( array(
		'name' => 'Is your hay fertilized?',
		'id' => $prefix . 'hay_fertilized',
		'type' => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
	) );

	$cmb_listing->add_field( array(
		'name' => 'Relative Feed Value (RFV)',
		'id' => $prefix . 'rfv',
		'type' => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
	) );

	$cmb_listing->add_field( array(
		'name' => 'What is the price of your hay?',
		'id' => $prefix . 'hay_price',
		'type' => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
	) );

	$cmb_listing->add_field( array(
		'name' => 'How much hay for that price?',
		'id' => $prefix . 'hay_price_unit',
		'type' => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
	) );


	/**
	* Initiate the Account Details metabox
	*/
	$cmb_listing = new_cmb2_box( array(
		'id'            => 'account_details',
		'title'         =>  __( 'Account Details', 'cmb2' ),
		'object_types'  =>  array( 'listing' ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    =>  true, // Show field names on the left
		'closed'        =>  false, // true to keep the metabox closed by default
	) );

	/**
	* Add the meta fields
	*/
	$cmb_listing->add_field( array(
		'name' => 'Email',
		'id' => $prefix . 'email',
		'type' => 'text_email',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
	) );

	$cmb_listing->add_field( array(
		'name' => 'Name',
		'id' => $prefix . 'name',
		'type' => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
	) );

	$cmb_listing->add_field( array(
		'name' => 'Phone',
		'id' => $prefix . 'phone',
		'type' => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
	) );

	$cmb_listing->add_field( array(
		'name' => 'Plan',
		'id' => $prefix . 'plan',
		'type' => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
	) );
}
