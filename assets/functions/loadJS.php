<?php

add_action( 'admin_enqueue_scripts', 'oe_admin_scripts' );
function oe_admin_scripts() {
	
	global $pagenow;
	
	wp_register_script( 'admin', THEME_DIR . '/assets/admin/js/pages.js', '', '', true);
	
	if( $pagenow === 'post.php' ) {	
		wp_enqueue_script( 'admin' );
	}

}

add_action( 'wp_enqueue_scripts', 'oe_enqueue_scripts' );
function oe_enqueue_scripts() {

// Load jQuery
	wp_deregister_script('jquery');
	wp_register_script('jquery', ('https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js'));
	wp_enqueue_script('jquery');

// Load Modernizr
	wp_register_script( 'modernizr', THEME_DIR . '/js/modernizr.js');
	wp_enqueue_script( 'modernizr' );

// Load PrefixFree
	wp_register_script( 'prefixfree', THEME_DIR . '/js/prefixfree.min.js');
	wp_enqueue_script( 'prefixfree' );

// Register Flex Slider
	wp_register_script( 'flexslider', THEME_DIR . '/js/jquery.flexslider-min.js', '', '', true);
	wp_register_script( 'flexsettings', THEME_DIR . '/js/flexsettings.js', '', '', true);
	
// Load Modal
	wp_enqueue_script( 'modal', THEME_DIR . '/js/modal.js');

// Load In Front Page
if ( is_home() ) {
	wp_enqueue_script( 'flexslider' );
	wp_enqueue_script( 'flexsettings' );
}

// Register Media Element
	wp_register_script( 'oe-mediaelement', THEME_DIR . '/js/mediaelement.js');
// Register Single JS
	wp_register_script( 'single', THEME_DIR . '/js/single.js', '', '', true);
// Register Sharrre
	wp_register_script( 'sharrre', THEME_DIR . '/js/sharrre.js', '', '', true);

// Load Taxonomy
if ( is_tax() || is_archive() || is_search() )	{
	wp_enqueue_script( 'grid', THEME_DIR . '/js/grid.js');
}

// Load Countdown
if ( is_page_template('templates/event.php') )	{
	wp_enqueue_script( 'countdown', THEME_DIR . '/js/jquery.countdown.min.js');
}

// Load Pajinate
	wp_register_script( 'pajinate', THEME_DIR . '/js/jquery.pajinate.min.js');
	if ( is_page( 'blog' ) || is_singular('post') || is_singular('devotion') || is_category() ) {
	    wp_enqueue_script( 'pajinate' );
	}

if( is_page_template('templates/new_here.php') || is_page_template('templates/event.php') ) {
// Load gMap
	wp_enqueue_script( 'map', THEME_DIR . '/js/map.js', '', '', true);
	wp_enqueue_script('gmap', ('https://maps.google.com/maps/api/js?sensor=false'));
}

// Load In Single Sermon
if ( 'post' == get_post_type() || 'sermon' == get_post_type() || 'ministry' == get_post_type() || is_page_template('templates/event.php') || is_page_template('templates/new_here.php') ) {
	wp_enqueue_script( 'oe-mediaelement' );
	wp_enqueue_script( 'sharrre' );
	wp_enqueue_script( 'single' );
}	
// Load Events
	wp_register_script( 'global', THEME_DIR . '/js/global.js', '', '', true);
	wp_enqueue_script( 'global' );
	
// Load Woocommerce
	wp_register_script( 'woocommerce-hacks', THEME_DIR . '/js/woocommerce.js', '', '', true);
	wp_enqueue_script( 'woocommerce-hacks' );
}