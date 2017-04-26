<?php
/**
 * Plant Blog
 *
 * @author  Lorie Ransom
 * @license GPL-2.0+
 * @link    http://tinywhalecreative.com
 */

// Template Name: Garden Locations


remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

add_action( 'genesis_after_header', 'lr_do_entry_header');
function lr_do_entry_header() {
    genesis_entry_header_markup_open();
    echo '<div class="wrap">';
    genesis_do_post_title();
    echo '</div>';
    genesis_entry_header_markup_close();
}

add_action('genesis_after_entry', 'pb_add_locations');
function pb_add_locations(){
    if( have_rows('garden_locations') ){
        while( have_rows('garden_locations')) : the_row();

            $title = get_sub_field('location_title');
            $title = (!empty($title)) ? $title : '';

            $description = get_sub_field('location_description');
            $description = (!empty($description)) ? $description : '';

            $image_id = get_sub_field('location_image');

            //get src object that contain the urls
            $image_large = wp_get_attachment_image_src( $image_id, 'large' );
            $image_full = wp_get_attachment_image_src( $image_id, '' );
       
            //get the url 
            $large_img_url = $image_large[0];
            $full_img_url = $image_full[0];

            
            $large_image_url = (!empty($large_img_url)) ? $large_image_url : get_site_url().'/wp-content/uploads/2016/07/Untitled-1.png';
            $full_image_url = (!empty($full_img_url)) ? $full_image_url : get_site_url().'/wp-content/uploads/2016/07/Untitled-1.png';



            $output = '<div class="locations wrap">';
            $output .='<div class="one-third first"><a data-rel="lightbox" href="'.$full_img_url.'"><img class="location-image" src="'.$large_img_url.'"/></a></div>';
            $output .='<div class="two-thirds"><h2>'.$title.'</h2>'.$description.'</div>';
            $output .= '</div>';
            echo $output;
        endwhile;
    }
}

// remove_action( 'genesis_loop', 'genesis_do_loop' );




genesis();
