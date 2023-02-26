<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/lopesgon/wordpress-plugins
 * @since      1.0.2
 *
 * @package    Search_Engine_Extender
 * @subpackage search-engine-extender/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Search_Engine_Extender
 * @subpackage search-engine-extender/admin
 * @author     Frederic Lopes <mag.frederic@icloud.com>
 */
class Search_Engine_Extender_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.2
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.2
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.2
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.2
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Search_Engine_Extender_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Search_Engine_Extender_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/search-engine-extender-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.2
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Search_Engine_Extender_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Search_Engine_Extender_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/search-engine-extender-admin.js', array('jquery'), $this->version, false);

		$script_params = array(
			'excludedPosts' => explode(',', get_option('see_excluded_ids'))
		);
		wp_localize_script( $this->plugin_name, 'see_admin_params', $script_params );
	}

	//---------------------------------------------------
	// MENU SECTION
	//---------------------------------------------------
	public function menu_section()
	{
        $menu_slug  = 'search-engine-extender-settings';

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

	public function register_settings() {
		register_setting('search-engine-extender-settings', 'see_excluded_ids');
	}

}
