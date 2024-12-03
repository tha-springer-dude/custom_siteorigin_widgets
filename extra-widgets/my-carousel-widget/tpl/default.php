<?php
// Ensure direct access is prevented
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="hello-world-widget position-relative" style="color: <?php echo esc_attr( $instance['color'] ); ?>">

    <!-- Hero Section (Background Image) -->
    
    <div class="hero-container position-relative">
        <div class="is-light">
            <!-- Background Overlay with Gradient and Opacity -->
            <span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span>
            
           
            <div class="text_layer">

                <?php echo "here is where it happens....."; ?>

            </div>
        </div>
    </div> <!-- End of Hero Section -->

</div> <!-- End of hello-world-widget -->
    