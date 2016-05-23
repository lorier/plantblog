<?php

// MCN-Specific functions
// Add typekit fonts


// Add widget for the sitemap and privacy policy
genesis_register_sidebar( array(
	'id'          => 'footer_legal_links',
	'name'        => __( 'Footer Legal Links', 'lean-kickstart' ),
	'description' => __( 'These are the links below the legal text in the footer', 'lean-kickstart' ),
) );
add_action('genesis_footer', 'pb_output_legal_links_widget', 10);
function pb_output_legal_links_widget(){
	genesis_widget_area( 'footer_legal_links', array( 'before' => '<div class="legal-links">', 'after' => '</div>') );
}


// Side navigation menus

// Services
genesis_register_sidebar( array(
	'id'            => 'plants-sidebar',
	'name'          => __( 'Plants Sidebar', 'lean-kickstart' ),
	'description'   => __( 'This is the filtering for plants', 'lean-kickstart' ),
) );

// add_action('genesis_sidebar', 'pb_output_plants_sidebar', 10);
// function pb_output_plants_sidebar(){
// 	if ( is_page_template('page-plants.php') ){
// 		remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
// 		genesis_widget_area( 'plants-sidebar', array( 'before' => '<div class="side-menu">', 'after' => '</div>') );
// 	}
// }