<?php
/**
 * AgriFlex3
 * @package      AgriFlex3
 * @since        1.0.0
 * @copyright    Copyright (c) 2013, Texas A&M AgriLife
 * @license      GPL-2.0+
 */


// Initialize Genesis
require_once TEMPLATEPATH . '/lib/init.php';

// Define some useful constants
define( 'AF_THEME_DIRNAME', 'AgriFlex3' );
define( 'AF_THEME_DIRPATH', get_stylesheet_directory() );
define( 'AF_THEME_DIRURL', get_stylesheet_directory_uri() );
define( 'AF_THEME_TEXTDOMAIN', 'agriflex' );

// Autoload all classes
spl_autoload_register( 'AgriFlex::autoload' );

// Intercept theme activation to ensure dependencies are installed and active first
$af_themeintercept = new AgriFlex_ThemeEnsureDependencies;

class AgriFlex {

	private static $file = __FILE__;

	private static $instance;

	private function __construct() {

		add_action( 'init', array( $this, 'init' ) );

    add_theme_support( 'html5', array() );

		// Speed up rss feed cache refresh.
		add_filter( 'wp_feed_cache_transient_lifetime', array( $this, 'rss_widget_refresh_interval' ) );

		// Make Feedzy use the smaller of the first two enclosure images in an RSS feed item.
		add_filter( 'feedzy_retrieve_image', array( $this, feedzy_retrieve_image ), 11, 2 );

    // remove_filter( 'genesis_attr_nav-link-wrap', 'genesis_attributes_nav_link_wrap' );

	}

	/**
	 * Initialize the various classes
	 * @since 1.0
	 * @return void
	 */
	public function init() {

		// Get Genesis setup the way we want it
		$af_genesis = new AgriFlex_Genesis;

		// Enqueue our assets
		$af_assets = new AgriFlex_Assets;

		// Fix the navigation
		$af_navigation = new AgriFlex_Navigation;

    // Add AgriLife Required DOM Elements
    $af_required = new AgriFlex_RequiredDOM;

    // Add AgriFlex theme customization
    $af_customize = new AgriFlex_ThemeCustomizer;

	}

	/**
	 * Speed up widget refresh interval.
	 *
	 * @since 1.6.5
	 * @param int $seconds The current refresh rate in seconds.
	 * @return int
	 */
	public function rss_widget_refresh_interval( $seconds ) {

		return 600;

	}

	/**
	 * Retrive image from the item object
	 *
	 * @since   1.6.6
	 *
	 * @param   string $the_thumbnail The thumbnail url.
	 * @param   object $item The item object.
	 *
	 * @return  string
	 */
	public function feedzy_retrieve_image( $the_thumbnail, $item ) {

		$data = $item->data;

		if ( array_key_exists( 'child', $data ) ) {

			$child = array_values( $data['child'] )[0];

			if ( count( $child['enclosure'] ) > 1 ) {

				$enclosure_a = array_values( $child['enclosure'][0]['attribs'] )[0];
				$enclosure_b = array_values( $child['enclosure'][1]['attribs'] )[0];
				$length_a    = $enclosure_a['length'];
				$length_b    = $enclosure_b['length'];

				if ( $length_b < $length_a ) {

					$the_thumbnail = $enclosure_b['url'];

				}
			}
		}

		return $the_thumbnail;

	}

	/**
	 * Autoloads any classes called within the theme
	 * @since 1.0
	 * @param  string $classname The name of the class
	 * @return void
	 */
	public static function autoload( $classname ) {

		$filename = dirname( __FILE__ ) .
      DIRECTORY_SEPARATOR .
      str_replace( '_', DIRECTORY_SEPARATOR, $classname ) .
      '.php';
    if ( file_exists( $filename ) )
      require $filename;

	}

	public static function get_instance() {

		return null == self::$instance ? new self : self::$instance;

	}

}

AgriFlex::get_instance();
