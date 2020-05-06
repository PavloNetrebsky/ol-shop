<?php
/*
 * Noo Mega Menu
 */

class Noo_Hermosa_Mega_Menu {

	function __construct() {

		// -- Load enqueue script Noo Mega Menu
			add_action( 'admin_enqueue_scripts', array( $this, 'noo_hermosa_mega_menu_enqueue_script_admin' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'noo_hermosa_mega_menu_enqueue_script' ) );

		// -- Edit menu walker
			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'noo_hermosa_mega_menu_edit_walker'), 10, 2 );

		// -- Setup nav menu item custom fields
			add_filter( 'wp_setup_nav_menu_item', array( $this, 'noo_hermosa_mega_menu_set_item' ) );

		// -- Save info custom fields nav menu item
			add_action( 'wp_update_nav_menu_item', array( $this, 'noo_hermosa_mega_menu_save_fields'), 10, 3 );

	}

	/**
	 * Edit walker detail
	 * @access public
	 * @since  1.0
	 * @name noo_hermosa_mega_menu_edit_walker
	 */
	public function noo_hermosa_mega_menu_edit_walker( $walker, $menu_id ) {

		return 'Noo_Hermosa_Walker_Edit';

	}

	/**
	 * Enqueue script for Noo Mega Menu
	 * @access public
	 * @since  1.0
	 * @name noo_hermosa_mega_menu_enqueue_script
	 */
	public function noo_hermosa_mega_menu_enqueue_script_admin( $hook ) {

		if ( $hook != 'nav-menus.php' ) {
			return;
		}

		// Enqueue style for Mega Menu admin
		wp_register_style( 'noo-megamenu-admin', get_template_directory_uri() . '/includes/admin_assets/css/noo-megamenu-admin.css' );
		wp_enqueue_style( 'noo-megamenu-admin' );

		wp_register_script( 'noo-megamenu-admin-js', get_template_directory_uri() . '/includes/admin_assets/js/min/noo-megamenu-admin.min.js', array( 'wp-color-picker' ), false, true );
		wp_enqueue_script( 'noo-megamenu-admin-js' );

		wp_register_style( 'noo-megamenu-awesome', get_template_directory_uri() . '/assets/vendor/fontawesome/css/font-awesome.min.css' );
		wp_enqueue_style( 'noo-megamenu-awesome' );

	}

	public function noo_hermosa_mega_menu_enqueue_script() {

		wp_register_style( 'noo-megamenu', get_template_directory_uri() . '/includes/admin_assets/css/noo-megamenu.css' );
		wp_enqueue_style( 'noo-megamenu' );


        wp_register_script( 'noo-megamenu-js', get_template_directory_uri() . '/includes/admin_assets/js/min/noo-megamenu.min.js' );
        wp_enqueue_script( 'noo-megamenu-js' );

	}

	/**
	 * Update custom fields columns
	 * @access 	public
	 * @since 	1.0
	 */
	public function noo_hermosa_mega_menu_save_fields( $menu_id, $menu_item_db_id, $args ) {
		// print_r($_REQUEST); die;
		// -- Process Enable megamenu
			if(isset($_REQUEST['menu-item-megamenu'][$menu_item_db_id]) && $_REQUEST['menu-item-megamenu'][$menu_item_db_id] !== ''):
		    
		        $megamenu_value = $_REQUEST['menu-item-megamenu'][$menu_item_db_id];
		        update_post_meta( $menu_item_db_id, '_menu_item_megamenu', $megamenu_value  );
			
			else :

				delete_post_meta ( $menu_item_db_id, '_menu_item_megamenu'  );
			
			endif;

		// -- Process megamenu columns
			if ( isset($_REQUEST['menu-item-megamenu_columns']) && is_array( $_REQUEST['menu-item-megamenu_columns']) ) :
		    
		        $megamenu_col_value = $_REQUEST['menu-item-megamenu_columns'][$menu_item_db_id];
		        update_post_meta( $menu_item_db_id, '_menu_item_megamenu_col', $megamenu_col_value );
		    
		    else :

				delete_post_meta ( $menu_item_db_id, '_menu_item_megamenu_col'  );
			
			endif;

		// -- Process Hide Mega menu heading
			if ( isset($_REQUEST['menu-item-megamenu_heading']) && is_array( $_REQUEST['menu-item-megamenu_heading']) ) :
		    
		        $megamenu_heading_value = $_REQUEST['menu-item-megamenu_heading'][$menu_item_db_id];
		        update_post_meta( $menu_item_db_id, '_menu_item_megamenu_heading', $megamenu_heading_value );
		    
		    else :

				delete_post_meta ( $menu_item_db_id, '_menu_item_megamenu_heading'  );
			
			endif;

		// -- Process Fly Menu
			if ( isset($_REQUEST['menu-item-fly_menu']) && is_array( $_REQUEST['menu-item-fly_menu']) ) :
		    
		        $fly_menu_value = $_REQUEST['menu-item-fly_menu'][$menu_item_db_id];
		        update_post_meta( $menu_item_db_id, '_menu_item_fly_menu', $fly_menu_value );
		    
		    else :

				delete_post_meta ( $menu_item_db_id, '_menu_item_fly_menu'  );
			
			endif;

		// -- Process Hide Mega menu icon
			if ( isset($_REQUEST['menu-item-megamenu_icon']) && is_array( $_REQUEST['menu-item-megamenu_icon']) ) :
		    
		        $megamenu_icon_value = $_REQUEST['menu-item-megamenu_icon'][$menu_item_db_id];
		        update_post_meta( $menu_item_db_id, '_menu_item_megamenu_icon', $megamenu_icon_value );
		    
		    else :

				delete_post_meta ( $menu_item_db_id, '_menu_item_megamenu_icon'  );
			
			endif;

		// -- Process Hide Mega menu icon color
			if ( isset($_REQUEST['menu-item-megamenu_icon_color']) && is_array( $_REQUEST['menu-item-megamenu_icon_color']) ) :
		    
		        $megamenu_icon_color_value = $_REQUEST['menu-item-megamenu_icon_color'][$menu_item_db_id];
		        update_post_meta( $menu_item_db_id, '_menu_item_megamenu_icon_color', $megamenu_icon_color_value );
		    
		    else :

				delete_post_meta ( $menu_item_db_id, '_menu_item_megamenu_icon_color'  );
			
			endif;

		// -- Process Hide Mega menu icon size
			if ( isset($_REQUEST['menu-item-megamenu_icon_size']) && is_array( $_REQUEST['menu-item-megamenu_icon_size']) ) :
		    
		        $megamenu_icon_size_value = $_REQUEST['menu-item-megamenu_icon_size'][$menu_item_db_id];
		        update_post_meta( $menu_item_db_id, '_menu_item_megamenu_icon_size', $megamenu_icon_size_value );
		    
		    else :

				delete_post_meta ( $menu_item_db_id, '_menu_item_megamenu_icon_color'  );
			
			endif;

		// -- Process Hide Mega menu icon alignment
			if ( isset($_REQUEST['menu-item-megamenu_icon_alignment']) && is_array( $_REQUEST['menu-item-megamenu_icon_alignment']) ) :
		    
		        $megamenu_icon_alignment_value = $_REQUEST['menu-item-megamenu_icon_alignment'][$menu_item_db_id];
		        update_post_meta( $menu_item_db_id, '_menu_item_megamenu_icon_alignment', $megamenu_icon_alignment_value );
		    
		    else :

				delete_post_meta ( $menu_item_db_id, '_menu_item_megamenu_icon_alignment'  );
			
			endif;

		// -- Process Mega Menu Widget Area
			if ( isset($_REQUEST['menu-item-megamenu_widgetarea']) && is_array( $_REQUEST['menu-item-megamenu_widgetarea']) ) :
		    
		        $megamenu_widgetarea_value = $_REQUEST['menu-item-megamenu_widgetarea'][$menu_item_db_id];
		        update_post_meta( $menu_item_db_id, '_menu_item_megamenu_widgetarea', $megamenu_widgetarea_value );
		    
		    else :

				delete_post_meta ( $menu_item_db_id, '_menu_item_megamenu_widgetarea'  );
			
			endif;

	}

	/**
	 * Add custom fields to $item nav object
	 * in order to be used in custom Walker
	 *
	 * @access      public
	 * @since       1.0 
	*/
	public function noo_hermosa_mega_menu_set_item( $menu_item ) {
		// -- set item mega menu
			$menu_item->megamenu = get_post_meta( $menu_item->ID, '_menu_item_megamenu', true );

		// -- set item columns
			$menu_item->megamenu_col = get_post_meta( $menu_item->ID, '_menu_item_megamenu_col', true );

		// -- set item hedding
			$menu_item->megamenu_heading = get_post_meta( $menu_item->ID, '_menu_item_megamenu_heading', true );

		// -- set item fly
			$menu_item->fly_menu = get_post_meta( $menu_item->ID, '_menu_item_fly_menu', true );

		// -- set item icon
			$menu_item->megamenu_icon = get_post_meta( $menu_item->ID, '_menu_item_megamenu_icon', true );

		// -- set item icon color
			$menu_item->megamenu_icon_color = get_post_meta( $menu_item->ID, '_menu_item_megamenu_icon_color', true );

		// -- set item icon size
			$menu_item->megamenu_icon_size = get_post_meta( $menu_item->ID, '_menu_item_megamenu_icon_size', true );

		// -- set item icon alignment
			$menu_item->megamenu_icon_alignment = get_post_meta( $menu_item->ID, '_menu_item_megamenu_icon_alignment', true );


		// -- set item widget
			$menu_item->megamenu_widgetarea = get_post_meta( $menu_item->ID, '_menu_item_megamenu_widgetarea', true );
	   
	    return $menu_item;

	} 

}

new Noo_Hermosa_Mega_Menu();

function noo_hermosa_notice_set_menu(){
	echo '';
}

get_template_part('includes/mega-menu/noo-edit-walker');
get_template_part('includes/mega-menu/noo-custom-walker');