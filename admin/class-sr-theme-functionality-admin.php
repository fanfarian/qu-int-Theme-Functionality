<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://stefan-reichert.com
 * @since      1.0.0
 *
 * @package    sr_theme_functionality
 * @subpackage sr_theme_functionality/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    sr_theme_functionality
 * @subpackage sr_theme_functionality/admin
 * @author     Stefan Reichert <reichert@qu-int.com>
 */
class sr_theme_functionality_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
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
     * 1. Allow upload of svg and font files
     * Add mime types for 'svg', 'ttf', 'otf', 'woff', 'woff2', 'eot' to media uploader
     *
     * @since  1.0.0
     * @access public
     * @return viod
     */
	public function add_mimes( $mimes ){
		$mimes['svg'] 	= 'image/svg+xml';
		$mimes['ttf'] 	= 'font/ttf';
		$mimes['otf'] 	= 'font/otf';
		$mimes['woff'] 	= 'font/woff';
		$mimes['woff2'] = 'font/woff2';
		$mimes['eot'] 	= 'font/eot';
		return $mimes;
	}

	/**
	 * 2. Embed Video iframes responsively
     * Add oEmbedded class for responsive iFrame Videos from Youtube/Vimeo.
     * You need to add custom css for .embed-container from http://embedresponsively.com/
     * .embed-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; } 
     * .embed-container iframe, .embed-container object, .embed-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
	public function oembed_filter($html) {
	    $html = preg_replace('# src="https://www\.youtube\.com([^"]*)"#', ' src="https://www.youtube-nocookie.com\\1&rel=0&modestbranding=1"', $html);
	
	    $return = '<div class="embed-container">'.$html.'</div>';
	    return $return;
	}
	
	/**
	 * 3. Remove admin bar 
     * Removes the Admin bar on front end for users without role 'edit_posts'
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
	public function remove_admin_bar() {
		if ( !current_user_can('edit_posts') ) {
			show_admin_bar( false ); 																															// Remove Admin bar
		}
	}
	
	/**
	 * 4. Remove admin navigation items
     * Remove 'Comment' navigation link for users without role 'moderate_comments'
	 * Remove 'Tools' navigation link for users without role 'manage_options'
     *
     * @since  1.0.0
     * @access public
     * @return void
     */	
	public function remove_menus () {
		
		global $menu;  
	    global $submenu;
		    
	    if (!current_user_can('moderate_comments')) {
	    	remove_menu_page('edit-comments.php'); 																												// Remove the comments menu
		}
	    if (!current_user_can('manage_options')) {
			remove_menu_page('tools.php'); 																														// Remove the tools menu
		}
	}
	
	/**
	 * 5. Update notification for Administrators
     * Show update notification only to admins to prevent user confusion 
     *
     * @since  1.0.0
     * @access public
     * @return void
     */	
	public function hide_update_notice() 
	{
	    if (!current_user_can('update_core')) {
	        remove_action( 'admin_notices', 'update_nag', 3 );																									// Remove Admin notice
	    }
	}

	/**
	 * 6. Quick Performance Report
     * Display a quick performance report for admins as HTML comment at the bottom of the page
     *
     * @since  1.0.0
     * @access public
     * @return void
     */	
	public function footer_performance() {
		if( current_user_can( 'update_core' ) ) {
		    $stat = sprintf( '%d queries in %.3f seconds, using %.2fMB memory',
		        get_num_queries(),
		        timer_stop( 0, 3 ),
		        memory_get_peak_usage() / 1024 / 1024
		    );
	    
	        echo "<!-- {$stat} -->";
	    }
	}
	
	/**
     * 7. Add custom CSS for Administrators
     * Checks if file exists in 'theme-folder/css/admin.css' and enqueues file automatically
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
	public function admin_styling() {
		
		$admin_css_path = get_stylesheet_directory_uri().'/css/admin.css';																						// Check in /css
		$admin_css_path_srtheme = get_stylesheet_directory_uri().'/assets/css/admin.css';																		// Check in /assets/css
		
		if($this->web_url_exists($admin_css_path)){
			wp_enqueue_style( 'css-admin', $admin_css_path );																									// include css from /css
		}
		elseif($this->web_url_exists($admin_css_path_srtheme)){
			wp_enqueue_style( 'css-admin', $admin_css_path_srtheme );																							// include css from /assets/css
		}
	}
	
	/**
     * 8. Add custom JS for Administrators
     * Checks if file exists in 'theme-folder/js/admin.min.js' and enqueues file automatically
     *
     * @since  2.3.0
     * @access public
     * @return void
     */
	public function admin_javascript() {
		
		$admin_js_min_path = get_stylesheet_directory_uri().'/js/admin.min.js';																					// Check in /js
		$admin_js_min_path_srtheme = get_stylesheet_directory_uri().'/assets/js/admin.min.js';																	// Check in /assets/js

		$admin_js_path = get_stylesheet_directory_uri().'/js/admin.js';																							// Check in /js
		$admin_js_path_srtheme = get_stylesheet_directory_uri().'/assets/js/admin.js';																			// Check in /assets/js
		
		if($this->web_url_exists($admin_js_min_path)){
			wp_enqueue_script( 'js-admin',  $admin_js_min_path, array('jquery'), '1.2', true );																	// include .min from /js
		}
		elseif($this->web_url_exists($admin_js_min_path_srtheme)){
			wp_enqueue_script( 'js-admin',  $admin_js_min_path_srtheme, array('jquery'), '1.2', true );															// include .min from /assets/js
		}
		elseif($this->web_url_exists($admin_js_path)){
			wp_enqueue_script( 'js-admin',  $admin_js_path, array('jquery'), '1.2', true );																		// include .js from /js
		}
		elseif($this->web_url_exists($admin_js_path_srtheme)){
			wp_enqueue_script( 'js-admin',  $admin_js_path_srtheme, array('jquery'), '1.2', true );																// include .js from /assets/js
		}
	}	
	
	
	/**
     * 9. Remove inline css style from gallery
     * You need to style your gallery through your own css files
     *
     * @since  1.0.0
     * @access public
     * @return string return custom output for inserted images in posts
     */
	public function gallery_style($css) {
		return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
	}
	
	/**
     * 10. Clean the output of attributes of images in editor. 
     * Courtesy of SitePoint. http://www.sitepoint.com/wordpress-change-img-tag-html/
     *
     * @since  1.0.0
     * @access public
     * @return string return custom output for inserted images in posts
     */
	public function image_tag_align_class($class, $id, $align, $size) {
		$align = 'align' . esc_attr($align);
		return $align;
	}
	
	/**
     * 11. Remove width and height in editor, for a better responsive world. 
     * Also sets 'alt' = 'titel' if no alt tag provided for the image
     *
     * @since  2.2.0
     * @access public
     * @return string return custom output for inserted images in posts
     */
	public function image_tag_responsive($html, $id, $alt, $title) {
		
		if(empty($alt)){
			$alt = $title;
		}
		
		return preg_replace(array(
				'/\s+width="\d+"/i',
				'/\s+height="\d+"/i',
				'/alt=""/i'
			),
			array(
				'',
				'',
				'',
				'alt="' . $alt . '"'
			),
			$html);
	}
	
	/**
     * 12. Redirect Attachment Pages (mostly images) to their parent page if available
     *
     * @since  1.0.0
     * @access public
     * @return string return custom output for inserted images in posts
     */
	public function template_redirect () {
	    global $wp_query, $post;
	
	    if ( is_attachment() ) {
	        $post_parent = $post->post_parent;
	
	        if ( $post_parent ) {
	            wp_redirect( get_permalink($post->post_parent), 301 );
	            exit;
	        }
	
			// $wp_query->set_404();
	        return;
	    }
	}
	

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sr-theme-functionality-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sr-theme-functionality-admin.js', array( 'jquery' ), $this->version, false );

	}

}
