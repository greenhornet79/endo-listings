<?php 

function ah_frontend_form_update_listing() {

	$prefix = '_listing_';
    
    $cmb_listing = new_cmb2_box( array(
        'id'           => 'manage-listing-form',
        'object_types' => array( 'listing' ),
        'hookup'       => false,
       // 'save_fields'  => false,
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
        'name'    => __( 'Description', 'wds-post-submit' ),
        'id' => $prefix . 'description',
		'type' => 'textarea_small',
       
    ) );

    $cmb_listing->add_field( array(
        'name' => __( 'Quantity', 'wds-post-submit' ),
        'id' => $prefix . 'hay_qty',
		'type' => 'text',
    ) );

    $cmb_listing->add_field( array(
		'name' => 'Price',
		'id' => $prefix . 'hay_price',
		'type' => 'text',
	) );

}

add_action( 'cmb2_init', 'ah_frontend_form_update_listing' );



/**
 * Handle the cmb-frontend-form shortcode
 *
 * @param  array  $atts Array of shortcode attributes
 * @return string       Form html
 */
function ah_do_frontend_form_submission_shortcode( $atts = array() ) {

    // Current user
    $user_id = get_current_user_id();

    // Use ID of metabox in wds_frontend_form_register
    $metabox_id = 'manage-listing-form';

    // since post ID will not exist yet, just need to pass it something
    $object_id  = get_the_ID();

    // Get CMB2 metabox object
    $cmb = cmb2_get_metabox( $metabox_id, $object_id );

    // Get $cmb object_types
    $post_types = $cmb->prop( 'object_types' );

    // // Parse attributes. These shortcode attributes can be optionally overridden.
    // $atts = shortcode_atts( array(
    //     'post_author' => $user_id ? $user_id : 1, // Current user, or admin
    //     'post_status' => 'pending',
    //     'post_type'   => reset( $post_types ), // Only use first object_type in array
    // ), $atts, 'cmb-frontend-form' );

    // Initiate our output variable
    $output = '';

    // Get our form
    $output .= cmb2_get_metabox_form( $cmb, $object_id, array( 'save_button' => __( 'Update Listing', 'wds-post-submit' ) ) );

    return $output;
}
add_shortcode( 'manage_listing_form', 'ah_do_frontend_form_submission_shortcode' );

