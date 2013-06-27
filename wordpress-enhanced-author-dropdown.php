<?php
/**
 * Plugin Name:         Enhanced Author Dropdown
 * Plugin URI:          http://www.chriscct7.com
 * Description:         Enhance the standard dropdown for authors on posts in WordPress
 * Author:              Chris Christoff
 * Author URI:          http://www.chriscct7.com
 *
 * Contributors:        sunnyratilal, pippinsplugins
 *
 * Version:             1.0
 * Requires at least:   3.5.0
 * Tested up to:        3.6 Beta 4
 *
 * Text Domain:         ead
 * Domain Path:         /languages/
 *
 * @category            Plugin
 * @copyright           Copyright © 2013 Chris Christoff
 * @author              Chris Christoff
 * @package             WPEAD
 */

/* Define an absolute path to our plugin directory. */
if ( ! defined( 'EAD_PLUGIN_DIR' ) ) define( 'EAD_PLUGIN_DIR', trailingslashit( dirname( __FILE__ ) ));
if ( ! defined( 'EAD_ASSETS_URL' ) ) define( 'EAD_ASSETS_URL', trailingslashit( plugins_url( 'WPEAD' , __FILE__ ) ) );
if ( ! defined( 'EAD_VERSION' ) ) define( 'EAD_VERSION', '1.0' );

/**
 * Main WPEAD class
 *
 * @package WPEAD
 * @since   1.0
 * @author  Chris Christoff
 */
class EAD_Main {
	/**
	 * @static
	 * @var object Holds the options
	 * @since 1.0
	 */
	public static $wpead_options;
	public static $id = 'ead';

	/**
	 * Constructor
	 *
	 * @access protected
	 * @since  1.0
	 * @return void
	 */
	public function __construct() {
		$this->title = __( 'Enhanced Author Dropdown', 'ead' );

		add_action( 'admin_init',     array( $this, 'load_textdomain_for_ead' ) );

		/*
		 * We must use wp_loaded here. "plugins_loaded" is too early, even at priority
		 * 9999 since plugin registered taxonomies aren't registered in time.
		 */
		add_action( 'wp_loaded',      array( $this, 'load_settings'             ) );
		add_action( 'plugins_loaded', array( $this, 'include_core'              ) );

		// Start a PHP session, if not yet tarted
		if ( ! session_id() ) session_start();
	}

	/**
	 * Load the textdomain
	 *
	 * @since  1.0
	 * @access public
	 * @return void
	 */
	public function load_textdomain_for_ead() {
		load_plugin_textdomain( 'ead', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Set static $wpead_options to hold options class
	 */
	public function load_settings() {
		if ( empty( self::$wpead_options ) ) {
			require_once EAD_PLUGIN_DIR . '/settings/classes/sf-class-settings.php';
			self::$wpead_options = new SF_Settings_API( self::$id, $this->title, 'options-general.php', __FILE__ );
			self::$wpead_options->load_options( EAD_PLUGIN_DIR . '/settings/sf-options.php' );
		}
	}

	/**
	 * Include core files
	 */
	public function include_core() {
		require_once EAD_PLUGIN_DIR . 'class-frontend.php';
		$GLOBALS['EAD_Frontend'] = new EAD_Frontend;
	}
}

$GLOBALS['EAD_Main'] = new EAD_Main;