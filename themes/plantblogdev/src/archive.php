<?php
/**
 * Plant Blog
 *
 * @author  Lorie Ransom
 * @license GPL-2.0+
 * @link    http://tinywhalecreative.com
 */
add_filter( 'genesis_post_info', 'pb_post_info_filter', 0 );
function pb_post_info_filter($post_info) {
		$post_info = '[post_date]';
		return $post_info;
}
remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );

add_action('genesis_before_loop','pb_add_archive_title');
function pb_add_archive_title(){
	$output = '<div class="archive-description taxonomy-archive-description taxonomy-description">';
	$output .= '<h1 class="archive-title">';
	// genesis_do_taxonomy_title_description();
	$output .= get_the_archive_title();
	$output .= '</h1></div>';
	echo $output;
}

remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

add_action('genesis_entry_header', 'genesis_post_meta');

genesis();