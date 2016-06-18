<?php
/**
 * Plant Blog
 *
 * @author  Lorie Ransom
 * @license GPL-2.0+
 * @link    http://tinywhalecreative.com
 */



remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

add_action('get_header', 'pb_output_plants_sidebar');
function pb_output_plants_sidebar(){
        remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
        // add_action( 'genesis_sidebar', 'pb_do_sidebar' );
}
// function pb_do_sidebar() {
//     dynamic_sidebar( 'plants-sidebar' );
// }

add_filter( 'genesis_attr_content', 'pb_add_custom_class' );
function pb_add_custom_class( $attributes ) {
          $attributes['class'] = $attributes['class'];
    
    return $attributes;
}

//http://www.rlmseo.com/blog/passing-get-query-string-parameters-in-wordpress-url/
//https://codepen.io/the_ruther4d/post/custom-query-string-vars-in-wordpress

$sort_by_taxonomy = sanitize_text_field($_GET['sort-by']);
lr_print_pre($sort_by_taxonomy);

// add_action( 'genesis_before_content', 'genesis_do_post_title', 1 );

//Define and add the taxonomy names the sort navigation
$plant_type = array('sort-by' => 'plant-type');
$location = array('sort-by' => 'location');

add_action('genesis_before_loop', 'pb_sort_menu', 15);
function pb_sort_menu(){
    global $plant_type;
    global $location;
    $output = '<ul id="sorter">';
        $output .= '<li>Sort By: </li>
        <li><a href="'.esc_url(add_query_arg($plant_type)).'">Plant Type</a></li>
        <li><a href="'.esc_url(add_query_arg($location)).'">Location</a></li>
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
    //loop through each term, gathering all the posts for each term in chunks
    $count = 0;
    foreach ( $tax_terms as $term ) {
        echo '<article class="teaser one-half';
        if($count % 2 == 0){ echo ' first'; };
        echo '">';
        //add title before each grouping
        echo '<h2>' . $term->name . '</h2> ';

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
                        $output .= pb_get_terms_list(get_the_ID());
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
        $count = $count+1;
        }
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

function pb_get_latin_name($id=null){
    $latin_name = get_field('latin-name', $id, true);
    if($latin_name){
        return $latin_name;
    }
    return 'Latin Name';

}

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
    $term_list = '<ul class="terms-list">';
    $term_list .= implode('', $term_items);
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