<?php
/**
 * NOO Customizer Package
 *
 * NOO Customizer Helper class
 * This file defines the helper class for NOO Customizer, it provides function for add section, sub-section and control.
 *
 * @package    NOO Framework
 * @subpackage NOO Customizer
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

/**
 * NOO-Customizer Helper class.
 *
 * This class has functions used when add section, sub-section and control to Customizer.
 *
 * @package    NOO Framework
 * @subpackage NOO Customizer
 * @since 1.0.0
 */
class NOO_Customizer_Helper {

	// Log section and sub-section as well as their priority.
	private static $section_list = array();

	// Remember current section to use when there's no input for section.
	private static $current_section = '';

	// Remember current sub_section to use when there's no input for sub_section.
	private static $current_sub_section = '';

	// Reserved priority number, every new section must not have the same
	// priority as every number stored in this array.
	private static $reserved_priority = array();

	private static $noo_options = array();

	// Object wp_customize
	public $wp_customize = null;

	public function __construct( $wp_customize = null ) {
		$this->wp_customize = $wp_customize;

		if ( empty ( self::$noo_options ) ) {
			self::$noo_options = $this->get_data();
		}
	}

	public static function get_options_list() {
		return self::$noo_options;
	}

	public function get_new_section_priority() {
		if ( !empty( self::$current_section ) ) {
			$priority = ( self::$section_list[self::$current_section]['priority'] / 100 + 1 ) * 100;
		} else {
			$priority = 100;
		}

		self::$reserved_priority[] = $priority;

		return $priority;
	}

	/**
	 * Add section function
	 */
	public function add_section( $id = '', $label = '', $description = '', $is_panel = false, $priority = 0, $args = array() ) {

		if ( empty( $id ) ) {
			return false;
		}

		if( is_array( $id ) ) {
			$temp_args = $id;
			$defaults = array(
				'id' => '',
				'label' => '',
				'description' => '',
				'is_panel' => false,
				'priority' => 0,
				'args' => array(),
			);
			extract( wp_parse_args( $temp_args, $defaults ) );

			if( empty( $id ) ) {
				return false;
			}
		} elseif( is_array( $label ) ) {
			$temp_args = $label;
			$defaults = array(
				'label' => '',
				'description' => '',
				'is_panel' => false,
				'priority' => 0,
				'args' => array(),
			);
			extract( wp_parse_args( $temp_args, $defaults ) );
		}

		do_action( 'noo_customizer_option_before_' . $id, $this );

		if ( !isset( self::$section_list[$id] ) ) {
			// log section
			self::$section_list[$id] = array();

			// register section priority
			if ( is_numeric( $priority ) && $priority > 0 ) {
				self::$section_list[$id]['priority'] = $priority * 100;
			}
			elseif ( !empty( self::$current_section ) ) {
				self::$section_list[$id]['priority'] = ( self::$section_list[self::$current_section]['priority'] / 100 + 1 ) * 100;
			}
			else {
				self::$section_list[$id]['priority'] = 100;
			}

			// reserve this priority number to prevent possible conflict
			while ( in_array( self::$section_list[$id]['priority'], self::$reserved_priority ) ) {
				self::$section_list[$id]['priority'] = self::$section_list[$id]['priority'] + 100;
			}
			self::$reserved_priority[] = self::$section_list[$id]['priority'];

			$args = array_merge(array(
					'title'       => $label,
					'description' => $description,
					'priority'    => self::$section_list[$id]['priority']
				), $args);

			global $wp_version;
			if ( $is_panel && $wp_version >= 4.0 ) {
				$this->wp_customize->add_panel( $id, $args );
				self::$section_list[$id]['is_panel'] = true;
			} else {
				$this->wp_customize->add_section( $id, $args );
				self::$section_list[$id]['is_panel'] = false;
			}

			self::$current_section = $id;
			self::$current_sub_section = null;
		}

		do_action( 'noo_customizer_option_after_' . $id, $this );
	}

