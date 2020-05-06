<?php
/**
 * New shortcode params to use on Timetable's shortcode
 *
 * @package     NooTimetable/Shortcodes
 * @since       2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
// Multiple Select
if ( ! function_exists( 'noo_timetable_class_multiple_select_param' ) ) :

	function noo_timetable_class_multiple_select_param( $settings, $value ) {
		$class           = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
		$param_value 	 = isset($settings['value']) ? $settings['value'] : '';
		$selected_values = explode( ',', $value );
		$html            = array( '<div class="noo_vc_custom_param class_category">' );
		$html[]          = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value . '" class="wpb_vc_param_value" />';
		$html[]          = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '" ' . '>';
		foreach ( $param_value as $text_val => $val ) {
			$html[] = '    <option value="' . $val . '" ' . ( in_array( $val,
					$selected_values ) ? 'selected="true"' : '' ) . '>';
			$html[] = '      ' . $text_val;
			$html[] = '    </option>';

		}

		$html[] = '  </select>';
		$html[] = '</div>';
		$html[] = '<script>';
		$html[] = '  jQuery("document").ready( function() {';
		$html[] = '    jQuery( "select[name=\'' . $settings['param_name'] . '-select\']" ).click( function() {';
		$html[] = '      var selected_values = jQuery(this).find("option:selected").map(function(){ return this.value; }).get().join(",");';
		$html[] = '      jQuery( "input[name=\'' . $settings['param_name'] . '\']" ).val( selected_values );';
		$html[] = '    } );';
		$html[] = '  } );';
		$html[] = '</script>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'multiselect', 'noo_timetable_class_multiple_select_param' );

endif;

if ( ! function_exists( 'noo_timetable_dropdown_group_param' ) ) :

	function noo_timetable_dropdown_group_param( $param, $param_value ) {
		$css_option = ns_get_dropdown_option( $param, $param_value );
		$param_line = '';
		$param_line .= '<select name="' . $param['param_name'] . '" class="dh-chosen-select wpb_vc_param_value wpb-input wpb-select ' . $param['param_name'] . ' ' . $param['type'] . ' ' . $css_option . '" data-option="' . $css_option . '">';
		foreach ( $param['optgroup'] as $text_opt => $opt ) {
			if ( is_array( $opt ) ) {
				$param_line .= '<optgroup label="' . $text_opt . '">';
				foreach ( $opt as $text_val => $val ) {
					if ( is_numeric( $text_val ) && ( is_string( $val ) || is_numeric( $val ) ) ) {
						$text_val = $val;
					}
					$selected = '';
					if ( $param_value !== '' && (string) $val === (string) $param_value ) {
						$selected = ' selected="selected"';
					}
					$param_line .= '<option class="' . $val . '" value="' . $val . '"' . $selected . '>' . htmlspecialchars( $text_val ) . '</option>';
				}
				$param_line .= '</optgroup>';
			} elseif ( is_string( $opt ) ) {
				if ( is_numeric( $text_opt ) && ( is_string( $opt ) || is_numeric( $opt ) ) ) {
					$text_opt = $opt;
				}
				$selected = '';
				if ( $param_value !== '' && (string) $opt === (string) $param_value ) {
					$selected = ' selected="selected"';
				}
				$param_line .= '<option class="' . $opt . '" value="' . $opt . '"' . $selected . '>' . htmlspecialchars( $text_opt ) . '</option>';
			}
		}
		$param_line .= '</select>';

		return $param_line;
	}

	ns_add_shortcode_param( 'noo_dropdown_group', 'noo_timetable_dropdown_group_param' );

endif;
// Categories select field type
if ( ! function_exists( 'noo_timetable_post_categories_param' ) ) :

	function noo_timetable_post_categories_param( $settings, $value ) {
		$categories      = get_categories( array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
		$class           = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
		$selected_values = explode( ',', $value );
		$html            = array( '<div class="noo_vc_custom_param post_categories">' );
		$html[]          = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value . '" class="wpb_vc_param_value" />';
		$html[]          = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '>';
		$html[]          = '    <option value="all" ' . ( in_array( 'all',
				$selected_values ) ? 'selected="true"' : '' ) . '>' . esc_html__( 'All',
				'noo-timetable' ) . '</option>';
		foreach ( $categories as $category ) {
			$html[] = '    <option value="' . $category->term_id . '" ' . ( in_array( $category->term_id,
					$selected_values ) ? 'selected="true"' : '' ) . '>';
			$html[] = '      ' . $category->name;
			$html[] = '    </option>';
		}

		$html[] = '  </select>';
		$html[] = '</div>';
		$html[] = '<script>';
		$html[] = '  jQuery("document").ready( function() {';
		$html[] = '	   jQuery( "select[name=\'' . $settings['param_name'] . '-select\']" ).click( function() {';
		$html[] = '      var selected_values = jQuery(this).find("option:selected").map(function(){ return this.value; }).get().join(",");';
		$html[] = '      jQuery( "input[name=\'' . $settings['param_name'] . '\']" ).val( selected_values );';
		$html[] = '	   } );';
		$html[] = '  } );';
		$html[] = '</script>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'post_categories', 'noo_timetable_post_categories_param' );

endif;

// Categories select field type
if ( ! function_exists( 'noo_timetable_post_tags_param' ) ) :

	function noo_timetable_post_tags_param( $settings, $value ) {
		$categories      = get_tags( array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
		$class           = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
		$selected_values = explode( ',', $value );
		$html            = array( '<div class="noo_vc_custom_param post_categories">' );
		$html[]          = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value . '" class="wpb_vc_param_value" />';
		$html[]          = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '" ' . '>';
		$html[]          = '    <option value="all" ' . ( in_array( 'all',
				$selected_values ) ? 'selected="true"' : '' ) . '>' . esc_html__( 'All',
				'noo-timetable' ) . '</option>';
		foreach ( $categories as $category ) {
			$html[] = '    <option value="' . $category->term_id . '" ' . ( in_array( $category->term_id,
					$selected_values ) ? 'selected="true"' : '' ) . '>';
			$html[] = '      ' . $category->name;
			$html[] = '    </option>';
		}

		$html[] = '  </select>';
		$html[] = '</div>';
		$html[] = '<script>';
		$html[] = '  jQuery("document").ready( function() {';
		$html[] = '	   jQuery( "select[name=\'' . $settings['param_name'] . '-select\']" ).click( function() {';
		$html[] = '      var selected_values = jQuery(this).find("option:selected").map(function(){ return this.value; }).get().join(",");';
		$html[] = '      jQuery( "input[name=\'' . $settings['param_name'] . '\']" ).val( selected_values );';
		$html[] = '	   } );';
		$html[] = '  } );';
		$html[] = '</script>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'post_tags', 'noo_timetable_post_tags_param' );

endif;// Categories select field type

if ( ! function_exists( 'noo_timetable_gallery_category_param' ) ) :

	function noo_timetable_gallery_category_param( $settings, $value ) {
		if ( taxonomy_exists( 'gallery_category' ) ):
			$categories      = get_terms( 'gallery_category', array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
			$class           = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
			$selected_values = explode( ',', $value );
			$html            = array( '<div class="noo_vc_custom_param project_categories">' );
			$html[]          = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value . '" class="wpb_vc_param_value" />';
			$html[]          = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '">';
			foreach ( $categories as $category ) {
				$html[] = '    <option value="' . $category->term_id . '" ' . ( in_array( $category->term_id,
						$selected_values ) ? 'selected="true"' : '' ) . '>';
				$html[] = '      ' . $category->name;
				$html[] = '    </option>';
			}

			$html[] = '  </select>';
			$html[] = '</div>';
			$html[] = '<script>';
			$html[] = '  jQuery("document").ready( function() {';
			$html[] = '	   jQuery( "select[name=\'' . $settings['param_name'] . '-select\']" ).click( function() {';
			$html[] = '      var selected_values = jQuery(this).find("option:selected").map(function(){ return this.value; }).get().join(",");';
			$html[] = '      jQuery( "input[name=\'' . $settings['param_name'] . '\']" ).val( selected_values );';
			$html[] = '	   } );';
			$html[] = '  } );';
			$html[] = '</script>';

			return implode( "\n", $html );
		endif;

		return '';
	}

	ns_add_shortcode_param( 'gallery_category', 'noo_timetable_gallery_category_param' );

endif;

if ( ! function_exists( 'noo_timetable_post_categories_product_cat_param' ) ) :

	function noo_timetable_post_categories_product_cat_param( $settings, $value ) {
		$categories      = get_categories( array(
			'orderby'  => 'NAME',
			'order'    => 'ASC',
			'taxonomy' => 'product_cat',
		) );
		$class           = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
		$selected_values = explode( ',', $value );
		$html            = array( '<div class="noo_vc_custom_param post_categories">' );
		$html[]          = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value . '" class="wpb_vc_param_value" />';
		if ( isset( $categories ) && ! empty( $categories ) && taxonomy_exists( 'product_cat' ) ) :
			$html[] = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '">';
			foreach ( $categories as $category ) {
				$html[] = '    <option value="' . intval( $category->term_id ) . '" ' . ( in_array( $category->term_id,
						$selected_values ) ? 'selected="true"' : '' ) . '>';
				$html[] = '      ' . $category->name;
				$html[] = '    </option>';
			}

			$html[] = '  </select>';
		endif;
		$html[] = '</div>';
		$html[] = '<script>';
		$html[] = '  jQuery("document").ready( function() {';
		$html[] = '    jQuery( "select[name=\'' . $settings['param_name'] . '-select\']" ).click( function() {';
		$html[] = '      var selected_values = jQuery(this).find("option:selected").map(function(){ return this.value; }).get().join(",");';
		$html[] = '      jQuery( "input[name=\'' . $settings['param_name'] . '\']" ).val( selected_values );';
		$html[] = '    } );';
		$html[] = '  } );';
		$html[] = '</script>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'noo_product_cat', 'noo_timetable_post_categories_product_cat_param' );

endif;

/**
 * Create field event category
 */

