<?php

define('TAXONOMIES', "['plant-type','location','year-planted','light-requirement']");

// Register the Third Nav menu
add_action( 'init', 'rcms_register_portal_menu' );
function rcms_register_portal_menu() {
	register_nav_menu( 'third-menu' ,__( 'Third Navigation Menu' ));
}

add_action( 'genesis_before', 'pb_move_featured_image' );
function pb_move_featured_image(){
	if( is_front_page()){
		remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8);
		add_action('genesis_entry_header', 'genesis_do_post_image', 8);
	}
}

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );

// Customize the legal text
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'sp_custom_footer' );
function sp_custom_footer() {
	$output = '<p> &copy; Copyright ';
	$output .= date('Y');
	$output .= ' Lorie Ransom. All rights reserved.';
	echo $output;
}

add_action('genesis_before', 'add_blank_div');

function add_blank_div(){
	echo '<div class="decorative-bar"></div>';
}

// Enable shortcode use in widgets
add_filter('widget_text', 'do_shortcode');

// Add "now viewing" to tag pages 
add_action('genesis_before_loop', 'rcms_add_tag_title');

function rcms_add_tag_title(){
	if (is_tag()){
		echo '<p class="tag-title">Viewing items tagged:</p>';
	}
}
// Change pagination button text 
add_filter( 'genesis_prev_link_text', 'rcms_review_prev_link_text' );
function rcms_review_prev_link_text() {
        $prevlink = 'Newer Posts';
        return $prevlink;
}
add_filter( 'genesis_next_link_text', 'rcms_review_next_link_text' );
function rcms_review_next_link_text() {
        $nextlink = 'Older Posts';
        return $nextlink;
}
// Change post meta text
add_filter( 'genesis_post_meta', 'rcms_post_meta_filter' );
function rcms_post_meta_filter($post_meta) {
if ( !is_page() ) {
	$post_meta = '[post_tags before="Tagged: "] [post_comments] [post_edit]';
	return $post_meta;
}}

add_filter('get_the_archive_title', 'rcms_add_tag_leader_text');

function rcms_add_tag_leader_text($title){
	echo 'filter called';
	$prefix = '';
	if ( is_tag() ) {
		// $prefix = '<p>Viewing posts tagged:</p>';
		$title = single_tag_title( '<p>Viewing posts tagged:</p>', false );
	}
	return $title;
}
// remove genesis favicon
remove_action('genesis_meta', 'genesis_load_favicon');

add_filter( 'genesis_pre_load_favicon', 'rcms_favicon_filter' );
function rcms_favicon_filter( $favicon_url ) {
	$base = get_stylesheet_directory_uri();
	return  esc_url($base) . 'images/favicon.ico';
}
// remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'genesis_post_info', 9 );
// add_action( 'genesis_entry_header', 'genesis_post_info', 0);

// add_action( 'loop_start', 'remove_titles_all_single_posts' );
function remove_titles_all_single_posts() {
    if ( is_front_page() ) {
        // remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
		remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
		remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
    }
}

add_filter( 'genesis_post_info', 'rcms_post_info_filter', 0 );
function rcms_post_info_filter($post_info) {
	if ( !is_page() ) {
		$post_info = '[post_date]';
		return $post_info;
	}
}

//////////////////////////////////////
// Template selection
//////////////////////////////////////

// @param string, default template path
// @return string, modified template path

// add_filter( 'template_include', 'lr_template_redirect' );
function lr_template_redirect( $template ) {
	// echo get_query_var( 'post_type');
 //    if ( is_post_type_archive('plant')){
 //        echo '<h1>this is the plant archive</h1>';
 //        // $template = locate_template( array('page-plants.php'), false ); 
 //        return $template;
 //    }else 
    if ( is_tax(['plant-type','location','year-planted','light-requirement'])) {
    	// echo 'is tax';
        $template = get_query_template( 'page-plants' );    
    }
    return $template;
}