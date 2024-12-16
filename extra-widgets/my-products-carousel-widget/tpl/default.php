<?php
// Ensure direct access is prevented
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Determine the appropriate term slug based on current language
$term_slug = 'tourideen-de'; // Default value

// Check if Polylang function exists
if ( function_exists( 'pll_current_language' ) ) {
    $language_code = pll_current_language();

    // Determine term slug based on language
    switch ( $language_code ) {
        case 'de': // German
            $term_slug = 'tourideen-de';
            break;
        case 'ar': // Arabic
            $term_slug = 'tourideen-ar';
            break;
        // Add more language cases as needed
        default:
            $term_slug = 'tourideen-de'; // Fallback to default
            break;
    }
}
?>

<?php
// Display the widget title
if ( !empty( $instance['widget_title'] ) ) {
    echo '<h2 class="widget-title product-widget-title">' . esc_html( $instance['widget_title'] ) . '</h2><br>';
}
?>

<div class="product-swiper-container swiper-container">
<div class="product-swiper-wrapper swiper-wrapper">
<?php
// WP Query to get WooCommerce products from language-specific category
$args = array(
    'post_type' => 'product', // WooCommerce product post type
    'posts_per_page' => -1, // Retrieve all products
    'product_cat' => $term_slug, // Dynamically set product category based on language
);
$query = new WP_Query( $args );

// Start the loop to display each product
if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
        $query->the_post();
        $product = wc_get_product( get_the_ID() );
        
        // Default duration text
        $duration_text = 'Duration'; // Fallback text
        
        // Try to get custom duration if functions exist
        if ( function_exists('get_tourid_from_sku') && 
             function_exists('get_productgroup_name_by_tour_id') ) {
            
            // Retrieve the SKU
            $product_sku = get_post_meta( $product->get_id(), '_sku', true );
            
            // Safely attempt to get tour ID and organizer
            $tour_id = function_exists('get_tourid_from_sku') ? 
                get_tourid_from_sku( $product_sku ) : null;
            $tour_organizer = function_exists('get_productgroup_name_by_tour_id') ? 
                get_productgroup_name_by_tour_id( $tour_id ) : null;
            
            // Attempt to get duration based on organizer
            if ( $tour_organizer === "Wien Pass" && function_exists('get_tour_duration') ) {
                $tour_duration = get_tour_duration( $tour_id );
                if ( $tour_duration ) {
                    $duration_parts = explode( ' ', $tour_duration );
                    $duration_number = $duration_parts[0];
                    $duration_unit = $duration_parts[1];
                    
                    // Construct duration text
                    $duration_text = $duration_number . ' ' . $duration_unit;
                }
            } elseif ( $tour_organizer === "Flexi Pass" && function_exists('get_tour_sights') ) {
                $sights = get_tour_sights( $tour_id );
                if ( $sights ) {
                    $duration_parts = explode( ' ', $sights );
                    $duration_number = $duration_parts[0];
                    $duration_text = $duration_number .' '. pll__( 'Sehenswürdigkeiten', 'taminotravel' );
                }
            }
        }
        
        // Get the product image
        $size = 'woocommerce_thumbnail';
        $image_size = apply_filters( 'single_product_archive_thumbnail_size', $size );
        $get_image = $product->get_image( $image_size );
        
        // Add the duration text inside the badge
        $get_image = '<span class="wpd-sale-thumbnail"><span class="wpd-sale-badges">' . esc_html($duration_text) . '</span>' . $get_image . '</span>';
        
        $product_title = get_the_title();
        $product_link = get_permalink();

        // HTML for each card
        echo '<div class="product-swiper-slide swiper-slide">';
        echo '<div class="product-card card">';
        echo '<div class="product-image-container">';
        echo '<a href="' . esc_url( $product_link ) . '">' . $get_image . '</a>';
        
        //echo $get_image; // Use the image with duration badge
        echo '</div>';
        echo '<div class="product-card-body card-body">';
        echo '<p class="product-title">' . esc_html( $product_title ) . '</p>';
        // Optional: Display product price
        echo '<p class="product-price price">' . $product->get_price_html() . '</p>';
        echo '</div>'; // product-card-body
        echo '</div>'; // product-card
        echo '</div>'; // product-swiper-slide
    }
} else {
    echo 'No products found in the ' . esc_html( $term_slug ) . ' category.';
}
wp_reset_postdata(); // Reset post data after the loop
?>
</div>
</div>