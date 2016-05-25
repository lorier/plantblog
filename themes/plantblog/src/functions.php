<?php

add_action( 'genesis_setup', 'pb_load_includes', 15 );
function pb_load_includes() {
    foreach ( glob( dirname( __FILE__ ) . '/plantblog_inc/*.php' ) as $file ) { include $file; }
}

// Start the engine
include_once( get_template_directory() . '/lib/init.php' );

// Set Localization (do not remove)
load_child_theme_textdomain( 'lean-kickstart', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'lean-kickstart' ) );


// Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'MCN' );
define( 'CHILD_THEME_URL', 'http://rcms.com' );
define( 'CHILD_THEME_VERSION', '0.0.1' );

add_action( 'wp_enqueue_scripts', 'kickstart_fonts_scripts' );
// Enqueue fonts
function kickstart_fonts_scripts() {
	wp_enqueue_style( 'sofia-font', get_stylesheet_directory_uri() . '/webfonts/sofia.css', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css', array(), '4.5.0' );
	wp_enqueue_style( 'google-font-open-sans', '//fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic', array(), CHILD_THEME_VERSION );

	wp_enqueue_style('google-font-merriweather-sans', '//fonts.googleapis.com/css?family=Merriweather+Sans:300,300italic,700,700italic', array(), CHILD_THEME_VERSION);
	// wp_enqueue_style('google-font-abel', '//fonts.googleapis.com/css?family=Abel', array(), CHILD_THEME_VERSION);
	// wp_enqueue_style('google-font-lora', '//fonts.googleapis.com/css?family=Lora:400,400italic,700,700italic', array(), CHILD_THEME_VERSION);
	// wp_enqueue_style('google-font-oswald', '//fonts.googleapis.com/css?family=Oswald:300,700,400', array(), CHILD_THEME_VERSION);
	wp_enqueue_style('google-font-volkov', '//fonts.googleapis.com/css?family=Volkhov:400italic', array(), CHILD_THEME_VERSION);
	wp_enqueue_style('google-font-playfair', '//fonts.googleapis.com/css?family=Playfair+Display:400,700,400italic,700italic', array(), CHILD_THEME_VERSION);

	wp_enqueue_script( 'kickstart-responsive-menu', get_stylesheet_directory_uri() . '/js/responsivemenu.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
	$output = array(
		'mainMenu' => __( 'Menu', 'no-sidebar' ),
		'subMenu'  => __( 'Menu', 'no-sidebar' ),
	);
	wp_localize_script( 'kickstart-responsive-menu', 'KickstartL10n', $output );
}


add_action( 'wp_enqueue_scripts', 'rcms_enqueue_stickynav_script' );
function rcms_enqueue_stickynav_script() {
	wp_enqueue_script( 'sample-sticky-menu', get_stylesheet_directory_uri() . '/js/stickynav.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_script( 'core', get_stylesheet_directory_uri() . '/js/core.js', array( 'jquery' ), '1.0.0' );

}

add_action( 'wp_enqueue_scripts', 'kickstart_enqueue_backstretch_scripts' );
// Enqueue Backstretch script and prepare images for loading
function kickstart_enqueue_backstretch_scripts() {

	// Load scripts only if custom background or featured image is being used

	// If we're on a page with no featured image or background image, leave
	if ( is_page() && ! has_post_thumbnail() && ! get_background_image() ) {
		return;
	}

	// If we're not on a page and there's no background image, leave
	if ( ! is_page() && ! get_background_image() ) {
		return;
	}

	wp_enqueue_script( 'kickstart-backstretch', get_stylesheet_directory_uri() . '/js/backstretch.js', array( 'jquery' ), '2.0.4' );
	wp_enqueue_script( 'kickstart-backstretch-set', get_stylesheet_directory_uri() . '/js/backstretch-set.js' , array( 'kickstart-backstretch' ), CHILD_THEME_VERSION );

	wp_localize_script( 'kickstart-backstretch-set', 'KickstartBackStretchImg', array( 'src' => str_replace( 'http:', '', get_background_image() ) ) );

	if ( is_home() ) {
		wp_localize_script( 'kickstart-backstretch-set', 'KickstartBackStretchImg', array( 'src' => str_replace( 'http:', '', get_background_image() ) ) );
	}
	else if ( has_post_thumbnail() ) {
		$image = array( 'src' => has_post_thumbnail() ? genesis_get_image( array( 'format' => 'url' ) ) : '' );
		wp_localize_script( 'kickstart-backstretch-set', 'KickstartBackStretchImg', $image );
	}
	else {
		wp_localize_script( 'milton-backstretch-set', 'KickstartBackStretchImg', array( 'src' => str_replace( 'http:', '', get_background_image() ) ) );
	}

}

// Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

// Add Accessibility support
add_theme_support( 'genesis-accessibility', array( 'headings', 'drop-down-menu',  'search-form', 'skip-links', 'rems' ) );

// Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

// Add support for custom background
// add_theme_support( 'custom-background', array( 'wp-head-callback' => 'kickstart_background_callback' ) );

// Move menu to Header Right and remove the wrap div
remove_action( 'genesis_after_header','genesis_do_nav' ) ;
add_action( 'genesis_header_right','genesis_do_nav' );
add_theme_support( 'genesis-structural-wraps', array( 'header', 'footer-widgets', 'footer' ) );

// Unregister secondary navigation menu
// add_theme_support( 'genesis-menus', array( 'primary' => __( 'Primary Navigation Menu', 'genesis' ) ) );

// Unregister alternate layouts
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'sidebar-content-sidebar' );

// Add custom background callback for background color
function kickstart_background_callback() {
    if ( ! get_background_color() ) {
        return;
    }
    printf( '<style>body { background-color: #%s; }</style>' . "\n", get_background_color() );
}

// Add support for 5-column footer widgets
add_theme_support( 'genesis-footer-widgets', 4 );

// Add post formats
add_theme_support( 'post-formats', array( 'aside', 'status', 'quote' ) );

// Add excerpt support for pages, because pages deserve excerpts too
add_post_type_support( 'page', 'excerpt' );

// Image sizes
add_image_size( 'post_featured', 360, 250, true );
add_image_size( 'post_medium', 400, 218, true );
add_image_size( 'post_large', 573, 285, true );

// Allow shortcodes in widgets
add_filter( 'widget_text', 'do_shortcode' );

add_filter( 'the_content_more_link', 'kickstart_read_more_link' );
// Modify the WordPress read more link
function kickstart_read_more_link() {
	return '<a class="more-link" href="' . get_permalink() . '">' . __( 'Read More', 'lean-kickstart' ) . '</a>';
}

// Unregister sidebars
unregister_sidebar( 'header-right' );
unregister_sidebar( 'sidebar-alt' );

// Register widget areas
genesis_register_sidebar( array(
	'id'          => 'home-top',
	'name'        => __( 'Home Top', 'lean-kickstart' ),
	'description' => __( 'This is the top section of the homepage.', 'lean-kickstart' ),
) );

genesis_register_sidebar( array(
	'id'          => 'before-footer',
	'name'        => __( 'Before Footer Widgets (Twitter)', 'lean-kickstart' ),
	'description' => __( 'Works well with the Genesis Latest Tweets plugin.', 'lean-kickstart' ),
) );
genesis_register_sidebar( array(
	'id'          => 'footer-social',
	'name'        => __( 'After Footer Widgets (Social)', 'lean-kickstart' ),
	'description' => __( 'Designed to work with the Simple Social Icons widget.', 'lean-kickstart' ),
) );

// // Move post info above the post title
// remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
// add_action( 'genesis_entry_header', 'genesis_post_info', 8 );


add_action ( 'genesis_before_footer', 'kickstart_before_footer', 5 );
// Add the 'before footer' widget area (before the erm, footer)
function kickstart_before_footer() {
	genesis_widget_area( 'before-footer', array(
		'before' => '<section class="before-footer"><div class="wrap">',
		'after'  => '</div></section>',
	) );
}
add_action ( 'genesis_before_footer', 'kickstart_footer_social', 15 );
// Add the 'footer social' widget area
function kickstart_footer_social() {
	genesis_widget_area( 'footer-social', array(
		'before' => '<section class="footer-social"><div class="wrap">',
		'after'  => '</div></section>',
	) );
}

add_filter( 'genesis_search_text', 'kickstart_search_text' );
// Customize search form input box text
function kickstart_search_text( $text ) {
	$search_text = __( 'Search', 'lean-kickstart' );

	return $search_text;
}

add_filter( 'genesis_search_button_text', 'kickstart_search_button_text' );
// Customize search form input button text
function kickstart_search_button_text( $text ) {
	$searchbutton_text = __( 'Go', 'lean-kickstart' );

	return $searchbutton_text;
}

add_action( 'genesis_after_entry', 'kickstart_single_next_prev', 5 );
// Next / previous post links
function kickstart_single_next_prev() {
	// Only show on single pages
	if( !is_single() ) {
		return;
	}

	$previouspost_text =  __( 'Previous Post', 'lean-kickstart' );
	$nextpost_text     =  __( 'Next Post', 'lean-kickstart' );

	echo '<div class="archive-pagination pagination">';
		previous_post_link( '<div class="pagination-previous alignleft">%link</div>', $previouspost_text );
		next_post_link( '<div class="pagination-next alignright">%link</div>', $nextpost_text );
	echo '</div>';
}
//Include MCN-specific Function files
add_action('wp_head', 'rcms_favicons' );
function rcms_favicons(){
	$blog_url = esc_url( get_stylesheet_directory_uri() ); 
	echo 
<<<EOT
	<link rel="apple-touch-icon" sizes="57x57" href="$blog_url/images/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="$blog_url/images/apple-touch-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="$blog_url/images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="$blog_url/images/apple-touch-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="$blog_url/images/apple-touch-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="$blog_url/images/apple-touch-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="$blog_url/images/apple-touch-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="$blog_url/images/apple-touch-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="$blog_url/images/apple-touch-icon-180x180.png">
	<link rel="icon" type="image/png" href="$blog_url/images/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="$blog_url/images/favicon-194x194.png" sizes="194x194">
	<link rel="icon" type="image/png" href="$blog_url/images/favicon-96x96.png" sizes="96x96">
	<link rel="icon" type="image/png" href="$blog_url/images/android-chrome-192x192.png" sizes="192x192">
	<link rel="icon" type="image/png" href="$blog_url/images/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="$blog_url/images/manifest.json">
	<link rel="mask-icon" href="$blog_url/images/safari-pinned-tab.svg" color="#4a0c70">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="msapplication-TileImage" content="images/mstile-144x144.png">
	<meta name="theme-color" content="#ffffff">
EOT;
}

// //grid loop
// function be_grid_loop_pagination( $query = false ) {
// 	// If no query is specified, grab the main query
// 	global $wp_query;
// 	if( !isset( $query ) || empty( $query ) || !is_object( $query ) )
// 		$query = $wp_query;
		
// 	// Sections of site that should use grid loop	
// 	if( ! ( $query->is_front_page() || $query->is_archive() ) )
// 		return false;
		
// 	// Specify pagination
// 	return array(
// 		'features_on_front' => 5,
// 		'teasers_on_front' => 6,
// 		'features_inside' => 0,
// 		'teasers_inside' => 12,
// 	);
// }

// function be_grid_loop_query_args( $query ) {
// 	$grid_args = be_grid_loop_pagination( $query );
// 	if( $query->is_main_query() && !is_admin() && $grid_args ) {
// 		// First Page
// 		$page = $query->query_vars['paged'];
// 		if( ! $page ) {
// 			$query->set( 'posts_per_page', ( $grid_args['features_on_front'] + $grid_args['teasers_on_front'] ) );
			
// 		// Other Pages
// 		} else {
// 			$query->set( 'posts_per_page', ( $grid_args['features_inside'] + $grid_args['teasers_inside'] ) );
// 			$query->set( 'offset', ( $grid_args['features_on_front'] + $grid_args['teasers_on_front'] ) + ( $grid_args['features_inside'] + $grid_args['teasers_inside'] ) * ( $page - 2 ) );
// 			// Offset is posts on first page + posts on internal pages * ( current page - 2 )
// 		}
// 	}
// }
// add_action( 'pre_get_posts', 'be_grid_loop_query_args' );

// function be_grid_loop_post_classes( $classes ) {
// 	global $wp_query;
	
// 	// Only run on main query
// 	if( ! $wp_query->is_main_query() )
// 		return $classes;
	
// 	// Only run on grid loop
// 	$grid_args = be_grid_loop_pagination();
// 	if( ! $grid_args || ! $wp_query->is_main_query() )
// 		return $classes;
		
// 	// First Page Classes
// 	if( ! $wp_query->query_vars['paged'] ) {
	
// 		// Features
// 		if( $wp_query->current_post < $grid_args['features_on_front'] ) {
// 			$classes[] = 'feature';
		
// 		// Teasers
// 		} else {
// 			$classes[] = 'one-third';
// 			if( 0 == ( $wp_query->current_post - $grid_args['features_on_front'] ) || 0 == ( $wp_query->current_post - $grid_args['features_on_front'] ) % 3 )
// 				$classes[] = 'first';
// 		}
		
// 	// Inner Pages
// 	} else {
// 		// Features
// 		if( $wp_query->current_post < $grid_args['features_inside'] ) {
// 			$classes[] = 'feature';
		
// 		// Teasers
// 		} else {
// 			$classes[] = 'one-third';
// 			if( 0 == ( $wp_query->current_post - $grid_args['features_inside'] ) || 0 == ( $wp_query->current_post - $grid_args['features_inside'] ) % 3 )
// 				$classes[] = 'first';
// 		}
	
// 	}
	
// 	return $classes;
// }
// add_filter( 'post_class', 'be_grid_loop_post_classes' );

// function be_grid_image_sizes() {
// 	add_image_size( 'be_grid', 175, 120, true );
// 	add_image_size( 'be_feature', 570, 333, true );
// }
// add_action( 'genesis_setup', 'be_grid_image_sizes', 20 );

// function be_grid_loop_image( $image_size ) {
// 	global $wp_query;
// 	$grid_args = be_grid_loop_pagination();
// 	if( ! $grid_args )
// 		return $image_size;
		
// 	// Feature
// 	if( ( ! $wp_query->query_vars['paged'] && $wp_query->current_post < $grid_args['features_on_front'] ) || ( $wp_query->query_vars['paged'] && $wp_query->current_post < $grid_args['features_inside'] ) )
// 		$image_size = 'be_feature';
		
// 	if( ( ! $wp_query->query_vars['paged'] && $wp_query->current_post > ( $grid_args['features_on_front'] - 1 ) ) || ( $wp_query->query_vars['paged'] && $wp_query->current_post > ( $grid_args['features_inside'] - 1 ) ) )
// 		$image_size = 'be_grid';
		
// 	return $image_size;
// }
// add_filter( 'genesis_pre_get_option_image_size', 'be_grid_loop_image' );

// function be_fix_posts_nav() {
	
// 	if( get_query_var( 'paged' ) )
// 		return;
		
// 	global $wp_query;
// 	$grid_args = be_grid_loop_pagination();
// 	if( ! $grid_args )
// 		return;
// 	$max = ceil ( ( $wp_query->found_posts - $grid_args['features_on_front'] - $grid_args['teasers_on_front'] ) / ( $grid_args['features_inside'] + $grid_args['teasers_inside'] ) ) + 1;
// 	$wp_query->max_num_pages = $max;
	
// }
// add_filter( 'genesis_after_endwhile', 'be_fix_posts_nav', 5 );

