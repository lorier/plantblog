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
// remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

// Remove custom headline and / or description from category / tag / taxonomy archive pages
// remove_action( 'genesis_before_loop', 'genesis_do_cpt_archive_title_description');

// // Add custom headline and / or description on category / tag / taxonomy archive pages
// add_action( 'genesis_after_header', 'lr_title_desc_opening' );
// add_action( 'genesis_after_header', 'lr_page_title_description' );
// add_action( 'genesis_after_header', 'lr_title_desc_closing' );

// function lr_title_desc_opening() {
//     echo '<div class="page-title-description"><div class="wrap">';
//     lr_page_title_description();
// }
// function lr_title_desc_closing() {
//     echo '</div></div>';
// }
// // //https://www.engagewp.com/retrieve-custom-post-type-archive-settings-genesis/
// function lr_page_title_description() {
//     // $description = get_field('page_description');
//     $description = !empty($description) ? $description : '';

//     echo '<h1 class="page-title">'.get_the_title().'</h1>';
//     // echo '<div class="page-description"><p>'.$description.'</p></div>';
// }

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

            $image = get_sub_field('location_image');
            $image = (!empty($image)) ? $image : get_site_url().'/wp-content/uploads/2016/07/Untitled-1.png';

            $output = '<div class="locations wrap">';
            $output .='<div class="one-third first"><a data-rel="lightbox" href="'.$image.'"><img class="location-image" src="'.$image.'"/></a></div>';
            $output .='<div class="two-thirds"><h2>'.$title.'</h2>'.$description.'</div>';
            $output .= '</div>';
            echo $output;
        endwhile;
    }
}

// remove_action( 'genesis_loop', 'genesis_do_loop' );




genesis();
