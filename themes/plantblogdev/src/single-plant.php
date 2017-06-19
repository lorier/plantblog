<?php

add_filter('genesis_pre_get_option_site_layout', '__genesis_return_sidebar_content');

remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );



remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'genesis_before_content', 'genesis_do_breadcrumbs' );

add_action('genesis_before', 'is_dead_plant');

function is_dead_plant(){
	$dead = pb_get_first_term_name($post, 'dead-alive');
	if ($dead != 'Dead'){
		add_action('genesis_entry_header', 'pb_add_gardeners_log',15);
		add_action('genesis_sidebar', 'pb_add_shade_rating');
		add_action('genesis_entry_header', 'pb_add_atg_comment',13);
		add_action('genesis_entry_header', 'pb_plant_stats',14);
	}else {
		add_action('genesis_entry_content', 'pb_list_reason' );
	}
}

function pb_list_reason(){
	$reason = get_field('reason', $post->id);
	$output = '<h3>Reason</h3>' . $reason;
	echo $output;
}
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

// add_action('genesis_entry_header', 'pb_add_gardeners_log',15);
function pb_add_gardeners_log(){
	global $post;
	$output = '';
	if ( have_rows('journal_notes')):
		$output = '<div class="notes accordion-1"><a class="accordion-title" href=""><h3>Gardener\'s Log</h3></a><div class="inside">';
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

function pb_add_shade_rating(){
	global $post;
	$output = '';
	
	//helper function in loops.php
	$shade_score = pb_get_first_term_name($post, 'shade-grade');

	//set default grade
	if ( $shade_score == ''){
		$shade_score = 'TBD';
	}

	if ( !empty($shade_score) && get_field('shade_summary') ):
		$output = '<div class="shade-assessment wrap">';
	$output .= '<h3>Shade Grade</h3>';
		$output .= '<div class="one-third first"><p class="grade">'.$shade_score.'</p></div>';
	    $output .= '<div class="two-thirds summary">'.get_field('shade_summary').'</div></div>';
    echo $output;
endif;
}


function pb_add_atg_comment(){
	global $post;
	$output = '';
	if ( get_field('atg_commentary')):
		$output = '<div class="atg-commentary">';
	    $output .= get_field('atg_commentary').'</div>';
    echo $output;
endif;
}

function pb_plant_stats(){
	$output ='<div class="stats">';
	$output .= '<div class="columns-3 "><div class="one-third first">';
	global $post;
	//get all term objects. $terms = array of terms
	$terms = get_object_taxonomies( $post, 'object' );
	
	$num_terms = count ($terms);
	$term_list = '';
	$term_count = 0;
	
	//get the terms for each taxonomy existing on the post and output them as a list
	foreach ($terms as $term) {
		if ($term_count == floor($num_terms/2) ){
			$term_list .= '</div><div class="one-third">';
		}
		$single_name = $term->labels->singular_name;
		$name = $term->name;
		$term_list .= '<ul class="taxonomy">';
		$label = '<li class="taxonomy-title '.$name.'">'.$single_name.'</li><li>';
		$term_list .= get_the_term_list( $post->ID, $term->name, $label, '</li><li>','</li>' );
		$term_list .= '</ul>';
		$term_count = $term_count + 1;
	}
	$output .= $term_list;
	$output .= '</div><div class="one-third"><div class="taxonomy">';
	if ( get_field('nursery_tag')):
			$output .= '<h5 class="taxonomy-title">Nursery Tag</h5><p>';
		    $output .= get_field('nursery_tag').'</p>';	        
	endif;
	$output .='</div></div></div></div><div class="clear">';
	
	echo $output;
}

function jptweak_remove_share() {
    remove_filter( 'the_content', 'sharing_display', 19 );
    remove_filter( 'the_excerpt', 'sharing_display', 19 );
    if ( class_exists( 'Jetpack_Likes' ) ) {
        remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
    }
}
add_action( 'loop_start', 'jptweak_remove_share' );

add_filter('the_content', 'pb_add_content_title');
function pb_add_content_title($content){
	if(!empty($content)){
		return '<div class="content-inner"><h3>Photo Gallery</h3>' . $content . '</div>';
	}else { return; }
}

function output_sharing_display(){
	if ( function_exists( 'sharing_display' ) ) {
	    sharing_display( '', true );
	}
}
add_action('genesis_after_loop', 'output_sharing_display');

genesis();