<?php

//* Edit color picker
// http://urosevic.net/wordpress/tips/custom-colours-tinymce-4-wordpress-39/
add_filter( 'tiny_mce_before_init', 'rcms_tiny_mce_customization', 2 );
function rcms_tiny_mce_customization( $init ) {
    //colors
    $default_colours = '
     "000000", "Black",
     "FFFFFF", "White"
     ';
    $custom_colours = '
	  	"401040", "MCN purple",
		"fcb53b", "MCN yellow",
		"67c39b", "MCN green",
		"cb2c30", "MCN red",
		"b2b2b2", "MCN gray rule",
		"f5f4f0", "MCN lightest gray"
     ';
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init['textcolor_map'] = '['.$default_colours.','.$custom_colours.']';
  
    return $init;
}
