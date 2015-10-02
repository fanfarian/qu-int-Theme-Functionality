<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://stefan-reichert.com
 * @since      1.0.0
 *
 * @package    sr_theme_functionality
 * @subpackage sr_theme_functionality/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    sr_theme_functionality
 * @subpackage sr_theme_functionality/public
 * @author     Stefan Reichert <reichert@qu-int.com>
 */
class sr_theme_functionality_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	/**
	 * Check if an item exists out there in the "ether".
	 *
     * @since  1.0.0
     * @access public
	 * @param string $url - preferably a fully qualified URL
	 * @return boolean - true if it is out there somewhere
	 */
	private function web_url_exists($url) {
	    if (($url == '') || ($url == null)) { return false; }
	    $response = wp_remote_head( $url, array( 'timeout' => 5 ) );
	    $accepted_status_codes = array( 200, 301, 302 );
	    if ( ! is_wp_error( $response ) && in_array( wp_remote_retrieve_response_code( $response ), $accepted_status_codes ) ) {
	        return true;
	    }
	    return false;
	}
	
	/**
     * Remove WP generated content from the head
     *
     * @since  1.0.0
     * @access private
     * @return void
     */
	public function clean_up() {

		remove_action( 'wp_head', 'feed_links_extra', 3 );																										// category feeds
		remove_action( 'wp_head', 'feed_links', 2 );																											// post and comment feeds
		remove_action( 'wp_head', 'rsd_link' );																													// EditURI link
		remove_action( 'wp_head', 'wlwmanifest_link' );																											// windows live writer
		remove_action( 'wp_head', 'index_rel_link' );																											// index link
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );																								// previous link
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );																								// start link
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );																					// links for adjacent posts
		remove_action( 'wp_head', 'wp_generator' );																												// WP version
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );																								// Shortlink
		remove_action( 'wp_head', 'rel_canonical');																												// Canonical links
		remove_action( 'set_comment_cookies', 'wp_set_comment_cookies');																						// Remove comment cookie

		// Remove some WPML stuff
		if ( function_exists('icl_object_id') ) {																												// WPML exists
			global $sitepress;

			remove_action( 'wp_head', array($sitepress, 'meta_generator_tag'));																					// WPML Infos aus <head> entfernen
			remove_action( 'wp_head', array($sitepress, 'head_langs'));																							// WPML Infos aus <head> entfernen
		
			define( 'ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);																								// WPML CSS aus <head> entfernen
			define( 'ICL_DONT_LOAD_LANGUAGES_JS', true);																										// WPML JS aus <head> entfernen
		}
			    
	    add_filter( 'the_generator', '__return_false');																											// remove WP version from RSS
		add_filter( 'style_loader_src',  array( $this, 'remove_wp_ver_css_js'), 9999 );																			// remove WP version from css + scripts
		add_filter( 'script_loader_src',  array( $this, 'remove_wp_ver_css_js'), 9999 );																		// remove WP version from css + scripts
	    add_filter( 'wp_head',  array( $this, 'remove_wp_widget_recent_comments_style'), 1 );																	// remove pesky injected css for recent comments widget
	    add_action( 'wp_head',  array( $this, 'remove_recent_comments_style'), 1 );																				// clean up comment styles in the head
		add_action( 'wp_head',  array( $this, 'remove_emojicons'), 1 );																							// Remove Emoji-Stuff
	    
	}
	
	/**
     * Disable Emojicons
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
	public function remove_emojicons() 
	{
	    // Remove from comment feed and RSS
	    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	
	    // Remove from emails
	    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	
	    // Remove from head tag
	    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	
	    // Remove from print related styling
	    remove_action( 'wp_print_styles', 'print_emoji_styles' );
	
	    // Remove from admin area
	    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	    remove_action( 'admin_print_styles', 'print_emoji_styles' );
	}	
	
	/**
     * Remove WP version from remove_emojicons and css files
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
	public function remove_wp_ver_css_js( $src ) {
	    if ( strpos( $src, 'ver=' ) )
	        $src = remove_query_arg( 'ver', $src );
	    return $src;
	}
	
	/**
     * Remove injected CSS for recent comments widget
     *
     * @since  1.0.0
     * @access private
     * @return void
     */
	public function remove_wp_widget_recent_comments_style() {
		if ( has_filter('wp_head', 'wp_widget_recent_comments_style') ) {
			remove_filter('wp_head', 'wp_widget_recent_comments_style' );
		}
	}

	/**
     * Remove injected CSS from recent comments widget
     *
     * @since  1.0.0
     * @access private
     * @return void
     */
	public function remove_recent_comments_style() {
		global $wp_widget_factory;
		if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
			remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
		}
	}
	
	/**
     * Add various favicons and logos for iOS, Android, Windows
     *
     * @since  1.0.0
     * @access public
     * @return viod
     */
	public function meta_icons(){
				
		$touch_icon_192 = get_stylesheet_directory_uri().'/touch-icon-192x192.png';																				// 192x192
		$touch_icon_180 = get_stylesheet_directory_uri().'/apple-touch-icon-180x180-precomposed.png';															// 180x180
		$touch_icon_152 = get_stylesheet_directory_uri().'/apple-touch-icon-152x152-precomposed.png';															// 152x152
		$touch_icon_120 = get_stylesheet_directory_uri().'/apple-touch-icon-120x120-precomposed.png';															// 120x120
		$touch_icon_76  = get_stylesheet_directory_uri().'/apple-touch-icon-76x76-precomposed.png';																// 76x76
		$touch_icon_57  = get_stylesheet_directory_uri().'/apple-touch-icon-precomposed.png';																	// 57x57
		
		$browserconfig  = get_stylesheet_directory_uri().'/browserconfig.xml';																					// 558x270 + 558x558
		$favicon  = get_stylesheet_directory_uri().'/favicon.ico';																								// 16x16 + 32x32

		echo '		<!-- Favicons -->
			<meta name="apple-mobile-web-app-title" content="'.get_bloginfo('name').'">
			<meta name="application-name" content="'.get_bloginfo('name').'">';
		
		if($this->web_url_exists($touch_icon_192)){
			echo '
			<link rel="icon" sizes="192x192" href="'.$touch_icon_192.'"><!-- For Chrome for Android: 192x192 -->';
		}
		if($this->web_url_exists($touch_icon_180)){
			echo '
			<link rel="apple-touch-icon-precomposed" sizes="180x180" href="'.$touch_icon_180.'"><!-- For iPhone 6 Plus with @3× display: 180x180 -->';
		}
		if($this->web_url_exists($touch_icon_152)){
			echo '
			<link rel="apple-touch-icon-precomposed" sizes="152x152" href="'.$touch_icon_152.'"><!-- For iPad with @2× display running iOS ≥ 7: 152x152 -->';
		}
		if($this->web_url_exists($touch_icon_120)){
			echo '
			<link rel="apple-touch-icon-precomposed" sizes="120x120" href="'.$touch_icon_120.'"><!-- For iPhone with @2× display running iOS ≥ 7: 120x120 -->';
		}
		if($this->web_url_exists($touch_icon_76)){
			echo '
			<link rel="apple-touch-icon-precomposed" sizes="76x76" href="'.$touch_icon_76.'"><!-- For the iPad mini and the first- and second-generation iPad (@1× display) on iOS ≥ 7: 76x76 -->';
		}
		if($this->web_url_exists($touch_icon_57)){
			echo '
			<link rel="apple-touch-icon-precomposed" href="'.$touch_icon_57.'"><!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: 57×57px -->';
		}
		if($this->web_url_exists($browserconfig)){
			echo '
			<meta name="msapplication-config" content="'.$browserconfig.'" />';	
		}
		if($this->web_url_exists($favicon)){
			echo '
			<link rel="shortcut icon" href="'.$favicon.'" />
			';
		}
	}



	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in sr_theme_functionality_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The sr_theme_functionality_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sr-theme-functionality-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in sr_theme_functionality_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The sr_theme_functionality_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sr-theme-functionality-public.js', array( 'jquery' ), $this->version, false );

	}

}