if ( ! function_exists( 'noo_timetable_event_category_param' ) ) :
	function noo_timetable_event_category_param( $settings, $value ) {
		$categories      = get_categories( array(
			'taxonomy' => 'event_category',
		) );
		$class           = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
		$selected_values = explode( ',', $value );
		$html            = array( '<div class="noo_vc_custom_param post_categories">' );
		$html[]          = '  <input type="hidden" name="' . $settings['param_name'] . '" value="" class="wpb_vc_param_value" />';
		if ( isset( $categories ) && ! empty( $categories ) && taxonomy_exists( 'event_category' ) ) :
			$html[] = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '">';
			foreach ( $categories as $category ) {
				$html[] = '    <option value="' . intval( $category->term_id ) . '" ' . ( in_array( $category->term_id,
						$selected_values ) ? 'selected="true"' : '' ) . '>';
				$html[] = '      ' . $category->name;
				$html[] = '    </option>';
			}

			$html[] = '  </select>';
		endif;
		$html[] = '</div>';
		$html[] = '<script>';
		$html[] = '  jQuery("document").ready( function($) {';
		$html[] = '    $( "select[name=\'' . $settings['param_name'] . '-select\']" ).chosen({';
		$html[] = '      width: "100%",';
		$html[] = '    });';
		if(esc_attr( $value ) == '') {
			$html[] = '    var order = $( "select[name=\'' . $settings['param_name'] . '-select\']" ).getSelectionOrder();';
		} else {
			$html[] = '    var order = \''.esc_attr( $value ).'\';';
			$html[] = '    order = order.split(",");';
		}
		$html[] = '    $( "select[name=\'' . $settings['param_name'] . '-select\']" ).setSelectionOrder(order);';
		$html[] = '    $( "select[name=\'' . $settings['param_name'] . '-select\']" ).unbind("change").bind( "change", function(e, params) {';
		$html[] = '      var input_val = $( "input[name=\'' . $settings['param_name'] . '\']" ).val();';
		$html[] = '      var check_selected = "selected" in params;';
		$html[] = '      var input_val_arr = input_val.split(",");';
		$html[] = '      var new_val = "";';
		$html[] = '      if(check_selected) {';
		$html[] = '        if(input_val == "") {';
		$html[] = '          new_val = input_val + params.selected;';
		$html[] = '        } else {';
		$html[] = '          new_val = input_val +","+ params.selected;';
		$html[] = '        }';
		$html[] = '      } else {';
		$html[] = '        new_val = input_val_arr.filter( function(ele) {';
		$html[] = '          return ele != params.deselected;';
		$html[] = '        });';
		$html[] = '        new_val = new_val.join(",");';
		$html[] = '      }';
		$html[] = '      $( "input[name=\'' . $settings['param_name'] . '\']" ).val( new_val );';
		$html[] = '    });';
		$html[] = '  });';
		$html[] = '</script>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'event_categories', 'noo_timetable_event_category_param');

endif;

/**
 * Create field event category
 */

if ( ! function_exists( 'noo_timetable_event_location_param' ) ) :

	function noo_timetable_event_location_param( $settings, $value ) {

		$categories      = get_categories( array(
			'orderby'  => 'NAME',
			'order'    => 'ASC',
			'taxonomy' => 'event_location',
		) );
		$class           = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
		$selected_values = explode( ',', $value );
		$html            = array( '<div class="noo_vc_custom_param post_categories">' );
		$html[]          = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value . '" class="wpb_vc_param_value" />';
		if ( isset( $categories ) && ! empty( $categories ) && taxonomy_exists( 'event_location' ) ) :
			$html[] = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '">';
			foreach ( $categories as $location ) {
				$html[] = '    <option value="' . intval( $location->term_id ) . '" ' . ( in_array( $location->term_id,
						$selected_values ) ? 'selected="true"' : '' ) . '>';
				$html[] = '      ' . $location->name;
				$html[] = '    </option>';
			}

			$html[] = '  </select>';
		endif;
		$html[] = '</div>';
		$html[] = '<script>';
		$html[] = '  jQuery("document").ready( function() {';
		$html[] = '    jQuery( "select[name=\'' . $settings['param_name'] . '-select\']" ).click( function() {';
		$html[] = '      var selected_values = jQuery(this).find("option:selected").map(function(){ return this.value; }).get().join(",");';
		$html[] = '      jQuery( "input[name=\'' . $settings['param_name'] . '\']" ).val( selected_values );';
		$html[] = '    } );';
		$html[] = '  } );';
		$html[] = '</script>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'noo_event_location', 'noo_timetable_event_location_param' );

endif;

if ( ! function_exists( 'noo_timetable_trainer_categories_param' ) ) :

	function noo_timetable_trainer_categories_param( $settings, $value ) {
		$categories      = get_terms( 'class_category', array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
		$class           = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
		$selected_values = explode( ',', $value );
		$html            = array( '<div class="noo_vc_custom_param trainer_categories">' );
		$html[]          = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value . '" class="wpb_vc_param_value" />';
		$html[]          = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '" ' . '>';
		foreach ( $categories as $category ) {
			$html[] = '    <option value="' . $category->term_id . '" ' . ( in_array( $category->term_id,
					$selected_values ) ? 'selected="true"' : '' ) . '>';
			$html[] = '      ' . $category->name;
			$html[] = '    </option>';
		}

		$html[] = '  </select>';
		$html[] = '</div>';
		$html[] = '<script>';
		$html[] = '  jQuery("document").ready( function($) {';
		$html[] = '    $( "select[name=\'' . $settings['param_name'] . '-select\']" ).chosen({';
		$html[] = '      width: "100%",';
		$html[] = '    });';
		if(esc_attr( $value ) == '') {
			$html[] = '    var order = $( "select[name=\'' . $settings['param_name'] . '-select\']" ).getSelectionOrder();';
		} else {
			$html[] = '    var order = \''.esc_attr( $value ).'\';';
			$html[] = '    order = order.split(",");';
		}
		$html[] = '    $( "select[name=\'' . $settings['param_name'] . '-select\']" ).setSelectionOrder(order);';
		$html[] = '    $( "select[name=\'' . $settings['param_name'] . '-select\']" ).unbind("change").bind( "change", function(e, params) {';
		$html[] = '      var input_val = $( "input[name=\'' . $settings['param_name'] . '\']" ).val();';
		$html[] = '      var check_selected = "selected" in params;';
		$html[] = '      var input_val_arr = input_val.split(",");';
		$html[] = '      var new_val = "";';
		$html[] = '      if(check_selected) {';
		$html[] = '        if(input_val == "") {';
		$html[] = '          new_val = input_val + params.selected;';
		$html[] = '        } else {';
		$html[] = '          new_val = input_val +","+ params.selected;';
		$html[] = '        }';
		$html[] = '      } else {';
		$html[] = '        new_val = input_val_arr.filter( function(ele) {';
		$html[] = '          return ele != params.deselected;';
		$html[] = '        });';
		$html[] = '        new_val = new_val.join(",");';
		$html[] = '      }';
		$html[] = '      $( "input[name=\'' . $settings['param_name'] . '\']" ).val( new_val );';
		$html[] = '    });';
		$html[] = '  });';
		$html[] = '</script>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'trainer_categories', 'noo_timetable_trainer_categories_param' );

endif;

if ( ! function_exists( 'noo_timetable_datepicker_param' ) ) :
	function noo_timetable_datepicker_param( $settings, $value ) {

		$html   = array( '<div class="my_param_block">' );
		$html[] = '<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput wpb-noo-date' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" />';
		$html[] = '</div>';
		$html[] = '<script>';
		$html[] = '  jQuery("document").ready( function() {';
		if ( isset( $settings['format'] ) && ! empty( $settings['format'] ) ) {
			$html[] = 'jQuery(".noo_datetimepicker_field").datetimepicker({ ';
			$html[] = 'format: "' . esc_attr( $settings['format'] ) . '", ';
			$html[] = 'scrollInput: false, ';
			$html[] = 'timepicker: false, ';
			$html[] = 'datepicker: true, ';
			$html[] = '})';
		} else {
			$html[] = 'jQuery(".noo_datetimepicker_field").datetimepicker()';
		}
		$html[] = '  } );';
		$html[] = '</script>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'noo_datetimepicker', 'noo_timetable_datepicker_param' );

endif;

if ( ! function_exists( 'noo_timetable_class_categories_param' ) ) :

	function noo_timetable_class_categories_param( $settings, $value ) {
		$categories      = get_terms( 'class_category', array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
		$class           = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
		$selected_values = explode( ',', $value );
		$html            = array( '<div class="noo_vc_custom_param class_category">' );
		$html[]          = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value . '" class="wpb_vc_param_value" />';
		$html[]          = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '" ' . '>';
		foreach ( $categories as $category ) {
			$html[] = '    <option value="' . $category->term_id . '" ' . ( in_array( $category->term_id,
					$selected_values ) ? 'selected="true"' : '' ) . '>';
			$html[] = '      ' . $category->name;
			$html[] = '    </option>';
		}

		$html[] = '  </select>';
		$html[] = '</div>';
		$html[] = '<script>';
		$html[] = '  jQuery("document").ready( function($) {';
		$html[] = '    jQuery( "select[name=\'' . $settings['param_name'] . '-select\']" ).chosen({';
		$html[] = '      width: "100%",';
		$html[] = '    });';
		if(esc_attr( $value ) == '') {
			$html[] = '    var order = $( "select[name=\'' . $settings['param_name'] . '-select\']" ).getSelectionOrder();';
		} else {
			$html[] = '    var order = \''.esc_attr( $value ).'\';';
			$html[] = '    order = order.split(",");';
		}
		$html[] = '    $( "select[name=\'' . $settings['param_name'] . '-select\']" ).setSelectionOrder(order);';
		$html[] = '    jQuery( "select[name=\'' . $settings['param_name'] . '-select\']" ).unbind("change").bind( "change", function(e, params) {';
		$html[] = '      var input_val = jQuery( "input[name=\'' . $settings['param_name'] . '\']" ).val();';
		$html[] = '      var check_selected = "selected" in params;';
		$html[] = '      var input_val_arr = input_val.split(",");';
		$html[] = '      var new_val = "";';
		$html[] = '      if(check_selected) {';
		$html[] = '        if(input_val == "") {';
		$html[] = '          new_val = input_val + params.selected;';
		$html[] = '        } else {';
		$html[] = '          new_val = input_val +","+ params.selected;';
		$html[] = '        }';
		$html[] = '      } else {';
		$html[] = '        new_val = input_val_arr.filter( function(ele) {';
		$html[] = '          return ele != params.deselected;';
		$html[] = '        });';
		$html[] = '        new_val = new_val.join(",");';
		$html[] = '      }';
		$html[] = '      jQuery( "input[name=\'' . $settings['param_name'] . '\']" ).val( new_val );';
		$html[] = '    });';
		$html[] = '  });';
		$html[] = '</script>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'class_categories', 'noo_timetable_class_categories_param' );

endif;

if ( ! function_exists( 'noo_timetable_class_levels_param' ) ) :

	function noo_timetable_class_levels_param( $settings, $value ) {
		$levels      = get_terms( 'class_level', array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
		$class           = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
		$selected_values = explode( ',', $value );
		$html            = array( '<div class="noo_vc_custom_param class_category">' );
		$html[]          = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value . '" class="wpb_vc_param_value" />';
		$html[]          = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '" ' . '>';
		foreach ( $levels as $level ) {
			$html[] = '    <option value="' . $level->term_id . '" ' . ( in_array( $level->term_id,
					$selected_values ) ? 'selected="true"' : '' ) . '>';
			$html[] = '      ' . $level->name;
			$html[] = '    </option>';
		}

		$html[] = '  </select>';
		$html[] = '</div>';
		$html[] = '<script>';
		$html[] = '  jQuery("document").ready( function($) {';
		$html[] = '    $( "select[name=\'' . $settings['param_name'] . '-select\']" ).chosen({';
		$html[] = '      width: "100%",';
		$html[] = '    });';
		if(esc_attr( $value ) == '') {
			$html[] = '    var order = $( "select[name=\'' . $settings['param_name'] . '-select\']" ).getSelectionOrder();';
		} else {
			$html[] = '    var order = \''.esc_attr( $value ).'\';';
			$html[] = '    order = order.split(",");';
		}
		$html[] = '    $( "select[name=\'' . $settings['param_name'] . '-select\']" ).setSelectionOrder(order);';
		$html[] = '    $( "select[name=\'' . $settings['param_name'] . '-select\']" ).unbind("change").bind( "change", function(e, params) {';
		$html[] = '      var input_val = $( "input[name=\'' . $settings['param_name'] . '\']" ).val();';
		$html[] = '      var check_selected = "selected" in params;';
		$html[] = '      var input_val_arr = input_val.split(",");';
		$html[] = '      var new_val = "";';
		$html[] = '      if(check_selected) {';
		$html[] = '        if(input_val == "") {';
		$html[] = '          new_val = input_val + params.selected;';
		$html[] = '        } else {';
		$html[] = '          new_val = input_val +","+ params.selected;';
		$html[] = '        }';
		$html[] = '      } else {';
		$html[] = '        new_val = input_val_arr.filter( function(ele) {';
		$html[] = '          return ele != params.deselected;';
		$html[] = '        });';
		$html[] = '        new_val = new_val.join(",");';
		$html[] = '      }';
		$html[] = '      $( "input[name=\'' . $settings['param_name'] . '\']" ).val( new_val );';
		$html[] = '    });';
		$html[] = '  });';
		$html[] = '</script>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'class_levels', 'noo_timetable_class_levels_param' );

endif;

if ( ! function_exists( 'noo_timetable_class_trainers_param' ) ) :

	function noo_timetable_class_trainers_param( $settings, $value ) {
		$trainers = get_posts([
			'post_type' => 'noo_trainer',
			'post_status' => 'publish',
			'numberposts' => -1,
		]);
		$class           = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
		$selected_values = explode( ',', $value );
		$html            = array( '<div class="noo_vc_custom_param class_category">' );
		$html[]          = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value . '" class="wpb_vc_param_value" />';
		$html[]          = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '" ' . '>';
		foreach ( $trainers as $trainer ) {
			$html[] = '    <option value="' . $trainer->ID . '" ' . ( in_array( $trainer->ID,
					$selected_values ) ? 'selected="true"' : '' ) . '>';
			$html[] = '      ' . $trainer->post_title;
			$html[] = '    </option>';
		}

		$html[] = '  </select>';
		$html[] = '</div>';
		$html[] = '<script>';
		$html[] = '  jQuery("document").ready( function($) {';
		$html[] = '    $( "select[name=\'' . $settings['param_name'] . '-select\']" ).chosen({';
		$html[] = '      width: "100%",';
		$html[] = '    });';
		if(esc_attr( $value ) == '') {
			$html[] = '    var order = $( "select[name=\'' . $settings['param_name'] . '-select\']" ).getSelectionOrder();';
		} else {
			$html[] = '    var order = \''.esc_attr( $value ).'\';';
			$html[] = '    order = order.split(",");';
		}
		$html[] = '    $( "select[name=\'' . $settings['param_name'] . '-select\']" ).setSelectionOrder(order);';
		$html[] = '    $( "select[name=\'' . $settings['param_name'] . '-select\']" ).unbind("change").bind( "change", function(e, params) {';
		$html[] = '      var input_val = $( "input[name=\'' . $settings['param_name'] . '\']" ).val();';
		$html[] = '      var check_selected = "selected" in params;';
		$html[] = '      var input_val_arr = input_val.split(",");';
		$html[] = '      var new_val = "";';
		$html[] = '      if(check_selected) {';
		$html[] = '        if(input_val == "") {';
		$html[] = '          new_val = input_val + params.selected;';
		$html[] = '        } else {';
		$html[] = '          new_val = input_val +","+ params.selected;';
		$html[] = '        }';
		$html[] = '      } else {';
		$html[] = '        new_val = input_val_arr.filter( function(ele) {';
		$html[] = '          return ele != params.deselected;';
		$html[] = '        });';
		$html[] = '        new_val = new_val.join(",");';
		$html[] = '      }';
		$html[] = '      $( "input[name=\'' . $settings['param_name'] . '\']" ).val( new_val );';
		$html[] = '    });';
		$html[] = '  });';
		$html[] = '</script>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'class_trainers', 'noo_timetable_class_trainers_param' );

endif;

if ( ! function_exists( 'noo_timetable_time_range_lists_param' ) ) :

    function noo_timetable_time_range_lists_param($settings, $value)
	{

		$class           = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
		$selected_values = explode( ',', $value );
		$html            = array( '<div class="noo_vc_custom_param time_range_lists">' );
		$html[]          = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value . '" class="wpb_vc_param_value" />';
		$html[]          = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '" ' . '>';

		// Get hours
		$hours = range( 1, 24 );
		foreach ( $hours as $key => $hour ) {
			$key++;
			$html[] = '    <option value="' . $key . '" ' . ( in_array( (string) $key,
					$selected_values ) ? 'selected="true"' : '' ) . '>';
			$html[] = '      ' . $hour . ':00:00';
			$html[] = '    </option>';
		}

		$html[] = '  </select>';
		$html[] = '</div>';
		$html[] = '<script>
		function range(start, end) {
    var ans = [];
    for (let i = start; i <= end; i++) {
        ans.push(i);
    }
    return ans;
}
';
		$html[] = '  jQuery("document").ready( function() {';
		$html[] = ' var min_time = jQuery(".min_time").length > 0 ? jQuery(".min_time").val() : "0:00:00";
		var max_time = jQuery(".max_time").length > 0 ? jQuery(".max_time").val() : "24:00:00";

		jQuery(".min_time").on( "change", function(e) {
			var _this = jQuery(this);
			var max_time = jQuery(".max_time").length > 0 ? jQuery(".max_time").val() : 24;
			var min_time_arr = _this.val().split(":");
			var max_time_arr = max_time.split(":");
			var max_time_hours = max_time_arr[0];
			var min_time_hours = min_time_arr[0];
			var new_range = range(parseInt(min_time_hours), parseInt(max_time_hours));
			var new_opts = "";
			var selected = jQuery( "input[name=\'' . $settings['param_name'] . '\']" ).val();
			var selected_arr = selected.split(",");
			for(var r = 0; r < new_range.length; r++) {
				var selected_str = "";
				if(selected_arr.indexOf(new_range[r].toString()) > -1) {
					selected_str = "selected";
				}
				new_opts += "<option value=\'"+new_range[r]+"\' "+selected_str+">"+new_range[r]+":00:00</option>";
			}
			jQuery( "select[name=\'' . $settings['param_name'] . '-select\'] option" ).remove();
			jQuery( "select[name=\'' . $settings['param_name'] . '-select\']" ).append(new_opts).trigger("chosen:updated");
		});
		jQuery(".max_time").on( "change", function(e) {
			var _this = jQuery(this);
			var min_time = jQuery(".min_time").length > 0 ? jQuery(".min_time").val() : 0;
			var min_time_arr = min_time.split(":");
			var max_time_arr = _this.val().split(":");
			var max_time_hours = max_time_arr[0];
			var min_time_hours = min_time_arr[0];
			var new_range = range(parseInt(min_time_hours), parseInt(max_time_hours));
			var new_opts = "";
			var selected = jQuery( "input[name=\'' . $settings['param_name'] . '\']" ).val();
			var selected_arr = selected.split(",");
			for(var r = 0; r < new_range.length; r++) {
				var selected_str = "";
				if(selected_arr.indexOf(new_range[r].toString()) > -1) {
					selected_str = "selected";
				}
				new_opts += "<option value=\'"+new_range[r]+"\' "+selected_str+">"+new_range[r]+":00:00</option>";
			}
			jQuery( "select[name=\'' . $settings['param_name'] . '-select\'] option" ).remove();
			jQuery( "select[name=\'' . $settings['param_name'] . '-select\']" ).append(new_opts).trigger("chosen:updated");
		});
		setTimeout( function() {
			jQuery(".min_time").trigger("change");
		}, 2000);
		';
		$html[] = '    jQuery( "select[name=\'' . $settings['param_name'] . '-select\']" ).chosen({';
		$html[] = '      width: "100%",';
		$html[] = '      display_selected_options: false,';
		$html[] = '    });';
		$html[] = '    jQuery( "select[name=\'' . $settings['param_name'] . '-select\']" ).unbind("change").bind( "change", function(e, params) {';
		$html[] = '      var input_val = jQuery( "input[name=\'' . $settings['param_name'] . '\']" ).val();';
		$html[] = '      var check_selected = "selected" in params;';
		$html[] = '      var input_val_arr = input_val.split(",");';
		$html[] = '      var new_val = "";';
		$html[] = '      if(check_selected) {';
		$html[] = '        if(input_val == "") {';
		$html[] = '          new_val = input_val + params.selected;';
		$html[] = '        } else {';
		$html[] = '          new_val = input_val +","+ params.selected;';
		$html[] = '        }';
		$html[] = '      } else {';
		$html[] = '        new_val = input_val_arr.filter( function(ele) {';
		$html[] = '          return ele != params.deselected;';
		$html[] = '        });';
		$html[] = '        new_val = new_val.join(",");';
		$html[] = '      }';
		$html[] = '      jQuery( "input[name=\'' . $settings['param_name'] . '\']" ).val( new_val );';
		$html[] = '    });';
		$html[] = '  } );';
		$html[] = '</script>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'time_range_lists', 'noo_timetable_time_range_lists_param' );

endif;

if ( ! function_exists( 'noo_timetable_user_list_param' ) ) :

	function noo_timetable_user_list_param( $settings, $value ) {
		$users = get_users( array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
		$class = 'wpb_vc_param_value wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
		$html  = array( '<div class="noo_vc_custom_param user_list">' );
		// $html[] = ' <input type="hidden" name="'. $settings['param_name'] . '" value="'. $value . '"
		// class="wpb_vc_param_value" />';
		$html[] = '  <select name="' . $settings['param_name'] . '" class="' . $class . '" ' . '>';
		foreach ( $users as $user ) {
			$html[] = '    <option value="' . $user->ID . '" ' . ( selected( $value, $user->ID, false ) ) . '>';
			$html[] = '      ' . $user->display_name;
			$html[] = '    </option>';
		}

		$html[] = '  </select>';
		$html[] = '</div>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'user_list', 'noo_timetable_user_list_param' );

endif;

if ( class_exists( 'RevSlider' ) ) {
	if ( ! function_exists( 'noo_timetable_noo_rev_slider_param' ) ) :

		function noo_timetable_noo_rev_slider_param( $settings, $value ) {
			$rev_slider = new RevSlider();
			$sliders    = $rev_slider->getArrSliders();
			$class      = 'wpb_vc_param_value wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
			$html       = array( '<div class="noo_vc_custom_param noo_rev_slider">' );
			$html[]     = '  <select name="' . $settings['param_name'] . '" class="' . $class . '" ' . '>';
			foreach ( $sliders as $slider ) {
				$html[] = '    <option value="' . $slider->getAlias() . '"' . ( selected( $value,
						$slider->getAlias() ) ) . '>' . $slider->getTitle() . '</option>';
			}
			$html[] = '  </select>';
			$html[] = '</div>';

			return implode( "\n", $html );
		}

		ns_add_shortcode_param( 'noo_rev_slider', 'noo_timetable_noo_rev_slider_param' );

	endif;
}

if ( ! function_exists( 'noo_timetable_ui_slider_param' ) ) :

	function noo_timetable_ui_slider_param( $settings, $value ) {
		$class     = 'noo-slider wpb_vc_param_value wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
		$id        = isset( $settings['id'] ) ? $settings['id'] : $settings['param_name'];
		$data_min  = ( isset( $settings['data_min'] ) && ! empty( $settings['data_min'] ) ) ? 'data-min="' . $settings['data_min'] . '"' : 'data-min="0"';
		$data_max  = ( isset( $settings['data_max'] ) && ! empty( $settings['data_max'] ) ) ? 'data-max="' . $settings['data_max'] . '"' : 'data-max="100"';
		$data_step = ( isset( $settings['data_step'] ) && ! empty( $settings['data_step'] ) ) ? 'data-step="' . $settings['data_step'] . '"' : 'data-step="1"';
		$html      = array();

		$html[] = '	<div class="noo-control">';
		$html[] = '		<input type="text" id="' . $id . '" name="' . $settings['param_name'] . '" class="' . $class . '" value="' . $value . '" ' . $data_min . ' ' . $data_max . ' ' . $data_step . '/>';
		$html[] = '	</div>';
		$html[] = '<script>';
		$html[] = 'jQuery("#' . $id . '").each(function() {';
		$html[] = '	var $this = jQuery(this);';
		$html[] = '	var $slider = jQuery("<div>", {id: $this.attr("id") + "-slider"}).insertAfter($this);';
		$html[] = '	$slider.slider(';
		$html[] = '	{';
		$html[] = '		range: "min",';
		$html[] = '		value: $this.val() || $this.data("min") || 0,';
		$html[] = '		min: $this.data("min") || 0,';
		$html[] = '		max: $this.data("max") || 100,';
		$html[] = '		step: $this.data("step") || 1,';
		$html[] = '		slide: function(event, ui) {';
		$html[] = '			$this.val(ui.value).attr("value", ui.value);';
		$html[] = '		}';
		$html[] = '	}';
		$html[] = '	);';
		$html[] = '	$this.change(function() {';
		$html[] = '		$slider.slider( "option", "value", $this.val() );';
		$html[] = '	});';
		$html[] = '});';
		$html[] = '</script>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'ui_slider', 'noo_timetable_ui_slider_param' );

endif;

if ( ! function_exists( 'noo_timetable_shortcode_admin_enqueue_assets' ) ) :

	function noo_timetable_shortcode_admin_enqueue_assets( $hook ) {
		if ( $hook != 'edit.php' && $hook != 'post.php' && $hook != 'post-new.php' ) {
			return;
		}
		// Enqueue style for VC admin
		wp_register_style( 'noo-vc-admin-css',
			Noo__Timetable__Main::plugin_url() . '/inc/framework/assets/css/noo-vc-admin.css',
			array( 'noo-jquery-ui-slider' ) );
		wp_enqueue_style( 'noo-vc-admin-css' );

		// Enqueue script for VC admin
		wp_register_script( 'noo-vc-admin-js',
			Noo__Timetable__Main::plugin_url() . '/inc/framework/assets/js/noo-vc-admin.js', null, null, false );
		wp_enqueue_script( 'noo-vc-admin-js' );
	}

	add_action( 'admin_enqueue_scripts', 'noo_timetable_shortcode_admin_enqueue_assets' );

endif;
