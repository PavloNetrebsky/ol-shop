<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Textarea with WYSIWYG HTML input shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_textarea_html_param' ) ) :

	function ns_textarea_html_param( $settings, $value ) {
		$name    = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$id      = isset( $settings['id'] ) ? $settings['id'] : $settings['param_name'];

		// We'll need 'nootheme' JS configuration to init tinymce from JS
		ns_maybe_load_wysiwyg();

		$tinymce_settings = array(
			'textarea_name'  => $name,
			'default_editor' => 'tinymce',
			'media_buttons'  => true,
			'wpautop'        => false,
			'editor_height'  => 250,
			'tinymce'        => array(
				'wp_skip_init' => true,
			),
		);

		$tinymce_settings = apply_filters( 'ns_tinymce_settings', $tinymce_settings );

		ob_start();
		echo '<div class="ns-swysiwyg"' . ns_pass_data_to_js( $tinymce_settings ) . '>';

		wp_editor( $value, $id, $tinymce_settings );

		echo '</div>';

		return ob_get_clean();
	}

	ns_add_shortcode_param( 'textarea_html', 'ns_textarea_html_param' );

endif;
