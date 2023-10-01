<?php

defined('ABSPATH') || exit;

class Search_Engine_Extender
{
  private $plugin_name;
  protected $loader;
  protected $search_engine_extender;
  protected $version;

  public function __construct()
  {
    if (defined('SEARCH_ENGINE_EXTENDER_VERSION')) {
      $this->version = SEARCH_ENGINE_EXTENDER_VERSION;
    } else {
      $this->version = '0.0.0';
    }

    if (defined('SEARCH_ENGINE_EXTENDER_PLUGIN_NAME')) {
      $this->plugin_name = SEARCH_ENGINE_EXTENDER_PLUGIN_NAME;
    } else {
      $this->plugin_name = 'undefined_search-engine-extender';
    }

    $this->load_dependencies();
    $this->set_locale();
    $this->define_admin_hooks();
    $this->define_public_hooks();
  }

  private function load_dependencies()
  {

    /**
     * The class responsible for orchestrating the actions and filters of the
     * core plugin.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-search-engine-extender-loader.php';

    /**
     * The class responsible for defining internationalization functionality
     * of the plugin.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-search-engine-extender-i18n.php';

    /**
     * The class responsible for defining all actions that occur in the admin area.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-search-engine-extender-admin.php';

    /**
     * The class responsible for defining all actions that occur in the public-facing
     * side of the site.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-search-engine-extender-public.php';

    $this->loader = new Search_Engine_Extender_Loader();
  }

  private function set_locale()
  {
    $plugin_i18n = new Search_Engine_Extender_i18n();
    $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
  }

  private function define_admin_hooks()
  {

    $plugin_admin = new Search_Engine_Extender_Admin($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
    $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
    // MENU SECTION
    $this->loader->add_action('admin_menu', $plugin_admin, 'menu_section');
    // OPTIONS
    $this->loader->add_action('admin_init', $plugin_admin, 'register_settings');
  }

  private function define_public_hooks()
  {

    $plugin_public = new Search_Engine_Extender_Public($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
    $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    $this->loader->add_action('pre_get_posts', $plugin_public, 'search_engine_exclude_filter');
  }

  public function run()
  {
    $this->loader->run();
  }

  public function get_plugin_name()
  {
    return $this->plugin_name;
  }

  public function get_loader()
  {
    return $this->loader;
  }

  public function get_version()
  {
    return $this->version;
  }
}