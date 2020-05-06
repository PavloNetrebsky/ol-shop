<?php

if ( defined('WPB_VC_VERSION') ) :
    if ( ! function_exists( 'noo_hermosa_vc_timetable' ) ) {
        function noo_hermosa_vc_timetable() {
            $category_plugin_name = esc_html__( 'By NooTimetable', 'noo-hermosa' );

            /**
             * Create shortcode: [ntt_trainer]
             *
             * @package     Noo Library
             * @author      Hung Ngo <hungnt@vietbrain.com> // update manhnv@vietbrain.com
             */
            vc_map( array(
                'name'          =>  esc_html__( 'Noo Trainer', 'noo-hermosa' ),
                'base'          =>  'ntt_trainer',
                'description'   =>  '',
                'icon'          =>  '',
                'category'      =>   $category_plugin_name,
                'params'        =>  array(
                   
                    array(
                        'param_name'  => 'title',
                        'heading'     => esc_html__( 'Title (optional)', 'noo-hermosa' ),
                        'description' => '',
                        'type'        => 'textfield',
                        'value'       => ''
                    ),

                    array(
                        'param_name'  => 'sub_title',
                        'heading'     => esc_html__( 'Sub Title (optional)', 'noo-hermosa' ),
                        'description' => '',
                        'type'        => 'textfield',
                        'value'       => ''
                    ),
                    array(
                        'param_name'    => 'layout_style',
                        'heading'       => esc_html__('Layout Style', 'noo-hermosa'),
                        'description'   => '',
                        'type'          => 'dropdown',
                        'admin_label' => true,
                        'value'       => array( 
                            esc_html__( 'Grid Masonry', 'noo-hermosa' )     => 'masonry',
                            esc_html__( 'Slider', 'noo-hermosa' )           => 'slider'
                        ),
                    ),
                    array(
                        'param_name'  => 'style_title', 
                        'heading'     => esc_html__( 'Style Title', 'noo-hermosa' ), 
                        'type'        => 'dropdown', 
                        'std'         => 'style1',
                        'admin_label' => true,
                        'value'       => array( 
                            esc_html__( 'Style 1', 'noo-hermosa' )      => 'style_title-1',
                            esc_html__( 'Style 2', 'noo-hermosa' )      => 'style_title-2',
                        ),
                        'dependency' => array( 'element' => 'layout_style', 'value' => array( 'slider' ) ), 
                    ),
                    array(
                        'param_name'  => 'autoplay', 
                        'heading'     => esc_html__( 'Auto Play Slider', 'noo-hermosa' ), 
                        'type'        => 'dropdown', 
                        'value'       => array( 
                            esc_html__( 'Yes', 'noo-hermosa' )   => 'true', 
                            esc_html__( 'No', 'noo-hermosa' ) => 'false'
                        ),
                        'dependency' => array( 'element' => 'layout_style', 'value' => array( 'slider' ) ), 
                    ),
                    array( 
                        'param_name' => 'columns', 
                        'heading'    => esc_html__( 'Columns', 'noo-hermosa' ), 
                        'type'       => 'ui_slider',
                        'admin_label' => true,
                        'value'      => '4', 
                        'data_min'   => '1', 
                        'data_max'   => '4',
                        'dependency' => array( 'element' => 'layout_style', 'value' => array( 'masonry', 'slider' ) ),
                    ),

                    array( 
                        'param_name'  => 'cat', 
                        'heading'     => esc_html__( 'Trainer Categories', 'noo-hermosa' ), 
                        'description' => '', 
                        'type'        => 'trainer_categories',
                    ),
                    array( 
                        'param_name' => 'filter', 
                        'heading'    => esc_html__( 'Show Category Filter', 'noo-hermosa' ), 
                        'type'       => 'checkbox', 
                        'value'      => array( '' => 'true' ),
                        'dependency' => array( 'element' => 'layout_style', 'value' => array( 'masonry' ) ), 
                    ),
                    array( 
                        'param_name'  => 'orderby', 
                        'heading'     => esc_html__( 'Order By', 'noo-hermosa' ), 
                        'description' => '', 
                        'admin_label' => true,
                        'type'        => 'dropdown', 
                        'value'       => array( 
                            esc_html__( 'Default', 'noo-hermosa' )                 => 'default', 
                            esc_html__( 'Recent First', 'noo-hermosa' )            => 'latest', 
                            esc_html__( 'Older First', 'noo-hermosa' )             => 'oldest', 
                            esc_html__( 'Title Alphabet', 'noo-hermosa' )          => 'alphabet', 
                            esc_html__( 'Title Reversed Alphabet', 'noo-hermosa' ) => 'ralphabet' )
                    ), 
                    array( 
                        'param_name' => 'limit', 
                        'heading'    => esc_html__( 'Max Number of Trainers', 'noo-hermosa' ), 
                        'type'       => 'ui_slider', 
                        'admin_label' => true,
                        'value'      => '4', 
                        'data_min'   => '1', 
                        'data_max'   => '50'
                    ),
                    array(
                        'param_name' => 'slider_speed',
                        'heading'    => esc_html__( 'Slide Speed (ms)', 'noo-hermosa' ),
                        'type'       => 'ui_slider',
                        'value'      => '800',
                        'data_min'   => '300',
                        'data_max'   => '5000',
                        'data_step'  => '100',
                        'dependency' => array( 'element' => 'layout_style', 'value' => array( 'slider' ) ),
                     ),
                    array(
                        'param_name'  => 'show_navigation',
                        'heading'     => __( 'Show Navigation', 'noo-hermosa' ),
                        'description' => '',
                        'type'        => 'dropdown',
                        'value'       => array(
                            esc_html__( 'Show', 'noo-hermosa' ) => 'true',
                            esc_html__( 'Hide', 'noo-hermosa' ) => 'false',
                        ),
                        'dependency' => array( 'element' => 'layout_style', 'value' => array( 'slider' ) ),
                    ),
                    array(
                        'param_name'  => 'show_pagination',
                        'heading'     => esc_html__( 'Show pagination', 'noo-hermosa' ),
                        'description' => '',
                        'type'        => 'dropdown',
                        'value'       => array(
                            esc_html__( 'Hide', 'noo-hermosa' ) => 'false',
                            esc_html__( 'Show', 'noo-hermosa' ) => 'true',
                        ),
                        'dependency' => array( 'element' => 'layout_style', 'value' => array( 'slider' ) ),
                    ),
                )
            ));


            /**
             * Create shortcode: [ntt_class]
             *
             * @package     Noo Library
             * @author      Hung Ngo <hungnt@vietbrain.com>
             */
            vc_map( array(
                'name'          =>  esc_html__( 'Noo Class', 'noo-hermosa' ),
                'base'          =>  'ntt_class',
                'description'   =>  '',
                'icon'          =>  '',
                'category'      =>   $category_plugin_name,
                'params'        =>  array(
                    array(
                        'param_name'  => 'title',
                        'heading'     => esc_html__( 'Title (optional)', 'noo-hermosa' ),
                        'description' => '',
                        'type'        => 'textfield',
                        'value'       => ''
                    ),
                    array(
                        'param_name'  => 'sub_title',
                        'heading'     => esc_html__( 'Sub Title (optional)', 'noo-hermosa' ),
                        'description' => '',
                        'type'        => 'textfield',
                        'value'       => ''
                    ),
                    array(
                        'param_name'  => 'layout_style', 
                        'heading'     => esc_html__( 'Layout Style', 'noo-hermosa' ), 
                        'type'        => 'dropdown', 
                        'admin_label' => true,
                        'value'       => array( 
                            esc_html__( 'Grid', 'noo-hermosa' )   => 'grid',
                            esc_html__( 'Slider', 'noo-hermosa' ) => 'slider'
                        ),
                    ),
                    array(
                        'param_name'  => 'sliders_style', 
                        'heading'     => esc_html__( 'Slider Style', 'noo-hermosa' ), 
                        'type'        => 'dropdown', 
                        'std'         => 'style1',
                        'admin_label' => true,
                        'value'       => array( 
                            esc_html__( 'Style 1', 'noo-hermosa' )      => 'style1',
                            esc_html__( 'Style 2', 'noo-hermosa' )      => 'style2',
                        ),
                        'dependency' => array( 
                            'element' => 'layout_style', 
                            'value' => array( 'slider' ) 
                        ), 

                    ),
                    array(
                        'param_name'  => 'autoplay', 
                        'heading'     => esc_html__( 'Auto Play Slider', 'noo-hermosa' ), 
                        'type'        => 'dropdown', 
                        'value'       => array( 
                            esc_html__( 'Yes', 'noo-hermosa' )   => 'true', 
                            esc_html__( 'No', 'noo-hermosa' ) => 'false'
                        ),
                        'dependency' => array( 'element' => 'layout_style', 'value' => array( 'slider' ) ), 
                    ),
                    array( 
                        'param_name'  => 'show_info', 
                        'heading'     => esc_html__( 'Show Info', 'noo-hermosa' ), 
                        'type'        => 'dropdown', 
                        'admin_label' => true,
                        'value'       => array( 
                            esc_html__( 'Show Date & Time', 'noo-hermosa' )    => 'all', 
                            esc_html__( 'Only Date', 'noo-hermosa' )   => 'date',
                            esc_html__( 'Only Time', 'noo-hermosa' )   => 'time',
                            esc_html__( 'Hide Date & Time', 'noo-hermosa' ) => 'null'
                        ),
                    ),
                    array( 
                        'param_name' => 'columns', 
                        'heading'    => esc_html__( 'Columns', 'noo-hermosa' ), 
                        'type'       => 'ui_slider',
                        'admin_label' => true,
                        'value'      => '4', 
                        'data_min'   => '1', 
                        'data_max'   => '4',
                        'dependency' => array( 'element' => 'layout_style', 'value' => array( 'grid', 'slider' ) ),
                    ),

                    array( 
                        'param_name'  => 'cat', 
                        'heading'     => esc_html__( 'Class Categories', 'noo-hermosa' ), 
                        'description' => '', 
                        'type'        => 'class_categories',
                    ),
                    array( 
                        'param_name'  => 'orderby', 
                        'heading'     => esc_html__( 'Order By', 'noo-hermosa' ), 
                        'description' => '', 
                        'admin_label' => true,
                        'type'        => 'dropdown',
                        'value'       => array( 
                            esc_html__( 'Default', 'noo-hermosa' )                 => 'default',
                            esc_html__( 'Open Date', 'noo-hermosa' )               => 'open_date',
                            esc_html__( 'Recent First', 'noo-hermosa' )            => 'latest',
                            esc_html__( 'Older First', 'noo-hermosa' )             => 'oldest',
                            esc_html__( 'Title Alphabet', 'noo-hermosa' )          => 'alphabet',
                            esc_html__( 'Title Reversed Alphabet', 'noo-hermosa' ) => 'ralphabet'
                        )
                    ), 
                    array( 
                        'param_name' => 'limit', 
                        'heading'    => esc_html__( 'Max Number of Classes', 'noo-hermosa' ), 
                        'type'       => 'ui_slider',
                        'admin_label' => true,
                        'value'      => '4', 
                        'data_min'   => '1', 
                        'data_max'   => '50'
                    ),
                    array(
                        'param_name' => 'slider_speed',
                        'heading'    => esc_html__( 'Slide Speed (ms)', 'noo-hermosa' ),
                        'type'       => 'ui_slider',
                        'value'      => '800',
                        'data_min'   => '300',
                        'data_max'   => '3000',
                        'data_step'  => '100',
                        'dependency' => array( 'element' => 'layout_style', 'value' => array( 'slider' ) ),
                    ),
                    array(
                        'param_name'  => 'show_navigation',
                        'heading'     => esc_html__( 'Show Navigation', 'noo-hermosa' ),
                        'description' => '',
                        'type'        => 'dropdown',
                        'value'       => array(
                            esc_html__( 'Hide', 'noo-hermosa' ) => 'false',
                            esc_html__( 'Show', 'noo-hermosa' ) => 'true',
                        ),
                        'dependency' => array( 'element' => 'layout_style', 'value' => array( 'slider' ) ),
                    ),
                    array(
                        'param_name'  => 'show_pagination',
                        'heading'     => esc_html__( 'Show pagination', 'noo-hermosa' ),
                        'description' => '',
                        'type'        => 'dropdown',
                        'value'       => array(
                            esc_html__( 'Hide', 'noo-hermosa' ) => 'false',
                            esc_html__( 'Show', 'noo-hermosa' ) => 'true',
                        ),
                        'dependency' => array( 'element' => 'layout_style', 'value' => array( 'slider' ) ),
                    ),
                )
            ));
        }

        add_action( 'admin_init', 'noo_hermosa_vc_timetable', 11 );
    }
endif;
