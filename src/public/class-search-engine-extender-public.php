<?php

defined('ABSPATH') || exit;

class Search_Engine_Extender_Public
{
  private $plugin_name;
  private $version;

  public function __construct($plugin_name, $version)
  {
    $this->plugin_name = $plugin_name;
    $this->version = $version;
  }

  public function enqueue_styles()
  {
    // wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/search-engine-extender-public.css', array(), $this->version, 'all');
  }

  public function enqueue_scripts()
  {
    // wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/search-engine-extender-public.js', array('jquery'), $this->version, false);
  }

  function search_engine_exclude_filter($query)
  {
    $excluded_ids = get_option('see_excluded_ids');
    $array = explode(',', $excluded_ids);
    if (!$query->is_admin && $query->is_search && $query->is_main_query()) {
      $query->set('post__not_in', $array);
    }
  }

}