	/**
	 * Add sub-section function
	 */
	public function add_sub_section( $id = '', $label = '', $description = '', $section_id = '' ) {

		if ( empty( $id ) ) {
			return false;
		}

		if( is_array( $id ) ) {
			$temp_args = $id;
			$defaults = array(
				'id' => '',
				'label' => '',
				'description' => null,
				'section_id' => array(),
			);
			extract( wp_parse_args( $temp_args, $defaults ) );

			if( empty( $id ) ) {
				return false;
			}
		} elseif( is_array( $label ) ) {
			$temp_args = $label;
			$defaults = array(
				'label' => '',
				'description' => null,
				'section_id' => array(),
			);
			extract( wp_parse_args( $temp_args, $defaults ) );
		}

		do_action( 'noo_customizer_option_before_' . $id, $this );

		$section_id = empty( $section_id ) ? self::$current_section : $section_id;

		if ( empty( $section_id ) || !isset( self::$section_list[$section_id] ) ) {
			if ( !empty( self::$current_section ) )
				$section_id = self::$current_section;
			else
				return false;
		}

		if ( !isset( self::$section_list[$section_id][$id] ) ) {
			// register sub-section priority
			self::$section_list[$section_id]['priority'] = ( self::$section_list[$section_id]['priority'] / 10 + 1 ) * 10;
			$priority = self::$section_list[$section_id]['priority'];

			$wp_customize = $this->wp_customize;

			global $wp_version;
			if ( self::$section_list[$section_id]['is_panel'] && $wp_version >= 4.0 ) {
				$this->wp_customize->add_section( $id, array(
					'title'       => $label,
					'description' => $description,
					'panel'       => $section_id,
					'priority'    => $priority
				) );
			} else {
				$wp_customize->add_setting( $id, array( 'sanitize_callback' => array(&$this, 'sanitize_callback') ) );
				$wp_customize->add_control(
					new NOO_Customizer_Sub_Section( $wp_customize, $id, array(
							'label'  => $label,
							'section' => $section_id,
							'settings' => $id,
							'json'  => array( 'description' => $description ),
							'priority' => $priority
						) )
				);
			}

			self::$current_sub_section = $id;
		}

		do_action( 'noo_customizer_option_after_' . $id, $this );
	}

	/**
	 * Add control function
	 */
	public function add_control(
		$id = '',
		$type = '',
		$label = '',
		$default = null,
		$control = array(),
		$settings = array()
	) {

		if ( empty( $id ) ) {
			return false;
		}

		if( is_array( $id ) ) {
			$temp_args = $id;
			$defaults = array(
				'id' => '',
				'type' => '',
				'label' => '',
				'default' => null,
				'control' => array(),
				'settings' => array(),
			);
			extract( wp_parse_args( $temp_args, $defaults ) );

			if( empty( $id ) || empty( $type ) ) {
				return false;
			}
		} elseif( is_array( $type ) ) {
			$temp_args = $type;
			$defaults = array(
				'type' => '',
				'label' => '',
				'default' => null,
				'control' => array(),
				'settings' => array(),
			);
			extract( wp_parse_args( $temp_args, $defaults ) );

			if( empty( $type ) ) {
				return false;
			}
		}

		if( !isset( $control['preview_type'] ) && isset( $preview_type ) ) {
			$control['preview_type'] = $preview_type;
		}

		if( !isset( $control['preview_type'] ) ) $control['preview_type'] = 'auto';

		if( !isset( $control['preview_params'] ) && isset( $preview_params ) ) {
			$control['preview_params'] = $preview_params;
		}

		if( !isset( $control['json'] ) && isset( $json ) ) {
			$control['json'] = $json;
		}

		if( !isset( $control['choices'] ) && isset( $choices ) ) {
			$control['choices'] = $choices;
		}

		// Add this options to list
		$this->register_new_option( compact( 'id', 'type', 'label', 'default', 'control', 'settings' ) );

		do_action( 'noo_customizer_option_before_' . $type, $this, $id );
		do_action( 'noo_customizer_option_before_' . $id, $this );

		$section_id = isset( $control['section'] ) && !empty( $control['section'] ) ? $control['section'] : self::$current_section;

		if ( empty( $section_id ) || !isset( self::$section_list[$section_id] ) ) {
			return false;
		}

		// register control priority// register sub-section priority
		$priority = ++self::$section_list[$section_id]['priority'];

		// if this section is panel, decrease 1 level
		if( self::$section_list[$section_id]['is_panel'] && !is_null(self::$current_sub_section) ) {
			$section_id = self::$current_sub_section;
		}

		// get the setting options
		$setting_options = array( 'type' => 'theme_mod' );
		if(!empty( $default )) {
			$setting_options['default'] = $default;
		}

		if ( !empty( $settings ) ) {
			$setting_options = array_merge( $setting_options, $settings );
		}

		if ( !isset( $setting_options['transport'] ) ) {
			$setting_options['transport'] = 'postMessage';
		}

		$sanitize_func = 'sanitize_callback';
		if( $type == 'color_control' || $type == 'alpha_color' ) {
			// Add sanitize function
			$sanitize_func = 'sanitize_color';
		}

		$wp_customize  = $this->wp_customize;

		// Below is some stupid code but I must do it to pass Theme Check
		if( !isset( $setting_options['sanitize_callback'] ) ) {
			$wp_customize->add_setting( $id, array_merge( $setting_options, array( 'sanitize_callback' => array(&$this, $sanitize_func) ) ) );	
		} else {
			$wp_customize->add_setting( $id, array_merge( $setting_options, array( 'sanitize_callback' => array(&$this, $setting_options['sanitize_callback']) ) ) );	
		}

		// get the control options
		$control_options = array(
			'label'  => $label,
			'section' => $section_id,
			'settings' => $id,
			'priority' => $priority
		);
		$control_options = !empty( $control ) ? array_merge( $control_options, $control ) : $control_options;

		switch ( trim( $type ) ) {
		case 'noo_switch':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Switch( $wp_customize, $id, $control_options ) );
			break;
		case 'ui_slider':
			$wp_customize->add_control(
				new NOO_Customizer_Control_UI_Slider( $wp_customize, $id, $control_options ) );
			break;

		case 'noo_radio':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Radio( $wp_customize, $id, $control_options ) );
			break;

