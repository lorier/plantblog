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
        'tax_query' => array(
                        array(
                            'taxonomy' => 'dead-alive',
                            'field'    => 'slug',
                            'terms'    => 'Dead',
                        )
                    )
    );

    //loop through each term, gathering all the posts for each term in chunks
    //get all the posts for each term
    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {

        do_action( 'genesis_before_while' );
        
        $half_num_posts = ceil( $query->found_posts / 2 );
        $count = 0;

        echo '<div class="column-grid"><div class="column">';
        
        while ( $query->have_posts() ) {
            
            $query->the_post();
            global $post;

            $count++;

            $thumb = lr_get_post_thumb($post);
           
            $plant_types = pb_get_terms_list($post->ID, '');
            $reason = get_field('reason', $post->id);

            $plant_types = !empty($plant_types) ? $plant_types : '--';
            $reason = !empty($reason) ? $reason : '--';

            do_action( 'genesis_before_entry' );
            
            printf( '<div %s>', genesis_attr( 'entry' ) );
                $output = '<div class="plant-list-thumb">'.$thumb.'</div>';
                $output .= '<div class="pb_entry_content"><h3><a href="'.esc_url(get_the_permalink()).'">'.get_the_title().'</a></h3>';
                $output .= '<p class="latin-name">'.pb_get_latin_name($post->post_id).'</p>';
                $output .= '<p><span>Reason: </span>'.wp_strip_all_tags( $reason ).'</p></div>';
                echo $output;
            echo '</div>';

            do_action( 'genesis_after_post' );

            if ( $half_num_posts == $count ){
                echo '</div><div class="column">';
            }
        }
        echo '</div></div>';

    } else {
        // no posts found
        do_action( 'genesis_loop_else' );
    }
    // Restore original Post Data
    wp_reset_postdata();
}


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
//Sidebar filter for big view
function pb_big_list_filter(){
    echo '<div><fieldset id="filterList" class="big-view-filter"><legend>Filter</legend>
            <div class="selectors"><a href="#" id="selectAll">Select All</a><span>&nbsp;</span>|<span>&nbsp;</span><a href="#" id="selectNone">Select None</a></div>
            <div id="checkboxContainer"></div>
           </fieldset></div>';
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
        'posts_per_page'         => '100',
        'posts_per_archive_page' => '100',
        'order'                  => 'ASC',
        'orderby'                => 'title',
    );

    echo '<main class="masonry pb-wrap">';

    //loop through each term, gathering all the posts for each term in chunks
    foreach ( $tax_terms as $term ) {

        $page_title = $term->name;

        echo '<article id="'.$term->slug.'-section" class="item filterable-items">';
        
        //add title before each grouping
        echo '<a href="'.esc_url(get_term_link($term->term_id)).'"><h2>' . ucfirst($page_title) . '</h2></a>';
        $desc = term_description($term) ? : '';
        if( !empty( $desc ) ){
            echo term_description($term);
        }
            $tax_args = array(
            'relation' => 'AND',
                array(
                    'taxonomy' => $sort_by_taxonomy,
                    'field' => 'slug',
                    'terms' =>$term->slug,
                ),
                array(
                    'taxonomy' => 'dead-alive',
                    'field'    => 'slug',
                    'terms'    => array('dead'),
                    'operator' => 'NOT IN'
                )
            );
            $args['tax_query'] = $tax_args;

            //get all the posts for each term
            $query = new WP_Query( $args );


            if ( $query->have_posts() ) {
                $output = '<div class="plant-cards">';

                do_action( 'genesis_before_while' );

                while ( $query->have_posts() ) {
                    
                    $query->the_post();
                    
                    global $post;

                    $shade_score = pb_get_first_term_name($post, 'shade-grade');

                    //set default grade
                    if ( empty($shade_score) ){
                        $shade_score = 'TBD';
                    }
                    //flag if new addition < 3 months
                    $post_date = new DateTime($post->post_date); 
                    $date_now   = new DateTime("now"); 
                    $date_diff  = $post_date->diff($date_now);

                    $new_plant_flag = $date_diff->days <= 30 ? "<span class='new-flag'>New! </span>" : "";
                    
                    $tax_classes = pb_get_post_terms($post);

                    $thumb = lr_get_post_thumb($post);

                    //check our custom field value
                    // $is_dead = get_field('is_this_plant_dead', $post->id);
                    $is_dead = get_field('is_this_plant_dead', $post->id);

                    $is_dead = $is_dead =='Yes' ? true : false;

                        //only output living plants
                        if($is_dead){
                            continue;
                        }else{
                            do_action( 'genesis_before_entry' );
                                $output .= '<div class="plant '. $tax_classes.'" >';
                                $output .= '<a href="'.esc_url(get_the_permalink()).'"><div class="plant-list-thumb"">'.$thumb.'</div>';
                                $output .= '<div class="text"><h3>'.$new_plant_flag .get_the_title().'</h3>';
                                $output .= '<p class="latin-name">'.pb_get_latin_name($post->post_id).'</p>';
                                $output .= '<p></p></div></a>'; //removed shade score but kept markup for styling
                                $output .= '</div>';//end .plant

                            do_action( 'genesis_after_post' );
                        }
                }
                
                $output .= '</div>';
                echo $output;
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

 