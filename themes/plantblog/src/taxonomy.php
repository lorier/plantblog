<?php
/**
 * Plant Blog
 *
 * @author  Lorie Ransom
 * @license GPL-2.0+
 * @link    http://tinywhalecreative.com
 */
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_sidebar_content');

add_filter( 'body_class', function( $classes ) {
    $classes[] = 'taxonomy';
    return $classes;
} );

remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
add_action('genesis_before_loop', 'pb_add_tag_title',9);
function pb_add_tag_title(){
        $output = '<div class="tax-title"><h1 class="tag-title">Category: ';
        $output .= pb_taxonomy_description();
        $output .= '</h1></div>';
        echo $output;
}
// add_action('genesis_before_content', 'genesis_do_taxonomy_title_description', 12);
function pb_taxonomy_description(){
    global $wp_query;
    $term = is_tax() ? get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ) : $wp_query->get_queried_object();
    if ( $headline = $term->name ){
        $headline = sprintf( '<span %s>%s</span>', genesis_attr( 'archive-title' ), strip_tags( $headline ) );
    }
    return $headline;
}


add_action('get_header', 'pb_output_plants_sidebar');
function pb_output_plants_sidebar(){
        remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
        add_action( 'genesis_sidebar', 'pb_do_sidebar' );
}

function pb_do_sidebar() {
    dynamic_sidebar( 'plants-sidebar' );
}

add_action('genesis_before_content', 'pb_add_plant_list_link');
function pb_add_plant_list_link(){
    echo '<p class="back-link"><span>< Back to Main <a href="'.esc_attr( get_post_type_archive_link( 'plant' ) ).'">Plant List</a></span></p>';
}


///////////////////////////////////////
// TODO implement this for titles 

/** Replace the standard loop with our custom loop */
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'pb_do_plant_loop' );


$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
$term_id = $term->term_id;


function pb_do_plant_loop() {
   
    global $term_id;
    $args = array (
        'post_type'              => array( 'plant' ),
        'post_status'            => array( 'publish' ),
        'nopaging'               => false,
        'posts_per_page'         => '30',
        'posts_per_archive_page' => '30',
        'order'                  => 'ASC',
        'orderby'                => 'title',
        'cache_results'          => true,
        'update_post_meta_cache' => true,
        'update_post_term_cache' => true,
        'tax_query' => array(
            array(
                'taxonomy' => get_query_var('taxonomy'),
                'field'    => 'term_id',
                'terms'    => $term_id
            ),
        ),
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
            $output = '';
            printf( '<article %s>', genesis_attr( 'entry' ) );
                if(has_post_thumbnail( $post_id )){
                    $output .= get_the_post_thumbnail($post->post_id, 'thumbnail', array('class'=>'alignleft') );
                }else { $output .= '<img src="'.get_site_url().'/wp-content/uploads/2016/07/Untitled-1-150x150.png" class="alignleft wp-post-image"/>'; }

                $output .= '<h2><a href="'.esc_attr(get_the_permalink()).'">'.get_the_title().'</a></h2>';
                $output .= '<p class="latin-name">'.pb_get_latin_name($post->post_id).'</p>';                // $output .= '<p>'.get_the_excerpt().'</p>';
                // $output .= pb_get_terms_list(get_the_ID());
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
genesis();
