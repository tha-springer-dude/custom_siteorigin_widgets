<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Initialize search parameters
$search_parameters = [];

// Detect the current post
global $post;
$product_id = $post->ID;

// Case 1: Single Location Page (tamino_travel_orte)
if (is_singular('tamino_travel_orte')) {
    $search_parameters[] = get_the_title(); // Add location title as search parameter
}

// Case 2: Single Location Page (tamino_tr_activities)
if (is_singular('tamino_tr_activities')) {
    $search_parameters[] = get_the_title(); // Add location title as search parameter
}


// Case 3: Single Product Page (WooCommerce Product)
if (is_singular('product')) {
    // Fetch associated locations
    $locations = get_locations($product_id);
    // Merge locations into search parameters
    $search_parameters = array_merge($search_parameters, $locations);
}

// Execute blog post search if there are valid parameters
if (!empty($search_parameters)) {
    get_blogposts_prototype($search_parameters);
}

// Function to get locations related to a product
function get_locations($product_id) {
    $locations = get_post_meta($product_id, '_tamino_travel_orte', true);
    $location_names = [];

    if (is_array($locations) && !empty($locations)) {
        foreach ($locations as $place_id) { // Directly use place ID
            $place_post = get_post($place_id);
            if ($place_post && $place_post->post_type === 'tamino_travel_orte') {
                $location_names[] = $place_post->post_title; // Store location name
            }
        }
    }

    return $location_names;
}

// Function to fetch and display related blog posts
function get_blogposts_prototype($search_param_prototype) {
    //error_log("Searchterm is: ".$search_param_prototype[0]);
    $unique_post_ids = []; // ✅ Track unique post IDs
    $unique_posts = [];

    if (!empty($search_param_prototype)) {
        foreach ((array) $search_param_prototype as $param) {
            $search_string = sanitize_title($param); // Convert location to slug

            $related_posts = new WP_Query([
                'post_type'      => 'post',
                'tag'            => $search_string,
                'posts_per_page' => 5,
            ]);

            if ($related_posts->have_posts()) {
                while ($related_posts->have_posts()) {
                    $related_posts->the_post();
                    $post_id = get_the_ID();

                    // ✅ **Deduplication: Ensure each post is only added once**
                    if (!isset($unique_post_ids[$post_id])) {
                        $unique_post_ids[$post_id] = true; // Track unique IDs
                        $unique_posts[] = get_post($post_id); // Store post object
                    }
                }
            }

            wp_reset_postdata();
        }
    }

    // ✅ **Now we only display unique posts**
    if (!empty($unique_posts)) {
        echo '<ul class="related-posts-list">';
        foreach ($unique_posts as $post) {
            $thumbnail = get_the_post_thumbnail($post->ID, 'thumbnail');
            $excerpt = get_the_excerpt($post->ID);
            $post_url = get_permalink($post->ID);

            echo '<li class="related-post-item">';
            echo '<h3 class="related-post-title"><a href="' . esc_url($post_url) . '">' . esc_html($post->post_title) . '</a></h3>';
            echo '<div class="related-post-content">';
            echo '<div class="related-post-thumbnail">' . $thumbnail . '</div>';
            echo '<div class="related-post-excerpt">' . esc_html($excerpt) . ' <br/>';
            echo '<a href="' . esc_url($post_url) . '">' . pll__('Weiterlesen', 'taminotravel') . '</a>';
            echo '</div></div></li>';
        }
        echo '</ul>';
    } else {
        echo '<p>' . pll__('Keine verwandten Blogbeiträge gefunden.', 'taminotravel') . '</p>';
    }
}
