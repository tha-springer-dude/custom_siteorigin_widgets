<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Initialize array for search parameters
$search_parameters = [];

// Case 1: Single Location Page (ttbm_places)
if (is_singular('ttbm_places')) { 
    $search_parameters[] = get_the_title(); // Add the location title
}

// Case 2: Product Page (WooCommerce Product)
if (is_singular('product')) {
    global $post;

    $product_sku = get_post_meta($post->ID, '_sku', true);
    $tour_id = get_tourid_from_sku_widget($product_sku);

    if ($tour_id) {
        // Fetch associated locations
        $locations = get_locations($tour_id);
        // Merge locations into search parameters
        $search_parameters = array_merge($search_parameters, $locations);
    }
}

// Execute blog post search if there are valid parameters
if (!empty($search_parameters)) {
    get_blogposts_prototype($search_parameters);
}

// Function to extract tour ID from SKU
function get_tourid_from_sku_widget($sku) {
    return (!empty($sku) && strpos($sku, 'taminotour-') === 0) ? str_replace('taminotour-', '', $sku) : null;
}

// Function to get locations related to a product
function get_locations($product_id) {
    $locations_serialized = get_post_meta($product_id, 'ttbm_hiphop_places', true);
    $locations = maybe_unserialize($locations_serialized);
    $location_names = [];

    if (is_array($locations) && !empty($locations)) {
        foreach ($locations as $location) {
            $place_id = $location['ttbm_city_place_id'] ?? null;
            if ($place_id) {
                $place_post = get_post($place_id);
                if ($place_post && $place_post->post_type === 'ttbm_places') {
                    $location_names[] = $place_post->post_title; // Store location name
                }
            }
        }
    }

    return $location_names;
}

// Function to fetch and display related blog posts
function get_blogposts_prototypeddd($search_param_prototype) {
    if (!empty($search_param_prototype)) {
        foreach ((array) $search_param_prototype as $param) {
            get_blogposts($param);
        }
    }
}

function get_blogposts_prototype($search_param_prototype) {
    $unique_post_ids = []; // ✅ This tracks all unique posts across locations
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
        echo '<p>No related blog posts found for these locations.</p>';
    }
}

?>
