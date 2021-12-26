<?php

define('TAXONOMIES', "['plant-type','location','year-planted','light-requirement']");

remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

//remove ability to comment on single images in jetpack gallery
function pb_tweakjp_rm_comments_att( $open, $post_id ) {
    $post = get_post( $post_id );
    if( $post->post_type == 'attachment' ) {
        return false;
    }
    return $open;
}
add_filter( 'comments_open', 'pb_tweakjp_rm_comments_att', 10 , 2 );
//* Modify breadcrumb arguments.
add_filter( 'genesis_breadcrumb_args', 'pb_breadcrumb_args' );
function pb_breadcrumb_args( $args ) {
	$args['home'] = 'Home';
	$args['sep'] = ' / ';
	$args['list_sep'] = ', '; // Genesis 1.5 and later
	$args['prefix'] = '<div class="breadcrumb">';
	$args['suffix'] = '</div>';
	$args['heirarchial_attachments'] = true; // Genesis 1.5 and later
	$args['heirarchial_categories'] = true; // Genesis 1.5 and later
	$args['display'] = true;
	$args['labels']['prefix'] = '';
	// $args['labels']['author'] = 'Archives for ';
	// $args['labels']['category'] = 'Archives for '; // Genesis 1.6 and later
	// $args['labels']['tag'] = 'Archives for ';
	// $args['labels']['date'] = 'Archives for ';
	// $args['labels']['search'] = 'Search for ';
	// $args['labels']['tax'] = 'Archives for ';
	// $args['labels']['post_type'] = 'Archives for ';
	// $args['labels']['404'] = 'Not found: '; // Genesis 1.5 and later
return $args;
}

// Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );

// Register the Third Nav menu
add_action( 'init', 'rcms_register_portal_menu' );
function rcms_register_portal_menu() {
	register_nav_menu( 'third-menu' ,__( 'Third Navigation Menu' ));
}

// remove_action( 'genesis_site_description', 'genesis_seo_site_description' );


// Customize the legal text
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'sp_custom_footer' );
function sp_custom_footer() {
	$output = '<p> &copy; Copyright ';
	$output .= date('Y');
	$output .= ' Lorie Ransom. All rights reserved. ';
	$output .= '<a href="'.get_page_link(1006 ).'">Privacy Policy</a> ';
	$output .= '<a href="'.get_page_link(1012 ).'">Terms of Service</a> ';
	echo $output;
}

// Enable shortcode use in widgets
add_filter('widget_text', 'do_shortcode');

// Change pagination button text 
add_filter( 'genesis_prev_link_text', 'rcms_review_prev_link_text' );
function rcms_review_prev_link_text() {
        $prevlink = 'Newer Posts';
        return $prevlink;
}
add_filter( 'genesis_next_link_text', 'rcms_review_next_link_text' );
function rcms_review_next_link_text() {
        $nextlink = 'Older Posts';
        return $nextlink;
}
// Change post meta text
add_filter( 'genesis_post_meta', 'rcms_post_meta_filter' );
function rcms_post_meta_filter($post_meta) {
if ( !is_page() ) {
	$post_meta = '[post_tags before="Tagged: "] [post_comments] [post_edit]';
	return $post_meta;
}}
// 
add_filter('get_the_archive_title', 'pb_add_tag_leader_text');
function pb_add_tag_leader_text($title){
	// echo 'filter called';
	$prefix = '';
	if ( is_category() ) {
		// $prefix = '<p>Viewing posts tagged:</p>';
		$title = single_tag_title( 'Category: ', false );
	}
	if ( is_tag() ) {
		// $prefix = '<p>Viewing posts tagged:</p>';
		$title = single_tag_title( 'Viewing items tagged: ', false );
	}	
	return $title;
}
// remove genesis favicon
remove_action('genesis_meta', 'genesis_load_favicon');

add_filter( 'genesis_pre_load_favicon', 'rcms_favicon_filter' );
function rcms_favicon_filter( $favicon_url ) {
	$base = get_stylesheet_directory_uri();
	return  esc_url($base) . 'images/favicon.ico';
}

