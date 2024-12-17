<?php
// Ensure direct access is prevented
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<?php

// Display the widget title

echo '<h2 class="widget-title">' . pll__( 'Interessante Orte', 'taminotravel' ) . '</h2><br>';

// Get the tags of the current post
$post_tags = get_the_tags();

if ($post_tags) {
    // Prepare the tag names
    $tag_names = array();
    foreach ($post_tags as $tag) {
        $tag_names[] = $tag->name;
    }

    // Create a search string using SQL REGEXP for fuzzy matching
    $search_string = implode('|', array_map('esc_sql', $tag_names));

    // Query locations based on fuzzy matching of tag names in the titles
    global $wpdb;
    $locations = $wpdb->get_results(
        $wpdb->prepare(
            "
            SELECT p.ID, p.post_title, p.post_name 
            FROM {$wpdb->posts} p
            WHERE p.post_type = 'ttbm_places' 
            AND p.post_status = 'publish'
            AND p.post_title REGEXP %s
            ",
            $search_string
        )
    );

    // Check if we have matching locations
    if ($locations) {
        echo '<ul class="related-posts-list">'; // Start the list

        foreach ($locations as $location) {
            $location_url = get_permalink($location->ID);
            $location_title = esc_html($location->post_title);

            // Get the featured image (small size)
            $thumbnail = get_the_post_thumbnail($location->ID, 'thumbnail'); // Use 'thumbnail' size

            // Get the excerpt for the location
            $excerpt = get_the_excerpt($location->ID);

            // Start the list item for each location
            echo '<li class="related-post-item">';
            
            // Display the title above the image
            echo '<h3 class="related-post-title"><a href="' . esc_url($location_url) . '">' . $location_title . '</a></h3>';
            
            // Display the featured image with the floating excerpt
            echo '<div class="related-post-content">';
            echo '<div class="related-post-thumbnail">' . $thumbnail . '</div>';
            echo '<div class="related-post-excerpt">' . esc_html($excerpt) . ' <br/>';
            echo '<a href="' . esc_url($location_url) . '">' . pll__( 'Weiterlesen', 'taminotravel' ) . '</a>';
            echo '</div>';
            echo '</div>'; // Close the content div

            echo '</li>';
        }

        echo '</ul>'; // End the list
    } else {
        echo 'No matching locations found based on the post tags.';
    }
} else {
    echo 'This post has no tags.';
}
?>


