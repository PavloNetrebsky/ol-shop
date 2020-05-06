<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * User List select shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_user_list_param' ) ) :

	function ns_user_list_param( $settings, $value ) {
		$name   = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$id     = isset( $settings['id'] ) ? $settings['id'] : $settings['param_name'];
		$users  = get_users( array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
		$class  = 'wpb_vc_param_value wpb-input wpb-select ' . esc_attr( $name ) . ' ' . $settings['type'] . '_field';

		$html   = array( '<div class="ns-user_list-wrapper">' );
		$html[] = '  <select name="' . esc_attr( $name ) . '" id="' . $id . '" class="' . $class . '" >';
		foreach ( $users as $user ) {
			$html[] = '    <option value="' . $user->ID . '" ' . ( selected( $value, $user->ID, false ) ) . '>';
			$html[] = '      ' . $user->display_name;
			$html[] = '    </option>';
		}

		$html[] = '  </select>';
		$html[] = '</div>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'user_list', 'ns_user_list_param', 'dropdown' );

endif;


