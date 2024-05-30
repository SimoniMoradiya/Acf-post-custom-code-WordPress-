function display_acf_custom_posts() {
    // Custom post type ko query karna
    $args = array(
        'post_type' => 'Doctor', // Apne custom post type ka slug yahan dalein
        'posts_per_page' => -1, // Saare posts ko retrieve karne ke liye
        'meta_query' => array(
            array(
                'key' => 'location', // ACF relationship field ka key yahan dalein
                'compare' => 'EXISTS', // Check karein ke relationship exist karta hai ya nahi
            ),
        ),
    );

    $custom_query = new WP_Query($args);

    if ($custom_query->have_posts()) {
        $output = '<div class="custom-posts-wrapper">';
        $output .= '<div class="et_pb_section">';
        $output .= '<div class="et_pb_row">';

        // Display filter button for locations
        $output .= '<div class="filter-button-wrapper">';
        $output .= '<button class="filter-button" data-filter="all">All</button>'; // Button to show all posts
        
        // Get unique locations from the query
        $locations = array();
        while ($custom_query->have_posts()) {
            $custom_query->the_post();
            $current_locations = get_field('location');

            if ($current_locations) {
                foreach ($current_locations as $location) {
                    $locations[] = $location->post_title;
                }
            }
        }

        // Remove duplicate locations
        $unique_locations = array_unique($locations);

        // Display buttons for each unique location
        foreach ($unique_locations as $location) {
            $output .= '<button class="filter-button" data-filter="' . $location . '">' . $location . '</button>';
        }
        $output .= '</div>'; // Close filter-button-wrapper

        // Restore original Post Data
        wp_reset_postdata();

        // Reset the query to the beginning
        $custom_query = new WP_Query($args);

        // Display posts
        while ($custom_query->have_posts()) {
            $custom_query->the_post();
            $post_locations = get_field('location'); // Renamed variable
            $post_specialties = get_field('specialty'); // Renamed variable
			$post_address = get_field('address');
			$post_directions = get_field('directions');
			$post_hours = get_field('hours');
			$post_phone = get_field('phone');

            $output .= '<div class="custom-post post-col et_pb_column et_pb_column_1_3">';
            if (has_post_thumbnail()) {
                $output .= '<div class="post-thumbnail">';
                $output .= get_the_post_thumbnail(get_the_ID(), 'medium'); // 'medium' size ko use karte hue
                $output .= '</div>';
            }
            $output .= '<a href="' . get_permalink() . '" class="view-profile" >View Profile</a>';
            $output .= '<h2>' . get_the_title() . '</h2>';
            
            // Display location
			if ($post_locations) {
				$output .= '<div class="location">';
				$locations_count = count($post_locations);
				$index = 0;
				foreach ($post_locations as $post_location) {
					$output .= '<span class="location">' . $post_location->post_title . '</span>';
					if ($index < $locations_count - 1) {
						$output .= ', '; // Add comma after each location except the last one
					}
					$index++;
				}
				$output .= '</div>';
			}



			// Display specialties
			if ($post_specialties) {
				$output .= '<div class="specialties">';
				$specialties_count = count($post_specialties);
				$index = 0;
				foreach ($post_specialties as $post_specialty) {
					$output .= '<span class="specialty">' . $post_specialty . '</span>';
					if ($index < $specialties_count - 1) {
						$output .= ', '; // Add comma after each specialty except the last one
					}
					$index++;
				}
				$output .= '</div>';
			}

			// Display address
			if($post_address){
				$output .= '<div class="post-address">';
					$output .= '<span class="address">'. $post_address . '</span>';
				$output .='</div>';
			}

			// Display directions
			if($post_directions){
				$output .= '<div class="directions">';
					$output .= '<a href="'. $post_directions .'" class="direction">'. $post_directions . '</a>';
				$output .='</div>';
			}

			// Display hours
			if($post_hours){
				$output .= '<div class="hours">';
					$output .= '<span class="hour">'. $post_hours . '</span>';
				$output .='</div>';
			}

			// Display phone
			if($post_phone){
				$output .='<div class="post-phone">';
					$output .='<a href="tel:'.$post_phone.'">'. $post_phone .'</a>';
				$output .='</div>';
			}

           


            $output .= '</div>';
        }

        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
    } else {
        $output = '<p>No posts found</p>';
    }

    return $output;
}

// Is function ko shortcode banakar bhi use kiya ja sakta hai
add_shortcode('display_custom_posts', 'display_acf_custom_posts');

function enqueue_custom_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('custom-post-filer', get_template_directory_uri() . '/js/custom-post-filer.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');
