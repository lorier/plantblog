<?php
/**
 * Plant Blog
 *
 * @author  Lorie Ransom
 * @license GPL-2.0+
 * @link    http://tinywhalecreative.com
 */

// Template Name: Plants

// Remove custom headline and / or description from category / tag / taxonomy archive pages
remove_action( 'genesis_before_loop', 'genesis_do_cpt_archive_title_description');

// Remove breadcrumb. It is selected in the admin to appear on all archive pages.
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );



// Add custom headline and / or description on category / tag / taxonomy archive pages
add_action( 'genesis_after_header', 'lr_taxonomy_title_description_opening_wrap' );
// add_action( 'genesis_after_header', 'genesis_do_cpt_archive_title_description' );
add_action( 'genesis_after_header', 'lr_taxonomy_title_description_closing_wrap' );

function lr_taxonomy_title_description_opening_wrap() {
    echo '<div class="custom-archive-description"><div class="wrap">';
    lr_cpt_archive_title_description();
}
function lr_taxonomy_title_description_closing_wrap() {
    echo '</div></div>';
}
//https://www.engagewp.com/retrieve-custom-post-type-archive-settings-genesis/
function lr_cpt_archive_title_description() {
    /**
     *  Genesis stores the archive settings in an option (array) named genesis-cpt-archive-settings-{post_type}
     *  This example uses a custom post type called 'service'
     */
    $archive_settings = get_option( 'genesis-cpt-archive-settings-plant' );
    echo '<div class="archive-title-container"><h1 class="archive-title">'.$archive_settings['headline'].'</h1></div>';
    echo '<div class="archive-description"><p>'.$archive_settings['intro_text'].'</p>'.do_shortcode('[wpdreams_ajaxsearchlite]').'</div>';
}

remove_action( 'genesis_loop', 'genesis_do_loop' );

add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

add_action('get_header', 'pb_output_plants_sidebar');
function pb_output_plants_sidebar(){
        remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
        // add_action( 'genesis_sidebar', 'pb_do_sidebar' );
}
// function pb_do_sidebar() {
//     dynamic_sidebar( 'plants-sidebar' );
// }

add_filter( 'genesis_attr_content', 'lr_add_custom_class' );
function lr_add_custom_class( $attributes ) {
          $attributes['class'] = $attributes['class'] . ' flexbox-columns';
    
    return $attributes;
}

//http://www.rlmseo.com/blog/passing-get-query-string-parameters-in-wordpress-url/
//https://codepen.io/the_ruther4d/post/custom-query-string-vars-in-wordpress
// add query args to the query for each link. 
add_action('genesis_after_header', 'js_pb_sort_menu', 10);
function js_pb_sort_menu(){
   

    //load the initial taxonomy. This is currently always 'plant-type' on first page load
    // global $sort_by_taxonomy; //used and set by the custom loop
    // $sorter_class = 'plant-type';
    // if ($sort_by_taxonomy) $sorter_class = $sort_by_taxonomy;

    // $plant_type = array('sort-by' => 'plant-type');
    // $location = array('sort-by' => 'location');
    // $light = array('sort-by' => 'light-requirement');
    $output = '<ul id="sorter" class="'.esc_attr($sorter_class).'">';
    $output .= '
        <li class="plant-type-link active-link"><a id="plant-type" href="">Plant Type</a></li>
        <li class="location-link"><a id="location" href="">Location</a></li>
        <li class="light-link"><a id="light-requirement" href="">Light Needs</a></li>
        <li class="light-link"><a id="shade-grade" href="">Shade Grade</a></li>
    </ul>';
    echo $output;
}

$sort_by_taxonomy = sanitize_text_field($_GET['sort-by']);

// add_action('genesis_after_header', 'pb_sort_menu', 10);
// function pb_sort_menu(){
//      //load the initial taxonomy. This is currently always 'plant-type' on first page load
//     global $sort_by_taxonomy; //used and set by the custom loop
//     $sorter_class = 'plant-type';
//     if ($sort_by_taxonomy) $sorter_class = $sort_by_taxonomy;

//     $plant_type = array('sort-by' => 'plant-type');
//     $location = array('sort-by' => 'location');
//     $light = array('sort-by' => 'light-requirement');
//     $grade = array('sort-by' => 'shade-grade');

//     $output = '<ul id="sorter" class="'.esc_attr($sorter_class).'">';
//     $output .= '
//         <li class="plant-type-link"><a href="'.esc_url(add_query_arg($plant_type)).'">Plant Type</a></li>
//         <li class="location-link"><a href="'.esc_url(add_query_arg($location)).'">Location</a></li>
//         <li class="light-link"><a href="'.esc_url(add_query_arg($light)).'">Light Needs</a></li>
//         <li class="grade-link"><a href="'.esc_url(add_query_arg($grade)).'">Shade Grade</a></li>
//     </ul>';
//     echo $output;
// }

add_action( 'genesis_before_content_sidebar_wrap', 'pb_add_light_needs_qualifier' );
function pb_add_light_needs_qualifier(){
    echo '<div class="notes"><div class="note note-light-requirement">Note: Plants may have multiple light needs.</div>
        <div class="note note-location">Note: Plants may appear in more than one location.</div></div>';
}

// For a list of given taxonomy terms, output the posts for each term, then move on to the next term
// https://gist.github.com/jaredkc/6191133

add_action( 'genesis_loop', 'pb_list_plants' );

genesis();
