<?php
if ( function_exists( 'vc_set_as_theme' ) ) :
    vc_set_as_theme( true );

endif;

if ( defined( 'WPB_VC_VERSION' ) ) :

    function noo_dropdown_group_param( $param, $param_value ) {
        $css_option = vc_get_dropdown_option( $param, $param_value );
        $param_line = '';
        $param_line .= '<select name="' . $param['param_name'] .
            '" class="dh-chosen-select wpb_vc_param_value wpb-input wpb-select ' . $param['param_name'] . ' ' .
            $param['type'] . ' ' . $css_option . '" data-option="' . $css_option . '">';
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
                    $param_line .= '<option class="' . $val . '" value="' . $val . '"' . $selected . '>' .
                        htmlspecialchars( $text_val ) . '</option>';
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
                $param_line .= '<option class="' . $opt . '" value="' . $opt . '"' . $selected . '>' .
                    htmlspecialchars( $text_opt ) . '</option>';
            }
        }
        $param_line .= '</select>';
        return $param_line;
    }
    vc_add_shortcode_param( 'noo_dropdown_group', 'noo_dropdown_group_param' );

    // Categories select field type
    if ( ! function_exists( 'noo_vc_field_type_post_categories' ) ) :

        function noo_vc_custom_param_post_categories( $settings, $value ) {
            $dependency = vc_generate_dependencies_attributes( $settings );
            $categories = get_categories( array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
            $class = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
            $selected_values = explode( ',', $value );
            $html = array( '<div class="noo_vc_custom_param post_categories">' );
            $html[] = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value .
                '" class="wpb_vc_param_value" />';
            $html[] = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '" ' .
                $dependency . '>';
            $html[] = '    <option value="all" ' . ( in_array( 'all', $selected_values ) ? 'selected="true"' : '' ) . '>' .
                esc_html__( 'All', 'noo-hermosa-core' ) . '</option>';
            foreach ( $categories as $category ) {
                $html[] = '    <option value="' . $category->term_id . '" ' .
                    ( in_array( $category->term_id, $selected_values ) ? 'selected="true"' : '' ) . '>';
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
        vc_add_shortcode_param( 'post_categories', 'noo_vc_custom_param_post_categories' );


    endif;// Categories select field type
    //
    //
    //// Categories select field type
    if ( ! function_exists( 'noo_vc_field_type_post_categories_events' ) ) :

        function noo_vc_field_type_post_categories_events( $settings, $value ) {
            $dependency = vc_generate_dependencies_attributes( $settings );
            $categories = get_categories( array( 'orderby' => 'NAME', 'order' => 'ASC', 'taxonomy'=>'tribe_events_cat' ) );
            $class = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
            $selected_values = explode( ',', $value );
            $html = array( '<div class="noo_vc_custom_param post_categories">' );
            $html[] = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value .
                '" class="wpb_vc_param_value" />';
            $html[] = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '" ' .
                $dependency . '>';
            $html[] = '    <option value="all" ' . ( in_array( 'all', $selected_values ) ? 'selected="true"' : '' ) . '>' .
                esc_html__( 'All', 'noo-hermosa-core' ) . '</option>';
            foreach ( $categories as $category ) {
                $html[] = '    <option value="' . $category->term_id . '" ' .
                    ( in_array( $category->term_id, $selected_values ) ? 'selected="true"' : '' ) . '>';
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
        vc_add_shortcode_param( 'categories_events', 'noo_vc_field_type_post_categories_events' );


    endif;// Categories select field type


    // Categories select field type
    if ( ! function_exists( 'noo_vc_field_type_post_tags' ) ) :

        function noo_vc_field_type_post_tags( $settings, $value ) {
            $dependency = vc_generate_dependencies_attributes( $settings );
            $categories = get_tags( array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
            $class = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
            $selected_values = explode( ',', $value );
            $html = array( '<div class="noo_vc_custom_param post_categories">' );
            $html[] = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value .
                '" class="wpb_vc_param_value" />';
            $html[] = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '" ' .
                $dependency . '>';
            $html[] = '    <option value="all" ' . ( in_array( 'all', $selected_values ) ? 'selected="true"' : '' ) . '>' .
                esc_html__( 'All', 'noo-hermosa-core' ) . '</option>';
            foreach ( $categories as $category ) {
                $html[] = '    <option value="' . $category->term_id . '" ' .
                    ( in_array( $category->term_id, $selected_values ) ? 'selected="true"' : '' ) . '>';
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
        vc_add_shortcode_param( 'post_tags', 'noo_vc_field_type_post_tags' );


    endif;// Categories select field type

    if ( ! function_exists( 'noo_vc_custom_param_gallery_category' ) ) :

        function noo_vc_custom_param_gallery_category( $settings, $value ) {
            if( taxonomy_exists('gallery_category') ):
                $categories = get_terms( 'gallery_category', array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
                $class = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
                $selected_values = explode( ',', $value );
                $html = array( '<div class="noo_vc_custom_param project_categories">' );
                $html[] = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value .
                    '" class="wpb_vc_param_value" />';
                $html[] = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '">';
                foreach ( $categories as $category ) {
                    $html[] = '    <option value="' . $category->term_id . '" ' .
                        ( in_array( $category->term_id, $selected_values ) ? 'selected="true"' : '' ) . '>';
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
        }
        vc_add_shortcode_param( 'gallery_category', 'noo_vc_custom_param_gallery_category' );

    endif;


    if ( ! function_exists( 'noo_vc_custom_param_portfolio_category' ) ) :

        function noo_vc_custom_param_portfolio_category( $settings, $value ) {
            if( taxonomy_exists('portfolio_category') ):
                $categories_portfolio = get_terms( 'portfolio_category', array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
                $class = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
                $selected_values = explode( ',', $value );
                $html = array( '<div class="noo_vc_custom_param portfolio_categories">' );
                $html[] = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value .
                    '" class="wpb_vc_param_value" />';
                $html[] = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '">';
                foreach ( $categories_portfolio as $category ) {
                    $html[] = '    <option value="' . $category->term_id . '" ' .
                        ( in_array( $category->term_id, $selected_values ) ? 'selected="true"' : '' ) . '>';
                    $html[] = '      ' . $category->name;
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
            endif;
        }
        vc_add_shortcode_param( 'portfolio_category', 'noo_vc_custom_param_portfolio_category' );

    endif;


    if ( ! function_exists( 'noo_vc_custom_param_portfolio_tag' ) ) :

        function noo_vc_custom_param_portfolio_tag( $settings, $value ) {
            if( taxonomy_exists('portfolio_tag') ):
                $categories = get_terms( 'portfolio_tag', array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
                $class = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
                $selected_values = explode( ',', $value );
                $html = array( '<div class="noo_vc_custom_param portfolio_tag">' );
                $html[] = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value .
                    '" class="wpb_vc_param_value" />';
                $html[] = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '">';
                foreach ( $categories as $category ) {
                    $html[] = '    <option value="' . $category->term_id . '" ' .
                        ( in_array( $category->term_id, $selected_values ) ? 'selected="true"' : '' ) . '>';
                    $html[] = '      ' . $category->name;
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
            endif;
        }
        vc_add_shortcode_param( 'portfolio_tag', 'noo_vc_custom_param_portfolio_tag' );

    endif;


    if ( ! function_exists( 'noo_vc_field_type_post_categories_product_cat' ) ) :

        function noo_vc_field_type_post_categories_product_cat( $settings, $value ) {
            $categories = get_categories( array( 'orderby' => 'NAME', 'order' => 'ASC', 'taxonomy'=>'product_cat' ) );
            $class = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
            $selected_values = explode( ',', $value );
            $html = array( '<div class="noo_vc_custom_param post_categories">' );
            $html[] = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value .
                '" class="wpb_vc_param_value" />';
            if ( isset($categories) && !empty($categories) && taxonomy_exists('product_cat') ) :
                $html[] = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '">';
                foreach ( $categories as $category ) {
                    $html[] = '    <option value="' . intval($category->term_id) . '" ' .
                        ( in_array( $category->term_id, $selected_values ) ? 'selected="true"' : '' ) . '>';
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
        vc_add_shortcode_param( 'noo_product_cat', 'noo_vc_field_type_post_categories_product_cat' );


    endif;

    /**
     * Create field event category
     *
     * @package     Noo_Hermosa_Core
     * @author      KENT <tuanlv@vietbrain.com>
     * @version     1.0
     */
    
    if ( ! function_exists( 'noo_vc_field_event_category' ) ) :
        
        function noo_vc_field_event_category( $settings, $value ) {
    
            $categories = get_categories( array( 'orderby' => 'NAME', 'order' => 'ASC', 'taxonomy'=>'event_category' ) );
            $class = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
            $selected_values = explode( ',', $value );
            $html = array( '<div class="noo_vc_custom_param post_categories">' );
            $html[] = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value .
                '" class="wpb_vc_param_value" />';
            if ( isset($categories) && !empty($categories) && taxonomy_exists('event_category') ) :
                $html[] = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '">';
                foreach ( $categories as $category ) {
                    $html[] = '    <option value="' . intval($category->term_id) . '" ' .
                        ( in_array( $category->term_id, $selected_values ) ? 'selected="true"' : '' ) . '>';
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

        vc_add_shortcode_param( 'event_categories', 'noo_vc_field_event_category' );
    
    endif;

    /**
     * Create field event category
     *
     * @package     Noo_Hermosa_Core
     * @author      KENT <tuanlv@vietbrain.com>
     * @version     1.0
     */
    
    if ( ! function_exists( 'noo_vc_field_event_location' ) ) :
        
        function noo_vc_field_event_location( $settings, $value ) {
    
            $categories = get_categories( array( 'orderby' => 'NAME', 'order' => 'ASC', 'taxonomy'=>'event_location' ) );
            $class = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
            $selected_values = explode( ',', $value );
            $html = array( '<div class="noo_vc_custom_param post_categories">' );
            $html[] = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value .
                '" class="wpb_vc_param_value" />';
            if ( isset($categories) && !empty($categories) && taxonomy_exists('event_location') ) :
                $html[] = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '">';
                foreach ( $categories as $location ) {
                    $html[] = '    <option value="' . intval($location->term_id) . '" ' .
                        ( in_array( $location->term_id, $selected_values ) ? 'selected="true"' : '' ) . '>';
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

        vc_add_shortcode_param( 'noo_event_location', 'noo_vc_field_event_location' );
    
    endif;

    if ( ! function_exists( 'noo_vc_custom_param_trainer_categories' ) ) :

        function noo_vc_custom_param_trainer_categories( $settings, $value ) {
            $dependency = vc_generate_dependencies_attributes( $settings );
            $categories = get_terms( 'class_category', array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
            $class = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
            $selected_values = explode( ',', $value );
            $html = array( '<div class="noo_vc_custom_param trainer_categories">' );
            $html[] = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value .
                 '" class="wpb_vc_param_value" />';
            $html[] = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '" ' .
                 $dependency . '>';
            $html[] = '    <option value="all" ' . ( in_array( 'all', $selected_values ) ? 'selected="true"' : '' ) . '>' .
                 __( 'All', 'noo-hermosa-core' ) . '</option>';
            foreach ( $categories as $category ) {
                $html[] = '    <option value="' . $category->term_id . '" ' .
                     ( in_array( $category->term_id, $selected_values ) ? 'selected="true"' : '' ) . '>';
                $html[] = '      ' . $category->name;
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
        vc_add_shortcode_param( 'trainer_categories', 'noo_vc_custom_param_trainer_categories' );
    
    
    endif;

    if ( ! function_exists( 'noo_vc_custom_param_class_categories' ) ) :

        function noo_vc_custom_param_class_categories( $settings, $value ) {
            $dependency = vc_generate_dependencies_attributes( $settings );
            $categories = get_terms( 'class_category', array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
            $class = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
            $selected_values = explode( ',', $value );
            $html = array( '<div class="noo_vc_custom_param class_category">' );
            $html[] = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value .
                 '" class="wpb_vc_param_value" />';
            $html[] = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '" ' .
                 $dependency . '>';
            $html[] = '    <option value="all" ' . ( in_array( 'all', $selected_values ) ? 'selected="true"' : '' ) . '>' .
                 __( 'All', 'noo-hermosa-core' ) . '</option>';
            foreach ( $categories as $category ) {
                $html[] = '    <option value="' . $category->term_id . '" ' .
                     ( in_array( $category->term_id, $selected_values ) ? 'selected="true"' : '' ) . '>';
                $html[] = '      ' . $category->name;
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
        vc_add_shortcode_param( 'class_categories', 'noo_vc_custom_param_class_categories' );
    
    
    endif;

    if ( ! function_exists( 'noo_vc_custom_param_time_range_lists' ) ) :

        function noo_vc_custom_param_time_range_lists( $settings, $value ) {
            $dependency = vc_generate_dependencies_attributes( $settings );
            $class = 'wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field';
            $selected_values = explode( ',', $value );
            $html = array( '<div class="noo_vc_custom_param time_range_lists">' );
            $html[] = '  <input type="hidden" name="' . $settings['param_name'] . '" value="' . $value .
                 '" class="wpb_vc_param_value" />';
            $html[] = '  <select name="' . $settings['param_name'] . '-select" multiple="true" class="' . $class . '" ' .
                 $dependency . '>';
            $html[] = '    <option value="all" ' . ( in_array( 'all', $selected_values ) ? 'selected="true"' : '' ) . '>' .
                 __( 'All', 'noo-hermosa-core' ) . '</option>';
            // Get hours
            $hours = range(0, 23);
            foreach ( $hours as $key => $hour ) {
                $html[] = '    <option value="' . $key . '" ' .
                     ( in_array( $key, $selected_values ) ? 'selected="true"' : '' ) . '>';
                $html[] = '      ' . $hour . ':00:00';
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
        vc_add_shortcode_param( 'time_range_lists', 'noo_vc_custom_param_time_range_lists' );
    
    
    endif;

    if ( ! function_exists( 'noo_vc_custom_param_user_list' ) ) :

        function noo_vc_custom_param_user_list( $settings, $value ) {
            $dependency = vc_generate_dependencies_attributes( $settings );
            $users = get_users( array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
            $class = 'wpb_vc_param_value wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] .
                '_field';
            $html = array( '<div class="noo_vc_custom_param user_list">' );
            // $html[] = ' <input type="hidden" name="'. $settings['param_name'] . '" value="'. $value . '"
            // class="wpb_vc_param_value" />';
            $html[] = '  <select name="' . $settings['param_name'] . '" class="' . $class . '" ' . $dependency . '>';
            foreach ( $users as $user ) {
                $html[] = '    <option value="' . $user->ID . '" ' . ( selected( $value, $user->ID, false ) ) . '>';
                $html[] = '      ' . $user->display_name;
                $html[] = '    </option>';
            }

            $html[] = '  </select>';
            $html[] = '</div>';

            return implode( "\n", $html );
        }
        vc_add_shortcode_param( 'user_list', 'noo_vc_custom_param_user_list' );


    endif;


    if ( class_exists( 'RevSlider' ) ) {
        if ( ! function_exists( 'noo_vc_rev_slider' ) ) :

            function noo_vc_rev_slider( $settings, $value ) {
                $dependency = vc_generate_dependencies_attributes( $settings );
                $rev_slider = new RevSlider();
                $sliders = $rev_slider->getArrSliders();
                $class = 'wpb_vc_param_value wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] .
                    '_field';
                $html = array( '<div class="noo_vc_custom_param noo_rev_slider">' );
                $html[] = '  <select name="' . $settings['param_name'] . '" class="' . $class . '" ' . $dependency . '>';
                foreach ( $sliders as $slider ) {
                    $html[] = '    <option value="' . $slider->getAlias() . '"' .
                        ( selected( $value, $slider->getAlias() ) ) . '>' . $slider->getTitle() . '</option>';
                }
                $html[] = '  </select>';
                $html[] = '</div>';

                return implode( "\n", $html );
            }

            vc_add_shortcode_param( 'noo_rev_slider', 'noo_vc_rev_slider' );


        endif;
    }

    if ( ! function_exists( 'noo_vc_custom_param_ui_slider' ) ) :

        function noo_vc_custom_param_ui_slider( $settings, $value ) {
            $dependency = vc_generate_dependencies_attributes( $settings );
            $class = 'noo-slider wpb_vc_param_value wpb-input wpb-select ' . $settings['param_name'] . ' ' .
                $settings['type'] . '_field';
            $data_min = ( isset( $settings['data_min'] ) && ! empty( $settings['data_min'] ) ) ? 'data-min="' .
                $settings['data_min'] . '"' : 'data-min="0"';
            $data_max = ( isset( $settings['data_max'] ) && ! empty( $settings['data_max'] ) ) ? 'data-max="' .
                $settings['data_max'] . '"' : 'data-max="100"';
            $data_step = ( isset( $settings['data_step'] ) && ! empty( $settings['data_step'] ) ) ? 'data-step="' .
                $settings['data_step'] . '"' : 'data-step="1"';
            $html = array();

            $html[] = '	<div class="noo-control">';
            $html[] = '		<input type="text" id="' . $settings['param_name'] . '" name="' . $settings['param_name'] .
                '" class="' . $class . '" value="' . $value . '" ' . $data_min . ' ' . $data_max . ' ' . $data_step .
                '/>';
            $html[] = '	</div>';
            $html[] = '<script>';
            $html[] = 'jQuery("#' . $settings['param_name'] . '").each(function() {';
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

        vc_add_shortcode_param( 'ui_slider', 'noo_vc_custom_param_ui_slider' );

    endif;

endif;

if ( defined( 'WPB_VC_VERSION' ) ) :
    if ( ! function_exists( 'noo_vc_admin_enqueue_assets' ) ) :

        function noo_vc_admin_enqueue_assets( $hook ) {
            if ( $hook != 'edit.php' && $hook != 'post.php' && $hook != 'post-new.php' ) {
                return;
            }
            // Enqueue style for VC admin
            wp_register_style( 'noo-vc-admin-css', NOO_ADMIN_ASSETS_URI . '/css/noo-vc-admin.css', array( 'noo-jquery-ui-slider' ) );
            wp_enqueue_style( 'noo-vc-admin-css' );

            // Enqueue script for VC admin
            wp_register_script(
                'noo-vc-admin-js',
                NOO_ADMIN_ASSETS_URI . '/js/noo-vc-admin.js',
                null,
                null,
                false );
            wp_enqueue_script( 'noo-vc-admin-js' );
        }

    endif;
    add_action( 'admin_enqueue_scripts', 'noo_vc_admin_enqueue_assets' );

endif;


?>