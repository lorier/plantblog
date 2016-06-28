<?php
/**
 * Plant Blog
 *
 * @author  Lorie Ransom
 * @license GPL-2.0+
 * @link    http://tinywhalecreative.com
 */
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar');
add_action('genesis_loop', 'show_query_vars');
echo 'this is archive';
function show_query_vars(){
	global $wp_query;
	
	lr_print_pre($wp_query->query_vars);
}
genesis();