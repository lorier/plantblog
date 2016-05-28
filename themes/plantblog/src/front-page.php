<?php
/**
 * Plant Blog
 *
 * @author  Lorie Ransom
 * @license GPL-2.0+
 * @link    http://tinywhalecreative.com
 */


// function pb_archive_post_class( $classes ) {
// 	global $wp_query;
// 	if( ! $wp_query->is_main_query() )
// 		return $classes;
		
// 	$classes[] = 'one-third';
// 	if( 0 == $wp_query->current_post || 0 == $wp_query->current_post % 3 )
// 		$classes[] = 'first';
// 	return $classes;
// }
// add_filter( 'post_class', 'pb_archive_post_class' );

// //* Add support for Genesis Grid Loop
// remove_action( 'genesis_loop', 'genesis_do_loop' );
// add_action( 'genesis_loop', 'pb_grid_loop_helper' );
// function pb_grid_loop_helper() {
//   if ( function_exists( 'genesis_grid_loop' ) ) {
// 		echo 'i am front page';
// 		genesis_grid_loop( array(
// 			'features' => 2,
// 			'feature_image_size' => 1,
// 			'feature_image_class' => 'alignleft post-image',
// 			'feature_content_limit' => 0,
// 			'grid_image_size' => 'grid-thumbnail',
// 			'grid_image_class' => 'alignleft post-image',
// 			'grid_content_limit' => 0,
// 			'more' => __( '[Continue reading...]', 'genesis' ),
// 		) );
// 	} else {
// 		genesis_standard_loop();
// 	}
// }

// //* Remove the post meta function for front page only
// remove_action( 'genesis_entry_footer', 'genesis_post_meta', 10 );
genesis();