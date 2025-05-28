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
function get_blogposts_prototype($unused = null) {
    $current_id = get_the_ID();
    $post_type = get_post_type($current_id);

    // Match against locations or activities
    if ($post_type === 'tamino_travel_orte') {
        $acf_key = 'linked_locations';
    } elseif ($post_type === 'tamino_tr_activities') {
        $acf_key = 'linked_activities';
    } else {
        return; // Skip unsupported post types
    }

    $related = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => 5,
        'meta_query'     => [[
            'key'     => $acf_key,
            'value'   => '"' . $current_id . '"',
            'compare' => 'LIKE',
        ]]
    ]);

    if ($related->have_posts()) {
        echo '<ul class="related-posts-list">';
        while ($related->have_posts()) {
            $related->the_post();
            $thumbnail = get_the_post_thumbnail(get_the_ID(), 'thumbnail');
            $excerpt   = get_the_excerpt();
            $post_url  = get_permalink();

            echo '<li class="related-post-item">';
            echo '<h3 class="related-post-title"><a href="' . esc_url($post_url) . '">' . esc_html(get_the_title()) . '</a></h3>';
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

    wp_reset_postdata();
}


if (is_singular('tamino_travel_orte')) {
    show_activities_linked_to_current_place();
}

function show_activities_linked_to_current_place() {
    $activities = get_field('ort_linked_activities'); // ACF field on Ort post

    if (!empty($activities)) {
        echo '<h3 class="widget-title">' . pll__('Beliebte Aktivitäten', 'taminotravel') . '</h3>';
echo '<div class="linked-activities-wrapper">';
foreach ($activities as $activity) {
    $title   = get_the_title($activity->ID);
    $excerpt = get_the_excerpt($activity->ID);
    $url     = get_permalink($activity->ID);
    $thumb   = get_the_post_thumbnail($activity->ID, 'thumbnail');

    echo '<div class="linked-activity-card">';
    echo '  <h4><a href="' . esc_url($url) . '">' . esc_html($title) . '</a></h4>';
    echo '  <div class="activity-thumb">' . $thumb . '</div>';
    echo '  <p>' . esc_html(wp_trim_words($excerpt, 20)) . '</p>';
    echo '<a href="' . esc_url($post_url) . '">' . pll__('Weiterlesen', 'taminotravel') . '</a>';
    echo '</div>';


    }
    echo '</div>';

    } else {
        echo '<p>' . pll__('Keine Aktivitäten verknüpft.', 'taminotravel') . '</p>';
    }
}



