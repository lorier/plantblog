<?php
/**
 * Plant Blog
 *
 * @author  Lorie Ransom
 * @license GPL-2.0+
 * @link    http://tinywhalecreative.com
 * 
 * Template Name: Plant Progress
 */

remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');
// add_action( 'genesis_after_header', 'pb_page_before' );

remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

add_action( 'genesis_after_header', 'lr_do_entry_header_gallery');
function lr_do_entry_header_gallery() {
	genesis_entry_header_markup_open();
	echo '<div class="wrap">';
	genesis_do_post_title();
	echo '</div>';
	genesis_entry_header_markup_close();
}

// Add before content section
// function pb_page_before() {
// 	// If a Featured Image is set for this page, create the background div
// 	if ( has_post_thumbnail() ) {
// 		echo '<div class="before-content"></div>';
// 	}
// }
add_action('genesis_after_content', 'pb_output_progress_slider');
function pb_output_progress_slider(){
	// function loop
	//   get all plants that have gallery metafield content
	//   output plant 
	//   - title
	//   - botanical name
	//   - link to plant
	//   - images
	//   - image dates (caption?)
	

	// Get all Plants, not sorted. For showing plant progress
	$args = array(
		'post_type' => 'plant',
		'post_status' => 'publish',
		'posts_per_page' => '-1',
	);
	// The Query
	$the_query = new WP_Query( $args );

	// The Loop
	if ( $the_query->have_posts() ) {
		$output = '<div class="slider-grid">';
		$count = 0;
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			global $post;
			$output .= pb_get_progress_gallery( $post, $count );
			$count++;
		}
		$output .= '</div>';
	} else {
		// no posts found
	}
	echo $output;
	/* Restore original Post Data */
	wp_reset_postdata();

	// if( have_rows('before_and_after_gallery') ){
	// 	while( have_rows('before_and_after_gallery')) : the_row();
	// 		// basic WP gallery
	// 		$title = get_sub_field('title_and_description');
	// 		$title = (!empty($title)) ? $title : '';

	// 		$hero = get_sub_field('hero');

	// 		$images = get_sub_field('gallery');

	// 		if( $images ){
	// 			echo '<div class="wrap entry-container">
	// 					<div class="one-fourth first">'.$title.'</div>
	// 					<div class="three-fourths">';
	// 				echo 		'<div class="hero one-half first">
	// 									<img src="'.$hero.'">
	// 							</div>';

	// 				echo 		'<div class="one-half">'.$images.'</div>
							
	// 					</div>
	// 				</div>';
	// 		   //  // $output = '<div class="variable-width">'; //TODO add object buffering here for count
	// 			  //  $image_count=0;
	// 		   //     $content = '';
	// 		   //     foreach( $images as $image ){
	// 			  //      	++$image_count;

	// 		   //          $content .= '<div class="slide-container"><div class="image">';
	// 		   //          $content .= '<a data-rel="lightbox" href="';
	// 		   //          $content .= $image["url"];
	// 		   //          $content .= '"><img class="valign" src="';
	// 		   //          $content .= $image["url"];
	// 		   //          $content .= '" alt="';
	// 		   //          $content .= $image["alt"].'"/>';
	// 					// $content .= '<div class="image-caption">';
	// 					// $content .= $image['caption'];
	// 					// $content .= '</div>';
	// 					// $content .= '</a></div>';
	// 					// $content .= '</div>';
	// 		   //     }
	// 		   //   $content .= '</div>';

	// 		   //   //class that helps us apply a carousel to more than one slide
	// 		   //   $num_images = $image_count > 1 ? 'multiple-slides' : 'one-slide';

	// 		   //   $output = '<div class="gallery-container '.$num_images.'">';
	// 		   //   $output .= '<div class="gallery-caption one-third first">';
	// 		   //   $output .= '<h2 class="">'.$title.'</h2><h6 class="">'. $comment .'</h6><div class="clear-line"></div></div>';
	// 		   //   $output .= '<div class="two-thirds"><div class="variable-width '. $num_images .'">' . $content;
	// 		   //   $output .= '</div><div class="clear-both"></div>';
	// 		   //   $output .= '</div>';
	// 		   //   $num_images = 0;
	// 		    }
	// 		// echo $output;
	// 		// $output = '';

	// 	endwhile;
	// }
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