<?php
/**
 * Settings Class
 * 
 * @author      NooTheme
 * @category    Admin
 * @package     NooTimetable/Framework/Settings
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( !class_exists('Noo__Timetable__Setting') ):

    class Noo__Timetable__Setting {

    	// A reference to an instance of this class.
        private static $instance;

		// Returns an instance of this class.
        public static function get_instance() {

            if( null == self::$instance ) {
                self::$instance = new Noo__Timetable__Setting();
            } 
            return self::$instance;

        } 

        // Returns an init of this class.
        public static function init() {
        	$class = __CLASS__;
	        new $class;
	    }

    	/**
		 * Error messages.
		 *
		 * @var array
		 */
		private static $errors   = array();

		/**
		 * Update messages.
		 *
		 * @var array
		 */
		private static $messages = array();

        public function __construct(){
            add_action( 'admin_init', array( &$this, 'setting_save' ) );
        }

        public function setting_save() {
            
           	register_setting( 'timetable-setting-group', 'timetable_settings' );

           	// Save settings if data has been posted
			if ( ! empty( $_POST ) && isset($_POST['timetable_settings']) ) {

				$settings = (array) get_option('timetable_settings');
				$_POST['timetable_settings'] = array_merge($settings, $_POST['timetable_settings']);
			}
        }

        /**
		 * Add a message.
		 * @param string $text
		 */
		public static function add_message( $text ) {
			self::$messages[] = $text;
		}

		/**
		 * Add an error.
		 * @param string $text
		 */
		public static function add_error( $text ) {
			self::$errors[] = $text;
		}

		/**
		 * Output messages + errors.
		 * @return string
		 */
		public static function show_messages() {
			if ( sizeof( self::$errors ) > 0 ) {
				foreach ( self::$errors as $error ) {
					echo '<div id="message" class="error inline"><p><strong>' . esc_html( $error ) . '</strong></p></div>';
				}
			} elseif ( sizeof( self::$messages ) > 0 ) {
				foreach ( self::$messages as $message ) {
					echo '<div id="message" class="updated inline"><p><strong>' . esc_html( $message ) . '</strong></p></div>';
				}
			}
		}

        public static function output() {

			$current_tab = empty( $_GET['page'] ) ? 'noo-timetable-settings' : sanitize_title( $_GET['page'] );
		    $tabs = array(
				'noo-timetable-settings' => 'Settings',
				'noo-timetable-schedule' => 'Schedule',
				'noo-timetable-update'   => 'Automatic Update'
		    );

		    // Add any posted messages
			if ( ! empty( $_GET['settings-updated'] ) ) {
				flush_rewrite_rules();
				if( stripslashes( $_GET['settings-updated'] ) == true ) {
					self::add_message( esc_html__( 'Your settings have been saved.', 'noo-timetable' ) );
				}
			}

			$form_return = ($current_tab != 'noo-timetable-settings') ? ' onsubmit="return false" ' : '';
			$form_return = '';

            ?>
			<div class="wrap noo-settings">
				<form method="post" action="options.php" id="mainform" enctype="multipart/form-data" <?php echo $form_return; ?>>
					<?php settings_fields( 'timetable-setting-group' ); ?>
					<?php do_settings_sections( 'timetable-setting-group' ); ?>
					<nav class="nav-tab-wrapper noo-nav-tab-wrapper">
						<?php
							foreach ( $tabs as $name => $label ) {
								echo '<a href="' . admin_url( 'admin.php?page=' . $name ) . '" class="nav-tab ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
							}
						?>
					</nav>
					<h1 class="screen-reader-text"><?php echo esc_html( $tabs[ $current_tab ] ); ?></h1>

					<?php self::show_messages(); ?>

					<?php
						$settings = self::get_settings($current_tab);

						self::output_fields( $settings );

						if ( $current_tab == 'noo-timetable-settings' ) {
                			
						}
						submit_button();
                	?>
				</form>
			</div>

            <?php
        }

        /**
		 * Output admin fields.
		 *
		 * @param array $options Opens array to output
		 */
		public static function output_fields( $options ) {

			if ( ! $options ) return;

			foreach ( $options as $value ) {
				if ( ! isset( $value['type'] ) ) {
					continue;
				}
				if ( ! isset( $value['id'] ) ) {
					$value['id'] = '';
				}
				if ( ! isset( $value['title'] ) ) {
					$value['title'] = isset( $value['name'] ) ? $value['name'] : '';
				}
				if ( ! isset( $value['class'] ) ) {
					$value['class'] = '';
				}
				if ( ! isset( $value['css'] ) ) {
					$value['css'] = '';
				}
				if ( ! isset( $value['default'] ) ) {
					$value['default'] = '';
				}
				if ( ! isset( $value['desc'] ) ) {
					$value['desc'] = '';
				}
				if ( ! isset( $value['placeholder'] ) ) {
					$value['placeholder'] = '';
				}

				// Description handling
				$field_description = self::get_field_description( $value );
				extract( $field_description );

				// Switch based on type
				switch ( $value['type'] ) {

					// Section Titles
					case 'title':
						if ( ! empty( $value['title'] ) ) {
							echo '<h2>' . esc_html( $value['title'] ) . '</h2>';
						}
						if ( ! empty( $value['desc'] ) ) {
							echo wpautop( wptexturize( wp_kses_post( $value['desc'] ) ) );
						}
						echo '<table class="form-table">'. "\n\n";
						break;

					// Section Ends
					case 'sectionend':
						echo '</table>';
						break;

					// Standard text inputs and subtypes like 'number'
					case 'text':
					case 'email':
					case 'number':
					case 'color' :
					case 'password' :

						$type         = $value['type'];
						$option_value = self::get_option( $value['id'], $value['default'] );

						if ( $value['type'] == 'color' ) {
							$type = 'text';
							$color_default = $value['default'] != '' ? "'" . $value['default'] . "'" : 'false';
						}
						?><tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
								
							</th>
							<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
								<input
									name="timetable_settings[<?php echo esc_attr( $value['id'] ); ?>]"
									id="<?php echo esc_attr( $value['id'] ); ?>"
									type="<?php echo esc_attr( $type ); ?>"
									value="<?php echo esc_attr( $option_value ); ?>"
									class="<?php echo esc_attr( $value['class'] ); ?>"
									style="<?php echo esc_attr( $value['css'] ); ?>"
									
									placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
									/> <?php echo $description; ?>

								<?php if ( 'color' == $value['type'] ) : 
									wp_enqueue_style( 'wp-color-picker' );
									wp_enqueue_script( 'wp-color-picker' );
								?>
								<script>
									jQuery(document).ready(function($) {
							        	$('#<?php echo esc_attr( $value['id'] ); ?>').wpColorPicker({
							        		defaultColor: <?php echo $color_default; ?>,
							        	});
									});
								</script>
								<?php endif; ?>
							</td>
						</tr><?php
						break;

					// Textarea
					case 'textarea':

						$option_value = self::get_option( $value['id'], $value['default'] );

						?><tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
								
							</th>
							<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
								<?php echo $description; ?>

								<textarea
									name="timetable_settings[<?php echo esc_attr( $value['id'] ); ?>]"
									id="<?php echo esc_attr( $value['id'] ); ?>"
									style="<?php echo esc_attr( $value['css'] ); ?>"
									class="<?php echo esc_attr( $value['class'] ); ?>"
									placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"									
									><?php echo esc_textarea( $option_value );  ?></textarea>
							</td>
						</tr><?php
						break;

					// Select boxes
					case 'select' :
					case 'multiselect' :

						$option_value = self::get_option( $value['id'], $value['default'] );

						?><tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
								
							</th>
							<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
								<select
									name="timetable_settings[<?php echo esc_attr( $value['id'] ); ?>]<?php if ( $value['type'] == 'multiselect' ) echo '[]'; ?>"
									id="<?php echo esc_attr( $value['id'] ); ?>"
									style="<?php echo esc_attr( $value['css'] ); ?>"
									class="<?php echo esc_attr( $value['class'] ); ?>"
																		
									<?php echo ( 'multiselect' == $value['type'] ) ? 'multiple="multiple"' : ''; ?>
									>
									<?php
										foreach ( $value['options'] as $key => $val ) {
											?>
											<option value="<?php echo esc_attr( $key ); ?>" <?php

												if ( is_array( $option_value ) ) {
													selected( in_array( $key, $option_value ), true );
												} else {
													selected( $option_value, $key );
												}

											?>><?php echo $val ?></option>
											<?php
										}
									?>
								</select> <?php echo $description; ?>
							</td>
						</tr><?php
						break;

					// Radio inputs
					case 'radio' :

						$option_value = self::get_option( $value['id'], $value['default'] );

						?><tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
								
							</th>
							<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
								<fieldset>
									<?php echo $description; ?>
									<ul>
									<?php
										foreach ( $value['options'] as $key => $val ) {
											?>
											<li>
												<label><input
													name="timetable_settings[<?php echo esc_attr( $value['id'] ); ?>]"
													value="<?php echo $key; ?>"
													type="radio"
													style="<?php echo esc_attr( $value['css'] ); ?>"
													class="<?php echo esc_attr( $value['class'] ); ?>"													
													<?php checked( $key, $option_value ); ?>
													/> <?php echo $val ?></label>
											</li>
											<?php
										}
									?>
									</ul>
								</fieldset>
							</td>
						</tr><?php
						break;

					// Checkbox input
					case 'checkbox' :

						$option_value    = self::get_option( $value['id'], $value['default'] );
						
						$visbility_class = array();

						if ( ! isset( $value['hide_if_checked'] ) ) {
							$value['hide_if_checked'] = false;
						}
						if ( ! isset( $value['show_if_checked'] ) ) {
							$value['show_if_checked'] = false;
						}
						if ( 'yes' == $value['hide_if_checked'] || 'yes' == $value['show_if_checked'] ) {
							$visbility_class[] = 'hidden_option';
						}
						if ( 'option' == $value['hide_if_checked'] ) {
							$visbility_class[] = 'hide_options_if_checked';
						}
						if ( 'option' == $value['show_if_checked'] ) {
							$visbility_class[] = 'show_options_if_checked';
						}

						if ( ! isset( $value['checkboxgroup'] ) || 'start' == $value['checkboxgroup'] ) {
							?>
								<tr valign="top" class="<?php echo esc_attr( implode( ' ', $visbility_class ) ); ?>">
									<th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ) ?></th>
									<td class="forminp forminp-checkbox">
										<fieldset>
							<?php
						} else {
							?>
								<fieldset class="<?php echo esc_attr( implode( ' ', $visbility_class ) ); ?>">
							<?php
						}

						if ( ! empty( $value['title'] ) ) {
							?>
								<legend class="screen-reader-text"><span><?php echo esc_html( $value['title'] ) ?></span></legend>
							<?php
						}

						?>
							<label for="<?php echo $value['id'] ?>">
								<input
									name="timetable_settings[<?php echo esc_attr( $value['id'] ); ?>]"
									id="<?php echo esc_attr( $value['id'] ); ?>"
									type="checkbox"
									class="<?php echo esc_attr( isset( $value['class'] ) ? $value['class'] : '' ); ?>"
									value="noo_checkbox_1"
									<?php checked( $option_value, 'yes'); ?>
								/> <?php echo $description ?>
							</label> 
						<?php

						if ( ! isset( $value['checkboxgroup'] ) || 'end' == $value['checkboxgroup'] ) {
										?>
										</fieldset>
									</td>
								</tr>
							<?php
						} else {
							?>
								</fieldset>
							<?php
						}
						break;

					// Default: run an action
					default:
						do_action( 'noo_timetable_admin_field_' . $value['type'], $value );
						break;
				}
			}
		}

		/**
		 * Helper function to get the formated description and tip HTML for a
		 * given form field. Plugins can call this when implementing their own custom
		 * settings types.
		 *
		 * @param  array $value The form field value array
		 * @return array The description and tip as a 2 element array
		 */
		public static function get_field_description( $value ) {
			$description  = '';

			if ( ! empty( $value['desc'] ) ) {
				$description  = $value['desc'];
			}
			if ( ! empty( $value['link'] ) ) {
				$link  = $value['link'];
			}

			if ( $description && in_array( $value['type'], array( 'textarea', 'radio' ) ) ) {
				$description = '<p style="margin-top:0">' . wp_kses_post( $description ) . '</p>';
			} elseif ( $description && in_array( $value['type'], array( 'checkbox' ) ) ) {
				$description = wp_kses_post( $description );
			} elseif ( $description ) {
				$description = '<p class="description">' . wp_kses_post( $description ) . '</p>';
			}

			if ( isset($link) ) {
				$description .= '<code><a href="'.$link.'">'.$link.'</a></code>';
			}	

			return array(
				'description'  => $description
			);
		}

		/**
		 * Get a setting from the settings API.
		 *
		 * @param mixed $option_name
		 * @return string
		 */
		public static function get_option( $option_name, $default = '' ) {

			$option_name = ('' != $option_name) ? 'timetable_settings['.$option_name.']' : $option_name;

			// Array value
			if ( strstr( $option_name, '[' ) ) {

				parse_str( $option_name, $option_array );

				// Option name is first key
				$option_name = current( array_keys( $option_array ) );

				// Get value
				$option_values = get_option( $option_name, '' );

				$key = key( $option_array[ $option_name ] );

				if ( isset( $option_values[ $key ] ) ) {
					$option_value = $option_values[ $key ];
				} else {
					$option_value = null;
				}

			// Single value
			} else {
				$option_value = get_option( $option_name, null );
			}

			if ( is_array( $option_value ) ) {
				$option_value = array_map( 'stripslashes', $option_value );
			} elseif ( ! is_null( $option_value ) ) {
				$option_value = stripslashes( $option_value );
			}

			return $option_value === null ? $default : $option_value;
		}

		public static function get_settings( $tab_name ) {

        	if ( ! $tab_name ) {
        		return NULL;
        	}

			$settings['noo-timetable-settings'] = noo_timetable_setting_general();
			$settings['noo-timetable-schedule'] = noo_timetable_setting_schedule();
			$settings['noo-timetable-update']   = noo_timetable_setting_update();

        	return $settings[$tab_name];
        }

    }
    new Noo__Timetable__Setting();

endif;

if( !function_exists( 'NOO_Settings' ) ) {

	function NOO_Settings() {
		return Noo__Timetable__Setting::get_instance();
	}
}