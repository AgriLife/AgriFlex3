<?php

/**
 * Loads required theme assets
 * @package AgriFlex3
 * @since 1.0
 */
class AgriFlex_AgencyAffiliation {

  public function __construct() {

    // Add fields
    require_once(AF_THEME_DIRPATH . '/fields/agrilife-data.php');

    // Add admin page
    add_action( 'admin_menu', array( $this, 'add_settings_page' ) );

  }

  public function add_settings_page() {

    acf_add_options_sub_page( array(
      'page_title'    => 'Agency Affiliation',
      'menu_title'    => 'Agency Affiliation',
      'menu_slug'     => 'agriflex3-settings',
      'capability'    => 'manage_options',
      'parent_slug'   => 'options-general.php'
    ) );

  }

}

