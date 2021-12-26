<?php

function lr_print_pre($value) {
    echo "<pre>",print_r($value, true),"</pre>";
}

//testing rewrites
function print_rules(){
		if (is_single()){
			global $wp_rewrite;
			lr_print_pre($wp_rewrite->rules);
	}
}
// add_action('wp', 'print_rules');

function generate_author_rewrite_rules() {
	// echo 'rewrite called';
	global $wp_rewrite;
	 // add_rewrite_rule(
  //       'writer/([^/]+)/?',
  //       'index.php?author_name=$matches[1]',
  //       'top');
	$new_rules = array(
		'writer/([^/]+)/?' => 'index.php?author_name='.$wp_rewrite->preg_index(1)
	);
	$wp_rewrite->rules = $new_rules + (array)$wp_rewrite->rules; //php is barfing on the + sign, though I see it used everywhere

}
add_action( 'init', 'generate_author_rewrite_rules' );

function welcome_rewrite_rule()
{
    add_rewrite_rule('^welcome$', 'index.php?p=1864', 'top');
}
add_action('init', 'welcome_rewrite_rule');




add_action( 'genesis_setup', 'pb_load_includes', 15 );
function pb_load_includes() {
    foreach ( glob( dirname( __FILE__ ) . '/plantblog_inc/*.php' ) as $file ) { include $file; }
}

// Start the engine
include_once( get_template_directory() . '/lib/init.php' );

// Set Localization (do not remove)
load_child_theme_textdomain( 'lean-kickstart', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'lean-kickstart' ) );


// Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'A Tree Garden' );
define( 'CHILD_THEME_URL', 'http://atreegarden.com' );
define( 'CHILD_THEME_VERSION', '0.0.1' );



add_action( 'wp_enqueue_scripts', 'kickstart_fonts_scripts' );
// Enqueue fonts
function kickstart_fonts_scripts() {
	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css', array(), '4.5.0' );
	wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Lobster|Muli:300,300i,400,400i|Oswald:300,400,700', array(), CHILD_THEME_VERSION);

	wp_enqueue_script( 'kickstart-responsive-menu', get_stylesheet_directory_uri() . '/js/responsivemenu.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
	$output = array(
		'mainMenu' => __( '', 'no-sidebar' ),
		'subMenu'  => __( '', 'no-sidebar' ),
	);
	wp_localize_script( 'kickstart-responsive-menu', 'KickstartL10n', $output );
}

add_action( 'wp_enqueue_scripts', 'pb_enqueue_corejs');

function pb_enqueue_corejs(){
	wp_register_script( 'core', get_stylesheet_directory_uri() . '/js/core.js', array( 'jquery' ), '1.0.0'  );

	//pass plant terms to browser
	$taxa = array('plant-type','light-requirement','location','shade-grade');
    $taxon_list = array();

	foreach($taxa as $taxon){
	    $data = array();
		$taxon_list[$taxon] = array(); 
		$plant_terms = get_terms($taxon);
	    foreach ($plant_terms as $plant_term){
	        $name = $plant_term->name;
	        $slug = $plant_term->slug;
	        if($slug=='evergreen'){
	        	continue;
	        }else {
		        $data[$slug]=$name;
	        }
	    }
	    $taxon_list[$taxon] = $data;
	}
	//send nested array of taxonomies. Each nested array contains a list of terms associated with a taxonomy name
    wp_localize_script( 'core', 'taxonomy_data', $taxon_list );

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

// Move menu to Header Right and remove the wrap div
remove_action( 'genesis_after_header','genesis_do_nav' ) ;
add_action( 'genesis_header_right','genesis_do_nav' );
add_theme_support( 'genesis-structural-wraps', array(  'footer-widgets', 'footer' ) );

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
add_theme_support( 'post-formats', array( 'quote' ) );

// Add excerpt support for pages, because pages deserve excerpts too
add_post_type_support( 'page', 'excerpt' );

// Image sizes
add_image_size( 'post_featured', 460, 311, true );
add_image_size( 'post_medium', 400, 218, true );
add_image_size( 'post_large', 573, 285, true );

//Set content width for Jetpack tiled gallery
if ( ! isset( $content_width ) ) {
    $content_width = 750;
}

// Allow shortcodes in widgets
add_filter( 'widget_text', 'do_shortcode' );

// Modify the WordPress read more link
add_filter( 'the_content_more_link', 'kickstart_read_more_link' );
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

add_action( 'genesis_after_entry', 'pb_single_next_prev', 5 );
// Next / previous post links
function pb_single_next_prev() {
	// Only show on single post pages (not plants)
	if( !is_singular('post') ) {
		return;
	}

	$previouspost_text =  __( 'Older Posts', 'lean-kickstart' );
	$nextpost_text     =  __( 'Newer Posts', 'lean-kickstart' );

	echo '<div class="archive-pagination pagination">';
		previous_post_link( '<div class="pagination-previous alignleft">%link</div>', $previouspost_text );
		next_post_link( '<div class="pagination-next alignright">%link</div>', $nextpost_text );
	echo '</div>';
}

//images in RSS
//// display featured post thumbnails in WordPress feeds
///http://wpcodesnippet.com/display-featured-post-thumbnails-wordpress-feeds/
add_filter( 'the_excerpt_rss', 'pb_post_thumbnails_in_feeds' );
add_filter( 'the_content_feed', 'pb_post_thumbnails_in_feeds' );

function pb_post_thumbnails_in_feeds( $content ) {
    global $post;
    if( has_post_thumbnail( $post->ID ) ) {
        $content = '<p>' . get_the_post_thumbnail( $post->ID ) . '</p>' . $content;
    }
    return $content;
}

add_action('wp_head', 'pb_favicons' );
function pb_favicons(){
	$blog_url = esc_url( get_stylesheet_directory_uri() ); 
	echo 
<<<EOT
<link rel="apple-touch-icon" sizes="57x57" href="$blog_url/images/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="$blog_url/images/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="$blog_url/images/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="$blog_url/images/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="$blog_url/images/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="$blog_url/images/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="$blog_url/images/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="$blog_url/images/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="$blog_url/images/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="$blog_url/images/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="$blog_url/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="$blog_url/images/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="$blog_url/images/favicon-16x16.png">
<link rel="manifest" href="$blog_url/images/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="$blog_url/images/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
EOT;
}
