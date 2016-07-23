<?php
/**
 * Plant Blog
 *
 * @author  Lorie Ransom
 * @license GPL-2.0+
 * @link    http://tinywhalecreative.com
 */
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar');

add_filter( 'genesis_post_info', 'pb_single_post_info_filter', 0 );

function pb_single_post_info_filter($post_info) {
		$post_info = '<span>Posted on [post_date] [post_comments]';
		return $post_info;
}

add_filter('genesis_post_meta', 'pb_single_post_meta_filter');
function pb_single_post_meta_filter($post_meta){
	$meta = '[post_categories before="Filed Under: "] [post_tags before="Tagged: "]';
	return $meta;
}

//add featured image to posts
add_action( 'genesis_before_entry_content', 'pb_single_featured_post_image', 8 );
function pb_single_featured_post_image() {
	$thumb_id = get_post_thumbnail_id();
	$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'large', true);
	$thumb_url = $thumb_url_array[0];

	if ( has_post_thumbnail() ) {
	    $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
	    if ( ! empty( $large_image_url[0] ) ) {
	    	// global $post;
	        printf( '<a data-rel="lightbox" href="%1$s" alt="%2$s">%3$s</a>',
	            esc_url( $large_image_url[0] ),
	            esc_attr( $thumb_url ),
	            // get_the_post_thumbnail( $post_id, 'thumbnail', array( 'class' => 'alignleft' ) )
	            get_the_post_thumbnail()
	        );
	    }
	}
}

genesis();