<!DOCTYPE HTML>
<?php
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE) echo 
'<!--[if lt IE 7]>     <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->';
?>
<head>
<title><?php bloginfo('name'); if ( is_singular() ) { the_title(' &#124; '); } ?></title>
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="robots" content="noodp, noydir" />
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php if( is_page_template('new-here.php') || is_page_template('event.php') ) echo 'onload="load()"'; ?>>
<?php
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE) echo
'<!--[if lt IE 7]>
<p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
<![endif]-->';
?>

<header id="main-header" class="group">
	<div id="header">
		<a href="<?php echo get_option('home'); ?>/" class="logo"></a>            
		<span id="search"></span>
		<span id="toggle"></span>
		<nav>
		<?php 
		wp_nav_menu(
			array(
				'theme_location' => 'header',
				'container' => false,
				'items_wrap' => '<ul id="%1$s">%3$s</ul>'
			)
		); 
		?>
		</nav>
	</div>
</header>

<div id="overlay">
    <?php get_search_form() ?>
    <a class="modal-close">&#215;</a>
</div>

<div id="content-wrap" class="group">