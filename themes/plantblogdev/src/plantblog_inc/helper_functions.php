<?php

// Helper functions called in other files

// Get plant progress galler
function pb_get_progress_gallery( $post, $count ){
    // echo 'gallery func called';
    global $post;
	$images = get_field('plant_progress');
    // lr_print_pre($images);
	$size = 'full'; // (thumbnail, medium, large, full or custom size)
	if( $images ) {
        // lr_print_pre($post);
        $output = '<div class="slider-container">';
        $output .= '<h2><a href="'. get_the_permalink() .'">' . get_the_title() . '</a></h2>';
		$output .= '<div class="swiper mySwiper">';
		$output .= '<div class="swiper-wrapper">';
		foreach( $images as $image_id ){
            $attachment = get_post($image_id);
            $caption = $attachment->post_excerpt;
			$output .= '<div class="swiper-slide">';
				$output .= '<div class="slide-caption">'.$caption .'</div>'; 
				$output .= '<div class="slide-image">'. wp_get_attachment_image( $image_id, $size ) .'</div>'; 
			$output .= '</div>';
		}
        $output .= '</div>';
        $output .= '<div class="swiper-pagination"></div>';
        $output .= '<div class="swiper-button-prev"></div>';
        $output .= '<div class="swiper-button-next"></div>';
        $output .= '</div>';
        $output .= '</div>';
	}
    return $output;
}

// Plant CPT

function pb_get_latin_name($id=null){
    $latin_name = get_field('latin_name', $id, true);
    if($latin_name){
        return $latin_name;
    }
    return '--';

}
function pb_get_pronunciation($id=null){
    $pronunciation = get_field('pronunciation', $id, true);
    if($pronunciation){
        return $pronunciation;
    }
    return '';

}

//use placeholder thumbnail when featured image is missing
function lr_get_post_thumb($post){
    // $thumb_id = get_post_thumbnail_id($post);
    // $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'medium', true);
    // $thumb_url = $thumb_url_array[0];
    
    if ( has_post_thumbnail($post) ) {
        if ( is_singular('plant') || is_archive('plant' ) || is_page('graveyard')){
            return get_the_post_thumbnail($post,'post_featured_large');
        }
    } else {
            return '<img src="'.get_stylesheet_directory_uri().'/images/pb-thumb-placeholder.png" class="size-post-thumbnail"/>';
    }
}

function pb_get_thumbnail($id=null){
    $image =  get_the_post_thumbnail( $id, 'thumbnail' );
    if (!$image){
        $image = '<img src="http://placehold.it/350x150">';
    }
    return $image;
}

//Get and return terms in a linked, unordered list
function pb_get_terms_list($id=null,$sort_by_taxonomy ){
    
    //get all the term objects
    $types = wp_get_post_terms($id, array('plant-type'), array("fields" => "all"));
    $light_reqs = wp_get_post_terms($id, array('light-requirement'), array("fields" => "all"));
    
    $plant_type_terms = array();
    $light_reqs_terms = array();

    //if we are viewing the list categorized by location, add the plant type to the listing
    
        foreach($types as $term_object) {
            $plant_type_terms[] = '<li><a href="'.get_term_link( $term_object->term_id, $term_object->taxonomy ).'">'.$term_object->name.'</a></li>';
        }
    
    foreach($light_reqs as $term_object) {
        $light_reqs_terms[] = '<li><a href="'.get_term_link( $term_object->term_id, $term_object->taxonomy ).'">'.$term_object->name.'</a></li>';
    }
    //build an unordered list from the contents of the term item array
    $term_list = '<ul class="terms-list">';
    if ($sort_by_taxonomy == 'location'){
        $term_list .= '<li class="term_title">Type: </li>';
        $term_list .= implode('<li class="spacer">|</li> ', $plant_type_terms);
        $term_list .= '<br>';
    }
    $term_list .= '<li class="term_title">Light: </li>';
    $term_list .= implode('<li class="spacer">|</li> ', $light_reqs_terms);
    $term_list .= '</ul>';
    return $term_list;
}