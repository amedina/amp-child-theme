<?php

/*
 * Unlike style.css, the functions.php of a child theme does not override its
 * counterpart from the parent. Instead, it is loaded in addition to the parent’s
 * functions.php. (Specifically, it is loaded right before the parent’s file.)
 *
 * The functions.php of a child theme provides a smart, trouble-free method of
 * modifying the functionality of a parent theme.
 *
 * The fact that a child theme’s functions.php is loaded first means that
 * you can make the user functions of your theme pluggable —that is, replaceable
 * by a child theme— by declaring them conditionally.
 *
 */
/**
 *  Load content filters
 */
require get_stylesheet_directory() . '/filters/content-filter.php';

function ampruntime_js() {
	echo '<script async src="https://cdn.ampproject.org/v0.js"></script>';
}

function amp_boilerplate_css() {
	echo '<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>';
}

function amp_social_share_js() {
	echo '<script custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js" async=""></script>';
}

function amp_custom_css() {
	$styles = wp_get_custom_css();
	$safe_styles = '';
	if ( $styles || is_customize_preview() ) {
		$safe_styles = strip_tags( $styles );
	}

	echo '<style amp-custom>' .file_get_contents(get_stylesheet_uri()) . $safe_styles . '</style>';
}

add_action( 'wp_head', 'ampruntime_js', 0 );
add_action( 'wp_head', 'amp_boilerplate_css', 0 );
add_action( 'wp_head', 'amp_custom_css', 0 );
add_action( 'wp_head', 'amp_social_share_js', 0 );

function wpcustom_inspect_scripts_and_styles() {
	global $wp_scripts;
	global $wp_styles;
	$scripts_list = '';
	$styles_list = '';
	// Runs through the queue scripts
	foreach( $wp_scripts->queue as $handle ) :
		$scripts_list .= $handle . ' | ';
	endforeach;

	// Runs through the queue styles
	foreach( $wp_styles->queue as $handle ) :
		$styles_list .= $handle . ' | ';
	endforeach;
}
//add_action( 'wp_print_scripts', 'wpcustom_inspect_scripts_and_styles' );