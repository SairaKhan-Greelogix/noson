<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              Greelogix.com
 * @since             1.0.0
 * @package           Noson_Order_Tracking_Gl
 *
 * @wordpress-plugin
 * Plugin Name:       Noson Order Tracking
 * Plugin URI:        https://dev.noson.ch/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Greelogix
 * Author URI:        Greelogix.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       noson-order-tracking-gl
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NOSON_ORDER_TRACKING_GL_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-noson-order-tracking-gl-activator.php
 */
function activate_noson_order_tracking_gl() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-noson-order-tracking-gl-activator.php';
	Noson_Order_Tracking_Gl_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-noson-order-tracking-gl-deactivator.php
 */
function deactivate_noson_order_tracking_gl() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-noson-order-tracking-gl-deactivator.php';
	Noson_Order_Tracking_Gl_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_noson_order_tracking_gl' );
register_deactivation_hook( __FILE__, 'deactivate_noson_order_tracking_gl' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-noson-order-tracking-gl.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_noson_order_tracking_gl() {

	$plugin = new Noson_Order_Tracking_Gl();
	$plugin->run();

}
run_noson_order_tracking_gl();
