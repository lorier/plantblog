<?php
/**
 * Plant Blog
 *
 * @author  Lorie Ransom
 * @license GPL-2.0+
 * @link    http://tinywhalecreative.com
 * 
 * Template Name: Before and After
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
add_action('genesis_after_entry', 'pb_add_before_after');
function pb_add_before_after(){
	if( have_rows('before_and_after') ){
		while( have_rows('before_and_after')) : the_row();
			// basic WP gallery
			$images = get_sub_field('gallery');
			$comment = get_sub_field('gallery_caption');

			if( $images ){
			    $output = '<div class="variable-width">'; //TODO add object buffering here for count
				    $image_count=0;
			       foreach( $images as $image ){
				       	++$image_count;
						$post = get_post($image);
			            $output .= '<div class="slick-slide"><div class="image">';
			            $output .= '<a data-rel="lightbox" href="';
			            $output .= $image["url"];
			            $output .= '"><img src="';
			            $output .= $image["url"];
			            $output .= '" alt="';
			            $output .= $image["alt"].'"/></a></div>';
						// $output .= '" /><p>';
						// $output .= $image['caption'];
						// $output .= '</p>
						$output .= '</div>';
			       }
			     $output .= '</div>';
			     $output .= '<span class="count_'. $image_count .'"></span>';
			    }
			 echo $output;
		endwhile;
	}
}
genesis();

//alternate for each - why is this showing only 1 slide?
// if( $images ){
// 			    // $output = '<div class="variable-width">'; 
// 				   $image_count=0;
// 			       foreach( $images as $image ){
// 				       	++$image_count;
// 						$post = get_post($image);
// 			            $output = '<div class="slick-slide"><div class="image">';
// 			            $output .= '<a data-rel="lightbox" href="';
// 			            $output .= $image["url"];
// 			            $output .= '"><img src="';
// 			            $output .= $image["url"];
// 			            $output .= '" alt="';
// 			            $output .= $image["alt"].'"/></a></div>';
// 						// $output .= '" /><p>';
// 						// $output .= $image['caption'];
// 						// $output .= '</p>
// 						$output .= '</div>';
// 			       }
// 			     $output .= '</div>';
// 			     $count = '<div class="variable-width count_'. $image_count .'">';
// 			     $concat = $count .$output;
// 			    }
// 			 echo $concat;