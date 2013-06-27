<?php
/**
 * Plugin Name:         WordPress Enhanced Author Dropdown
 * Plugin URI:          http://www.chriscct7.com
 * Description:         Enhance the standard dropdown for authors on posts in WordPress
 * Author:              Chris Christoff
 * Author URI:          http://www.chriscct7.com
 *
 * Contributors:		sunnyratilal, pippinsplugins 
 *
 * Version:             1.0
 * Requires at least:   3.5.0
 * Tested up to:        3.6 Beta 3
 *
 * Text Domain:         wpead
 * Domain Path:         /languages/
 *
 * @category            Plugin
 * @copyright           Copyright © 2013 Chris Christoff
 * @author              Chris Christoff
 * @package             WPEAD
 */


	/* Define an absolute path to our plugin directory. */
	if ( !defined( 'wpead_plugin_dir' ) ) define( 'wpead_plugin_dir', trailingslashit( dirname( __FILE__ ) ));
	if ( !defined( 'wpead_assets_url' ) ) define( 'wpead_assets_url', trailingslashit( plugins_url( 'WPEAD' , __FILE__ ) ) );


	/**
	 * Main WPEAD class
	 *
	 * @package WPEAD
	 */
	class WPEAD_Main
	{

		public static $wpead_options;
		public static $id = 'wpead';

		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$this->title = __( 'WordPress Enhanced Author Dropdown', 'wpead' ); 

			add_action( 'admin_init', array( $this, 'load_textdomain_for_wpead' ) );
			/* 
			We must use wp_loaded here. "plugins_loaded" is too early, even at priority
			9999 since plugin registered taxonomies aren't registered in time.
			*/
			add_action( 'wp_loaded', array( $this, 'load_settings' ) );
			add_action( 'plugins_loaded', array( $this, 'include_core' ) );

			// Start a PHP session, if not yet started
			if ( ! session_id() ) session_start();
		}

		public function load_textdomain_for_wpead()
		{
			load_plugin_textdomain( 'wpead', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}


		/**
		 * Set static $wpead_options to hold options class
		 */
		public function load_settings()
		{
			if ( empty( self::$wpead_options ) ) {
				require_once wpead_plugin_dir . '/settings/classes/sf-class-settings.php';
				self::$wpead_options = new SF_Settings_API( self::$id, $this->title, 'options-general.php', __FILE__ );
				self::$wpead_options->load_options( wpead_plugin_dir . '/settings/sf-options.php' );
			}
		}


		/**
		 * Include core files
		 */
		public function include_core()
		{
			require_once wpead_plugin_dir . 'class-frontend.php';
			new WPEAD_Frontend;
		}
	}
	new WPEAD_Main;

