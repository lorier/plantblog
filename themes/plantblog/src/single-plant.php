<?php

add_filter('genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar');
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

add_action('genesis_sidebar', 'pb_single_plant_sidebar');



function pb_single_plant_sidebar(){
	$output ='<h3>Plant Stats</h3>';
	
	global $post;
	//get all term objects. $terms = array of terms
	$terms = get_object_taxonomies( $post, 'object' );
	// lr_print_pre($terms);
	$term_list = '';

	//get all the taxonomy names. Feed this list into get_the_terms_list
	foreach ($terms as $term) {
		global $term_list;
		$single_name = $term->labels->name;
		$label = '<ul class="'.$single_name.'"><li>'.$single_name.'</li>';
		$term_list .= get_the_term_list( $post->ID, $term->name, $label, '</li><li>','</li></ul>' );
	}
	$output .= $term_list;

	echo $output;
}

genesis();