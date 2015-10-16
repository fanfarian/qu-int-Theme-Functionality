<?php
	
// Enqueue CSS + JS
/*########################################################################################################################################################################################*/
if( ! function_exists( 'sr_enqueue_style' )) {
	function sr_enqueue_style()
	{
		# CSS Stylesheet -> Child
		wp_enqueue_style( 'sr-css', get_stylesheet_directory_uri() . '/css/style.css', array(), '', 'all' );
		
		# JavaScript
		wp_deregister_script( 'jquery' );																														// disable WP internal jquery for own version
		wp_enqueue_script( 'jquery',  		get_stylesheet_directory_uri() . '/js/jquery.min.js', false, '2.1.4', true );										// your own jQuery version
		wp_enqueue_script( 'modernizr', 	get_stylesheet_directory_uri() . '/js/modernizr.dev.min.js', false, '3.0', false );									// Modernizr 3
//		wp_enqueue_script( 'sr', 			get_stylesheet_directory_uri() . '/js/JS-FILE.min.js', false, '1.0', true );										// Child-Javascript
//		wp_localize_script( 'sr', 			'sr', array( 'siteurl' => get_stylesheet_directory_uri() ));														// Site URL in Javasript available		
	}
}
add_action( 'wp_enqueue_scripts', 'sr_enqueue_style', 999 );


// Additional Images sizes for Content
/*########################################################################################################################################################################################*/
if( ! function_exists( 'sr_image_sizes' )) {
	function sr_image_sizes() {
	
		add_theme_support( 'post-thumbnails' );																													// Add post thumbnail supports. http://codex.wordpress.org/Post_Thumbnails
		set_post_thumbnail_size( 240, 240, true );																												// square thumbnails
	
		add_image_size( 'NAME', 240, 0 );
	}
}
add_action( 'after_setup_theme', 'sr_image_sizes', 999 );


# Add new image sizes to mediathek
/*
if( ! function_exists( 'sr_image_sizes_choose' )) {
	function sr_image_sizes_choose($sizes) {
		$custom_sizes = array(
			'sr_medium' 		=> 'Normal',																													// Querformat Large
			'sr_large' 		=> 'Big',																														// Querformat Medium
		);
		return array_merge($sizes, $custom_sizes);
	//	return $custom_sizes;
	}
}
add_filter( 'image_size_names_choose', 'sr_image_sizes_choose', 999 );
*/


// Theme Support
/*########################################################################################################################################################################################*/ 
if( ! function_exists( 'sr_theme_support' )) {
	function sr_theme_support() {
	
		// Add language supports.
		load_theme_textdomain( 'sr', get_template_directory() . '/lang' );
		
		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );
		
		// Switch default core markup for search form, comment form, and comments to output valid HTML5.
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
		) );
		
		// Enable support for Post Formats.
		add_theme_support( 'post-formats', array(
			'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
		) );
	
	    // Add menu support. http://codex.wordpress.org/Function_Reference/register_nav_menus
	    register_nav_menus(array(
	        'primary' 	=> 'Hauptnavigation',
	        'subnav' 	=> 'Subnavigation',
	    ));
	}
}
add_action( 'after_setup_theme', 'sr_theme_support' );


// Get page ID from slug
/*########################################################################################################################################################################################*/ 
function get_page_by_slug($page_slug, $post_type = 'page' ) { 
	global $wpdb; 
	$page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s AND post_status = 'publish'", $page_slug, $post_type ) ); 
		
	if ( $page ) 
		return get_post($page, OBJECT); 
	return false; 
}


// Login-Logo, Link und Tooltip
/*########################################################################################################################################################################################*/
/*
if( ! function_exists( 'qwp_login_logo' )) {
	function qwp_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url('<?php echo get_stylesheet_directory_uri(  ) ?>/img/login_logo.svg');
            background-size: cover;
            width: 100% !important;
            height: 100px !important;
        }
    </style>
<?php }
}
add_action( 'login_enqueue_scripts', 'qwp_login_logo', 999 );


function qwp_loginpage_custom_link() {
	return 'http://www.qu-int.com';
}
add_filter('login_headerurl','qwp_loginpage_custom_link');


function change_title_on_logo() {
	return 'qu-int.gmbh | marken | medien | kommunikation';
}
add_filter('login_headertitle', 'change_title_on_logo');
*/


