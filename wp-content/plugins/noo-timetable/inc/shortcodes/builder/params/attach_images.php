<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Attach image(s) shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_attach_images_param' ) ) :

	function ns_attach_images_param( $settings, $value ) {
		$name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';

		$img_ids  = empty( $value ) ? array() : array_map( 'intval', explode( ',', $value ) );
		$multiple = ( isset( $settings['multiple'] ) AND $settings['multiple'] );

		if ( $multiple ) {
			wp_enqueue_script( 'jquery-ui-sortable' );
		}

		$output = '<div class="ns-imgattach" data-multiple="' . intval( $multiple ) . '">';
		$output .= '<ul class="ns-imgattach-list">';
		foreach ( $img_ids as $img_id ) {
			$output .= '<li data-id="' . $img_id . '"><a href="javascript:void(0)" class="ns-imgattach-delete">&times;</a>' . wp_get_attachment_image( $img_id,
					'thumbnail', true ) . '</li>';
		}
		$output        .= '</ul>';
		$add_btn_title = $multiple ? __( 'Add images', 'noo-shortcode-builder' ) : __( 'Add image',
			'noo-shortcode-builder' );
		$output        .= '<a href="javascript:void(0)" class="ns-imgattach-add" title="' . $add_btn_title . '">+</a>';
		$output        .= '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" />';
		$output        .= '</div>';

		return $output;
	}

	ns_add_shortcode_param( 'attach_image', 'ns_attach_images_param' );
	ns_add_shortcode_param( 'attach_images', 'ns_attach_images_param' );

endif;
