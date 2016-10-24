<?php

/**
 * Ensure dependencies are present before allowing theme activation
 * @package AgriFlex3
 * @since 1.1.6
 */
class AgriFlex_ThemeEnsureDependencies
{

  public function __construct()
  {

    // Intercept theme switch
    add_action( 'after_switch_theme', array( $this, 'check_theme_dependencies' ), 10, 2 );

  }

  /**
   * Returns missing plugin names
   * @since 1.1.6
   * @return array
   */
  private function get_missing_plugins(){

    // Folder names of plugins
    $neededplugins = array(
      'AgriLife Core' => 'agrilife-core',
      'Advanced Custom Fields' => 'advanced-custom-fields'
    );
    // Directories of each plugin's main file
    $siteplugins = array_map( 'strtolower', get_option('active_plugins') );
    $networkplugins = array();
    if(function_exists('wp_get_active_network_plugins')){
      $networkplugins = array_map( 'strtolower', str_replace( WP_PLUGIN_DIR . '/', '', wp_get_active_network_plugins() ) );
    }
    $activeplugins = array_merge( $siteplugins, $networkplugins );

    // Missing plugins
    $missing = array();

    foreach($neededplugins as $key => $value){

      // Test each needed plugin against all active plugins
      $found = false;

      foreach($activeplugins as $plugin){

        // Compare needed plugin name to each active plugin name
        $match = preg_match('/^' . $value . '/', $plugin);
        if($match){
          // Needed plugin is active
          $found = true;
        }
      }

      // If needed plugin is not active, return its name
      if(!$found){
        $missing[$key] = $value;
      }

    }

    // Return array of missing plugin names
    return $missing;

  }

  /**
   * Revert theme and display error message if dependencies not met
   * @since 1.1.6
   * @return void
   */
  public function check_theme_dependencies( $oldtheme_name, $oldtheme ) {

    // Get array of missing plugins
    $missing_plugins = $this->get_missing_plugins();

    if ( count( $missing_plugins ) > 0 ){

      // Update default admin notice: Theme not activated.
      add_filter( 'gettext', array( $this, 'update_activation_admin_notice' ), 10, 3 );

      // Custom styling for default admin notice.
      add_action( 'admin_head', array( $this, 'error_activation_admin_notice' ) );

      // Switch back to previous theme.
      switch_theme( $oldtheme->stylesheet );
        return false;

    }

  }

  /**
   * Replace success message with error
   * @since 1.1.6
   * @return void
   */
  public function update_activation_admin_notice( $translated, $original, $domain ) {

    $message = 'AgriFlex3 Error. The following plugin(s) are not installed and active: %s. Restoring previous theme.';

    // Specify error
    $error = array();

    // Get array of missing plugins
    $missing_plugins = $this->get_missing_plugins();
    foreach($missing_plugins as $key => $value){
      $error[] = $key;
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

  /**
   * Style success message as error
   * @since 1.1.6
   * @return void
   */
  public function error_activation_admin_notice() {

    echo '<style>#message2{border-left-color:#dc3232;}</style>';

  }

}