// Disables the Google Sitelink Search Box functionality in WordPress SEO (Premium) 
/*########################################################################################################################################################################################*/ 
// add_filter( 'disable_wpseo_json_ld_search', '__return_true' );
	
	
// Hide some expert settings from normal users: 
// WISIWYG/HTML editor tabs in edit posts/pages 
/*########################################################################################################################################################################################*/
/*
if( ! function_exists( 'sr_disable_html_editor' )) {	
	function sr_disable_html_editor() {
	    global $current_user;
	    get_currentuserinfo();
	    
	    echo '<style type="text/css">	
				#icl_div_config
				{
					display:none !important;
				}
			</style>';
	}
}
add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );
add_action( 'admin_head', 'sr_disable_html_editor' );
*/


// Session
/*########################################################################################################################################################################################*/
/*
add_action('init', 'sr_start_session', 1);																														// start session on pageload
add_action('wp_login', 'sr_end_session');																														// stop session on login
add_action('wp_logout', 'sr_end_session');																														// stop session on logout

if( ! function_exists( 'sr_start_session' )) {	
	function sr_start_session() {
	    if(!session_id()) {
	        session_start();
	    }
	}
}

if( ! function_exists( 'sr_end_session' )) {	
	function sr_end_session() {
	    session_destroy();
	}
}
*/

// Background MediaQueries
/*########################################################################################################################################################################################*/
/*
if ( ! function_exists( 'generate_background_mediaqueries' )) {
	function generate_background_mediaqueries($args) {
	
		$anzEntries = $anzHelper = $anzMQ = 0;																													// Zähler 0 setzen
	
		foreach($args as $entry){																																// Jeder Eintrag als Entry
			
			$anzQuery = 0;																																		// Zähler 0 setzen
			$class = array_keys($args)[$anzEntries];																											// CSS-Klasse = Array-Key
			$anzEntries++;																																		// Zähle Entries = CSS-Klassen
			
			foreach($entry as $query){																															// jeder Entry als Query
			
				$anzQuery++;																																	// Zähle Queries
				
				if( empty($query['pos']) ){																														// background-position leer
					$query['pos'] = 'center center';																											// default = center center
				}
				
				if ( (array) $query['img'] !== $query['img'] ) { 																								// img nicht als Array
					$image = $query['img'];																														// feld ist url
				}
				else{																																			// img als Array -> ACF
					$image = $query['img'][0];																													// erstes Feld ist url
				}
				
				if($anzQuery == 1){																																// erste query ohne Media Query
					$style .= '.'.$class.'{
									background-image: url('.$image.');
									background-position: '.$query['pos'].';
									background-repeat: no-repeat;
								}
								';
				}
				else{																																			// alle anderen mit MQ -> sichere in Array
					$helpArray[$anzHelper]['name'] = $class;																									// Name
					$helpArray[$anzHelper]['mq'] = $query['mq'];																								// @media
					$helpArray[$anzHelper]['img'] = $image;																										// Url
					$helpArray[$anzHelper]['pos'] = $query['pos'];																								// Positon
					$anzHelper++;																																// Zähle Helper Einträge
				}
			}
		}
	
		## Sortieren nach Mediaquery	
		foreach ($helpArray as $key => $row){
		    $mediaquery[$key] = $row['mq'];																														// Sortier-Schlüssel
		}
		array_multisort($mediaquery, SORT_ASC, $helpArray);																										// MutliArray Sortieren			
	
		
		## Ausgabe sortiert nach @media
		foreach($helpArray as $helpEntry){
			
			$class = $helpEntry['name'];																														// Klasse
			$mq = $helpEntry['mq'];																																// @media
			$image = $helpEntry['img'];																															// URL
			$pos = $helpEntry['pos'];																															// Position
			$anzMQ++;																																			// Zähle Einträge
			
			if($mq == $preMQ){																																	// MediaQuery == Vorgänger
				$style .= '
							.'.$class.'{
								background-image: url('.$image.');
								background-position: '.$pos.';
								background-repeat: no-repeat;
							}';
			}
			else{																																				// Neue @media qiery
				if($anzMQ > 1){																																	// alle außer dem ersten
					$style .= '
						}
					';																																			// schließe vorheriges @media vor neuem @media
				}
				
				$style .= '@media screen and (min-width: '.$mq.'px){
							.'.$class.'{
								background-image: url('.$image.');
								background-position: '.$pos.';
								background-repeat: no-repeat;
							}';			
			}
			
			$preMQ = $mq;																																		// Sichere als PreMQ-Wert für nächsten Foreach-Durchgang
		}
		$style .= '
						}
				';																																				// schließe letztes @media
		
		return '<style id="background_mediaqueries">'.$style.'</style>';
	}
}
*/


