<?php

/**
 * Sets up Genesis Framework to our needs
 * @package AgriFlex3
 * @since 1.0
 */
class AgriFlex_Genesis {

	public function __construct() {

		// Add the responsive viewport
		$this->add_responsive_viewport();

		// Add the responsive viewport
		$this->add_accessibility();

		// Keep Genesis from loading any stylesheets
		$this->remove_stylesheet();

		// Force IE out of compatibility mode
		add_action( 'genesis_meta', array( $this, 'fix_compatibility_mode' ) );

		// Fix html5shiv
		remove_action( 'wp_head', 'genesis_html5_ie_fix' );
		add_action( 'wp_head', array( $this, 'html5shiv' ), 0);

		// Add respond.js
		add_action( 'wp_head', array( $this, 'respond_js' ), 40 );

		// Specify the favicon location
		add_filter( 'genesis_pre_load_favicon', array( $this, 'add_favicon' ) );

		// Create the structural wraps
		$this->add_structural_wraps();

		// Clean up the comment area
		add_filter( 'comment_form_defaults', array( $this, 'cleanup_comment_text' ) );

		// Remove profile fields
		add_action( 'admin_init', array( $this, 'remove_profile_fields' ) );

		// Remove unneeded layouts
		$this->remove_genesis_layouts();

		// Remove unneeded sidebars
		$this->remove_genesis_sidebars();

		// Move Genesis in-post SEO box to a lower position
		remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );
		add_action( 'admin_menu', array( $this, 'move_inpost_seo_box' ) );

		// Move Genesis in-post layout box to a lower position
		remove_action( 'admin_menu', 'genesis_add_inpost_layout_box' );
		add_action( 'admin_menu', array( $this, 'move_inpost_layout_box' ) );

		// Remove some Genesis settings metaboxes
		add_action( 'genesis_theme_settings_metaboxes', array( $this, 'remove_genesis_metaboxes' ) );

