<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/lopesgon/wordpress-plugins
 * @since             1.0.2
 * @package           Search Engine Extender
 *
 * @wordpress-plugin
 * Plugin Name:       Search Engine Extender
 * Plugin URI:        https://github.com/lopesgon/wordpress-plugins
 * Description:       Provides admin settings to customize Wordpress search engine results
 * Version:           1.0.2
 * Author:            lopesgon
 * Author URI:        https://github.com/lopesgon/wordpress-plugins
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
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
define( 'SEARCH_ENGINE_EXTENDER_VERSION', '1.0.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_search_engine_extender() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-search-engine-extender-activator.php';
	Search_Engine_Extender_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-search-engine-extender-deactivator.php
 */
function deactivate_search_engine_extender() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-search-engine-extender-deactivator.php';
	Search_Engine_Extender_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_search_engine_extender' );
register_deactivation_hook( __FILE__, 'deactivate_search_engine_extender' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-search-engine-extender.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.1
 */
function run_search_engine_extender() {

	$plugin = new Search_Engine_Extender();
	$plugin->run();

}
run_search_engine_extender();
