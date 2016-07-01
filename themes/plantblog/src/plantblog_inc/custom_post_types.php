<?php 
//Plant post type
if ( ! function_exists('pb_plant') ) {

// Register Custom Post Type
function pb_plant() {

	$labels = array(
		'name'                  => _x( 'Plants', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Plant', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Plants', 'text_domain' ),
		'name_admin_bar'        => __( 'Plant', 'text_domain' ),
		'archives'              => __( 'Plant Archives', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Plant:', 'text_domain' ),
		'all_items'             => __( 'All Plants', 'text_domain' ),
		'add_new_item'          => __( 'Add New Plant', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Plant', 'text_domain' ),
		'edit_item'             => __( 'Edit Plant', 'text_domain' ),
		'update_item'           => __( 'Update Plant', 'text_domain' ),
		'view_item'             => __( 'View Plant', 'text_domain' ),
		'search_items'          => __( 'Search Plant', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into plant', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this plant', 'text_domain' ),
		'items_list'            => __( 'Plant list', 'text_domain' ),
		'items_list_navigation' => __( 'Plant list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter plant list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Plant', 'text_domain' ),
		'description'           => __( 'A plant', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'genesis-cpt-archives-settings' ),
		'taxonomies'            => array( 'plant_type', 'pb_light_requirement', 'pb_year_planted', 'pb_location' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'rewrite'				=> array( 'slug' => 'plant-list' )
	);
	register_post_type( 'plant', $args );

}
add_action( 'init', 'pb_plant', 0 );

}