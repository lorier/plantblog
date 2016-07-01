<?php
/**
 * Plant Blog
 *
 * @author  Lorie Ransom
 * @license GPL-2.0+
 * @link    http://tinywhalecreative.com
 */

// Template Name: Plants

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

$sort_by_taxonomy = sanitize_text_field($_GET['sort-by']);

add_action('genesis_before_loop', 'pb_sort_menu', 10);
function pb_sort_menu(){
    // global $plant_type;
    // global $location;
    global $sort_by_taxonomy;
    $sorter_class = 'plant-type';
    if ($sort_by_taxonomy) $sorter_class = $sort_by_taxonomy;

    $plant_type = array('sort-by' => 'plant-type');
    $location = array('sort-by' => 'location');
    $light = array('sort-by' => 'light-requirement');
    $output = '<ul id="sorter" class="'.esc_attr($sorter_class).'">';
        $output .= '
        <li id="sort-by">Sort By:</li>
        <li class="plant-type-link"><a href="'.esc_url(add_query_arg($plant_type)).'">Plant Type</a></li>
        <li class="location-link"><a href="'.esc_url(add_query_arg($location)).'">Location</a></li>
        <li class="light-link"><a href="'.esc_url(add_query_arg($light)).'">Light</a></li>
    </ul>';
    echo $output;
}


// For a list of given taxonomy terms, output the posts for each term, then move on to the next term
// https://gist.github.com/jaredkc/6191133

add_action( 'genesis_loop', 'list_posts_by_term' );
function list_posts_by_term( ) {
    global $sort_by_taxonomy;

    //retrieve an array of terms from the taxonomy
    if ($sort_by_taxonomy){
        $tax_terms = get_terms( $sort_by_taxonomy, 'orderby=name');
    } else {
        //default sort order
        $tax_terms = get_terms( 'plant-type', 'orderby=name');
        $sort_by_taxonomy = 'plant-type';
    }
    
    $args = array (
        'post_type'              => array( 'plant' ),
        'post_status'            => array( 'publish' ),
        'nopaging'               => false,
        'posts_per_page'         => '30',
        'posts_per_archive_page' => '30',
        'order'                  => 'ASC',
        'orderby'                => 'title',
        // 'cache_results'          => true,
        // 'update_post_meta_cache' => true,
        // 'update_post_term_cache' => true,
        'tax_query' =>          array(
                array(
                    'taxonomy' => $sort_by_taxonomy,
                    'field'     => 'term_id',
                    'terms'     => $tax_terms
                )
        )
    );
    echo '<main class="masonry">';
    //loop through each term, gathering all the posts for each term in chunks
    foreach ( $tax_terms as $term ) {

        //don't show certain types
        if ( in_array($term->slug, ['evergreen', 'dead']) ) {
            continue;
        }

        //if a plural form of the term is available, use that
        $plural = get_term_meta( $term->term_id, 'plural', true );
        
        if( !empty($plural) ){
            $page_title = $plural;
        }else {
            $page_title = $term->name;
        }

        echo '<article class="item">';
        //add title before each grouping
        echo '<a href="'.esc_url(get_term_link($term->term_id)).'"><h2>' . ucfirst($page_title) . '</h2></a>';
        if( !empty( term_description($term) ) ){
            echo term_description($term);
        }
            $tax_args = array(
                array(
                    'taxonomy' => $sort_by_taxonomy,
                    'field' => 'slug',
                    'terms' =>$term->slug,
                )
             );
            $args['tax_query'] = $tax_args;

            //get all the posts for each term
            $query = new WP_Query( $args );


            if ( $query->have_posts() ) {

                do_action( 'genesis_before_while' );

                while ( $query->have_posts() ) {
                    
                    $query->the_post();

                    do_action( 'genesis_before_entry' );
                    
                    global $post;
                    printf( '<div %s>', genesis_attr( 'entry' ) );
                        $output = pb_get_thumbnail($post->post_id);
                        $output .= '<h3><a href="'.esc_url(get_the_permalink()).'">'.get_the_title().'</a></h3>';
                        $output .= '<p class="latin-name">'.pb_get_latin_name($post->post_id).'</p>';
                        $output .= pb_get_terms_list(get_the_ID(), $sort_by_taxonomy);
                        echo $output;
                    echo '</div>';

                    do_action( 'genesis_after_post' );

                    // do_action( 'genesis_after_entry' );
                }
                // do_action( 'genesis_after_endwhile' );

            } else {
                // no posts found
                do_action( 'genesis_loop_else' );
            }
        echo '</article>';
        }
    echo '</main>';
    // Restore original Post Data
    wp_reset_postdata();
}

genesis();


function pb_get_thumbnail($id=null){
    $image =  get_the_post_thumbnail( $id, 'thumbnail' );
    if (!$image){
        $image = '<img src="http://placehold.it/350x150">';
    }
    return $image;
}



//Get and return terms in a linked, unordered list

function pb_get_terms_list($id=null,$sort_by_taxonomy ){
    
    //get all the term objects
    $types = wp_get_post_terms($id, array('plant-type'), array("fields" => "all"));
    $light_reqs = wp_get_post_terms($id, array('light-requirement'), array("fields" => "all"));
    
    $plant_type_terms = [];
    $light_reqs_terms = [];

    //if we are viewing the list categorized by location, add the plant type to the listing
    
        foreach($types as $term_object) {
            $plant_type_terms[] = '<li><a href="'.get_term_link( $term_object->term_id, $term_object->taxonomy ).'">'.$term_object->name.'</a></li>';
        }
    
    foreach($light_reqs as $term_object) {
        $light_reqs_terms[] = '<li><a href="'.get_term_link( $term_object->term_id, $term_object->taxonomy ).'">'.$term_object->name.'</a></li>';
    }
    //build an unordered list from the contents of the term item array
    $term_list = '<ul class="terms-list">';
    if ($sort_by_taxonomy == 'location'){
        $term_list .= '<li class="term_title">Type: </li>';
        $term_list .= implode('<li class="spacer">|</li> ', $plant_type_terms);
        $term_list .= '<br>';
    }
    $term_list .= '<li class="term_title">Light: </li>';
    $term_list .= implode('<li class="spacer">|</li> ', $light_reqs_terms);
    $term_list .= '</ul>';
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

