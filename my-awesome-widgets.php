<?php
/*
Plugin Name: My Custom SiteOrigin Widgets
Description: A collection of custom widgets using SiteOrigin Widgets Bundle
Version: 1.0
Author: Willie Springer
*/

function add_my_awesome_widgets_collection( $folders ) {
    $folders[] = plugin_dir_path( __FILE__ ) . 'extra-widgets/';
    return $folders;
}
add_filter( 'siteorigin_widgets_widget_folders', 'add_my_awesome_widgets_collection' );