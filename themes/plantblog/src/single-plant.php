<?php

add_filter('genesis_pre_get_option_site_layout', '__genesis_return_sidebar_content');

remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

add_action('genesis_sidebar', 'pb_single_plant_sidebar');

remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'genesis_before_content', 'genesis_do_breadcrumbs' );

//add featured image to sidebar
add_action( 'genesis_before_sidebar_widget_area', 'pb_featured_image_sidebar', 8 );
function pb_featured_image_sidebar() {
	global $post;
	the_post_thumbnail('post-image');
	$caption = get_post( get_post_thumbnail_id( $post->ID ) )->post_excerpt;
	if ($caption){
		echo '<p class="featured-image-caption">'.$caption.'</p>';
	}
}

add_action('genesis_entry_header', 'pb_add_latin_name');
function pb_add_latin_name(){
	global $post;
	echo '<h4>'.esc_textarea( pb_get_latin_name($post->ID) ).'</h4>';
}

function pb_single_plant_sidebar(){
	$output ='<h3>Plant Stats</h3>';
	
	global $post;
	//get all term objects. $terms = array of terms
	$terms = get_object_taxonomies( $post, 'object' );
	// lr_print_pre($terms);
	$term_list = '';

	//get the terms for each taxonomy existing on the post and output them as a list
	foreach ($terms as $term) {
		$single_name = $term->labels->singular_name;
		$label = '<ul class="'.$single_name.'"><li>'.$single_name.'</li>';
		$term_list .= get_the_term_list( $post->ID, $term->name, $label, '</li><li>','</li></ul>' );
	}
	$output .= $term_list;

	echo $output;
}

genesis();