<?php
/**
 * Plant Blog
 *
 * @author  Lorie Ransom
 * @license GPL-2.0+
 * @link    http://tinywhalecreative.com
 */
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

add_filter( 'genesis_post_info', 'pb_post_info_filter', 0 );
function pb_post_info_filter($post_info) {
		$post_info = '[post_date]';
		return $post_info;
}

genesis();