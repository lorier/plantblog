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

remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

add_action( 'genesis_after_header', 'lr_do_entry_header');
function lr_do_entry_header() {
	genesis_entry_header_markup_open();
	echo '<div class="wrap">';
	genesis_do_post_title();
	echo '</div>';
	genesis_entry_header_markup_close();

}

// Add before content section
function kickstart_page_before() {
	// If a Featured Image is set for this page, create the background div
	if ( has_post_thumbnail() ) {
		echo '<div class="before-content"></div>';
	}
}

genesis();
