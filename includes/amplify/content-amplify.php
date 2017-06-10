<?php

namespace amp_child_theme;

require_once( __THEME__DIR__ . '/includes/utils/class-amp-dom-utils.php' );
require_once( __THEME__DIR__ . '/includes/utils/class-amp-html-utils.php' );
require_once( __THEME__DIR__ . '/includes/utils/class-amp-string-utils.php' );
require_once( __THEME__DIR__ . '/includes/utils/class-amp-wp-utils.php' );

require_once( __THEME__DIR__ . '/includes/filters/class-amp-style-filter.php' );
require_once( __THEME__DIR__ . '/includes/filters/class-amp-blacklist-filter.php' );
require_once( __THEME__DIR__ . '/includes/filters/class-amp-tag-and-attribute-filter.php' );
require_once( __THEME__DIR__ . '/includes/filters/class-amp-img-filter.php' );
require_once( __THEME__DIR__ . '/includes/filters/class-amp-video-filter.php' );
require_once( __THEME__DIR__ . '/includes/filters/class-amp-iframe-filter.php' );
require_once( __THEME__DIR__ . '/includes/filters/class-amp-audio-filter.php' );
require_once( __THEME__DIR__ . '/includes/filters/class-amp-playbuzz-filter.php' );

require_once( __THEME__DIR__ . '/includes/embeds/class-amp-twitter-embed.php' );
require_once( __THEME__DIR__ . '/includes/embeds/class-amp-youtube-embed.php' );
require_once( __THEME__DIR__ . '/includes/embeds/class-amp-dailymotion-embed.php' );
require_once( __THEME__DIR__ . '/includes/embeds/class-amp-soundcloud-embed.php' );
require_once( __THEME__DIR__ . '/includes/embeds/class-amp-gallery-embed.php' );
require_once( __THEME__DIR__ . '/includes/embeds/class-amp-instagram-embed.php' );
require_once( __THEME__DIR__ . '/includes/embeds/class-amp-vine-embed.php' );
require_once( __THEME__DIR__ . '/includes/embeds/class-amp-facebook-embed.php' );
require_once( __THEME__DIR__ . '/includes/embeds/class-amp-pinterest-embed.php' );

require_once (get_stylesheet_directory() . '/includes/embeds/class-amp-embed-handlers.php');
require_once (get_stylesheet_directory() . '/includes/filters/class-amp-filters.php');

class AMP_Content_Amplifier {

	private $content = '';
	private $amp_content = '';
	private $amp_scripts = array();
	private $amp_styles = array();
	private $embed_handler_classes = array();
	private $embed_handlers = array();
	private $filter_classes = array();

	public function __construct( $content, $embed_handler_classes, $filter_classes, $args = array() ) {
		$this->content               = $content;
		$this->args                  = $args;
		$this->embed_handler_classes = $embed_handler_classes;
		$this->filter_classes        = $filter_classes;
		$this->register_embed_handlers();
	}

	private function add_scripts( $scripts ) {
		$this->amp_scripts = array_merge( $this->amp_scripts, $scripts );
	}

	private function add_styles( $styles ) {
		$this->amp_styles = array_merge( $this->amp_styles, $styles );
	}

	public function get_scripts() {
		return $this->amp_scripts;
	}

	public function get_styles( ) {
		return $this->amp_styles;
	}

	public function enqueue_scripts() {
		foreach ($this->amp_scripts as $handle => $script) {
			wp_enqueue_script( $handle, $script, array(), false, false );
		}
	}

	public function register_embed_handlers() {
		$embed_handlers = array();

		foreach ( $this->embed_handler_classes as $embed_handler_class => $args ) {
			$embed_handler = new $embed_handler_class( array_merge( $this->args, $args ) );

			if ( ! is_subclass_of( $embed_handler, '\amp_child_theme\AMP_Base_Embed_Handler' ) ) {
				_doing_it_wrong( __METHOD__, sprintf( esc_html__( 'Embed Handler (%s) must extend `AMP_Base_Embed_Handler`', 'amp' ), $embed_handler_class ), '0.1' );
				continue;
			}

			$embed_handler->register_embed();
			$embed_handlers[] = $embed_handler;
		}

		$this->embed_handlers = $embed_handlers;
	}

	private function grab_embed_handler_scripts( ) {
		foreach ( $this->embed_handlers as $embed_handler ) {
			$this->add_scripts( $embed_handler->get_scripts() );
//			$embed_handler->unregister_embed();
		}
	}

	private function amplify( $content, $filter_classes, $global_args = array() ) {

		$scripts = array();
		$styles = array();
		$dom = AMP_DOM_Utils::get_dom_from_content( $content );

		foreach ( $filter_classes as $filter_class => $args ) {
			if ( ! class_exists( $filter_class ) ) {
				_doing_it_wrong( __METHOD__, sprintf( esc_html__( 'Filter (%s) class does not exist', 'amp' ), esc_html( $filter_class ) ), '0.4.1' );
				continue;
			}

			$filter = new $filter_class( $dom, array_merge( $global_args, $args ) );

			if ( ! is_subclass_of( $filter_class, 'amp_child_theme\AMP_Base_Filter' ) ) {
				_doing_it_wrong( __METHOD__, sprintf( esc_html__( 'Filter (%s) must extend `AMP_Base_Filter`', 'amp' ), esc_html( $filter_class ) ), '0.1' );
				continue;
			}

			$filter->sanitize();

			$scripts = array_merge( $this->amp_scripts, $filter->get_scripts() );
			$styles = array_merge( $styles, $filter->get_styles() );
		}

		$filtered_content = AMP_DOM_Utils::get_content_from_dom( $dom );

		$this->add_scripts( $scripts );
		$this->add_styles( $styles );

		return $filtered_content;

	}

	public function transform($content) {
		$this->amp_content = $this->amplify( $content, $this->filter_classes, $this->args );
		$this->grab_embed_handler_scripts();
		$this->enqueue_scripts();
		return $this->amp_content;
	}
}

/**
 * @param $content
 * Filter function hooked to the 'the_content' filter
 * @return Amplified content
 */

function filter_the_content_in_the_main_loop( $content ) {

	$amplified_content = $GLOBALS['content_amplifier']->transform($content);

	if ( get_theme_mod( 'markup' ) == "AMP" ) {
		// Dequeue external/custom JS scripts
		$enqueued_scripts = wpcustom_inspect_scripts();
		foreach ( $enqueued_scripts as $script ) {
			if ( substr( $script, 0, 4 ) !== "amp-" ) {
				wp_dequeue_script( $script );
			}
		}
		// Dequeue external/custom JS styles
		$enqueued_styles = wpcustom_inspect_styles();
		foreach ( $enqueued_styles as $style ) {
			if ( substr( $style, 0, 4 ) !== "amp-" ) {
				wp_dequeue_style( $style );
			}
		}
	}
	return $amplified_content;
}
add_filter( 'the_content', '\amp_child_theme\filter_the_content_in_the_main_loop' );