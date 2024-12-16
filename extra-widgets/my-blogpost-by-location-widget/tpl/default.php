<?php
// Ensure direct access is prevented
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<?php

// Display the widget title
if ( !empty( $instance['widget_title'] ) ) {
    echo '<h2 class="widget-title">' . esc_html( $instance['widget_title'] ) . '</h2><br>';
}

?>

<?php
// Step 1: Get the current location (e.g., "Hofburg in Wien")
$current_location = ''; // Initialize variable
if (is_singular('ttbm_places')) { // Check if viewing a location
    $current_location = get_the_title(); // Or fetch using other methods

    //echo "Location is: " . $current_location;
}

// Step 2: Prepare a search string for the tag
if (!empty($current_location)) {
    $search_string = sanitize_title($current_location); // Convert to a slug

    // Step 3: Query blog posts by tag
    $related_posts = new WP_Query(array(
        'post_type' => 'post',
        'tag' => $search_string, // Use sanitized location name as the tag
        'posts_per_page' => 5, // Limit number of posts
    ));

    // Step 4: Output the posts with featured image, title, and excerpt
    if ($related_posts->have_posts()) {
        echo '<ul class="related-posts-list">';
        while ($related_posts->have_posts()) {
            $related_posts->the_post();

            // Get the featured image (small size)
            $thumbnail = get_the_post_thumbnail(get_the_ID(), 'thumbnail'); // Use 'thumbnail' size

            // Get the post excerpt
            $excerpt = get_the_excerpt();
            
            // Start the list item for each post
            echo '<li class="related-post-item">';
            
            // Display the title above the image
            echo '<h3 class="related-post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            
            // Display the featured image with the floating excerpt
            echo '<div class="related-post-content">';
            echo '<div class="related-post-thumbnail">' . $thumbnail . '</div>';
            echo '<div class="related-post-excerpt">' . $excerpt . '</div>';
            echo '</div>'; // Close the content div

            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No related blog posts found for this location.</p>';
    }

    wp_reset_postdata();
}
?>
