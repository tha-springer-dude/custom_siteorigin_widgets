<?php
/*
Widget Name: Custom Headlin and Slogan Widget
Description: A simple widget that displays a customizable Headline and Slogan 
Author: Willie Springer
Author URI: http://example.com
*/

class Headline_Slogan_Widget extends SiteOrigin_Widget {

    function __construct() {
        parent::__construct(
            'custom-headline-slogan-widget',
            __( 'Headline Slogan Widget', 'my-custom-siteorigin-Widgets' ),
            array(
                'description' => __( 'A simple widget to display a Headline and Slogan', 'my-custom-siteorigin-Widgets' ),
                'help'        => 'http://example.com/hello-world-widget-docs',
            ),
            array(),
            array(
            'header' => array(
                'type' => 'text',
                'label' => __( 'Header', 'my-custom-siteorigin-Widgets' ),
                'default' => 'My epic header',
            ),
            'slogan' => array(
                'type' => 'text',
                'label' => __( 'Slogan', 'my-custom-siteorigin-Widgets' ),
                'default' => 'My cool slogan!',
            ),
        ),
            plugin_dir_path( __FILE__ )
        );
    }

    function get_template_name( $instance ) {
        return 'default';
    }

    function get_style_name( $instance ) {
        return 'default';
    }
}
siteorigin_widget_register( 'custom-headline-slogan-widget', __FILE__, 'Headline_Slogan_Widget' );