<?php
function endo_listings_create_listing($entry, $form) {

	// get form values needed to generate the post title
	$state        = $entry['3'];
	$city         = $entry['4'];
	$hay_qty      = $entry['6'];
	$hay_qty_unit = $entry['7'];
	$hay_type     = $entry['9'];
	$hay_size     = $entry['10'];
	$latlng 	  = str_replace( array( '(', ')' ), '', $entry['28'] );
	
	$listing_type = $entry['25'];

	// $email 			= $entry['15'];
	// $password		= $entry['24'];
	// $first_name		= $entry['22.3'];
	// $last_name		= $entry['22.6'];
	// $phone 			= $entry['19'];

	// // create new user
	// $userdata = array(
	//     'user_login'  =>  $email,
	//     'user_url'    =>  $website,
	//     'user_pass'   =>  $password,
	//     'first_name'  =>  $first_name,
	//     'last_name'	  =>  $last_name
	// );

	$user_id = get_current_user_id();

	update_user_meta( $user_id, 'el_seller_phone', $phone );
	update_user_meta( $user_id, 'el_is_seller', true );

	// set plural for hay quantity unit if applicable
	if ( floatval($hay_qty) != 1.0 )
		$hay_qty_unit = $hay_qty_unit . 's';

	// $post_title   = 'Hay For Sale in ' . $city . ', ' . $state . ' - ' . $hay_qty . ' ' . $hay_qty_unit . ' of ' . $hay_type . ' (' . $hay_size . ')';

	$listing_title = $hay_qty . ' ' . $hay_qty_unit . ' of ' . $hay_size . ' ' . $hay_type;
	
	$post_title = $listing_title . ' in ' . $city . ', ' . $state;

	// combine the first and last name to use as full name
	$full_name    = $entry['22.3'] . ' ' . $entry['22.6'];

	// Create post object
	$new_listing = array(
	  'post_title'    => $post_title,
	  'post_content'  => $entry['8'],
	  'post_status'   => 'publish',
	  'post_type' 	   => 'listing',
	  'post_author'		=> get_current_user_id(),
	);

	// Insert the post into the database
	$listing_id = wp_insert_post( $new_listing );

	// index new listing in facet wp so it can be searched for
	FWP()->indexer->index();

	// save entry array to a custom meta field
	update_post_meta( $listing_id, '_listing_title', $listing_title );
	update_post_meta( $listing_id, '_listing_city', $city );
	update_post_meta( $listing_id, '_listing_state', $state );
	update_post_meta( $listing_id, '_listing_lat_lng', $latlng );
	update_post_meta( $listing_id, '_listing_description', $entry['8'] );
	update_post_meta( $listing_id, '_listing_hay_qty', $hay_qty );
	update_post_meta( $listing_id, '_listing_hay_qty_unit', $hay_qty_unit );
	update_post_meta( $listing_id, '_listing_hay_type', $hay_type );
	update_post_meta( $listing_id, '_listing_hay_size', $hay_size );
	update_post_meta( $listing_id, '_listing_hay_fertilized', $entry['11'] );
	update_post_meta( $listing_id, '_listing_rfv', $entry['23'] );
	update_post_meta( $listing_id, '_listing_hay_price', $entry['12'] );
	update_post_meta( $listing_id, '_listing_hay_price_unit', $entry['13'] );
	update_post_meta( $listing_id, '_listing_email', $entry['15'] );
	update_post_meta( $listing_id, '_listing_name', $full_name );
	update_post_meta( $listing_id, '_listing_phone', $entry['19'] );
	update_post_meta( $listing_id, '_listing_plan', $entry['21'] );

	if ( $listing_type == 'featured|12' ) {
		update_post_meta( $listing_id, '_listing_is_featured', 'on' );
	}

	if ( $listing_type == 'featured_account|100' ) {
		update_user_meta( $user_id, '_is_featured_account', true );
		update_user_meta( $user_id, '_featured_account_start_date', current_time('mysql') );
		update_post_meta( $listing_id, '_listing_is_featured', 'on' );
	}
	
	$img_data = rgar( $entry, '24' );

	$parts = explode( '|', $img_data );

	$img_id = array_pop( $parts );

	// If our photo upload was successful, set the featured image
	if ( $img_id && ! is_wp_error( $img_id ) ) {
	    set_post_thumbnail( $listing_id, $img_id );
	}

	if(is_wp_error($img_id)){
            echo "Error uploading file: " . $img_id->get_error_message();
    }


}
add_action('gform_after_submission_1', 'endo_listings_create_listing', 10, 2);




add_action('gform_after_submission_4', 'endo_listings_create_account_and_listing', 10, 2);

