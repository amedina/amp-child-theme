<?php

namespace amp_child_theme;

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
define( '__THEME__FILE__', __FILE__ );
define( '__THEME__DIR__', dirname( __FILE__ ) );

/**
 *  Load content amplifier
 */
require get_stylesheet_directory() . '/includes/filters/class-amp-filters.php';
require get_stylesheet_directory() . '/includes/embeds/class-amp-embed-handlers.php';
require get_stylesheet_directory() . '/includes/amplify/content-amplify.php';

function amp_child_theme_setup() {

	$GLOBALS['amp_filters'] = new AMP_Filters();;
	$GLOBALS['amp_embed_handlers'] = new AMP_Embed_Handlers();
	$GLOBALS['content_amplifier'] = new AMP_Content_Amplifier(
		'',
		$GLOBALS['amp_embed_handlers']::get_embed_handlers(),
		$GLOBALS['amp_filters']::get_amp_filters(),
		array( 'content_max_width' => 600)
	);

	add_action( 'wp_head', '\amp_child_theme\amp_boilerplate_css', 0 );
	////add_action( 'wp_head', '\amp_child_theme\amp_custom_css', 0 );
	add_action( 'wp_head', '\amp_child_theme\ampruntime_js', 0 );
	add_action( 'wp_head', '\amp_child_theme\amp_social_share_js', 0 );
}
add_action( 'after_setup_theme', '\amp_child_theme\amp_child_theme_setup' );


function ampruntime_js() {
//	echo '<script async src="https://cdn.ampproject.org/v0.js"></script>';
	wp_enqueue_script( 'amp-runtime', 'https://cdn.ampproject.org/v0.js' );
}

function amp_boilerplate_css() {
	echo '<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>';
}

function amp_social_share_js() {
//	echo '<script custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js" async=""></script>';
	wp_enqueue_script( 'amp-social-share', 'https://cdn.ampproject.org/v0/amp-social-share-0.1.js');
}

//
// TODO (amedina) this function is loading the @import statements: FIX needed.
//
function amp_custom_css() {
	$styles = wp_get_custom_css();
	$safe_styles = '';
	if ( $styles || is_customize_preview() ) {
		$safe_styles = strip_tags( $styles );
	}
	echo '<style amp-custom>' .file_get_contents(get_stylesheet_uri()) . $safe_styles . '</style>';
}

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

function wpcustom_inspect_scripts() {
	global $wp_scripts;
	$enqueued_scripts = array();

	// Runs through the queue scripts
	foreach( $wp_scripts->queue as $handle ) :
		array_push($enqueued_scripts, $handle);
	endforeach;

	return $enqueued_scripts;
}

function wpcustom_inspect_styles() {

	global $wp_styles;
	$enqueued_styles = array();

	// Runs through the queue styles
	foreach( $wp_styles->queue as $handle ) :
		array_push($enqueued_styles, $handle);
	endforeach;

	return $enqueued_styles;
}
//add_action( 'wp_print_scripts', 'wpcustom_inspect_scripts_and_styles' );