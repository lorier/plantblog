<?php
/**
 * Kickstart Pro
 *
 * @author  Lean Themes
 * @license GPL-2.0+
 * @link    http://demo.leanthemes.co/kickstart/
 */

// Template Name: Plants
// Make variable output pretty
function lr_print_pre($value) {
    echo "<pre>",print_r($value, true),"</pre>";
}


add_filter('genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar');

add_action('get_header', 'pb_output_plants_sidebar');
function pb_output_plants_sidebar(){
        remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
        add_action( 'genesis_sidebar', 'pb_do_sidebar' );
}

function pb_do_sidebar() {
    dynamic_sidebar( 'plants-sidebar' );
}

/** Replace the standard loop with our custom loop */
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'pb_do_custom_loop' );
function pb_do_custom_loop() {
    // WP_Query arguments
    $args = array (
        'post_type'              => array( 'plant' ),
        'post_status'            => array( 'publish' ),
        'nopaging'               => false,
        'posts_per_page'         => '30',
        'posts_per_archive_page' => '30',
        'order'                  => 'DESC',
        'orderby'                => 'modified',
        'cache_results'          => true,
        'update_post_meta_cache' => true,
        'update_post_term_cache' => true,
    );

    // The Query
    $query = new WP_Query( $args );

    // The Loop
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            echo '<h2>'.the_title().'</h2>';
            echo pb_get_terms(get_the_ID());
        }
    } else {
        // no posts found
        echo 'no posts';
    }

    // Restore original Post Data
    wp_reset_postdata();
 
}
genesis();


//Get and return terms

function pb_get_terms($id=null){
    $term_list = wp_get_post_terms($id, array('plant_type','pb_location', 'pb_light_requirement', 'pb_year_planted'), array("fields" => "all"));
    $the_slugs = null;
    foreach($term_list as $term_single) {
        $the_slugs .= $term_single->slug; //do something here
    }
    return $the_slugs;
}


//For each record show
    //Common name
    //Latin name
    //Description
    //Image
    //Taxonomy:
        // Garden Location
        // Plant type
        // Light needs
        // Year Planted
        // 