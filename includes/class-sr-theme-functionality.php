<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://stefan-reichert.com
 * @since      1.0.0
 *
 * @package    sr_theme_functionality
 * @subpackage sr_theme_functionality/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    sr_theme_functionality
 * @subpackage sr_theme_functionality/includes
 * @author     Stefan Reichert <reichert@qu-int.com>
 */
class sr_theme_functionality {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      sr_theme_functionality_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'sr-theme-functionality';
		$this->version = '2.8.7';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - sr_theme_functionality_Loader. Orchestrates the hooks of the plugin.
	 * - sr_theme_functionality_i18n. Defines internationalization functionality.
	 * - sr_theme_functionality_Admin. Defines all hooks for the admin area.
	 * - sr_theme_functionality_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sr-theme-functionality-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sr-theme-functionality-i18n.php';


		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sr-theme-functionality-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sr-theme-functionality-public.php';

		$this->loader = new sr_theme_functionality_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the sr_theme_functionality_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new sr_theme_functionality_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}
	
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new sr_theme_functionality_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'upload_mimes', 			$plugin_admin, 'add_mimes' );																		// 1. Allow upload of svg and font files
		$this->loader->add_action( 'embed_oembed_html', 	$plugin_admin, 'oembed_filter' );																	// 2. Embed Video iframes responsively
		$this->loader->add_action( 'set_current_user', 		$plugin_admin, 'remove_admin_bar' );																// 3. Remove admin bar
		$this->loader->add_action( 'admin_menu', 			$plugin_admin, 'remove_menus' );																	// 4. Remove admin navigation items
		$this->loader->add_action( 'admin_notices', 		$plugin_admin, 'hide_update_notice', 1 );															// 5. Update notification for Administrators
		$this->loader->add_action( 'wp_footer', 			$plugin_admin, 'footer_performance' );																// 6. Quick Performance Report
		$this->loader->add_action( 'admin_init', 			$plugin_admin, 'admin_styling' );																	// 7. Add custom CSS for Administrators
		$this->loader->add_action( 'admin_init', 			$plugin_admin, 'admin_javascript' );																// 8. Add custom JS for Administrators
		$this->loader->add_action( 'gallery_style', 		$plugin_admin, 'gallery_style' );																	// 9. Remove inline css style from gallery
		$this->loader->add_filter( 'get_image_tag_class', 	$plugin_admin, 'image_tag_align_class', 10, 4 );													// 10. Clean the output of attributes of images in editor
		$this->loader->add_action( 'post_thumbnail_html', 	$plugin_admin, 'image_tag_responsive', 10 );														// 11. Remove width and height in editor
		$this->loader->add_action( 'image_send_to_editor', 	$plugin_admin, 'image_tag_responsive', 10 );														// 11. Remove width and height in editor
	//	$this->loader->add_action( 'template_redirect', 	$plugin_admin, 'template_redirect', 999 );															// 12. Redirect Attachment Pages (mostly images) to their parent page

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new sr_theme_functionality_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_public, 'clean_up' );																						// 13. Remove WP generated content from the head/footer
		$this->loader->add_action( 'wp_head', $plugin_public, 'meta_icons' );																					// 14. Add various favicons and logos for iOS, Android, Windows
		$this->loader->add_action( 'the_category', $plugin_public, 'remove_category_rel_from_category_list' );													// 15. Removes invalid rel attribute values in the categorylist
		$this->loader->add_action( 'body_class', $plugin_public, 'add_slug_to_body_class' );																	// 16. Add page slug to body class
		$this->loader->add_action( 'nav_menu_css_class', $plugin_public, 'add_slug_to_navigation_class', 10, 2 );												// 17. Add page slug to navigation class with prefix 'menu-item'
		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    sr_theme_functionality_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
