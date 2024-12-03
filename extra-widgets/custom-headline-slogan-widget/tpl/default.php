<?php
// Ensure direct access is prevented
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<div class="hello-world-widget position-relative" style="color: <?php echo esc_attr( $instance['color'] ); ?>">

    <!-- Hero Section (Background Image) -->
    
    <div class="hero-container position-relative">
        <div class="wp-block-cover is-light">
            <!-- Background Overlay with Gradient and Opacity -->
            <span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span>
            
           
            <div class="text_layer">

                <h1 class="wp-block-heading_slogan">
                    <?php echo wp_kses_post( $instance['slogan'] ); ?>
                </h1>
                <!-- Text Overlay in Hero Section -->
                <h2 class="wp-block-heading">
                    <?php echo wp_kses_post( $instance['header'] ); ?>
                </h2>


            </div>
        </div>
    </div> <!-- End of Hero Section -->

</div> <!-- End of hello-world-widget -->
