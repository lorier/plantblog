<?php


// Register the Third Nav menu
add_action( 'init', 'rcms_register_portal_menu' );
function rcms_register_portal_menu() {
	register_nav_menu( 'third-menu' ,__( 'Third Navigation Menu' ));
}

// Add the Third Nav to the page
add_action( 'genesis_before', 'add_third_nav_genesis' ); 
function add_third_nav_genesis() {
	$output = wp_nav_menu( array( 
		'items_wrap'     => '<ul><span class="portal-title">Portal Login:</span>%3$s</ul>',
		'theme_location' => 'third-menu', 
		'container_class' => 'portal-logins desktop-hidden',
		'echo' => false
		) );
	echo $output;
}

// End MCN Init Functions
////////////////////////////////////


//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_header', 'rcms_secondary_nav', 1 );

function rcms_secondary_nav(){
	echo '<div id="nav-secondary-wrap" class="mobile-hidden "><div class="wrap">';
	genesis_do_subnav();
	echo '</div></div>';
}

add_filter('genesis_do_subnav', 'rcms_subnav_portal_logins');
function rcms_subnav_portal_logins($nav){
	$portals = wp_nav_menu( array( 
		'items_wrap'     => '<ul><span class="portal-title">Portal Login:</span>%3$s</ul>',
		'theme_location' => 'third-menu', 
		'container_class' => 'portal-logins genesis-nav-menu'		
		) );
	$output = $nav;
	$output .= $portals;
	return $output;
}

// Customize the legal text
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'sp_custom_footer' );
function sp_custom_footer() {
	$output = '<p> &copy; Copyright ';
	$output .= date('Y');
	$output .= ' Medical Consultants Network, LLC. All Rights Reserved.';
	echo $output;

}

// Remove Page Titles
add_action('genesis_before', 'rcms_remove_post_title');
function rcms_remove_post_title(){

	add_action( 'genesis_before_loop', 'genesis_do_blog_template_heading' );
	
	if ( is_home() ){
	  	remove_action( 'genesis_before_loop', 'genesis_do_posts_page_heading' );
	} 
	else if ( is_page() ){
		remove_action( 'genesis_entry_header', 'genesis_do_post_title');
	}
}
// Enable shortcode use in widgets
add_filter('widget_text', 'do_shortcode');

// Add banner containers and titles
add_action('genesis_after_header', 'rcms_banner_strip' );
function rcms_banner_strip(){
	if ( !is_front_page() ){
		global $post;
		$output = '<div class="banner-strip"><h1 class="banner-title">';
		if ( is_page() ){
			$output .= get_the_title( $post ).'</h1></div>';
		}
		else if ( is_404() ){
			$output .= 'Page Not Found</h1></div>';
		}
		else if ( is_search() ) {
			$output .= 'Search Results</h1></div>';
		}
		else if ( is_home() || is_single() || is_archive() ){
			global $post;
			$title = apply_filters('the_title',get_page( get_option('page_for_posts') )->post_title );
			$output .=  $title . '<br><span class="blog-subtitle">News, Insights & Opinions</span></h1></div>';
		} 
		else { //for all other possible options so the page layout doesn't break
			$output .= get_the_title( $post ).'</h1></div>';
		}
		echo $output;
	}
}
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

// add_action('genesis_after_content', 'rcms_add_sidebar_to_single');
function rcms_add_sidebar_to_single(){
	global $post;
	if (is_singular($post)){
		genesis_do_sidebar();
	}
}
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

add_filter( 'genesis_post_info', 'rcms_post_info_filter' );
function rcms_post_info_filter($post_info) {
	if ( !is_page() ) {
		$post_info = '[post_date] by [post_author_posts_link]';
		return $post_info;
	}
}
