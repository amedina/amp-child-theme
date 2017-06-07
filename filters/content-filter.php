<?php

add_filter( 'the_content', 'filter_the_content_in_the_main_loop' );
function filter_the_content_in_the_main_loop( $content ) {
	// Check if we're inside the main loop in a single post page.
	if ( is_single() && in_the_loop() && is_main_query() ) {
		return $content . '<span style="color:red">' . "I'm filtering the content inside the main loop" . '</span>';
	}
	return $content;
}