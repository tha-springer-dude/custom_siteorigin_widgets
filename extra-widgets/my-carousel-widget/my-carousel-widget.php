<?php
/*
Widget Name: Show Tourlocations
Description: A simple widget that displays woocommerce loop
Author: Willie Springer
Author URI: http://example.com
*/

class Custom_Locations_Carousel_Widget extends SiteOrigin_Widget {

    function __construct() {
        parent::__construct(
            'custom-locations-carousel-siteorigin-widget',
            __( 'Show Tour Locations', 'my-custom-siteorigin-Widgets' ),
            array(
                'description' => __( 'A simple widget to display tourlocations in a loop', 'my-custom-siteorigin-widgets' ),
                'help'        => 'http://example.com/hello-world-widget-docs',
            ),
            array(),
            array(),
            plugin_dir_path( __FILE__ )
        );
        // Enqueue widget styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_widget_styles' ) );
    }

        // Enqueue custom widget styles
    public function enqueue_widget_styles() {
        wp_enqueue_style(
            'locations-carousel-widget-styles',  // Handle for the stylesheet
            plugin_dir_url( __FILE__ ) . 'styles.css',  // Path to the stylesheet
            array(),  // Dependencies (if any)
            filemtime( plugin_dir_path( __FILE__ ) . 'styles.css' )  // Version based on file modification time
        );
    }

    function get_template_name( $instance ) {
        return 'default';
    }

    function get_style_name( $instance ) {
        return 'default';
    }
}
siteorigin_widget_register( 'custom-locations-carousel-siteorigin-widget', __FILE__, 'Custom_Locations_Carousel_Widget' );