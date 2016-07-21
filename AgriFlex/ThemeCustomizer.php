<?php

class AgriFlex_ThemeCustomizer {

  public function __construct() {

    add_action( 'customize_register', array( $this, 'agriflex_customize_register' ));

    add_action( 'wp_head', array( $this, 'agriflex_customize_background' ));

    // Add custom image size 1280 x 215 with a crop top center
    if(defined('AG_EXTUNIT_DIRNAME') || defined('AG_EXT_DIRNAME')){
      add_image_size( 'agriflex_background_image', 1280, 215, array( 'center', 'top') );
    }

  }

  // Add Customization Control
  public function agriflex_customize_register($wp_customize){
    // A group of options for the control
    if(defined('AG_EXTUNIT_DIRNAME') || defined('AG_EXT_DIRNAME')){
      $wp_customize->add_section(
        'agriflex_background_options',
        array(
          'title'     => 'Background Image',
          'priority'  => 30,
          'description' => __('Recommended width of 1280px', 'agriflex3')
        )
      );
      // Register the option's data for the control
      $wp_customize->add_setting(
        'agriflex_background_image',
        array(
          'default'      => AF_THEME_DIRURL . '/img/ext-bg/headline-bg-noblur.jpg',
          'transport'    => 'refresh',
        )
      );
      // Display a control in Appearance > Customize
      $wp_customize->add_control(
        new WP_Customize_Image_Control(
          $wp_customize,
          'agriflex_background_image',
          array(
            'label'    => '',
            'settings' => 'agriflex_background_image',
            'section'  => 'agriflex_background_options'
          )
        )
      );
      // Register option to crop image
      $wp_customize->add_setting(
        'agriflex_background_crop',
        array(
          'default'     => false,
        )
      );
      $wp_customize->add_control(
        'agriflex_backgroundoverflow',
        array(
          'settings'   => 'agriflex_background_crop',
          'label'      => __( 'Cropped', 'cropped' ),
          'section'    => 'agriflex_background_options',
          'type'       => 'checkbox'
        )
      );
    }
  }

  /* Use the control's data in the theme.
  ------------------------------------------ */
  // Add CSS
  public function agriflex_customize_background() {
    
    global $wpdb;

    if ( get_theme_mod('agriflex_background_image') != '' && 0 < count( strlen( ( $background_image_url = get_theme_mod( 'agriflex_background_image' ) ) ) ) ) {

      // Check for image size preference
      $cropped = boolval( get_theme_mod( 'agriflex_background_crop' ) );
      if($cropped === true){

        $image_url = get_theme_mod('agriflex_background_image');
        $image_id = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
        $src = wp_get_attachment_image_src( $image_id[0], 'agriflex_background_image');

        if( $src ){
          $background_image_url = $src[0];
        }

      }
      ?>
      <style type="text/css">
        @media only screen and (min-width: 880px) {
          body.home .site-container,
          body .site-container {
            background-image: url(<?php echo $background_image_url; ?>) !important;
          }
        }
      </style>
      <?php

    }

  }
  
}
