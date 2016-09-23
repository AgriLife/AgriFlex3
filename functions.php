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

// Check for dependencies
add_action( 'after_switch_theme', 'check_theme_dependencies', 10, 2 );
function check_theme_dependencies( $oldtheme_name, $oldtheme ) {

  $missing_dependencies = false;

  $agrilifecore_installed = defined('AG_CORE_DIRNAME');
  $acf_installed = count(glob(get_theme_root() . '/[aA]dvanced-[cC]ustom-[fF]ields', GLOB_ONLYDIR)) > 0;

  if( !$agrilifecore_installed || !$acf_installed ){
    $missing_dependencies = true;
  }

  if ( $missing_dependencies ){

    // Update default admin notice: Theme not activated.
    add_filter( 'gettext', 'update_activation_admin_notice', 10, 3 );

    // Custom styling for default admin notice.
    add_action( 'admin_head', 'error_activation_admin_notice' );

    // Switch back to previous theme.
    switch_theme( $oldtheme->stylesheet );
      return false;

  }
}

function update_activation_admin_notice( $translated, $original, $domain ) {

  $message = 'AgriFlex3 Error: %s. Restoring previous theme.';

  // Specify error
  $error = array();
  $agrilifecore_installed = defined('AG_CORE_DIRNAME');
  $acf_installed = count(glob(get_theme_root() . '/[aA]dvanced-[cC]ustom-[fF]ields', GLOB_ONLYDIR)) > 0;

  if(!$agrilifecore_installed){
    $error[] = 'AgriLife Core plugin is not installed and active';
  }
  if(!$acf_installed){
    $error[] = 'Advanced Custom Fields plugin is not installed and active';
  }

  $message = sprintf($message, implode($error, ', '));
  
  $strings = array(
    'New theme activated.' => $message
  );

  if ( isset( $strings[$original] ) ) {
    // Translate but without running all the filters again.
    $translations = get_translations_for_domain( $domain );
    $translated = $translations->translate( $strings[$original] );
  }

  return $translated;
}

function error_activation_admin_notice() {
  echo '<style>#message2{border-left-color:#dc3232;}</style>';
}

// Autoload all classes
spl_autoload_register( 'AgriFlex::autoload' );

class AgriFlex {

	private static $file = __FILE__;

	private static $instance;

	private function __construct() {

		add_action( 'init', array( $this, 'init' ) );
    
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
