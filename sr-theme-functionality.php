<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://stefan-reichert.com
 * @since             1.0.0
 * @package           sr_theme_functionality
 *
 * @wordpress-plugin
 * Plugin Name:       Theme Functionality
 * Plugin URI:        https://github.com/fanfarian/sr-theme-functionality
 * Description:       Functionality plugin with important settings, enhancements and fixes for WordPress themes
 * Version:           2.3.0
 * Author:            Stefan Reichert
 * Author URI:        http://stefan-reichert.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sr-theme-functionality
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sr-theme-functionality-activator.php
 */
function activate_sr_theme_functionality() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sr-theme-functionality-activator.php';
	sr_theme_functionality_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sr-theme-functionality-deactivator.php
 */
function deactivate_sr_theme_functionality() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sr-theme-functionality-deactivator.php';
	sr_theme_functionality_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sr_theme_functionality' );
register_deactivation_hook( __FILE__, 'deactivate_sr_theme_functionality' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sr-theme-functionality.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sr_theme_functionality() {

	$plugin = new sr_theme_functionality();
	$plugin->run();

}
run_sr_theme_functionality();
