<?php
/**
 * Plant Blog
 *
 * @author  Lorie Ransom
 * @license GPL-2.0+
 * @link    http://tinywhalecreative.com
 */

// Template Name: Plants
// Make variable output pretty

// $vars = $_GET[];
// lr_print_pre($vars);
// lr_print_pre( $_GET['plant-type'] );
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

    global $loop_counter;

    $loop_counter = 0;

    // The Loop
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();

            do_action( 'genesis_before_entry' );

            printf( '<article %s>', genesis_attr( 'entry' ) );

                $output = '<h2>'.get_the_title().'</h2>';
                $output .= '<p>'.get_the_excerpt().'</p>';
                $output .= pb_get_terms_list(get_the_ID());
                echo $output;
            echo '</article>';

            do_action( 'genesis_after_post' );
            $loop_counter++;

            // do_action( 'genesis_after_entry' );
        }
        do_action( 'genesis_after_endwhile' );

    } else {
        // no posts found
        do_action( 'genesis_loop_else' );
    }

    // Restore original Post Data
    wp_reset_postdata();
 
}
genesis();


//Get and return terms in a linked, unordered list

function pb_get_terms_list($id=null){
    
    //get all the term objects
    $post_terms = wp_get_post_terms($id, array('plant-type','location', 'light-requirement', 'year-planted'), array("fields" => "all"));
    
    //loop through term objects and build an li for each term. Add the li to the term item array.
    $term_items;
    foreach($post_terms as $term_object) {
        $term_items[] = '<li><a href="'.get_term_link( $term_object->term_id, $term_object->taxonomy ).'">'.$term_object->name.'</a></li>';
    }
    //build an unordered list from the contents of the term item array
    ob_start();
    $term_list = '<ul>';
    $term_list .= implode('', $term_items);
    $term_list .= '</ul>';
    ob_clean(); 
    // lr_print_pre($term_list);
    return $term_list;
}

// function ()

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