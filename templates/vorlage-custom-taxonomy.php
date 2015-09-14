<?php
/**
 * Custom Taxonomy Template
 *
 * @since       1.0.0
 * @created		28.04.2015
 * @author      Stefan Reichert <reichert@qu-int.com>
 */


// Custom taxonomy: asdfgh
/*##############################################################*/
class Qu_Int_Theme_Functionality_Register_Taxonomies {

    /**
     * Initialize the class
     */
//    public function __construct() {
//		add_action( 'init', array( $this, 'register_taxonomy_asdfgh') );        
//  }

    /**
     * Register Gallerytags Taxonomy
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function register_taxonomy_asdfgh() {	
	    $labels = array(
	            'name'                       => _x( 'Asdfgh Tags', 'taxonomy general name' ),
	            'singular_name'              => _x( 'Asdfgh Tag', 'taxonomy singular name' ),
	            'search_items'               => __( 'Asdfgh Suchen' ),
	            'popular_items'              => __( 'Häufig verwendete Asdfgh' ),
	            'all_items'                  => __( 'All Asdfgh Tags' ),
				'parent_item'      			 => __( 'Übergeordneter Asdfgh' ),
				'parent_item_colon' 		 => __( 'Übergeordnete Asdfgh:' ),
	            'edit_item'                  => __( 'Asdfgh bearbeiten' ),
	            'update_item'                => __( 'Asdfgh aktualisieren' ),
	            'add_new_item'               => __( 'Asdfgh hinzufügen' ),
	            'new_item_name'              => __( 'Neuer Asdfgh' ),
	            'separate_items_with_commas' => __( 'Asdfgh mit Komma trennen' ),
	            'add_or_remove_items'        => __( 'Asdfgh hinzufügen oder entfernen' ),
	            'choose_from_most_used'      => __( 'Wählen Sie aus den meist verwendeten Asdfgh' ),
	            'menu_name'                  => __( 'Asdfgh Tags' ),
				'view_item'					 => __( 'Asdfgh anzeigen' ),
				'not_found'            	  	 => __( 'Asdfgh nicht gefunden' ),
	    );
	    $args = array(
			'label' 				=> 'Asdfgh Label',
			'labels' 				=> $labels,
			'public'       			=> true,															// available for all
			'show_ui'				=> true,
			'show_in_nav_menus' 	=> false,
			'show_tagcloud'			=> false,
			'show_admin_column' 	=> true,
			'hierarchical' 			=> false,
			'query_var' 			=> true,
	        'rewrite'       		=> array( 'slug' => 'asdfgh', 'with_front' => false, 'heirarchical' => false),
	    );
	    register_taxonomy( '1q2w3e_asdfgh', '1q2w3e', $args );
	}
}

?>