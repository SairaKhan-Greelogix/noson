<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       Greelogix.com
 * @since      1.0.0
 *
 * @package    Noson_Order_Tracking_Gl
 * @subpackage Noson_Order_Tracking_Gl/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Noson_Order_Tracking_Gl
 * @subpackage Noson_Order_Tracking_Gl/includes
 * @author     Greelogix <abuzer@greelogix.com>
 */
class Noson_Order_Tracking_Gl_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'noson-order-tracking-gl',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
