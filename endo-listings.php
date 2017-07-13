<?php
/**
 * Plugin Name: Listings
 * Description: A custom listings plugin for All Hay
 * Version: 1.0.0
 * Author: Endo Creative
 * Author URI: http://www.endocreative.com/
 * Text Domain: endo-listings
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Get the CMB2 bootstrap!
 */
if ( file_exists(  __DIR__ . '/lib/cmb2/init.php' ) ) {
  require_once  __DIR__ . '/lib/cmb2/init.php';
} elseif ( file_exists(  __DIR__ . '/lib/CMB2/init.php' ) ) {
  require_once  __DIR__ . '/lib/CMB2/init.php';
}


/**
 * Enqueue scripts and styles.
 */
function endo_listings_load_scripts() {

	$apikey = 'AIzaSyCR9GEv4RhRylqwBmWp8APi0HO7JVHVe3Y';
	$mapsapi = '//maps.googleapis.com/maps/api/js?key=' . $apikey;
	wp_register_script( 'googlemaps', $mapsapi );
	wp_enqueue_script( 'googlemaps' );

	// wp_enqueue_script( 'listing-script', plugin_dir_url( __FILE__ ) . 'js/script.js', array('jquery'), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'endo_listings_load_scripts' );


/**
 * Call in necessary files.
 */
require plugin_dir_path( __FILE__ ) . 'includes/functions.php';
require plugin_dir_path( __FILE__ ) . 'includes/post-types.php';
require plugin_dir_path( __FILE__ ) . 'includes/shortcodes.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-submissions.php';
require plugin_dir_path( __FILE__ ) . 'includes/front-end-form.php';
// require plugin_dir_path( __FILE__ ) . 'lib/meta/cmb2_show_on_filters.php';
require plugin_dir_path( __FILE__ ) . 'lib/meta/listings.php';
require plugin_dir_path( __FILE__ ) . 'includes/dashboard-widget.php';