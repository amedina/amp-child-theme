<?php
//
// TODO(@amedina): invoke from here the functionality of creating the
// TODO(@amedina):AMP_Content class and generating the AMP content
add_filter( 'the_content', 'filter_the_content_in_the_main_loop' );
function filter_the_content_in_the_main_loop( $content ) {
	// Check if we're inside the main loop in a single post page.
	if ( is_single() && in_the_loop() && is_main_query() ) {
		if ( get_theme_mod( 'markup' ) == "AMP" ) {
			return $content . '<span style="color:red">' . "Content passed through the AMP filter!" . '</span>';
		} else {
			return $content . '<span style="color:blue">' . "Content was NOT passed through the AMP filter!" . '</span>';
		}
	}
	return $content;
}