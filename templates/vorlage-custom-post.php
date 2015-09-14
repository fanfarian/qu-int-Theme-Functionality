<?php
/**
 * Custom Post Template
 *
 * @since       1.0.0
 * @created		28.04.2015
 * @author      Stefan Reichert <reichert@qu-int.com>
 */


// Custom post type: Qwertz
/*##############################################################*/
class Qu_Int_Theme_Functionality_Register_Post_Types {

    /**
     * Initialize the class
     */
//    public function __construct() {
//	   add_action( 'init', array( $this, 'register_custom_post_1q2w3e' ) );     
//    }


    /**
     * Register Screenshots Post Type
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
	public function register_custom_post_1q2w3e() {
	    $labels = array(
			'name'					=> _x( 'Qwertz Name' ),
			'singular_name'			=> _x( 'Qwertz Singular' ),
			'menu_name'				=> _x( 'Qwertz Menu' ),
			'name_admin_bar'		=> _x( 'Qwertz Admin' ),
			'add_new'				=> _x( 'Qwertz hinzufügen' ),
			'add_new_item'			=> __( 'Neues Qwertz' ),
	        'new_item'				=> __( 'Neues Qwertz' ),
			'edit_item'				=> __( 'Qwertz bearbeiten' ),
			'view_item'				=> __( 'Qwertz-Seite anzeigen' ),
			'all_items'				=> __( 'Alle Qwertz' ),
			'search_items'			=> __( 'Qwertz Suchen' ),
			'parent_item_colon'   	=> __( 'Übergeordnete Qwertz' ),
			'not_found'				=> __( 'Keine Qwertz gefunden' ),
			'not_found_in_trash'	=> __( 'Keine Qwertz im Papierkorb gefunden' ),
	    );
		
		$args = array(
			'label'               	=> __( 'Qwertz Label' ),
			'labels'              	=> $labels,
			'description'         	=> __( 'Qwertz Beschreibung' ),
			'public'       		  	=> true,																// available for all
			'exclude_from_search'	=> false,
			'publicly_queryable'  	=> true,
			'show_ui'             	=> true,
			'show_in_nav_menus' 	=> false,
			'show_in_menu'        	=> true,
			'show_in_admin_bar'   	=> true,
			'menu_position' 		=> 20,																	// position in admin menu
			'menu_icon' 		  	=> 'dashicons-list-view',												// https://developer.wordpress.org/resource/dashicons
	        'capability_type'     	=> 'page',																// Page or Post
			'hierarchical' 			=> false,																// no hierachical posts
			'supports' 				=> array('title','editor','thumbnail','revisions','page-attributes'),	// available standard content
			'taxonomies' 			=> array('asdfgh'),														// Custom or default taxonomies
			'has_archive'  			=> true,																// with archive page
	        'rewrite'       		=> array( 'slug' => '1q2w3e', 'with_front' => false),
			'query_var' 			=> true,
			'can_export'          	=> true,
	    );
	    register_post_type( '1q2w3e', $args );
	}
}


// Custom columns for custom posts
/*########################################################################################################################################################################################*/
/*
function qwp_1q2w3e_columns($columns) {
	unset($columns['date']);																// unset date column for later modification
	$new_columns = array(
		'123' 		=> 'Qwertz',															// define column
		'456'		=> 'Asdfgh',															// define columns
		'789' 		=> 'Yxcvbn',															// define column
		'date' 		=> 'Datum',																// define columns
	);
    return array_merge($columns, $new_columns);												// return columns modified array
}
add_filter('manage_1q2w3e_posts_columns' , 'qwp_1q2w3e_columns');							// add custom columns to overview



function qwp_custom_columns($col, $id) {
   switch($col) {

	case '123':
		if (function_exists('get_field_object') && function_exists('get_field')){			// ACF existiert
			if(get_field('fokusprojekt', $id))
				echo 'X';																	// Feld ausgeben
		}
	break;
	
	case '456':
		if (function_exists('get_field_object') && function_exists('get_field')){			// ACF existiert
			$taxonomy = get_field('gebaeudetyp', $id);										// Taxonomy Object
			if($taxonomy){
				echo $taxonomy->name;														// Taxonomy Name
			}
		}
	break;

	case '789':
		if (function_exists('get_field_object') && function_exists('get_field')){			// ACF existiert
			$taxonomy = get_field('leistungen', $id);										// Taxonomy Object					
			if($taxonomy){				
				foreach($taxonomy as $key)													// Taxonomy Array für mehrere Werte
					echo $key->name.'<br>';												// Taxonomy Name
			}	
		}
	break;

   }
}
add_action('manage_posts_custom_column' , 'qwp_custom_columns', 10, 2 );						// get custom columns from database
*/

?>