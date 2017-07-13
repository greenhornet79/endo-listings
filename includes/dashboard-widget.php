<?php 

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Register the recent buyer/seller dashboard widget
 * @since  3.8.0
 */
function endo_listings_register_recent_buyerseller_dashboard_widget() {

	if ( current_user_can( 'manage_options' ) ) {
		wp_add_dashboard_widget('endo_listings_dashboard_widget', 'All Hay Dashboard', 'endo_listings_load_recent_buyerseller_dashboard_widget');
		wp_add_dashboard_widget('endo_current_listings_dashboard_widget', 'Current Listings Dashboard', 'endo_listings_load_current_buyerseller_dashboard_widget');
	}
	
}
add_action('wp_dashboard_setup', 'endo_listings_register_recent_buyerseller_dashboard_widget' );

/**
 * Output the contents of the recent subscribers dashboard widget
 * @since  3.8.0
 */
function endo_listings_load_recent_buyerseller_dashboard_widget( $post, $callback_args ) {

	?>

	<h3><strong>Recent Sellers</strong></h3>

	<?php 

		$args = array(
			'order'	=> 'DESC',
			'orderby'	=> 'ID',
			'number'	=> 20,
			'meta_query'	=> array(
				array(
					'key'	=> 'el_is_seller',
					'value' => true,
					'compare'	=> '='
				)
			)
		);

		$users = get_users( $args ); 

		if ( $users ) {
			?>	
			<table class="widefat">
				<thead>
					<tr>
						<th>Date Registered</th><th>Name</th><th># of Listings</th>
					</tr>
				</thead>
			<?php 	

			foreach ( $users as $user ) {

				$date = $user->user_registered;
				$name = $user->first_name . ' ' . $user->last_name;

				if ( !trim($name) ) {
					$name = $user->user_email;
				}

				$num_listings = get_num_seller_listings( $user );
			

				echo '<tr><td>' . date( 'M d, Y', strtotime($date) ) . '</td><td> <a href="' . admin_url() . '/user-edit.php?user_id=' . $user->ID . '">' . $name . '</a></td><td>' . $num_listings . '</td>';
			}

			echo '</table>';
		} else {
			echo '<p>No sellers found.</p>';
		}

		// echo '<p><a href="' . admin_url() . 'admin.php?page=endo-listings-sellers">See all Sellers »</a></p>';
		
	?>
	<p>&nbsp;</p>
	<h3><strong>Recent Buyers</strong></h3>

	<?php 

		$args = array(
			'order'	=> 'DESC',
			'orderby'	=> 'ID',
			'number'	=> 20,
			'meta_query'	=> array(
				array(
					'key'	=> 'rcp_status',
					// 'value' => 'active',
					'compare'	=> 'EXISTS'
				)
			)
		);

		$users = get_users( $args ); 

		if ( $users ) {
			?>	
			<table class="widefat">
				<thead>
					<tr>
						<th>Date Registered</th><th>Name</th><th>Status</th>
					</tr>
				</thead>
			<?php 	

			foreach ( $users as $user ) {

				$date = $user->user_registered;
				$name = $user->first_name . ' ' . $user->last_name;

				if ( !trim($name) ) {
					$name = $user->user_email;
				}

				$status = get_user_meta( $user->ID, 'rcp_status', true );



				echo '<tr><td>' . date( 'M d, Y', strtotime($date) ) . '</td><td> <a href="' . admin_url() . '/user-edit.php?user_id=' . $user->ID . '">' . $name . '</a></td><td>' . $status . '</td>';

			}

			echo '</table>';
		} else {
			echo '<p>No buyers found.</p>';
		}

		// echo '<p><a href="' . admin_url() . 'admin.php?page=endo-listings-buyers">See all Buyers »</a></p>';
		
	?>

	
	<?php 
}



/**
 * Output the contents of the recent subscribers dashboard widget
 * @since  3.8.0
 */
function endo_listings_load_current_buyerseller_dashboard_widget( $post, $callback_args ) {

	$args = array(
	  'post_type' => 'listing',
	  'posts_per_page'	=> 900,
	 
	);

	$listings = new WP_Query( $args );


	?>

	<h3><strong>Current Listings (<?php echo $listings->post_count; ?>)</strong></h3>

	<?php 

		wp_reset_postdata();

		$args = array(
			'order'	=> 'ASC',
			'orderby'	=> 'display_name',
			'number'	=> 600,
			'meta_query'	=> array(
				array(
					'key'	=> 'el_is_seller',
					'value' => true,
					'compare'	=> '='
				)
			)
		);

		$users = get_users( $args ); 

		if ( $users ) {
			?>	
			<table class="widefat">
				<thead>
					<tr>
						<th>Name</th><th>State</th><th>Created</th><th>Removed</th><th>&nbsp;</th>
					</tr>
				</thead>
			<?php 	

			foreach ( $users as $user ) {

			
				$name = $user->first_name . ' ' . $user->last_name;

				if ( !trim($name) ) {
					$name = $user->user_email;
				}

				$args = array(
				  'post_type' => 'listing',
				  'posts_per_page'	=> 200,
				  'author' => $user->ID,
				  'post_status' => 'publish, trash'
				);

				$the_query = new WP_Query( $args );

				$count = $the_query->post_count;

				if ( $the_query->have_posts() ) {
					
					while ( $the_query->have_posts() ) {
						$the_query->the_post();

						$date = get_the_date();
						$state = get_post_meta( $the_query->post->ID, '_listing_state', true );
						$removed = get_post_meta( $the_query->post->ID, '_listing_date_removed', true );
						
						echo '<tr><td> <a href="' . admin_url() . '/user-edit.php?user_id=' . $user->ID . '">' . $name . '</a></td><td>' . $state . '</td><td>' . $date . '</td><td>' . date( 'Y-m-d', $removed ) . '</td><td><a target="_blank" href="' . get_the_permalink() . '">View</a></td></tr>';
					}
					
				}
				wp_reset_postdata();
		
				
			}

			echo '</table>';

		}


}

function get_num_seller_listings( $user ) {

		$args = array(
		  'post_type' => 'listing',
		  'posts_per_page'	=> 200,
		  'author' => $user->ID
		);

		$the_query = new WP_Query( $args );

		return $the_query->post_count;
	
}

add_action('admin_init', 'update_endo_listing_data' );


function update_endo_listing_data() {

	$args = array(
	  'post_type' => 'listing',
	  'posts_per_page' => 300
	);

	$the_query = new WP_Query( $args );

	// The Loop
	if ( $the_query->have_posts() ) {
		
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			
			update_user_meta( $the_query->post->post_author, 'el_is_seller', true );

		}
		
	} else {
		// no posts found
	}
	/* Restore original Post Data */
	wp_reset_postdata();
	
}
