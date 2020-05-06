<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * jQuery UI Slider shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */

if ( ! function_exists( 'ns_ui_slider_param' ) ) :

	function ns_ui_slider_param( $settings, $value ) {
		$class     = 'wpb_vc_param_value wpb-input ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
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

	ns_add_shortcode_param( 'ui_slider', 'ns_ui_slider_param' );

endif;
