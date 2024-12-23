<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://jonldavis.net
 * @since             1.0.0
 * @package           Festweb
 *
 * @wordpress-plugin
 * Plugin Name:       festweb
 * Plugin URI:        https://jonldavis.net
 * Description:       Display info for a music festival
 * Version:           1.0.0
 * Author:            Jon Davis
 * Author URI:        https://jonldavis.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       festweb
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
define( 'FESTWEB_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-festweb-activator.php
 */
function activate_festweb() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-festweb-activator.php';
	Festweb_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-festweb-deactivator.php
 */
function deactivate_festweb() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-festweb-deactivator.php';
	Festweb_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_festweb' );
register_deactivation_hook( __FILE__, 'deactivate_festweb' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-festweb.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_festweb() {

	$plugin = new Festweb();
	$plugin->run();

}
run_festweb();
