<?php

defined('ABSPATH') || exit;

class Search_Engine_Extender_Admin
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
    wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/main.css', array(), $this->version, 'all');
  }

  public function enqueue_scripts()
  {
    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/main.js', array(), $this->version, false);

    $script_params = array(
      'excludedPosts' => explode(',', get_option('see_excluded_ids'))
    );
    wp_localize_script($this->plugin_name, 'see_admin_params', $script_params);
  }

  //---------------------------------------------------
  // MENU SECTION
  //---------------------------------------------------
  public function menu_section()
  {
    $menu_slug = 'search-engine-extender-settings';

    // main item
    add_menu_page('Search Engine Extender', 'Search Engine Extender', 'manage_options', $menu_slug, array($this, 'menu_section_display'), 'dashicons-search', 4);
    // settings item
    add_submenu_page($menu_slug, 'Search Engine Settings', esc_html__('Settings', $menu_slug), 'edit_others_posts', $menu_slug);
  }

  public function menu_section_display()
  {
    if (current_user_can('manage_options')) {
      ob_start();
      include_once plugin_dir_path(__FILE__) . 'partials/admin-menu-display.php';
      echo ob_get_clean();
    } else {
      echo '<p>' . esc_html__('You do not have adequate permissions for this action!', 'search-engine-extender-menu') . '</p>';
    }
  }

  public function submenu_stats_display()
  {
    if (current_user_can('edit_others_posts')) {
      ob_start();
      include_once plugin_dir_path(__FILE__) . 'templates/stats.php';
      echo ob_get_clean();
    } else {
      echo 'Access Denied';
    }
  }

  public function register_settings()
  {
    register_setting('search-engine-extender-settings', 'see_excluded_ids');
  }

}