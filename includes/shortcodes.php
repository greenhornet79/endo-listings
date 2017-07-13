<?php
// Display the specified content only if user is logged in
function endo_listings_loggedin_shortcode( $atts, $content = null ) {
	if ( is_user_logged_in() ) {
		return do_shortcode($content);
	}
}
add_shortcode('loggedin', 'endo_listings_loggedin_shortcode');

// Display the specified content if user is NOT logged in
function endo_listings_loggedout_shortcode( $atts, $content = null ) {
	if ( !is_user_logged_in() ) {
		return do_shortcode($content);
	}
}
add_shortcode('loggedout', 'endo_listings_loggedout_shortcode');

// display login form
function endo_listings_login_form_shortcode() {
	ob_start();

	if ( is_user_logged_in() ) {
		echo 'Welcome back.';
	} else {

		wp_login_form();
?>
		<a href="<?php echo home_url() . '/register'; ?>">Create account</a>
<?php
	}
	
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
add_shortcode( 'login-form', 'endo_listings_login_form_shortcode' );


// manage my listings
function endo_listings_manage_listings_shortcode() {
	ob_start();

	if ( is_user_logged_in() ) {

		$args = array(
		  'post_type' => 'listing',
		  'author'	=> get_current_user_id()
		);

		$the_query = new WP_Query( $args );

		// The Loop
		if ( $the_query->have_posts() ) {
			echo '<h2>Manage My Listings</h2>';
			echo '<ul class="manage-listings">';
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				echo '<li><a style="font-size: 20px;" href="' . get_the_permalink() . '?manage_listing=true">' . get_the_title() . '</a><br>';
				echo '<a onclick="return confirm(\'Are you SURE you want to delete this listing?\')" href="' . home_url() . '/manage-listings/?delete_listing=' . $the_query->post->ID . '" style="color: red;">Delete Listing</a>';
				echo '</li>';
			}
			echo '</ul>';
		} else {
			echo '<p>You do not have any active listings.</p>';
			echo '<p><a href="' . home_url() . '/add-listing/">Add New Listing</a></p>';
		}
		/* Restore original Post Data */
		wp_reset_postdata();

	} else {

		wp_login_form();
?>
		<a href="<?php echo home_url() . '/register'; ?>">Create account</a>
<?php
	}
	
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
add_shortcode( 'manage_listings', 'endo_listings_manage_listings_shortcode' );



function display_all_listings_func( $atts ) {

    ob_start(); ?>
    
    	<?php

		$args = array(
		  'post_type' => 'listing',
		  'posts_per_page' => 999,
		  'meta_key' => '_listing_state',
		  'orderby'	=> 'meta_value',
		  'order'	=> 'ASC'
		);

		$the_query = new WP_Query( $args );

		// The Loop
		if ( $the_query->have_posts() ) {
			echo '<ul>';
			while ( $the_query->have_posts() ) {
				$the_query->the_post();

				$state = get_post_meta( $the_query->post->ID, '_listing_state', true );
				echo '<li>' . $state . ' - <a href="' . get_the_permalink() . '">' . get_the_title() . '</a></li>';
				
			}
			echo '</ul>';
		} else {
			// no posts found
		}
		/* Restore original Post Data */
		wp_reset_postdata();
    
    $content = ob_get_contents();
	ob_end_clean();

	return $content; 
}
add_shortcode( 'display_all_listings', 'display_all_listings_func' );

function display_listings_by_state( $atts ) {

	$a = shortcode_atts( array(
	    'state' => '',
	), $atts );

    ob_start(); ?>
    
    	<?php

		$args = array(
		  'post_type' => 'listing',
		  'posts_per_page' => 999,
		  'meta_key' => '_listing_city',
		  'orderby'	=> 'meta_value',
		  'order'	=> 'ASC',
		  'meta_query' => array(
	          'relation' => 'AND',
	          array(
          			'key'     => '_listing_state',
          			'value'   => $a['state'],
          		),
	          
	      ),
		);

		$the_query = new WP_Query( $args );

		// The Loop
		if ( $the_query->have_posts() ) {
			echo '<ul>';
			while ( $the_query->have_posts() ) {
				$the_query->the_post();

				echo '<li><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></li>';
				
			}
			echo '</ul>';
		} else {
			// no posts found
		}
		/* Restore original Post Data */
		wp_reset_postdata();
    
    $content = ob_get_contents();
	ob_end_clean();

	return $content; 
}
add_shortcode( 'display_listings_by_state', 'display_listings_by_state' );