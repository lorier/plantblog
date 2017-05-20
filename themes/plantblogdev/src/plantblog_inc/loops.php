<?php

//Dead Plants Loop
function pb_list_dead_plants() {

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
    );

    //loop through each term, gathering all the posts for each term in chunks
    //get all the posts for each term
    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {

        do_action( 'genesis_before_while' );

        while ( $query->have_posts() ) {
            
            $query->the_post();
            global $post;

            $thumb = lr_get_post_thumb($post);
           
            //check our custom field value
            $is_dead = get_field('is_this_plant_dead', $post->id);

                //only output living plants
                if($is_dead == 'No'){
                    continue;
                }else {
                    $plant_types = pb_get_terms($post->ID);
                    $reason = get_field('reason', $post->id);

                    $plant_types = !empty($plant_types) ? $plant_types : '--';
                    $reason = !empty($reason) ? $reason : '--';

                    do_action( 'genesis_before_entry' );
                    
                    printf( '<div %s>', genesis_attr( 'entry' ) );
                        // $output = '<a href="'.esc_url(get_the_permalink()).'">'.pb_get_thumbnail($post->post_id);
                        $output = '<a href="'.esc_url(get_the_permalink()).'"><div class="plant-list-thumb"">'.$thumb.'</div>';
                        $output .= '<div class="pb_entry_content"><h3>'.get_the_title().'</h3>';
                        $output .= '<p class="latin-name">'.pb_get_latin_name($post->post_id).'</p></a>';
                        $output .= '<p><span>Reason: </span>'.$reason.'</p>';
                        $output .= '<p><span>Type: </span>'.$plant_types.'</p></div>';
                        echo $output;
                    echo '</div>';

                    do_action( 'genesis_after_post' );
                }
        }
    } else {
        // no posts found
        do_action( 'genesis_loop_else' );
    }
    // Restore original Post Data
    wp_reset_postdata();
}

// //Get the plant type(s) for each entry
// function pb_get_terms($post_id, $term){
//     $term_array = wp_get_post_terms( $post_id, $term );
//     $terms = array();
//     if(is_array($term_array)){
//         foreach($term_array as $term_object){
//             // lr_print_pre($term_object);
//             $terms[] = $term_object->name;
//         }
//         $the_terms = implode(", ", $terms);
//     }
//     return $the_terms;
// }
//Get the plant type(s) for each entry

function pb_get_first_term_name($post, $taxonomy){

    $term = ''; //fallback value if score isn't set
    
    $terms = get_the_terms( $post, $taxonomy );
    // lr_print_pre( $terms );
    
    if ( !empty( $terms) ){
        //get only the first recorded term object
        $term_obj = reset($terms);
        $term = $term_obj->name;
    }
    return $term;
}

//Plant List Loop
function pb_list_plants() {
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

    echo '<main class="masonry pb-wrap">';

    //loop through each term, gathering all the posts for each term in chunks
    foreach ( $tax_terms as $term ) {

        //don't show certain types
        if ( in_array($term->slug, array('evergreen', 'dead') ) ) {
            continue;
        }

        $page_title = $term->name;


        echo '<article class="item">';
        
        //add title before each grouping
        echo '<a href="'.esc_url(get_term_link($term->term_id)).'"><h2>' . ucfirst($page_title) . '</h2></a>';
        $desc = term_description($term) ? : '';
        if( !empty( $desc ) ){
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

            // print_r($tax_args);

            //get all the posts for each term
            $query = new WP_Query( $args );


            if ( $query->have_posts() ) {

                do_action( 'genesis_before_while' );

                while ( $query->have_posts() ) {
                    
                    $query->the_post();
                    
                    global $post;

                    $shade_score = pb_get_first_term_name($post, 'shade-grade');

                    //set default grade
                    if ( empty($shade_score) ){
                        $shade_score = 'TBD';
                    }

                    $tax_classes = pb_get_post_terms($post);

                    $thumb = lr_get_post_thumb($post);

                    //check our custom field value
                    $is_dead = get_field('is_this_plant_dead', $post->id);
                    $is_dead = $is_dead =='Yes' ? true : false;

                        //only output living plants
                        if($is_dead){
                            continue;
                        }else{
                            do_action( 'genesis_before_entry' );
                            printf( '<div class="plant '. $tax_classes.'" %s>', genesis_attr( 'entry' ) );
                                $output = '<a href="'.esc_url(get_the_permalink()).'"><div class="plant-list-thumb"">'.$thumb.'</div>';
                                $output .= '<div class="text"><h3>'.get_the_title().'</h3>';
                                $output .= '<p class="latin-name">'.pb_get_latin_name($post->post_id).'</p></div></a>';
                                $output .= '<div class="grade"><span>'. $shade_score . '</span></div>';
                                echo $output;
                            echo '</div>';

                            do_action( 'genesis_after_post' );
                        }
                }
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

/**
 * Get taxonomies terms links.
 *
*/

function pb_get_post_terms($post) {

 
    // Get taxonomies on the post object. Returns array of taxonomy names.
    $taxonomies = get_object_taxonomies( $post );
    
    $output = array();
 
    foreach ( $taxonomies as $taxonomy ){
    
        // Get the terms related to post.
        $terms = get_the_terms( $post->ID, $taxonomy );

        $output[] = $taxonomy;

        if ( ! empty( $terms ) ) {
            foreach ( $terms as $term ) {

                /* MAJOR EDIT HERE FOR JS *********************************************************/
                $output[] .= $term->slug;
                // $output[] = $taxonomy . '-' . $term->slug;
            }
        }
    }
    // lr_print_pre($output);
    return implode( ' ', $output );
}

 