<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://qu-int.com/koepfe/stefan-reichert
 * @since             1.0.0
 * @package           Qu_Int_Theme_Functionality
 *
 * @wordpress-plugin
 * Plugin Name:       qu-int Theme Functionality
 * Plugin URI:        https://github.com/fanfarian/qu-int-Theme-Functionality
 * Description:       Functionality-Plugin mit wichtigen Einstellungen & Custom Posts für qu-int.Themes
 * Version:           2.1.0
 * Author:            Stefan Reichert
 * Author URI:        http://qu-int.com/koepfe/stefan-reichert
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       qu-int-theme-functionality
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

include_once( 'github-plugin-updater/updater.php' );

if ( is_admin() ) {
    $config = array(
        'slug'                  => plugin_basename( __FILE__ ),
        'proper_folder_name'    => 'qu-int-theme-functionality',
        'api_url'               => 'https://api.github.com/repos/fanfarian/qu-int-Theme-Functionality',
        'raw_url'               => 'https://raw.github.com/fanfarian/qu-int-Theme-Functionality/master',
        'github_url'            => 'https://github.com/fanfarian/qu-int-Theme-Functionality',
        'zip_url'               => 'https://github.com/fanfarian/qu-int-Theme-Functionality/zipball/master',
        'sslverify'             => true,
        'requires'              => '4.0',
        'tested'                => '4.3.1',
        'readme'                => 'README.txt',
        'access_token'          => ''
    );
    new WP_GitHub_Updater( $config );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-qu-int-theme-functionality-activator.php
 */
function activate_qu_int_theme_functionality() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-qu-int-theme-functionality-activator.php';
	Qu_Int_Theme_Functionality_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-qu-int-theme-functionality-deactivator.php
 */
function deactivate_qu_int_theme_functionality() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-qu-int-theme-functionality-deactivator.php';
	Qu_Int_Theme_Functionality_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_qu_int_theme_functionality' );
register_deactivation_hook( __FILE__, 'deactivate_qu_int_theme_functionality' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-qu-int-theme-functionality.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_qu_int_theme_functionality() {

	$plugin = new Qu_Int_Theme_Functionality();
	$plugin->run();

}
run_qu_int_theme_functionality();
