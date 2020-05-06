<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Output elements list to choose from
 */

global $ns_uri;
$elements = ns_get_elements();

$output = '<div class="ns-shortcode-list"><div class="ns-shortcode-list-h">';
$output .= '<h2 class="ns-shortcode-list-title">' . __( 'Insert shortcode', 'noo-shortcode-builder' ) . '</h2>';
$output .= '<div class="ns-shortcode-list-closer">&times;</div>';
$output .= '<ul class="ns-shortcode-list-list">';
foreach ( $elements as $name => $elm ) {
	$output .= '<li class="ns-shortcode-list-item for_' . $name . '" data-name="' . $name . '"><div class="ns-shortcode-list-item-h">';
	$output .= '<div class="ns-shortcode-list-item-icon"><i class="ns-item-icon';
	if ( isset( $elm['icon'] ) AND ! empty( $elm['icon'] ) ) {
		$output .= ' ' . esc_attr( $elm['icon'] );
	}
	$output .= '"></i></div>';
	$output .= '<div class="ns-shortcode-list-item-title">' . ( isset( $elm['name'] ) ? $elm['name'] : $name ) . '</div>';
	if ( isset( $elm['description'] ) AND ! empty( $elm['description'] ) ) {
		$output .= '<div class="ns-shortcode-list-item-description">' . $elm['description'] . '</div>';
	}
	$output .= '</div></li>';
}
$output .= '</ul></div></div>';

echo $output;

