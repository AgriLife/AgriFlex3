<?php

class AgriFlex_ThemeCustomizer {

  public function __construct() {

    if( defined('AG_EXTUNIT_DIRNAME') || defined('AG_EXT_DIRNAME') || defined('AG_COL_DIRNAME') ){

      // Add custom image size 1280 x 215 with a crop top center
      add_image_size( 'agriflex_background_image', 1280, 215, array( 'center', 'top') );

      // Add theme customization controls
      add_action( 'customize_register', array( $this, 'agriflex_customize_register' ) );

      // Embed styles
      add_action( 'wp_head', array( $this, 'agriflex_customize_background' ) );

      if( defined('AG_COL_DIRNAME') ){

        // Use separate wrapper elements for image and gradient
        add_action( 'genesis_before', array( $this, 'agriflex_college_background' ) );

      }

      // Add class to apply default background gradient
      add_filter( 'body_class', array( $this, 'agriflex_background_bodyclass' ) );

    } else {

      // Add class to apply default background gradient
      add_filter( 'body_class', array( $this, 'agriflex_background_bodyclass' ) );

    }

  }

  // Add Customization Controls
  public function agriflex_customize_register($wp_customize){

    // Inform users of ideal background image properties
    $bgimage_message = 'Recommendation: 1280px width';
    if( defined('AG_COL_DIRNAME') )
      $bgimage_message .= ', grayscale';

    // A group of options for the control
    $wp_customize->add_section(
      'agriflex_background_options',
      array(
        'title'     => 'Background Image',
        'priority'  => 30,
        'description' => __($bgimage_message, 'agriflex3')
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

  /* Use the control's data in the theme.
  ------------------------------------------ */
  // Add CSS
  public function agriflex_customize_background() {
    
    global $wpdb;

    // Check for presence of custom background image
    $background_image_url = get_theme_mod('agriflex_background_image');

    if( $background_image_url != '' && 0 < count( strlen( $background_image_url ) ) ){
      
      $image_id = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $background_image_url ));

      if($image_id != null){

        // Image is in media folder.
        // Check for image size preference.
        $cropped = boolval( get_theme_mod( 'agriflex_background_crop' ) );

        if($cropped === true){

          $src = wp_get_attachment_image_src( $image_id[0], 'agriflex_background_image');

          if( $src ){
            $background_image_url = $src[0];
          }

        } else {

          $src = wp_get_attachment_image_src( $image_id[0], 'full' );

        }

      } else {

        // Theme image, no data available.
        $data = getimagesize( $background_image_url );
        $src = array( $background_image_url, $data[0], $data[1] );

      }

      // Render styles
      ?>
      <style type="text/css">
      <?php

      if( defined('AG_COL_DIRNAME') ){

        ?>
        @media only screen and (min-width: 880px) {
          .agriflex-college-background {
            background-image: url("<?php echo $background_image_url; ?>");
          }
        }
        <?php

        $style = '@media only screen and (min-width:%spx){.agriflex-college-background{background-image:url("%s");height:%spx;}}';
        $ratio = $src[2] / $src[1];
        $breakpoints = array(1140, 1340, 1540, 1740, 1905);

        foreach($breakpoints as $size){
          $height = round( $size * $ratio );
          echo sprintf($style, $size, $src[0], $height);
        }

      } else {

        ?>
        @media only screen and (min-width: 880px) {
          body.home .site-container,
          body .site-container {
            background-image: url("<?php echo $background_image_url; ?>");
          }
        }
        <?php
      }
      ?>
      </style>
      <?php

    }

  }

  // Add custom wrappers for gradient overlay
  public function agriflex_college_background() {

    $background_image_url = get_theme_mod('agriflex_background_image');

    if( $background_image_url != '' && 0 < count( strlen( $background_image_url ) ) ){

      ?>
        <div class="agriflex-college-background"><div class="agriflex-college-background-gradient"></div></div>
      <?php

    }

  }

  // Add custom class to denote absence of custom background
  public function agriflex_background_bodyclass( $classes ){

    $background_image_url = get_theme_mod('agriflex_background_image');

    if( $background_image_url == '' || 0 == count( strlen( $background_image_url ) ) ){
      
      $classes[] = 'agriflex-no-custom-bg';

    }

    return $classes;

  }
  
}
