<?php
// enable Password field for Gravity Forms
add_filter( 'gform_enable_password_field', '__return_true' );


add_action('init', 'maybe_delete_listing' );

function maybe_delete_listing() {

	if ( !$_GET['delete_listing'] ) {
		return;
	}

	$listing_id = sanitize_text_field( $_GET['delete_listing'] );

	update_post_meta( $listing_id, '_listing_date_removed', current_time('timestamp') );

	wp_delete_post( $listing_id );

}

add_action('admin_init', 'el_no_mo_dashboard' );
add_action('after_setup_theme', 'el_remove_admin_bar' );

	// don't allow users to access WordPress admin
function el_no_mo_dashboard() {

	$current_user = wp_get_current_user();

	if ( ! current_user_can( 'delete_others_posts' ) && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
  		wp_redirect(home_url()); exit;
  	}

}

	// remove admin bar from non admin users
function el_remove_admin_bar() {
	if (!current_user_can('delete_others_posts') && !is_admin()) {
		show_admin_bar(false);
	}
}