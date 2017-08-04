<?php

// Add Custom Podcast RSS Feed
add_action('init', 'customRSS');
function customRSS(){
        add_feed('feedname', 'customRSSFunc');
}
function customRSSFunc(){
        get_template_part('rss', 'feedname');
}

// Flush Rewrite Rules
function theme_activation() {
	flush_rewrite_rules();
}
add_action('after_switch_theme', 'theme_activation');

function theme_deactivation() {
	flush_rewrite_rules();
}
add_action('switch_theme', 'theme_deactivation');

// Set Variables For Function Libraries
define("THEME_DIR", get_template_directory_uri());
$admin_path = TEMPLATEPATH . '/assets/admin/';
$functions_path = TEMPLATEPATH . '/assets/functions/';
$includes_path = TEMPLATEPATH . '/assets/inc/';

// Includes
include ($includes_path . '/cuztom/cuztom.php');		 // Custom Post Type Helper
include ($includes_path . '/inflector.php');			 // Pluralize English Language
include ($includes_path . '/woohacks.php');				 // Woocommerce Hacks
														 
// Load Functions										 
require_once ($functions_path . 'cleanup.php');			 // Cleanup Wordpress
require_once ($functions_path . 'custom.php');			 // Custom Functions
require_once ($functions_path . 'shortcodes.php');		 // Theme Shortcodes
require_once ($functions_path . 'loadJS.php');			 // Load Javascript
														 
// Load Admin
require_once ($admin_path . 'authors.php');				 // Author Settings
require_once ($admin_path . 'post_types/sermons.php');	 // Sermon Post Type
require_once ($admin_path . 'post_types/ministries.php');// Ministry Post Type
require_once ($admin_path . 'post_types/devotions.php'); // Devotion Post Type
require_once ($admin_path . 'post_types/radio.php');	 // Radio Post Type
require_once ($admin_path . 'post_types/posts.php');	 // Custom Post Options
require_once ($admin_path . 'pages/contact.php');		 // Contact Options
require_once ($admin_path . 'pages/event.php');			 // Event Options
require_once ($admin_path . 'pages/new_here.php');		 // New Here Options

// Load Custom Templates
$single_templates = array('sermon', 'ministry');

add_filter( 'template_include', function( $template ) use ( $single_templates ) {

	global $post;
	
	echo 'post type = '.json_encode($post);
	
	if( !empty($post) ) {
		$post_type = $post->post_type;
		
		if( in_array( $post_type, $single_templates ) ) {
		
			if( is_post_type_archive( $post_type ) ) {
	
				return __DIR__ . '/templates/archives/'. $post_type .'.php';
			}		
			return __DIR__ . '/templates/post_types/' . $post_type . '.php';
		}
	}

	return $template;
});

$tax_templates = array('series');

// loads taxonomy templates
add_filter( 'template_include', function( $template ) use ( $tax_templates ) {

	$taxonomy = get_query_var( 'taxonomy' );

	if( in_array( $taxonomy, $tax_templates ) ) {
		
		$new_template = __DIR__ . '/templates/taxonomies/'. $taxonomy .'.php';
	
		if ( !empty($new_template) ) {
			return $new_template ;
		}
	}

	return $template;
});

// Load Stylesheets
function oe_enqueue_styles() {
	// Default Styles
	wp_register_style( 'default', THEME_DIR . '/css/app.css');
	wp_enqueue_style('default');
}
add_action( 'wp_enqueue_scripts', 'oe_enqueue_styles' );

// Register Google Web Fonts
function load_fonts() {
	wp_register_style( 'googlefonts', ('https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700'));
	wp_enqueue_style( 'googlefonts' );
}
add_action( 'wp_enqueue_scripts', 'load_fonts' );