		// Add Read More excerpt link
		add_filter( 'excerpt_more', array( $this, 'agriflex_auto_excerpt_more' ) );

	}

	/**
	 * Adds the responsive viewport meta tag
	 * @since 1.0
	 * @return void
	 */
	private function add_responsive_viewport() {

		add_theme_support( 'genesis-responsive-viewport' );

	}

	/**
	 * Adds the responsive viewport meta tag
	 * @since 1.0
	 * @return void
	 */
	private function add_accessibility() {

		add_theme_support( 'genesis-accessibility', array( 'search-form' ) );

	}

	/**
	 * Removes any stylesheet Genesis may try to load
	 * @since 1.0
	 * @return void
	 */
	private function remove_stylesheet() {

		remove_action( 'genesis_meta', 'genesis_load_stylesheet' );

	}

	/**
	 * Forces IE out of compatibility mode
	 * @since 1.0
	 * @return void
	 */
	public function fix_compatibility_mode() {

		echo '<meta http-equiv="X-UA-Compatible" content="IE=Edge">';

	}

	/**
	 * Places html5shiv in the right place
	 * @since 1.0
	 * @return void
	 */
	public function html5shiv() { ?>

		<!--[if lt IE 9]>
	    <script type="text/javascript" src="<?php AF_THEME_DIRPATH . '/bower_components/html5shiv/dist/html5shiv.js'; ?>"></script>
		<![endif]-->

	<?php
	}

	/**
	 * Loads Respond.js when needed
	 * @since 1.0
	 * @return void
	 */
	public function respond_js() { ?>

		<!--[if lt IE 9]>
	    <script type="text/javascript" src="<?php AF_THEME_DIRPATH . '/bower_components/respond/dest/respond.min.js'; ?>"></script>
		<![endif]-->

	<?php
	}

	/**
	 * Changes the Genesis default favicon location
	 * @since 1.0
	 * @param string $favicon_url The default favicon location
	 * @return string
	 */
	public function add_favicon( $favicon_url ) {

		return AF_THEME_DIRURL . '/img/favicon.ico';

	}

	/**
	 * Adds structural wraps to the specified elements
	 * @since 1.0
	 * @return void
	 */
	private function add_structural_wraps() {

		add_theme_support(
			'genesis-structural-wraps',
			array(
				'header',
				'menu-primary',
				'site-inner',
				'footer',
			)
		);

	}


	/**
	 * Remove unneeded user profile fields
	 * @since 1.0
	 * @return void
	 */
	public function remove_profile_fields() {

		remove_action( 'show_user_profile', 'genesis_user_options_fields' );
		remove_action( 'edit_user_profile', 'genesis_user_options_fields' );
		remove_action( 'show_user_profile', 'genesis_user_archive_fields' );
		remove_action( 'edit_user_profile', 'genesis_user_archive_fields' );
		remove_action( 'show_user_profile', 'genesis_user_seo_fields'     );
		remove_action( 'edit_user_profile', 'genesis_user_seo_fields'     );
		remove_action( 'show_user_profile', 'genesis_user_layout_fields'  );
		remove_action( 'edit_user_profile', 'genesis_user_layout_fields'  );

	}

	/**
	 * Removes any layouts that we don't need
	 * @since 1.0
	 * @return void
	 */
	private function remove_genesis_layouts() {

		genesis_unregister_layout( 'content-sidebar-sidebar' );
		genesis_unregister_layout( 'sidebar-sidebar-content' );
    if(!defined('AG_EXT_DIRNAME') && !defined('AG_EXTUNIT_DIRNAME') ) {
      // Extension uses three column
  		genesis_unregister_layout( 'sidebar-content-sidebar' );
    }
    if(defined('AG_EXT_DIRNAME') ) {
      // Extension does not use side-content or content-sidebar
      //genesis_unregister_layout( 'content-sidebar' );
      //genesis_unregister_layout( 'sidebar-content' );
    }

	}

	/**
	 * Removes any default sidebars that we don't need
	 * @since 1.0
	 * @return void
	 */
	private function remove_genesis_sidebars() {

		if(!defined('AG_EXT_DIRNAME') && !defined('AG_EXTUNIT_DIRNAME') ) {
			unregister_sidebar( 'sidebar-alt' );
		}
		if(defined('AG_COL_DIR_PATH')){
			unregister_sidebar( 'header-right' );
		}

	}

	/**
	 * Cleans up the default comments text
	 * @since 1.0
	 * @param  array $args The default arguments
	 * @return array       The new arguments
	 */
	public function cleanup_comment_text( $args ) {

		$args['comment_notes_before'] = '';
		$args['comment_notes_after']  = '';

		return $args;

	}

	/**
	 * Moves the Genesis in-post SEO box to a lower position
	 * @since 1.0
	 * @author Bill Erickson
	 * @return void
	 */
	public function move_inpost_seo_box() {

		if ( genesis_detect_seo_plugins() )
			return;

		foreach ( (array) get_post_types( array( 'public' => true ) ) as $type ) {
			if ( post_type_supports( $type, 'genesis-seo' ) )
				add_meta_box( 'genesis_inpost_seo_box', __( 'Theme SEO Settings', AF_THEME_TEXTDOMAIN ), 'genesis_inpost_seo_box', $type, 'normal', 'default' );
		}

	}

	/**
	 * Moves the Genesis in-post layout box to a lower postion
	 * @since 1.0
	 * @return void
	 */
	public function move_inpost_layout_box() {

		if ( ! current_theme_supports( 'genesis-inpost-layouts' ) )
			return;

		foreach ( (array) get_post_types( array( 'public' => true ) ) as $type ) {
			if ( post_type_supports( $type, 'genesis-layouts' ) )
				add_meta_box( 'genesis_inpost_layout_box', __( 'Layout Settings', 'genesis' ), 'genesis_inpost_layout_box', $type, 'normal', 'default' );
		}

	}

	public function remove_genesis_metaboxes( $_genesis_theme_settings_pagehook ) {

		if ( ! is_super_admin() )
			remove_meta_box( 'genesis-theme-settings-version', $_genesis_theme_settings_pagehook, 'main' );

		//remove_meta_box( 'genesis-theme-settings-feeds',      $_genesis_theme_settings_pagehook, 'main' );
		//remove_meta_box( 'genesis-theme-settings-header',     $_genesis_theme_settings_pagehook, 'main' );
		remove_meta_box( 'genesis-theme-settings-nav',        $_genesis_theme_settings_pagehook, 'main' );
		//remove_meta_box( 'genesis-theme-settings-breadcrumb', $_genesis_theme_settings_pagehook, 'main' );
		//remove_meta_box( 'genesis-theme-settings-comments',   $_genesis_theme_settings_pagehook, 'main' );
		//remove_meta_box( 'genesis-theme-settings-posts',      $_genesis_theme_settings_pagehook, 'main' );
		//remove_meta_box( 'genesis-theme-settings-blogpage',   $_genesis_theme_settings_pagehook, 'main' );
		remove_meta_box( 'genesis-theme-settings-scripts',    $_genesis_theme_settings_pagehook, 'main' );

	}

	/**
	 * Adds the Read More link to post excerpts
	 */
	public function agriflex_auto_excerpt_more( $more ) {

		return '... <span class="read-more"><a href="' . get_permalink() . '">' .
    __( 'Read More &rarr;', 'agriflex' ) . '</a></span>';

  }

}
