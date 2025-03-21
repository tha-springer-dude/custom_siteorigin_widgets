<?php
/*
Widget Name: Show Tour Products
Description: A simple widget that displays woocommerce products loop
Author: Willie Springer
Author URI: http://example.com
*/

class Custom_Products_Carousel_Widget extends SiteOrigin_Widget {

    function __construct() {
        parent::__construct(
            'custom-wooproducts-carousel-siteorigin-widget',
            __( 'Show Tour Products', 'my-custom-siteorigin-Widgets' ),
            array(
                'description' => __( 'A simple widget to display toue products in a loop', 'my-custom-siteorigin-widgets' ),
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
            'products-carousel-widget-styles',  // Handle for the stylesheet
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
siteorigin_widget_register( 'custom-wooproducts-carousel-siteorigin-widget', __FILE__, 'Custom_Products_Carousel_Widget' );