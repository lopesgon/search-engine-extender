<?php

defined('ABSPATH') || exit;

class Search_Engine_Extender_i18n
{


  /**
   * Load the plugin text domain for translation.
   *
   * @since    1.0.2
   */
  public function load_plugin_textdomain()
  {
    load_plugin_textdomain(
      'plugin-name',
      false,
      dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
    );
  }
}