		case 'noo_same_as_radio':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Same_As_Radio( $wp_customize, $id, $control_options ) );
			break;

		case 'radio':
		case 'text':
		// case 'checkbox':
		case 'select':
			$control_options['type'] = $type;
			$wp_customize->add_control( $id, $control_options );
			break;

		case 'checkbox':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Checkbox( $wp_customize, $id, $control_options ) );
			break;

		case 'image_control':
		case 'noo_image':
			if ( floatval( get_bloginfo( 'version' ) ) >= 4.1 ) {
				$wp_customize->add_control(
					new WP_Customize_Image_Control( $wp_customize, $id, $control_options ) );
			} else {
				$wp_customize->add_control(
					new NOO_Customize_Image_Control( $wp_customize, $id, $control_options ) );
			}

			break;

		case 'color_control':
			$wp_customize->add_control(
				new WP_Customize_Color_Control( $wp_customize, $id, $control_options ) );
			break;

		case 'alpha_color':
			$wp_customize->add_control(
				new NOO_Customize_Alpha_Color( $wp_customize, $id, $control_options ) );
			break;

		case 'textarea':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Textarea( $wp_customize, $id, $control_options ) );
			break;

		case 'divider':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Divider( $wp_customize, $id, $control_options ) );
			break;

		case 'widgets_select':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Widgets_Select( $wp_customize, $id, $control_options ) );
			break;

		case 'pages_select':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Pages_Select( $wp_customize, $id, $control_options ) );
			break;

		case 'posts_select':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Posts_Select( $wp_customize, $id, $control_options ) );
			break;

		case 'terms_select':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Terms_Select( $wp_customize, $id, $control_options ) );
			break;

		case 'google_fonts':
			// add more settings for font weight and font subset
			$font_default = array();
			$font_default['weight'] = isset( $control_options['weight'] ) ? $control_options['weight'] : '400';
			$font_default['style'] = isset( $control_options['style'] ) ? $control_options['style'] : '';
			$font_default['subset'] = isset( $control_options['subset'] ) ? $control_options['subset'] : 'latin';

			$wp_customize->add_setting( $id.'_weight', array( 'default' => $font_default['weight'], 'transport' => 'postMessage', 'sanitize_callback' => array(&$this, 'sanitize_callback') ) );
			$wp_customize->add_setting( $id.'_style', array( 'default' => $font_default['style'], 'transport' => 'postMessage', 'sanitize_callback' => array(&$this, 'sanitize_callback') ) );
			$wp_customize->add_setting( $id.'_subset', array( 'default' => $font_default['subset'], 'transport' => 'postMessage', 'sanitize_callback' => array(&$this, 'sanitize_callback') ) );

			$wp_customize->add_control(
				new NOO_Customizer_Control_Google_Fonts( $wp_customize, $id, $control_options ) );
			break;

		case 'font_size':
			$wp_customize->add_control(
				new NOO_Customizer_Control_Font_Size( $wp_customize, $id, $control_options ) );
			break;

		case 'mailchimp':
			// add setting for API key
			$wp_customize->add_setting( $id.'_api', array( 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => array(&$this, 'sanitize_callback') ) );
			$wp_customize->add_control(
				new NOO_Customizer_Control_MailChimp_Mail_List( $wp_customize, $id, $control_options ) );
			break;

		case 'upload_control':
			$wp_customize->add_control(
				new WP_Customize_Upload_Control( $wp_customize, $id, $control_options ) );
			break;

		case 'import_settings':
			$wp_customize->add_control(
				new NOO_Customize_Settings_Upload( $wp_customize, $id, $control_options ) );
			break;

		case 'export_settings':
			$wp_customize->add_control(
				new NOO_Customize_Settings_Download( $wp_customize, $id, $control_options ) );
			break;

		}

		do_action( 'noo_customizer_option_after_' . $id, $this, $id );
		do_action( 'noo_customizer_option_after_' . $type, $this );
	}

	public function sanitize_callback( $value ) {
		return $value;
	}

	public function sanitize_color( $value ) {
		if( $value == false || $value == 'false' ) {
			$value = '';
		}

		return $value;
	}
 
	private function get_data() {
		return apply_filters( 'noo_customizer_data', array() );
	}

	private function register_new_option( $args = array() ) {
		$defaults = array(
			'id' => '',
			'type' => '',
			'label' => '',
			'default' => null,
			'control' => array(),
			'settings' => array(),
		);
		extract( wp_parse_args( $args, $defaults ) );

		// Add this options to list
		global $noo_customize_options;
		if( empty( $noo_customize_options ) ) {
			$noo_customize_options = array();
		}
		$option = new stdClass();
		$option->id = $id;

		// preview_type: [default]/[any string]: auto refresh for normal option and redirect for post type options,
		// none: do nothing, auto generate script
		// custom: have to write custom function
		// update_css: require 'preview_params' => array( css => design/typography/header/layout/ )
		$option->preview_type = isset( $control['preview_type'] ) ? $control['preview_type'] : 'auto';
		$option->auto_script = $option->preview_type != 'custom';

		if( $option->preview_type == 'update_css' ) {
			$option->preview_params = isset( $control['preview_params'] ) ? $control['preview_params'] : array();
		}

		$noo_customize_options[$id] = $option;
	}
}