# Show custom stuff in dashboard activity widget
/*########################################################################################################################################################################################*/
/*
// register your custom activity widgets
if( ! function_exists( 'sr_add_custom_dashboard_activity' )) {	
	function sr_add_custom_dashboard_activity() {
	    wp_add_dashboard_widget('custom_dashboard_activity', 'Projekte', 'sr_dashboard_site_activity');
	    wp_add_dashboard_widget('dashboard_widget', 'Dokumentation zum CMS', 'sr_doku_dashboard_widget_function');
	
	}
}
add_action('wp_dashboard_setup', 'sr_add_custom_dashboard_activity' );


// Function that outputs the contents of the dashboard widget
if( ! function_exists( 'sr_doku_dashboard_widget_function' )) {	
	function sr_doku_dashboard_widget_function() {
		echo '<p><a href="'.get_stylesheet_directory_uri().'/PATH/"><span class="dashicons-before dashicons-media-document"></span> <span class="ab-label">'._x( 'TEXT', 'admin bar menu group label' ).'</span></a></p>';
	}
}

// The Dashboard widget
if( ! function_exists( 'sr_dashboard_site_activity' )) {	
	function sr_dashboard_site_activity() {
	
	    echo '<div id="activity-widget">';
	
	    $future_posts = sr_dashboard_recent_post_types( array(
	        'post_type'  => 'projekte',
	        'display' => 5,
	        'max'     => 7,
	        'status'  => 'future',
	        'order'   => 'ASC',
	        'title'   => __( 'Publishing Soon' ),
	        'id'      => 'future-posts',
	    ) );
	
	    $recent_posts = sr_dashboard_recent_post_types( array(
	        'post_type'  => 'projekte',
	        'display' => 5,
	        'max'     => 7,
	        'status'  => 'publish',
	        'order'   => 'DESC',
	        'title'   => __( 'Recently Published' ),
	        'id'      => 'published-posts',
	    ) );
	
	    $recent_comments = wp_dashboard_recent_comments( 10 );
	
	    if ( !$future_posts && !$recent_posts && !$recent_comments ) {
	        echo '<div class="no-activity">';
	        echo '<p class="smiley"></p>';
	        echo '<p>' . __( 'No activity yet!' ) . '</p>';
	        echo '</div>';
	    }
	
	    echo '</div>';
	}
}

// Get Dashboard data
if( ! function_exists( 'sr_dashboard_recent_post_types' )) {	
	function sr_dashboard_recent_post_types( $args ) {
	
		if ( ! $args['post_type'] ) {
			$args['post_type'] = 'any';
		}
	
		$query_args = array(
			'post_type'      => $args['post_type'],
			'post_status'    => $args['status'],
			'orderby'        => 'date',
			'order'          => $args['order'],
			'posts_per_page' => intval( $args['max'] ),
			'no_found_rows'  => true,
			'cache_results'  => false
		);
		$posts = new WP_Query( $query_args );
	
		if ( $posts->have_posts() ) {
	
			echo '<div id="' . $args['id'] . '" class="activity-block">';
	
			if ( $posts->post_count > $args['display'] ) {
				echo '<small class="show-more hide-if-no-js"><a href="#">' . sprintf( __( 'See %s more…'), $posts->post_count - intval( $args['display'] ) ) . '</a></small>';
			}
	
			echo '<h4>' . $args['title'] . '</h4>';
			echo '<ul>';
	
			$i = 0;
			$today    = date( 'Y-m-d', current_time( 'timestamp' ) );
			$tomorrow = date( 'Y-m-d', strtotime( '+1 day', current_time( 'timestamp' ) ) );
	
			while ( $posts->have_posts() ) {
				$posts->the_post();
	
				$time = get_the_time( 'U' );
				if ( date( 'Y-m-d', $time ) == $today ) {
					$relative = __( 'Today' );
				} elseif ( date( 'Y-m-d', $time ) == $tomorrow ) {
					$relative = __( 'Tomorrow' );
				} else {
					// translators: date and time format for recent posts on the dashboard, see http://php.net/date
					$relative = date_i18n( __( 'M jS' ), $time );
				}
				
				$text = sprintf(
				// translators: 1: relative date, 2: time, 4: post title
					__( '<span>%1$s, %2$s</span> <a href="%3$s">%4$s</a>' ),
					$relative,
					get_the_time(),
					get_edit_post_link(),
					_draft_or_post_title()
				);
	
				$hidden = $i >= $args['display'] ? ' class="hidden"' : '';
				echo "<li{$hidden}>$text</li>";
				$i++;
			}
	
			echo '</ul>';
			echo '</div>';
	
		} else {
			return false;
		}
		wp_reset_postdata();
	
		return true;
	}
}
*/


?>