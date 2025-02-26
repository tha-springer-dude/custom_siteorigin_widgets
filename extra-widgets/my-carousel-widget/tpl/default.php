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
        // Query Orte (tamino_travel_orte) directly instead of WooCommerce products
        $args = array(
            'post_type'      => 'tamino_travel_orte',  // Custom post type for Orte
            'posts_per_page' => -1,                    // Retrieve all Orte
        );

        $query = new WP_Query( $args );

        // Start the loop to display each Ort
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $ort_id    = get_the_ID();
                $ort_title = get_the_title();
                $ort_link  = get_permalink();
                $ort_image = get_the_post_thumbnail_url( $ort_id, 'medium' ); // Adjust the image size

                // HTML for each card
                echo '<div class="swiper-slide">';
                echo '<a href="' . esc_url( $ort_link ) . '" class="card-link">';
                echo '<div class="card">';
                if ( $ort_image ) {
                    echo '<img src="' . esc_url( $ort_image ) . '" alt="' . esc_attr( $ort_title ) . '">';
                }
                echo '<div class="card-body">';
                echo '<p>' . esc_html( $ort_title ) . '</p>';
                echo '</div>'; // card-body
                echo '</div>'; // card
                echo '</a>'; // card-link
                echo '</div>'; // swiper-slide
            }
        } else {
            echo 'No Orte found.';
        }

        wp_reset_postdata(); // Reset post data after the loop
        ?>
    </div>
</div>
