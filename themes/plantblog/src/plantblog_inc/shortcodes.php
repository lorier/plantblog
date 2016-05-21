<?php

// MCN-Specific functions
// Add typekit fonts

add_shortcode( 'clients-button', 'rcms_clients_button_shortcode' );
function rcms_clients_button_shortcode($atts, $content = null) {
    
    $a = shortcode_atts( array(
        'title' => 'Clients',
        'subtitle' => 'Create an Online Account',
        'link' => ''
        // ...etc
    ), $atts );

    $link =  $a['link']; 

     $short = '<a class="clients-button" target="_blank" href="';
     $short .= esc_url( $link );
     $short .= '"><div><h3>';
     $short .= $a['title'];
     $short .= '</h3>';
     $short .= '<p>';
     $short .= $a['subtitle'];
     $short .= '</p></div></a>';

     return $short;
}

add_shortcode( 'providers-button', 'rcms_providers_button_shortcode' );
function rcms_providers_button_shortcode($atts, $content = null) {
    
    $a = shortcode_atts( array(
        'title' => 'Providers',
        'subtitle' => 'Credentialing Process',
        'link' => ''
        // ...etc
    ), $atts );

    $link =  $a['link']; 

     $short = '<a class="providers-button" href="';
     $short .= esc_url( $link );
     $short .= '"><div><h3>';
     $short .= $a['title'];
     $short .= '</h3>';
     $short .= '<p>';
     $short .= $a['subtitle'];
     $short .= '</p></div></a>';

     return $short;
}
add_shortcode( 'taglist', 'rcms_tag_list' );
function rcms_tag_list($atts){
    $tags = get_tags();
    if ($tags) {
        $content = '<ul class="tag-list">';
        foreach ($tags as $tag) {
            $thetaglink = get_tag_link($tag->term_id);
            $content .= '<li><a href="'. esc_url( $thetaglink ).'">'. $tag->name  . '</a>&nbsp;(<span class="count">' . $tag->count . '</span>)</li>';
            // rcms_print_pre($tag);
            // return;
            }
       $content .= '</ul>';
    }
    return $content;
}


