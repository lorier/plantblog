<?php
/**
 * Plant Blog
 *
 * @author  Lorie Ransom
 * @license GPL-2.0+
 * @link    http://tinywhalecreative.com
 */
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');
add_action( 'genesis_after_header', 'kickstart_page_before' );

// Add before content section
function kickstart_page_before() {
	// If a Featured Image is set for this page, create the background div
	if ( has_post_thumbnail() ) {
		echo '<div class="before-content"></div>';
	}
}

genesis();
