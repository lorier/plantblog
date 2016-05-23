<?php
// enable viewing of subpages in a temporary nav panel
// add_action('genesis_before_content', 'sg_temporary_nav');
function rcms_temporary_nav(){
    if(is_page(611) || is_page(609)){
        global $post;
        $parentID = wp_get_post_parent_id( $post->ID );
        $args = array(
        'authors'      => '',
        'child_of'     => $post->ID,
        'date_format'  => get_option('date_format'),
        'depth'        => 0,
        'echo'         => 0,
        'exclude'      => '',
        'include'      => '',
        'link_after'   => '',
        'link_before'  => '',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'show_date'    => '',
        'sort_column'  => 'menu_order, post_title',
            'sort_order'   => '',
        'title_li'     => __('(Subpage Nav)')
    );
        echo wp_list_pages($args);
    }
}
/// Enable LiveReload. Compatible with MAMP Source: http://robandlauren.com/2014/02/05/live-reload-grunt-wordpress/ */
add_action( 'wp_enqueue_scripts', 'tw_setup_livereload');
function tw_setup_livereload(){
    if (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
      wp_register_script('livereload', '//localhost:90000/livereload.js?snipver=1', null, false, true);
      wp_enqueue_script('livereload');
    }
}
// Make variable output pretty
function rcms_print_pre($value) {
    echo "<pre>",print_r($value, true),"</pre>";
}

