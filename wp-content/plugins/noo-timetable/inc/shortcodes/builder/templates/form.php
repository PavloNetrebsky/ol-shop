<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Output a single element's editing form
 *
 * @var $name               string Element name
 * @var $params             array List of config-based params
 * @var $values             array List of param_name => value
 * @var $field_id_pattern   string Sprintf pattern to generate field string ID
 */

// Validating and sanitizing input
global $ns_form_index;
$field_id_pattern = isset( $field_id_pattern ) ? $field_id_pattern : ( 'ns_form_' . $ns_form_index . '_%s' );
$values           = ( isset( $values ) AND is_array( $values ) ) ? $values : array();

// Validating, sanitizing and grouping params
$groups = array();
foreach ( $params as &$param ) {
	$param_name = $param['param_name'];

	$param['type'] = isset( $param['type'] ) ? $param['type'] : 'textfield';

	if ( $param['type'] == 'textarea_html' AND $param_name != 'content' ) {
		// For VC-compatibility we may have only one wysiwyg field and it should be called content
		$param['type'] = 'textarea_raw_html';
	}
	$param['classes'] = isset( $param['classes'] ) ? $param['classes'] : '';
	$param['std']     = ns_map_get_param_default( $param );
	// Filling missing values with standard ones
	if ( ! isset( $values[ $param_name ] ) ) {
		$values[ $param_name ] = $param['std'];
	}
	$group = isset( $param['group'] ) ? $param['group'] : __( 'General', 'noo-shortcode-builder' );
	if ( ! isset( $groups[ $group ] ) ) {
		$groups[ $group ] = array();
	}
	$groups[ $group ][ $param_name ] = &$param;
}

$output = '<div class="ns-form" data-shortcode="' . $name . '"><div class="ns-form-h">';
if ( count( $groups ) > 1 ) {
	$group_index = 0;
	$output      .= '<div class="ns-tabs">';
	$output      .= '<div class="ns-tabs-list">';
	foreach ( $groups as $group => &$group_params ) {
		$output .= '<div class="ns-tabs-item' . ( $group_index ? '' : ' active' ) . '">' . $group . '</div>';
		$group_index ++;
	}
	$output .= '</div>';
}
$output .= '<div class="ns-tabs-sections">';

$group_index = 0;
foreach ( $groups as &$group_params ) {
	$output .= '<div class="ns-tabs-section" style="display: ' . ( $group_index ? 'none' : 'block' ) . '">';
	$output .= '<div class="ns-tabs-section-h">';
	foreach ( $group_params as $param_name => &$param ) {
		// Field params
		$param['id']     = sprintf( $field_id_pattern, $param_name );
		$param_base_type = ns_get_param_base_type( $param['type'] );

		// Handle dynamical field visibility
		$field_is_shown = isset( $param['dependency'] ) ? ns_check_visible_by_dependency( $param['dependency'],
			$values ) : true;

		$output .= '<div class="ns-form-control type_' . $param_base_type . ' ' . $param['classes'] . '" data-param_name="' . $param_name . '" data-param_type="' . $param_base_type . '"' . ( $field_is_shown ? '' : ' style="display: none"' ) . '>';
		if ( isset( $param['heading'] ) AND ! empty( $param['heading'] ) ) {
			$output .= '<div class="ns-form-control-title">';
			$output .= '<label for="' . esc_attr( $param['id'] ) . '">' . $param['heading'] . '</label>';
			$output .= '</div>';
		}
		$output .= '<div class="ns-form-control-field">';

		if ( $param['type'] == 'attach_images' ) {
			$param['multiple'] = true;
		}

		$value  = isset( $values[ $param_name ] ) ? $values[ $param_name ] : $param['std'];
		$output .= ns_render_param( $param['type'], $param, $value, $name );

		$output .= '</div>';
		if ( isset( $param['description'] ) AND ! empty( $param['description'] ) ) {
			$output .= '<div class="ns-form-control-description">' . $param['description'] . '</div>';
		}
		if ( isset( $param['dependency'] ) AND ! empty( $param['dependency'] ) ) {
			$output .= '<div class="ns-form-control-dependency"' . ns_pass_data_to_js( $param['dependency'] ) . '></div>';
		}
		$output .= '</div><!-- .ns-form-control -->';
	}
	$output .= '</div></div><!-- .ns-tabs-section -->';
	$group_index ++;
}
$output .= '</div><!-- .ns-tabs-sections -->';

if ( count( $groups ) > 1 ) {
	$output .= '</div><!-- .ns-tabs -->';
}

// Param scripts
$output .= ns_enqueue_param_scripts();

$output .= '</div></div>';

echo $output;


