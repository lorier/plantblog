<?php
/**
 * Kickstart Pro
 *
 * @author  Lean Themes
 * @license GPL-2.0+
 * @link    http://demo.leanthemes.co/kickstart/
 */

// Template Name: Plants

add_filter('genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar');
/** Replace the standard loop with our custom loop */
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'pb_do_custom_loop' );

add_action('get_header', 'pb_output_plants_sidebar');
function pb_output_plants_sidebar(){
		remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
		add_action( 'genesis_sidebar', 'pb_do_sidebar' );
}

function pb_do_sidebar() {
	dynamic_sidebar( 'plants-sidebar' );
}

function pb_do_custom_loop() {
 
    global $paged; // current paginated page
    global $query_args; // grab the current wp_query() args
    $args = array(
        'post_type' => 'plant', // exclude posts from this category
        'paged'            => $paged, // respect pagination
    );
 
    genesis_custom_loop( wp_parse_args($query_args, $args) );
 
}
genesis();