<?php

add_filter('genesis_pre_get_option_site_layout', '__genesis_return_sidebar_content');

remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );


add_action('genesis_sidebar', 'pb_single_plant_sidebar');

remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'genesis_before_content', 'genesis_do_breadcrumbs' );

function pb_the_content_filter($content) {
  // otherwise returns the database content
	// $content_header = '<h4>Plant Information</h4>';
  return $content_header . $content;
}

add_filter( 'the_content', 'pb_the_content_filter' );


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
	} else {
	    	echo '<img src="'.get_site_url().'/wp-content/uploads/2016/07/Untitled-1.png" class="alignleft wp-post-image"/>';
	    }
}

add_action('genesis_entry_header', 'pb_alt_names',11);
function pb_alt_names(){
	global $post;
	echo '<h4 class="latin">'.esc_textarea( pb_get_latin_name($post->ID) ).'</h4>';
}

// add_action('genesis_entry_header', 'pb_notes',12);
// function pb_alt_names(){
// 	global $post;
// 	echo '<h4 class="latin">'.esc_textarea( pb_notes($post->ID) ).'</h4>';
// }

add_action('genesis_entry_header', 'pb_add_common_name',10);
function pb_add_common_name(){
	global $post;
	$output = '';
	$loopcount = 0;
	if ( have_rows('alternate_common_names')):
		$output = '<p class="alt-names"><strong>AKA: </strong>';
		while ( have_rows('alternate_common_names') ) : the_row();

	        // Your loop code
	        if ($loopcount > 0 ){
	        	$output .= ', ';
	        }
	        $output .= '<span>'.get_sub_field('common_name').'</span>';
	        $loopcount++;
	        
    	endwhile;
    	$output .= '</p>';
    echo $output;
    else:

    // no rows found
endif;
	
	
}

add_action('genesis_entry_header', 'pb_add_gardeners_log',13);
function pb_add_gardeners_log(){
	global $post;
	$output = '';
	if ( have_rows('journal_notes')):
		$output = '<div class="notes accordion-1"><a class="accordion-title" href=""><h4>Gardener\'s Log</h4></a><div class="inside">';
		while ( have_rows('journal_notes') ) : the_row();
	        $output .= '<p><span class="date">'.get_sub_field('month_year').': </span>';
	        $output .= get_sub_field('note').'</p>';
	        $loopcount++;
	        
    	endwhile;
    	$output .= '</div></div>';
    echo $output;
    else:

    // no rows found
endif;
}

add_action('genesis_entry_header', 'pb_add_nursery_tag',14);
function pb_add_nursery_tag(){
	global $post;
	$output = '';
	if ( get_field('nursery_tag')):
		$output = '<div class="notes accordion-2"><a class="accordion-title" href=""><h4>Plant Details</h4></a><div class="inside">';
	    $output .= get_field('nursery_tag').'</div></div>';	        
    echo $output;
endif;
}
add_action('genesis_entry_header', 'pb_add_shade_rating',15);
function pb_add_shade_rating(){
	global $post;
	$output = '';

	if ( get_field('shade_summary') && get_field('shade_rating') ):
		$output = '<div class="shade-assessment wrap"><h5>Shady Assessment</h5>';
		$output .= '<div class="one-sixth first"><p class="grade">'.get_field('shade_rating').'</p></div>';
	    $output .= '<div class="five-sixths summary">'.get_field('shade_summary').'</div></div>';	        
    echo $output;
endif;
}

// add_action('genesis_entry_header', 'pb_add_shade_summary',13);
// function pb_add_shade_summary(){
// 	global $post;
// 	$output = '';
// 	if ( get_field('shade_summary')):
// 		$output = '<div class="shade-assessment"><h5>Shady Assessment</h5>';
// 	    $output .= get_field('shade_summary').'</div>';	        
//     echo $output;
// endif;
// }

add_action('genesis_entry_header', 'pb_add_atg_comment',12);
function pb_add_atg_comment(){
	global $post;
	$output = '';
	if ( get_field('atg_commentary')):
		$output = '<div class="atg-commentary">';
	    $output .= get_field('atg_commentary').'</div>';	        
    echo $output;
endif;
}


add_action('genesis_entry_header', 'pb_add_other_photo_headling',16);
function pb_add_other_photo_headling(){
	echo '<h5>More Photos</h5>';
}

function pb_single_plant_sidebar(){
	$output ='<h3>Tree Garden Stats</h3>';
	
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