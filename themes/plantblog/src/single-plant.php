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
	$thumb_id = get_post_thumbnail_id();
	$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'medium', true);
	$thumb_url = $thumb_url_array[0];

	if ( has_post_thumbnail() ) {
	    $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
	    if ( ! empty( $large_image_url[0] ) ) {
	    	// global $post;
	        printf( '<a data-rel="lightbox" href="%1$s" alt="%2$s">%3$s</a>',
	            esc_url( $large_image_url[0] ),
	            esc_attr( $thumb_url ),
	            // get_the_post_thumbnail( $post_id, 'thumbnail', array( 'class' => 'alignleft' ) )
	            get_the_post_thumbnail()
	        );
	    }
	}
}

add_action('genesis_entry_header', 'pb_alt_names',12);
function pb_alt_names(){
	global $post;
	echo '<h4 class="latin">'.esc_textarea( pb_get_latin_name($post->ID) ).'</h4>';
}
add_action('genesis_entry_header', 'pb_add_latin_name',11);
function pb_add_latin_name(){
	global $post;
	$output = '';
	$loopcount = 0;
	if ( have_rows('alternate_common_names')):
		$output = '<h5 class="alt-names">AKA: ';
		while ( have_rows('alternate_common_names') ) : the_row();

	        // Your loop code
	        if ($loopcount > 0 ){
	        	$output .= ', ';
	        }
	        $output .= '<span>'.get_sub_field('common_name').'</span>';
	        $loopcount++;
	        
    	endwhile;
    	$output .= '</h5>';
    echo $output;
    else:

    // no rows found
endif;
	
	
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
		// lr_print_pre($term);
		$name = $term->name;
		$label = '<ul class="taxonomy"><li class="taxonomy-title '.$name.'">'.$single_name.'</li>';
		$term_list .= get_the_term_list( $post->ID, $term->name, $label, '</li><li>','</li></ul>' );
	}
	$output .= $term_list;

	echo $output;
}

genesis();