// blog and archive - move featured image to top
add_action( 'genesis_before', 'pb_move_featured_image' );
function pb_move_featured_image(){
	if( is_front_page() || is_archive() ){
		remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8);
		remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
		
		add_action('genesis_entry_header', 'genesis_do_post_image', 1);
		add_action( 'genesis_entry_header', 'genesis_do_post_title', 13);

	}
}
// back to top
add_action( 'genesis_before_footer', 'pb_add_backtotop', 5 );
function pb_add_backtotop(){
	echo '<a class="back-to-top off" href="#"><i class="fa fa-angle-up" aria-hidden="true"></i>
</a>';
}

add_filter( 'genesis_post_meta', 'pb_post_meta_filter' );

function pb_post_meta_filter( $post_meta ) {
	if (is_archive() || is_home()){
	    $post_meta = '[post_categories before=""]';
	}
 	return $post_meta;
}


add_shortcode('wp_caption', 'pb_img_caption_shortcode');
add_shortcode('caption', 'pb_img_caption_shortcode');

//Copied from core. This function enables me to add a pipe character to captions that gets turned into a line break.
function pb_img_caption_shortcode( $attr, $content = null ) {
	// New-style shortcode with the caption inside the shortcode with the link and image tags.
	if ( ! isset( $attr['caption'] ) ) {
		if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
			$content = $matches[1];
			$attr['caption'] = trim( $matches[2] );
		}
	} elseif ( strpos( $attr['caption'], '<' ) !== false ) {
		$attr['caption'] = wp_kses( $attr['caption'], 'post' );
	}

	/**
	 * Filters the default caption shortcode output.
	 *
	 * If the filtered output isn't empty, it will be used instead of generating
	 * the default caption template.
	 *
	 * @since 2.6.0
	 *
	 * @see img_caption_shortcode()
	 *
	 * @param string $output  The caption output. Default empty.
	 * @param array  $attr    Attributes of the caption shortcode.
	 * @param string $content The image element, possibly wrapped in a hyperlink.
	 */
	$output = apply_filters( 'img_caption_shortcode', '', $attr, $content );
	if ( $output != '' )
		return $output;

	$atts = shortcode_atts( array(
		'id'	  => '',
		'align'	  => 'alignnone',
		'width'	  => '',
		'caption' => '',
		'class'   => '',
	), $attr, 'caption' );

	$atts['width'] = (int) $atts['width'];
	
	if ( $atts['width'] < 1 || empty( $atts['caption'] ) )
		return $content;

	if ( ! empty( $atts['id'] ) )
		$atts['id'] = 'id="' . esc_attr( sanitize_html_class( $atts['id'] ) ) . '" ';

	$class = trim( 'wp-caption ' . $atts['align'] . ' ' . $atts['class'] );
	
	$new_caption = str_replace("|", "<br /><span class='linebreak'></span>", $attr['caption']);
	
	$html5 = current_theme_supports( 'html5', 'caption' );
	// HTML5 captions never added the extra 10px to the image width
	$width = $html5 ? $atts['width'] : ( 10 + $atts['width'] );

	/**
	 * Filters the width of an image's caption.
	 *
	 * By default, the caption is 10 pixels greater than the width of the image,
	 * to prevent post content from running up against a floated image.
	 *
	 * @since 3.7.0
	 *
	 * @see img_caption_shortcode()
	 *
	 * @param int    $width    Width of the caption in pixels. To remove this inline style,
	 *                         return zero.
	 * @param array  $atts     Attributes of the caption shortcode.
	 * @param string $content  The image element, possibly wrapped in a hyperlink.
	 */
	$caption_width = apply_filters( 'img_caption_shortcode_width', $width, $atts, $content );

	$style = '';
	if ( $caption_width ) {
		$style = 'style="width: ' . (int) $caption_width . 'px" ';
	}

	if ( $html5 ) {
		$html = '<figure ' . $atts['id'] . $style . 'class="' . esc_attr( $class ) . '">'
		. do_shortcode( $content ) . '<figcaption class="wp-caption-text">' . $new_caption . '</figcaption></figure>';
	} else {
		$html = '<div ' . $atts['id'] . $style . 'class="' . esc_attr( $class ) . '">'
		. do_shortcode( $content ) . '<p class="wp-caption-text">' . $new_caption . '</p></div>';
	}

	return $html;
}

