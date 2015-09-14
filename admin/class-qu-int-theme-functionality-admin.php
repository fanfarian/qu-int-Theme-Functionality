<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://qu-int.com/koepfe/stefan-reichert
 * @since      1.0.0
 *
 * @package    Qu_Int_Theme_Functionality
 * @subpackage Qu_Int_Theme_Functionality/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Qu_Int_Theme_Functionality
 * @subpackage Qu_Int_Theme_Functionality/admin
 * @author     Stefan Reichert <reichert@qu-int.com>
 */
class Qu_Int_Theme_Functionality_Admin {

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
     * Allow upload of svg and fonts
     *
     * @since  1.0.0
     * @access public
     * @return viod
     */
	public function add_mimes( $mimes ){
		$mimes['svg'] 	= 'image/svg+xml';
		$mimes['ttf'] 	= 'font/ttf';
		$mimes['otf'] 	= 'font/otf';
		$mimes['woff2'] = 'font/woff2';
		$mimes['woff'] 	= 'font/woff';
		$mimes['eot'] 	= 'font/eot';
		return $mimes;
	}

	/**
     * Add oEmbedded Class for Responsive iFrame Videos from Youtube/Vimeo
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
	public function oembed_filter($html, $url, $attr, $post_ID) {
	    $html = preg_replace('# src="https://www\.youtube\.com([^"]*)"#', ' src="https://www.youtube-nocookie.com\\1&rel=0&modestbranding=1"', $html);
	
	    $return = '<div class="embed-container">'.$html.'</div>';
	    return $return;
	}
	
	/**
     * Insert Login Logo
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
	public function login_logo() { 
		
		$login_image_path = get_stylesheet_directory_uri().'/img/login_logo.png';																				// Login Logo
		
		if($this->web_url_exists($login_image_path)){																											// Check File exist
			
			echo '<style type="text/css">
		        body.login div#login h1 a {
		            background-image: url('.$login_image_path.');
		            background-size: auto;
		            width: 320px !important;
		            height: 180px !important;
		            padding-bottom: 20px;
		        }
		   </style>';
		}
	}
	
	/**
     * Add custom CSS for admin
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
	public function admin_styling() {
		
		$admin_css_path = get_stylesheet_directory_uri().'/css/admin.css';
		
		if($this->web_url_exists($admin_css_path)){
			wp_enqueue_style( 'qtf-admin', $admin_css_path );
		}
	}

	/**
     * Remove Admin bar for normal users
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
     * Remove Admin navigation items
     *
     * @since  1.0.0
     * @access public
     * @return void
     */	
	public function remove_menus () {
		
		global $menu;  
	    global $submenu;
		    
	    if (!current_user_can('update_core')) {
	    	remove_menu_page('edit-comments.php'); 																												// Remove the comments menu
			remove_menu_page('tools.php'); 																														// Remove the tools menu
		}
	}
	
	/**
     * Show update notification only to admins
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
     * Displaying a Quick Performance Report for Admins 
     *
     * @since  1.0.0
     * @access public
     * @return void
     */	
	public function footer_performance() {
	    $stat = sprintf( '%d queries in %.3f seconds, using %.2fMB memory',
	        get_num_queries(),
	        timer_stop( 0, 3 ),
	        memory_get_peak_usage() / 1024 / 1024
	    );
	    if( current_user_can( 'update_core' ) ) {
	        echo "<!-- {$stat} -->";
	    }
	}
	
	/**
     * Remove injected CSS from gallery
     *
     * @since  1.0.0
     * @access public
     * @return string return custom output for inserted images in posts
     */
	public function gallery_style($css) {
		return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
	}
	
	/**
     *  Clean the output of attributes of images in editor. Courtesy of SitePoint. http://www.sitepoint.com/wordpress-change-img-tag-html/
     *
     * @since  1.0.0
     * @access public
     * @return string return custom output for inserted images in posts
     */
	public function image_tag_class($class, $id, $align, $size) {
		$align = 'align' . esc_attr($align);
		return $align;
	}
	
	/**
     * Redirect Attachment Pages (mostly images) to their parent page
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
	
	        $wp_query->set_404();
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
		 * defined in Qu_Int_Theme_Functionality_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Qu_Int_Theme_Functionality_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/qu-int-theme-functionality-admin.css', array(), $this->version, 'all' );

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
		 * defined in Qu_Int_Theme_Functionality_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Qu_Int_Theme_Functionality_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/qu-int-theme-functionality-admin.js', array( 'jquery' ), $this->version, false );

	}

}