if ( ! function_exists( 'noo_customizer_add_control' ) ) :
	function noo_customizer_add_control( $wp_customize = null, $args = array() ) {
		if( empty( $wp_customize ) || empty( $args ) )
			return false;

		$helper = new NOO_Customizer_Helper( $wp_customize );
		$helper->add_control( $args );
	}
endif;

if ( ! function_exists( 'noo_customizer_add_controls' ) ) :
	function noo_customizer_add_controls( $wp_customize = null, $options = array() ) {
		if( empty( $wp_customize ) || empty( $options ) )
			return false;

		foreach ($options as $id => $option) {
			if( empty( $id ) || empty( $option ) || !is_array( $option ) )
				continue;

			$option['id'] = $id;
			noo_customizer_add_control( $wp_customize, $option );
		}
	}
endif;

if ( ! function_exists( 'noo_customizer_add_section' ) ) :
	function noo_customizer_add_section( $wp_customize = null, $args = array() ) {
		if( empty( $wp_customize ) || empty( $args ) )
			return false;

		$helper = new NOO_Customizer_Helper( $wp_customize );
		$helper->add_section( $args );
	}
endif;


if ( ! function_exists( 'noo_customizer_add_sub_section' ) ) :
	function noo_customizer_add_sub_section( $wp_customize = null, $args = array() ) {
		if( empty( $wp_customize ) || empty( $args ) ) return false;
		$helper = new NOO_Customizer_Helper( $wp_customize );
		$helper->add_sub_section( $args );
	}
endif;
