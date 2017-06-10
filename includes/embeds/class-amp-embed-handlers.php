<?php

namespace amp_child_theme;

class AMP_Embed_Handlers {
	private static $embeds = array(
		'\amp_child_theme\AMP_Twitter_Embed_Handler'     => array(),
		'\amp_child_theme\AMP_YouTube_Embed_Handler'     => array(),
		'\amp_child_theme\AMP_DailyMotion_Embed_Handler' => array(),
		'\amp_child_theme\AMP_SoundCloud_Embed_Handler'  => array(),
		'\amp_child_theme\AMP_Instagram_Embed_Handler'   => array(),
		'\amp_child_theme\AMP_Vine_Embed_Handler'        => array(),
		'\amp_child_theme\AMP_Facebook_Embed_Handler'    => array(),
		'\amp_child_theme\AMP_Pinterest_Embed_Handler'   => array(),
		'\amp_child_theme\AMP_Gallery_Embed_Handler'     => array(),
	);

	public static function get_embed_handlers() {
		return AMP_Embed_Handlers::$embeds;
	}
}