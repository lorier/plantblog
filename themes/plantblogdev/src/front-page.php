<?php
/**
 * Plant Blog
 *
 * @author  Lorie Ransom
 * @license GPL-2.0+
 * @link    http://tinywhalecreative.com
 */
// add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');


add_filter( 'genesis_post_info', 'pb_post_info_filter', 0 );
function pb_post_info_filter($post_info) {
		$post_info = '[post_date]';
		return $post_info;
}
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

add_action('genesis_entry_header', 'genesis_post_meta');



genesis();