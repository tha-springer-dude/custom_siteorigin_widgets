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
<div class="swiper-container">
    <div class="swiper-wrapper">
        <?php
        // WP Query to get the places
        $args = array(
            'post_type'      => 'ttbm_places',  // Custom post type
            'posts_per_page' => -1,              // Retrieve all places
        );

        $query = new WP_Query( $args );


        // Start the loop to display each place
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $place_title = get_the_title();
                $place_link  = get_permalink();
                $place_image = get_the_post_thumbnail_url( get_the_ID(), 'medium' ); // Adjust the image size

                // HTML for each card
                echo '<div class="swiper-slide">';
                 echo '<a href="' . esc_url( $place_link ) . '" class="card-link">';
                echo '<div class="card">';
                if ( $place_image ) {
                    echo '<img src="' . esc_url( $place_image ) . '" alt="' . esc_attr( $place_title ) . '">';
                }
                echo '<div class="card-body">';
                echo '<p>' . esc_html( $place_title ) . '</p>';
                //echo '<p>Short description or details about the place.</p>';
                echo '</div>'; // card-body
                echo '</div>'; // card
                echo '</a>'; // card-link
                echo '</div>'; // swiper-slide
            }
        } else {
            echo 'No places found.';
        }

        wp_reset_postdata(); // Reset post data after the loop
        ?>
    </div>
</div>