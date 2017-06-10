<?php

namespace amp_child_theme;

class AMP_Filters {
	private static $filters = array(
		'\amp_child_theme\AMP_Style_Filter'             => array(),
		'\amp_child_theme\AMP_Img_Filter'               => array(),
		'\amp_child_theme\AMP_Video_Filter'             => array(),
		'\amp_child_theme\AMP_Audio_Filter'             => array(),
		'\amp_child_theme\AMP_Playbuzz_Filter'          => array(),
		'\amp_child_theme\AMP_Iframe_Filter'            => array( 'add_placeholder'=> true, ),
		'\amp_child_theme\AMP_Tag_And_Attribute_Filter' => array(),
	);

	public static function get_amp_filters() {
		return AMP_Filters::$filters;
	}
}