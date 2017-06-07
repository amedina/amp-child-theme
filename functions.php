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