<?php

/**
 * The plugin bootstrap file
 *
 * @wordpress-plugin
 * Plugin Name:       Search Engine Extender
 * Plugin URI:        https://github.com/lopesgon/wordpress-plugins
 * Description:       Provides admin settings to customize Wordpress search engine results
 * Version:           1.1.0
 * Author:            lopesgon
 * Author URI:        https://github.com/lopesgon/wordpress-plugins
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:       /languages
 */

defined('ABSPATH') || exit;


define('SEARCH_ENGINE_EXTENDER_VERSION', '1.1.0');
define('SEARCH_ENGINE_EXTENDER_PLUGIN_NAME', 'search-engine-extender');
define('SEARCH_ENGINE_EXTENDER_DEVELOPMENT_MODE', __SEARCH_ENGINE_EXTENDER_DEVELOPMENT_MODE__);

function activate_search_engine_extender()
{
  require_once plugin_dir_path(__FILE__) . 'includes/class-search-engine-extender-activator.php';
  Search_Engine_Extender_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-search-engine-extender-deactivator.php
 */
function deactivate_search_engine_extender()
{
  require_once plugin_dir_path(__FILE__) . 'includes/class-search-engine-extender-deactivator.php';
  Search_Engine_Extender_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_search_engine_extender');
register_deactivation_hook(__FILE__, 'deactivate_search_engine_extender');

require plugin_dir_path(__FILE__) . 'includes/class-search-engine-extender.php';

function run_search_engine_extender()
{
  $plugin = new Search_Engine_Extender();
  $plugin->run();
}

run_search_engine_extender();