function endo_listings_create_account_and_listing( $entry, $form ) {

	// save user
	$userdata = array(
		'user_login'	=> $entry['30'],
		'user_pass'		=> $entry['33'],
		'user_email'	=> $entry['32'],
		'first_name'	=> $entry['31.3'],
		'last_name'		=> $entry['31.6'],
		'display_name'	=> $entry['31.3'] . ' ' . $entry['31.6']
	);

	$user_id = wp_insert_user( $userdata );

	// mark user as a seller
	update_user_meta( $user_id, 'el_is_seller', true );


	// save listing
	$listing_data = array(
		'first_name'	=> $entry['22.3'],
		'last_name'		=> $entry['22.6'],
		'email'			=> $entry['15'],
		'phone'			=> $entry['19'],
		'state'			=> $entry['3'],
		'city'			=> $entry['4'],
		'hay_qty' 		=> $entry['6'],
		'hay_qty_unit' 	=> $entry['7'],
		'hay_type'		=> $entry['9'],
		'hay_size'		=> $entry['10'],
		'hay_desc'		=> $entry['8'],
		'hay_fertilized'	=> $entry['11'],
		'hay_rfv'		=> $entry['23'],
		'hay_price'		=> $entry['12'],
		'hay_price_unit'	=> $entry['13'],
		'plan'			=> $entry['27'],
		'image_data'	=> rgar( $entry, '24' ),
		'latlng'		=> str_replace( array( '(', ')' ), '', $entry['28'] ),
		'listing_type'	=> $entry['25']
	);

	process_new_listing( $listing_data, $user_id );

}

function process_new_listing( $listing_data, $user_id = '' ) {

	// set plural for hay quantity unit if applicable
	if ( floatval( $listing_data['hay_qty'] ) != 1.0 ) {
		$listing_data['hay_qty_unit'] = $listing_data['hay_qty_unit'] . 's';
	}

	$listing_title = $listing_data['hay_qty'] . ' ' . $listing_data['hay_qty_unit'] . ' of ' . $listing_data['hay_size'] . ' ' . $listing_data['hay_type'];
	$post_title = $listing_title . ' in ' . $listing_data['city'] . ', ' . $listing_data['state'];

		// combine the first and last name to use as full name
	$full_name    = $listing_data['first_name'] . ' ' . $listing_data['last_name'];

	// Create post object
	$new_listing = array(
	  'post_title'    => $post_title,
	  'post_content'  => $listing_data['hay_desc'],
	  'post_status'   => 'publish',
	  'post_type' 	   => 'listing',
	  'post_author'		=> $user_id ? $user_id : get_current_user_id(),
	);

	// Insert the post into the database
	$listing_id = wp_insert_post( $new_listing );

	// index new listing in facet wp so it can be searched for
	FWP()->indexer->index();

	update_post_meta( $listing_id, '_listing_title', $listing_title );
	update_post_meta( $listing_id, '_listing_city', $listing_data['city'] );
	update_post_meta( $listing_id, '_listing_state', $listing_data['state'] );
	update_post_meta( $listing_id, '_listing_lat_lng', $listing_data['latlng'] );
	update_post_meta( $listing_id, '_listing_description', $listing_data['hay_desc'] );
	update_post_meta( $listing_id, '_listing_hay_qty', $listing_data['hay_qty'] );
	update_post_meta( $listing_id, '_listing_hay_qty_unit', $listing_data['hay_qty_unit'] );
	update_post_meta( $listing_id, '_listing_hay_type', $listing_data['hay_type'] );
	update_post_meta( $listing_id, '_listing_hay_size', $listing_data['hay_size'] );
	update_post_meta( $listing_id, '_listing_hay_fertilized', $listing_data['hay_fertilized'] );
	update_post_meta( $listing_id, '_listing_rfv', $listing_data['hay_rfv'] );
	update_post_meta( $listing_id, '_listing_hay_price', $listing_data['hay_price'] );
	update_post_meta( $listing_id, '_listing_hay_price_unit', $listing_data['hay_price_unit'] );
	update_post_meta( $listing_id, '_listing_email', $listing_data['email']);
	update_post_meta( $listing_id, '_listing_name', $listing_data['first_name'] . ' ' . $listing_data['last_name'] );
	update_post_meta( $listing_id, '_listing_phone', $listing_data['phone'] );
	update_post_meta( $listing_id, '_listing_plan', $listing_data['plan'] );

	if ( $listing_data['listing_type'] == 'featured|12' ) {
		update_post_meta( $listing_id, '_listing_is_featured', 'on' );
	}

	if ( $listing_data['listing_type'] == 'featured_account|100' ) {
		update_user_meta( $user_id, '_is_featured_account', true );
		update_user_meta( $user_id, '_featured_account_start_date', current_time('mysql') );
		update_post_meta( $listing_id, '_listing_is_featured', 'on' );
	}

	// if an image was uploaded then set as featured
	$parts = explode( '|', $listing_data['image_data'] );
	$img_id = array_pop( $parts );

	// If our photo upload was successful, set the featured image
	if ( $img_id && ! is_wp_error( $img_id ) ) {
	    set_post_thumbnail( $listing_id, $img_id );
	}

	if(is_wp_error($img_id)){
            echo "Error uploading file: " . $img_id->get_error_message();
    }
}

// FOR FORM 1, if the user has featured, hide pricing fields
//_is_featured_account 

add_filter( 'gform_field_value_user_is_featured', 'ah_custom_population_function' );
function ah_custom_population_function( $value ) {

	if ( get_user_meta( get_current_user_id(), '_is_featured_account', true ) ) {
		return 'true';
	}

	return;

}