<?php
/**
 * NOO Visual Composer Add-ons
 *
 * Customize Visual Composer to suite NOO Framework
 *
 * @package    NOO Framework
 * @subpackage NOO Visual Composer Add-ons
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
//
// Variables.
//
$category_name         = esc_html__( 'By NooHermosa', 'noo-hermosa-core' );

// custom [row]
vc_add_param('vc_row', array(
        "type"        =>  "checkbox",
        "admin_label" =>  true,
        "heading"     =>  "Using Container",
        "param_name"  =>  "container_width",
        "description" =>  esc_html__( 'If checked container will be set to width 1170px for content.','noo-hermosa-core'),
        'weight'      => 1,
        'value'       => array( esc_html__( 'Yes', 'noo-hermosa-core' ) => 'yes' )
    )
);

/**
 * Create shortcode: [noo_product]
 *
 * @package     Noo Library
 * @author      KENT <tuanlv@vietbrain.com> //  Create Grid masonry manhnv@vietbrain.com
 * @version     1.0
 */
vc_map( array(
    'name'          =>  esc_html__( 'Noo Product', 'noo-hermosa-core' ),
    'base'          =>  'noo_product',
    'description'   =>  esc_html__( 'Display post to product', 'noo-hermosa-core' ),
    'icon'          =>  'noo_icon_product',
    'category'      =>   $category_name,
    'params'        =>  array(
        
       
        array(
			'param_name'  => 'title',
			'heading'     => esc_html__( 'Title', 'noo-hermosa-core' ),
			'description' => '',
			'type'        => 'textfield',
			'admin_label' => true,
			'holder'      => 'div',
            'value'       => ''
        ),

        array(
			'param_name'  => 'sub_title',
			'heading'     => esc_html__( 'Sub Title', 'noo-hermosa-core' ),
			'description' => '',
			'type'        => 'textfield',
			'admin_label' => true,
			'holder'      => 'div',
			'value'       => ''
        ),
        array(
            'param_name'    => 'layout_style',
            'heading'       => esc_html( 'Choose Layout Product Slider Or Grid Masonry'),
            'description'   => '',
            'type'          => 'dropdown',
            'admin_label'   => true,
            'holder'        => 'div',
            'value'         => array(
                esc_html__( 'Slider','noo-hermosa-core' )       => 'slider',
                esc_html__( 'Grid Masonry ','noo-hermosa-core') => 'grid',
            ),
        ),
         array(
            'param_name'    => 'style_title',
            'heading'       => esc_html__( 'Choose Style Title', 'noo-hermosa-core' ),
            'description'   => '',
            'type'          => 'dropdown',
            'holder'        => 'div',
            'std'           => 'style-1',
            'value'         => array(
                esc_html__( 'Style 1', 'noo-hermosa-core' )   => 'style-1',
                esc_html__( 'Style 2', 'noo-hermosa-core' )   => 'style-2'
            ),
            'dependency'    => array(
                'element'   => 'layout_style',
                'value'     => array( 'slider' )
            ),
        ),
        array(
            'param_name'    => 'slider_style',
            'heading'       => esc_html( 'Choose Style Product Slider'),
            'description'   => '',
            'type'          => 'dropdown',
            'admin_label'   => true,
            'holder'        => 'div',
            'value'         => array(
                esc_html__( 'Style 1','noo-hermosa-core' )   => 'style_1',
                esc_html__( 'Style 2','noo-hermosa-core')    => 'style_2',
            ),
            'dependency'    => array(
                'element'   => 'layout_style',
                'value'     => array( 'slider' )
            ),
        ),

        array(
            'param_name'    => 'product_cat',
            'heading'       => esc_html__( 'Choose categories', 'noo-hermosa-core' ),
            'description'   => '',
            'type'          => 'noo_product_cat',
            'admin_label'   => true,
            'holder'        => 'div',
        ),

        array(
            'param_name'    => 'orderby',
            'heading'       => esc_html__( 'Order By', 'noo-hermosa-core' ),
            'description'   => '',
            'type'          => 'dropdown',
            'holder'        => 'div',
            'std'           => 'latest',
            'value'         => array(
                esc_html__( 'Recent First', 'noo-hermosa-core' )             => 'latest',
                esc_html__( 'Older First', 'noo-hermosa-core' )              => 'oldest',
                esc_html__( 'Title Alphabet', 'noo-hermosa-core' )           => 'alphabet',
                esc_html__( 'Title Reversed Alphabet', 'noo-hermosa-core' )  => 'ralphabet'
            )
        ),
        array(
            'type'          =>  'dropdown',
            'holder'        =>  'div',
            'class'         =>  '',
            'heading'       =>  esc_html__('Config columns','noo-hermosa-core'),
            'param_name'    =>  'columns',
            'value'         =>  array(
                esc_html__('5 columns','noo-hermosa-core')     =>  '5',
                esc_html__('4 columns','noo-hermosa-core')     =>  '4',
                esc_html__('3 columns','noo-hermosa-core')     =>  '3',
                esc_html__('2 columns','noo-hermosa-core')     =>  '2',
                esc_html__('1 columns','noo-hermosa-core')     =>  '1',
            )
        ),
        array(
            'type'          =>  'dropdown',
            'holder'        =>  'div',
            'class'         =>  '',
            'heading'       =>  esc_html__('Auto Slider','noo-hermosa-core'),
            'param_name'    =>  'auto_slider',
            'value'         =>  array(
                esc_html__('False','noo-hermosa-core')     =>  'false',
                esc_html__('True','noo-hermosa-core')     =>  'true'
            ),
            'dependency'    => array(
                'element'   => 'layout_style',
                'value'     => array( 'slider' )
            ),
        ),
        array(
            'param_name'  => 'posts_per_page',
            'heading'     => esc_html__( 'Posts Per Page', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => 10
        ),

        array(
            'param_name' => 'slider_speed',
            'heading'    => esc_html__( 'Slide Speed (ms)', 'noo-hermosa-core' ),
            'type'       => 'ui_slider',
            'holder'     => 'div',
            'value'      => '800',
            'data_min'   => '300',
            'data_max'   => '3000',
            'data_step'  => '100',
            'dependency'    => array(
                'element'   => 'layout_style',
                'value'     => array( 'slider' )
            ),
         ),
        // array(
        //     'param_name'  => 'show_navigation',
        //     'heading'     => esc_html__( 'Show Navigation', 'noo-hermosa-core' ),
        //     'description' => '',
        //     'type'        => 'dropdown',
        //     'holder'      => 'div',
        //     'value'       => array(
        //         esc_html__( 'Hide', 'noo-hermosa-core' ) => 'false',
        //         esc_html__( 'Show', 'noo-hermosa-core' ) => 'true',
        //     ),
        //     'dependency'    => array(
        //         'element'   => 'layout_style',
        //         'value'     => array( 'slider' )
        //     ),
        // ),
        array(
            'param_name'  => 'show_pagination',
            'heading'     => __( 'Show pagination', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
                esc_html__( 'Hide', 'noo-hermosa-core' ) => 'false',
                esc_html__( 'Show', 'noo-hermosa-core' ) => 'true',
            ),
            'dependency'    => array(
                'element'   => 'layout_style',
                'value'     => array( 'slider' )
            ),
        ),

        array(
            'param_name'  => 'button_link',
            'heading'     => esc_html__( 'Button', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'vc_link',
            'holder'      => 'div',
        ),
    )
));

/**
 * Create shortcode: [noo_product]
 *
 * @package     Noo Library
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
    'name'          =>  esc_html__( 'Noo Event Slider', 'noo-hermosa-core' ),
    'base'          =>  'noo_event_slider',
    'description'   =>  '',
    'icon'          =>  'noo_icon_event',
    'category'      =>   $category_name,
    'params'        =>  array(
        
        array(
            'param_name'  => 'title',
            'heading'     => esc_html__( 'Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'admin_label' => true,
            'holder'      => 'div',
            'value'       => ''
        ),

        array(
            'param_name'  => 'sub_title',
            'heading'     => esc_html__( 'Sub Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'admin_label' => true,
            'holder'      => 'div',
            'value'       => ''
        ),

        array(
            'param_name'    => 'event_cat',
            'heading'       => esc_html__( 'Choose categories', 'noo-hermosa-core' ),
            'description'   => '',
            'type'          => 'event_categories',
            'admin_label'   => true,
            'holder'        => 'div',
        ),
        
        array(
            'param_name'    => 'orderby',
            'heading'       => esc_html__( 'Order By', 'noo-hermosa-core' ),
            'description'   => '',
            'type'          => 'dropdown',
            'holder'        => 'div',
            'std'           => 'latest',
            'value'         => array(
                esc_html__( 'Recent First', 'noo-hermosa-core' )             => 'latest',
                esc_html__( 'Older First', 'noo-hermosa-core' )              => 'oldest',
                esc_html__( 'Title Alphabet', 'noo-hermosa-core' )           => 'alphabet',
                esc_html__( 'Title Reversed Alphabet', 'noo-hermosa-core' )  => 'ralphabet'
            )
        ),

        array(
            'param_name'  => 'posts_per_page',
            'heading'     => esc_html__( 'Posts Per Page', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => 10
        ),
        
        array(
            'param_name'  => 'button_link',
            'heading'     => esc_html__( 'Button', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'vc_link',
            'holder'      => 'div',
        ),

        array(
            'param_name'  => 'hide_past_event',
            'heading'     => esc_html__( 'Hide Past Event', 'noo-hermosa-core' ),
            'default'     => 'no',
            'type'        => 'checkbox',
            'value'       => array( esc_html__( 'Yes', 'noo-hermosa-core' ) => 'yes' )
        ),
    )
));

/**
 * Create shortcode: [noo_title]
 *
 * @package     Noo Library
 * @author      Hung Ngo <hungnt@vietbrain.com>
 */
vc_map( array(
    'name'          =>  esc_html__( 'Noo Title', 'noo-hermosa-core' ),
    'base'          =>  'noo_title',
    'description'   =>  '',
    'icon'          =>  '',
    'category'      =>   $category_name,
    'params'        =>  array(
        
        array(
            'param_name'  => 'title',
            'heading'     => esc_html__( 'Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'admin_label' => true,
            'holder'      => 'div',
            'value'       => ''
        ),

        array(
            'param_name'  => 'sub_title',
            'heading'     => esc_html__( 'Sub Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'admin_label' => true,
            'holder'      => 'div',
            'value'       => ''
        ),

        array(
            'param_name'    => 'text_align',
            'heading'       => esc_html__( 'Text Align', 'noo-hermosa-core' ),
            'description'   => '',
            'type'          => 'dropdown',
            'holder'        => 'div',
            'std'           => 'center',
            'value'         => array(
                esc_html__( 'Center', 'noo-hermosa-core' ) => 'center',
                esc_html__( 'Left', 'noo-hermosa-core' )   => 'left',
                esc_html__( 'Right', 'noo-hermosa-core' )  => 'right'
            )
        ),
        array(
            'param_name'    => 'style',
            'heading'       => esc_html__( 'Choose Style Title', 'noo-hermosa-core' ),
            'description'   => '',
            'type'          => 'dropdown',
            'holder'        => 'div',
            'std'           => 'style-1',
            'value'         => array(
                esc_html__( 'Style 1', 'noo-hermosa-core' )   => 'style-1',
                esc_html__( 'Style 2', 'noo-hermosa-core' )   => 'style-2',
            )
        ),

    )
));

/**
 * Create shortcode: [noo_trainer]
 *
 * @package     Noo Library
 * @author      Hung Ngo <hungnt@vietbrain.com>
 */
vc_map( array(
    'name'          =>  esc_html__( 'Noo Trainer', 'noo-hermosa-core' ),
    'base'          =>  'noo_trainer',
    'description'   =>  '',
    'icon'          =>  '',
    'category'      =>   $category_name,
));

/**
 * Create shortcode: [noo_class_grid_slider]
 *
 * @package     Noo Library
 * @author      Hung Ngo <hungnt@vietbrain.com>
 */
vc_map( array(
    'name'          =>  esc_html__( 'Noo Class Grid Slider', 'noo-hermosa-core' ),
    'base'          =>  'noo_class_grid_slider',
    'description'   =>  '',
    'icon'          =>  '',
    'category'      =>   $category_name,
));

/**
 * Create shortcode: [noo_class_schedule]
 *
 * @package     Noo Library
 * @author      Hung Ngo <hungnt@vietbrain.com>
 */
$hours = range(0, 24);
foreach ($hours as $k => $v) {
    $hours[$k] = $v . ':00:00';
}
vc_map( array(
    'name'            =>  esc_html__( 'Noo Class Schedule', 'noo-hermosa-core' ),
    'base'            =>  'noo_class_schedule',
    'description'     =>  '',
    'icon'            =>  '',
    'category'        =>   $category_name,
    'params'          =>  array(
        
        array(
            'param_name'  => 'title',
            'heading'     => esc_html__( 'Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'admin_label' => true,
            'holder'      => 'div',
            'value'       => ''
        ),
        array(
            'param_name'  => 'sub_title',
            'heading'     => esc_html__( 'Sub Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'admin_label' => true,
            'holder'      => 'div',
            'value'       => ''
        ),
        array( 
            "type"        => "dropdown",
            "heading"     => esc_html__( "Default View", 'noo-hermosa-core' ), 
            'description' => esc_html__('View of Schedule','noo-hermosa-core'),
            "param_name"  => "default_view", 
            "admin_label" => true, 
            'holder'      => 'div', 
            "value"       => array( 
                esc_html__( 'Weekly View', 'noo-hermosa-core' )  => 'agendaWeek', 
                esc_html__( 'Monthly view', 'noo-hermosa-core' ) => 'month'
            )
        ),
        array( 
            "type"        => "dropdown",
            "heading"     => esc_html__( "Schedule Min Time", 'noo-hermosa-core' ), 
            'description' => esc_html__('Time start of Schedule (Hour), ex: 05:00:00','noo-hermosa-core'),
            "param_name"  => "min_time", 
            "admin_label" => true, 
            'holder'      => 'div', 
            "value"       => $hours
        ),
        array(
            "type"        => "dropdown",
            "heading"     => esc_html__( "Schedule Max Time", 'noo-hermosa-core' ),
            'description' => esc_html__('Time end of Schedule (Hour), ex: 21:00:00','noo-hermosa-core'),
            "param_name"  => "max_time",
            "admin_label" => true, 
            'holder'      => 'div', 
            "value"       => $hours
        ),
        array(
            "type"        => "textfield",
            "heading"     => esc_html__( "Schedule Height", 'noo-hermosa-core' ),
            'description' => esc_html__('Input height of schedule, leave blank for auto height.','noo-hermosa-core'),
            "param_name"  => "content_height",
            "admin_label" => true, 
            'holder'      => 'div', 
            "value"       => ''
        ),
        array(
            "type"        => "time_range_lists",
            "heading"     => esc_html__( "Hide Times from Schedule", 'noo-hermosa-core' ),
            'description' => esc_html__('Hours selected here will be hidden from the schedule. Note that you shouldn\'t select hours that have classes as it will lead to wrong calculation.','noo-hermosa-core'),
            "param_name"  => "hide_time_range",
            "admin_label" => true, 
            'holder'      => 'div',
        ),
        array( 
            "param_name"  => "show_time_column", 
            "type"        => "dropdown",
            "heading"     => esc_html__( "Show Time Column", 'noo-hermosa-core' ),
            "admin_label" => true, 
            'holder'      => 'div', 
            "value"       => array( 
                esc_html__( 'Yes', 'noo-hermosa-core' ) => 'yes', 
                esc_html__( 'No', 'noo-hermosa-core' )  => 'no'
            )
        ),
        array( 
            "type"        => "dropdown",
            "heading"     => esc_html__( "Show Weekends", 'noo-hermosa-core' ),
            "param_name"  => "show_weekends", 
            "admin_label" => true, 
            'holder'      => 'div', 
            "value"       => array( 
                esc_html__( 'Yes', 'noo-hermosa-core' ) => 'yes', 
                esc_html__( 'No', 'noo-hermosa-core' )  => 'no'
            )
        ),
        array( 
            "param_name"  => "show_export", 
            "type"        => "dropdown",
            "heading"     => esc_html__( "Show Export", 'noo-hermosa-core' ),
            "admin_label" => true, 
            'holder'      => 'div', 
            "value"       => array( 
                esc_html__( 'Yes', 'noo-hermosa-core' ) => 'yes', 
                esc_html__( 'No', 'noo-hermosa-core' )  => 'no'
            )
        ),
    )
));

/**
 * Create shortcode: [noo_event_calendar]
 *
 * @package     Noo Library
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
    'name'          =>  esc_html__( 'Noo Event Calendar', 'noo-hermosa-core' ),
    'base'          =>  'noo_event_calendar',
    'description'   =>  '',
    'icon'          =>  'noo_icon_event_calendar',
    'category'      =>   $category_name,
    'params'        =>  array(   

        array(
            'param_name'  => 'title',
            'heading'     => esc_html__( 'Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'admin_label' => true,
            'holder'      => 'div',
            'value'       => ''
        ),

        array(
            'param_name'  => 'sub_title',
            'heading'     => esc_html__( 'Sub Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'admin_label' => true,
            'holder'      => 'div',
            'value'       => ''
        ),

        array( 
            'type'        => 'dropdown',
            'heading'     => esc_html__( 'Show Weekends', 'noo-hermosa-core' ),
            'param_name'  => 'show_weekends', 
            'admin_label' => true, 
            'holder'      => 'div', 
            'value'       => array( 
                esc_html__( 'Yes', 'noo-hermosa-core' ) => 'yes', 
                esc_html__( 'No', 'noo-hermosa-core' )  => 'no'
            )
        ),  

        array( 
            'param_name'  => 'show_export', 
            'type'        => 'dropdown',
            'heading'     => esc_html__( 'Show Export', 'noo-hermosa-core' ),
            'admin_label' => true, 
            'holder'      => 'div', 
            'value'       => array( 
                esc_html__( 'Yes', 'noo-hermosa-core' ) => 'yes', 
                esc_html__( 'No', 'noo-hermosa-core' )  => 'no'
            )
        )
        
    )
));


/**
 * Create ShortCode: [noo_counter]
 *
 * @package     Noo Library
 * @author      Tu Nguyen <tunguyen@vietbrain.com>
 * @version     1.0
 */
vc_map(array(
    'name'      =>  esc_html__('Noo Counter','noo-hermosa-core'),
    'base'      =>  'noo_counter',
    'description'   =>  esc_html__('Counter block','noo-hermosa-core'),
    'icon'      =>  'noo_icon_counter',
    'category'  =>   $category_name,
    'params'    =>  array(
        array(
            'param_name'    =>  'icon',
            'heading'       =>  esc_html__('Upload Icon', 'noo-hermosa-core'),
            'description'   =>  '',
            'type'          =>  'attach_image',
            'holder'        =>  'div'
        ),
        array(
            'param_name'  => 'number',
            'heading'     => esc_html__( 'Import number', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'holder'      => 'div',
        ),
        array(
            'param_name'  => 'title',
            'heading'     => esc_html__( 'Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'holder'      => 'div',
        )
    )
));
/**
 * Create ShortCode: [noo_short_intro]
 *
 * @package     Noo Library
 * @author      Tu Nguyen <tunguyen@vietbrain.com>
 * @version     1.0
 */
vc_map(array(
    'name'      =>  esc_html__('Short Introduction','noo-hermosa-core'),
    'base'      =>  'noo_short_intro',
    'description'   =>  esc_html__('Display short introduction','noo-hermosa-core'),
    'icon'      =>  'noo_icon_short_intro',
    'category'  =>   $category_name,
    'params'    =>  array(
        array(
            'param_name'    =>  'icon',
            'heading'       =>  esc_html__('Upload Icon', 'noo-hermosa-core'),
            'description'   =>  '',
            'type'          =>  'attach_image',
            'holder'        =>  'div'
        ),
        array(
            'param_name'  => 'title',
            'heading'     => esc_html__( 'Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'holder'      => 'div',
        ),
        array(
            'type'          => 'textarea',
            'heading'       => esc_html__( 'Description', 'noo-hermosa-core' ),
            'param_name'    => 'description',
            'admin_label'   => true
        ),
        array(
            'param_name'    => 'custom_link',
            'heading'       => esc_html__( 'Link', 'noo-hermosa-core' ),
            'description'   => '',
            'type'          => 'vc_link',
            'admin_label'   => true,
            'holder'        => 'div',
            'value'         =>  '#'
        )
    )
));


/**
 * Create ShortCode: [noo_form]
 *
 * @package     Noo Library
 * @author      Tu Nguyen <tunguyen@vietbrain.com>
 * @version     1.0
 */
$noo_cf7 = get_posts('post_type="wpcf7_contact_form"&posts_per_page=-1');

$noo_contact_forms = array();
$noo_contact_forms[__('Choose contact form','noo-hermosa-core')]  =   '';
if ($noo_cf7) {
    foreach ($noo_cf7 as $cform) {
        $noo_contact_forms[$cform->post_title] = $cform->ID;
    }
} else {
    $noo_contact_forms[esc_html__('No contact forms found', 'noo-hermosa-core')] = 0;
}
vc_map(array(
    'name'        =>  esc_html__('Noo Contact form','noo-hermosa-core'),
    'base'        =>  'noo_form',
    'icon'        =>  'noo_icon_form',
    'category'    =>   $category_name,
    'description' =>   esc_html__('Display contact form7','noo-hermosa-core'),
    'params'      =>  array(
        array(
            'param_name'  => 'style_contact_form', 
            'heading'     => esc_html__( 'Style Contact Form', 'noo-hermosa' ), 
            'type'        => 'dropdown', 
            'std'         => 'style-default',
            'admin_label' => true,
            'value'       => array( 
                esc_html__( 'Style Default', 'noo-hermosa' )      => 'style-default',
                esc_html__( 'Style 2', 'noo-hermosa' )             => 'style-2',
            ),
        ),
        array(
            'param_name'  => 'title_1',
            'heading'     => esc_html__( 'Title', 'noo-hermosa-core' ),
            'description' => esc_html__( 'using sign / to down the line and create style', 'noo-hermosa-core' ),
            'type'        => 'textfield',
            'holder'      => 'div',
            'dependency' => array( 
                'element' => 'style_contact_form', 
                'value' => array( 'style-default' ) 
            ),
        ),
        array(
            'param_name'  => 'title_2',
            'heading'     => esc_html__( 'Title', 'noo-hermosa-core' ),
            'description' => esc_html__( 'Use <i>CONTENT</i> to make color for CONTENT', 'noo-hermosa-core' ),
            'type'        => 'textfield',
            'holder'      => 'div',
            'dependency' => array( 
                'element' => 'style_contact_form', 
                'value' => array( 'style-2' ) 
            ),
        ),
        array(
            'type'          => 'textarea',
            'heading'       => esc_html__( 'Description', 'noo-hermosa-core' ),
            'param_name'    => 'description',
            'admin_label'   => true
        ),
        array(
            'type'          => 'dropdown',
            'heading'       => esc_html__('Select contact form', 'noo-hermosa-core'),
            'param_name'    => 'custom_form',
            'value'         => $noo_contact_forms,
            'description'   => esc_html__('Choose previously created contact form from the drop down list.', 'noo-hermosa-core')
        ),
        array(
            'type'        => 'colorpicker',
            'heading'     => __( 'Select Color Contact Form', 'noo-hermosa-core' ),
            'param_name'  => 'color',
            'value'       => '#face00',
            'description' => __( 'Select Option Color Contact Form Icon', 'noo-hermosa-core' ),
            'dependency' => array( 
                'element' => 'style_contact_form', 
                'value' => array( 'style-2' ) 
            ),
        ),
        array(
            'type'        => 'colorpicker',
            'heading'     => __( 'SelectColor Color Button ', 'noo-hermosa-core' ),
            'param_name'  => 'color_text',
            'value'       => '#2a2924',
            'description' => __( 'SelectColor Color Button Contact Form', 'noo-hermosa-core' ),
            'dependency' => array( 
                'element' => 'style_contact_form', 
                'value' => array( 'style-2' ) 
            ),
        ),
    )
));

/**
 * Create ShortCode: [noo_video]
 *
 * @package     Noo Library
 * @author      Tu Nguyen <tunguyen@vietbrain.com>
 * @version     1.0
 */
vc_map(array(
    'name'      =>  esc_html__('Noo Video','noo-hermosa-core'),
    'base'      =>  'noo_video',
    'description'   =>  esc_html__('Display video of youtube or vimeo','noo-hermosa-core'),
    'icon'      =>  'noo_icon_video',
    'category'  =>   $category_name,
    'params'    =>  array(
        array(
            'param_name'  => 'title',
            'heading'     => esc_html__( 'Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'holder'      => 'div',
        ),
        array(
            'type'          => 'textarea',
            'heading'       => esc_html__( 'Description', 'noo-hermosa-core' ),
            'param_name'    => 'description',
            'admin_label'   => true
        ),
        array(
            'param_name'  => 'style',
            'heading'     => esc_html__( 'Choose Style', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'std'         => 'style-1',
            'value'       =>  array(
                esc_html__( 'Style One', 'noo-hermosa-core' ) =>  'style-1',
                esc_html__( 'Style Two', 'noo-hermosa-core' ) =>  'style-2',
                esc_html__( 'Style Three', 'noo-hermosa-core' ) =>  'style-3',
            )
        ),
        array(
            'param_name'  => 'thumb_id',
            'heading'     => esc_html__( 'Thumbnail video', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'attach_image',
            'holder'      => 'div',
        ),
        array(
            'param_name'  => 'video_id',
            'heading'     => esc_html__( 'Import video id', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'holder'      => 'div',
        ),
        array(
            "param_name"  => "video_type",
            "type"        => "dropdown",
            "heading"     => esc_html__( "Video type", 'noo-hermosa-core' ),
            "admin_label" => true,
            'holder'      => 'div',
            "value"       => array(
                esc_html__( 'Youtube', 'noo-hermosa-core' ) => 'youtube',
                esc_html__( 'Vimeo', 'noo-hermosa-core' )   => 'vimeo'
            )
        )
    )
));


/**
 * Create ShortCode: [noo_gallery]
 *
 * @package     Noo Library
 * @author      Manh Nguyen <manhnv@vietbrain.com>
 * @version     1.0
 */
vc_map(array(
    'name'      =>  esc_html__('Noo Gallery ','noo-hermosa-core'),
    'base'      =>  'noo_gallery',
    'description'   =>  esc_html__('Display Gallery','noo-hermosa-core'),
    'icon'      =>  'noo_icon_video',
    'category'  =>   $category_name,
    'params'    =>  array(
        array(
            'param_name'  => 'style_title', 
            'heading'     => esc_html__( 'Style Title', 'noo-hermosa' ), 
            'type'        => 'dropdown', 
            'std'         => 'style1',
            'admin_label' => true,
            'value'       => array( 
                esc_html__( 'Style 1', 'noo-hermosa' )      => 'style_title-1',
                esc_html__( 'Style 2', 'noo-hermosa' )      => 'style_title-2',
                esc_html__( 'Style 3', 'noo-hermosa' )      => 'style_title-3',
            ),
        ),
    
        array(
            'param_name'  => 'title',
            'heading'     => esc_html__( 'Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'holder'      => 'div',
        ),
        array(
            'type'          => 'textarea',
            'heading'       => esc_html__( 'Sub title', 'noo-hermosa-core' ),
            'param_name'    => 'sub_title',
            'admin_label'   => true
        ),
        array(
            'param_name'    =>'gallery_style',
            'heading'       => esc_html__('Gallery Style','noo-hermosa-core'),
            'description'   => '',
            'type'          => 'dropdown',
            'holder'        => 'div', 
            'admin_label'   => true,
            'value'         => array( 
                esc_html__( 'Gallery Style 1', 'noo-hermosa-core' )   => 'style-1',
                esc_html__( 'Gallery Style 2', 'noo-hermosa-core' )   => 'style-2',
            ),
        ),
        array(
            'param_name'    =>'filters_gallery',
            'heading'       => esc_html__('Show / Hidden Filters Gallery','noo-hermosa-core'),
            'description'   => '',
            'type'          => 'dropdown',
            'holder'        => 'div', 
            'admin_label'   => true,
            'value'         => array( 
                esc_html__( 'Show', 'noo-hermosa-core' )     => 'block',
                esc_html__( 'Hidden', 'noo-hermosa-core' )   => 'none',
            ),
        ),
        array(
            'type'          => 'gallery_category',
            'heading'       => esc_html__( 'Categories', 'noo-hermosa-core' ),
            'param_name'    => 'categories',
            'admin_label'   => true
        ),
        array(
            'type'        => 'dropdown',
            'heading'     => esc_html__( 'Show/hide \"All\" in filter', 'noo-hermosa' ),
            'param_name'  => 'show_all_filter',
            'description' => '',
            'value'       => array(
                esc_html__( 'Hide', 'noo-hermosa' ) => 'hide',
                esc_html__( 'Show', 'noo-hermosa' ) => 'show'
            ),
            'edit_field_class' => 'vc_col-sm-6 vc_column vc_column-with-padding',
        ),
        array(
            'param_name'  => 'limit',
            'heading'     => esc_html__( 'Limit', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       =>  10
        ),
        array(
            "param_name"  => "columns",
            "type"        => "dropdown",
            "heading"     => esc_html__( "Choose columns", 'noo-hermosa-core' ),
            "admin_label" => true,
            'holder'      => 'div',
            "value"       => array(
                esc_html__( 'Columns 4', 'noo-hermosa-core' ) => '3',
                esc_html__( 'Columns 3', 'noo-hermosa-core' )   => '4',
                esc_html__( 'Columns 2', 'noo-hermosa-core' )   => '6'
            )
        ),
         array(
            "param_name"  => "order",
            "type"        => "dropdown",
            "heading"     => esc_html__( "Order", 'noo-hermosa-core' ),
            "admin_label" => true,
            'holder'      => 'div',
            "std"         => 'desc',
            "value"       => array(
                 esc_html__( 'DESC', 'noo-hermosa-core' )   => 'desc',
                esc_html__( 'ASC', 'noo-hermosa-core' ) => 'asc'
            )
        ),
         array(
            "param_name"  => "order_by",
            "type"        => "dropdown",
            "heading"     => esc_html__( "Orderby", 'noo-hermosa-core' ),
            "admin_label" => true,
            'holder'      => 'div',
            "std"         => 'date',
            "value"       => array(
                esc_html__( 'Date', 'noo-hermosa-core' ) => 'date',
                esc_html__( 'Title', 'noo-hermosa-core' )   => 'title',
                esc_html__( 'Rand', 'noo-hermosa-core' )   => 'rand'
            )
        )
    )
));


/**
 * Create ShortCode: [noo_partner]
 *
 * @package     Noo Library
 * @author      Tu Nguyen <tunguyen@vietbrain.com>
 * @version     1.0
 */
vc_map(array(
    'name'          =>  esc_html__('Noo Partner','noo-hermosa-core'),
    'base'          =>  'noo_partner',
    'description'   =>  esc_html__('Display Partner','noo-hermosa-core'),
    'icon'          =>  'noo_icon_partner',
    'category'      =>   $category_name,
    'params'        =>  array(
        array(
            'param_name'  => 'limit_oneslider',
            'heading'     => esc_html__( 'Limited post of Slider',  'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'admin_label' => true,
            'holder'      => 'div',
            'value'       =>  array(
                '6'       =>  6,
                '5'       =>  5,
                '4'       =>  4
            )
        ),
        array(
            'param_name'  => 'images',
            'heading'     => esc_html__( 'Upload Images', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'attach_images',
            'holder'      => 'div',
        ),
        array(
            'param_name'  => 'custom_link',
            'heading'     => esc_html__( 'Custom links', 'noo-hermosa-core' ),
            'description' => esc_html__('Enter links for each Clients here. Divide links with linebreaks (Enter) .', 'noo-hermosa-core'),
            'type'        => 'exploded_textarea'
        ),
        array(
            'param_name'  => 'target',
            'heading'     => esc_html__( 'Custom link target', 'noo-hermosa-core' ),
            'description' => esc_html__('Select where to open custom links.', 'noo-hermosa-core'),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
                'Same window'   =>  'same',
                'New window'    =>  'new'
            )
        ),
        array(
            'param_name'  => 'autoplay',
            'heading'     => esc_html__( 'Auto Play', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       =>  array(
                esc_html__( 'Yes', 'noo-hermosa-core' )   =>  'true',
                esc_html__( 'No', 'noo-hermosa-core' )    =>  'false'
            )
        )

    )
));


/**
 * Create ShortCode: [noo_blog]
 *
 * @package     Noo Library
 * @author      Tu Nguyen <tunguyen@vietbrain.com>
 * @version     1.0
 */
add_filter( 'vc_autocomplete_noo_blog_include_callback',
    'vc_include_field_search', 10, 1 ); // Get suggestion(find). Must return an array
add_filter( 'vc_autocomplete_noo_blog_include_render',
    'vc_include_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)
vc_map(array(
    'base'        => 'noo_blog',
    'name'        => esc_html__( 'Noo Blog', 'noo-hermosa-core' ),
    'class'       => 'noo-icon-blog',
    'icon'        => 'noo-icon-blog',
    'category'    => $category_name,
    'description' => esc_html__( 'Display post with muti style', 'noo-hermosa-core' ),
    'params'      => array(
        array(
            'type'          => 'textfield',
            'heading'       => esc_html__( 'Title', 'noo-hermosa-core' ),
            'param_name'    => 'title',
            'admin_label'   => true,
            'holder'        => 'div',
        ),
        array(
            'type'          => 'textarea',
            'heading'       => esc_html__( 'Sub title', 'noo-hermosa-core' ),
            'param_name'    => 'sub_title',
            'admin_label'   => true
        ),
        array(
            'param_name'  => 'style',
            'heading'     => esc_html__( 'Style blog', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       =>  array(
                esc_html__( 'Style 1', 'noo-hermosa-core' )      =>  'style_1',
                esc_html__( 'Style 2', 'noo-hermosa-core' )      =>  'style_2'
            )
        ),
        array(
            'param_name'    =>  'type_query',
            'heading'       =>  esc_html__('Data Source', 'noo-hermosa-core'),
            'description'   =>  esc_html__('Select content type', 'noo-hermosa-core'),
            'type'          =>  'dropdown',
            'admin_label'   => true,
            'holder'        =>  '',
            'value'         =>  array(
                'Category'      =>  'cate',
                'Tags'          =>  'tag',
                'Posts'         =>  'post_id'
            )
        ),
        array(
            'param_name'    => 'categories',
            'heading'       => esc_html__( 'Categories', 'noo-hermosa-core' ),
            'description'   => esc_html__('Select categories.', 'noo-hermosa-core' ),
            'type'          => 'post_categories',
            'admin_label'   => true,
            'holder'        => 'div',
            'dependency'    => array(
                'element'   => 'type_query',
                'value'     => array( 'cate' )
            ),
        ),
        array(
            'param_name'    => 'tags',
            'heading'       => esc_html__( 'Tags', 'noo-hermosa-core' ),
            'description'   => esc_html__('Select Tags.', 'noo-hermosa-core' ),
            'type'          => 'post_tags',
            'admin_label'   => true,
            'holder'        => 'div',
            'dependency'    => array(
                'element'   => 'type_query',
                'value'     => array( 'tag' )
            ),
        ),
        array(
            'type'        => 'autocomplete',
            'heading'     => esc_html__( 'Include only', 'noo-hermosa-core' ),
            'param_name'  => 'include',
            'description' => esc_html__( 'Add posts, pages, etc. by title.', 'noo-hermosa-core' ),
            'admin_label'   => true,
            'holder'        => 'div',
            'settings' => array(
                'multiple' => true,
                'sortable' => true,
                'groups'   => true,
            ),
            'dependency'    => array(
                'element'   => 'type_query',
                'value'     => array( 'post_id' )
            ),
        ),
        array(
            'param_name'  => 'orderby',
            'heading'     => esc_html__( 'Order By', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
                esc_html__( 'Recent First', 'noo-hermosa-core' ) => 'latest',
                esc_html__( 'Older First', 'noo-hermosa-core' ) => 'oldest',
                esc_html__( 'Title Alphabet', 'noo-hermosa-core' ) => 'alphabet',
                esc_html__( 'Title Reversed Alphabet', 'noo-hermosa-core' ) => 'ralphabet' )
        ),
        array(
            'type'          =>  'dropdown',
            'holder'        =>  'div',
            'class'         =>  '',
            'heading'       =>  esc_html__('Config columns','noo-hermosa-core'),
            'param_name'    =>  'columns',
            'value'         =>  array(
                esc_html__('3 columns','noo-hermosa-core')     =>  '3',
                esc_html__('5 columns','noo-hermosa-core')     =>  '5',
                esc_html__('4 columns','noo-hermosa-core')     =>  '4',
                esc_html__('2 columns','noo-hermosa-core')     =>  '2',
            )
        ),
        array(
            'param_name'  => 'posts_per_page',
            'heading'     => esc_html__( 'Posts Per Page', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => 10
        ),
        array(
            'type'          =>  'dropdown',
            'holder'        =>  'div',
            'class'         =>  '',
            'heading'       =>  esc_html__('Box Shadow ?','noo-hermosa-core'),
            'param_name'    =>  'box_shadow',
            'value'         =>  array(
                esc_html__('Yes','noo-hermosa-core')     =>  'yes_shadow',
                esc_html__('No','noo-hermosa-core')     =>  'no_shadow'
            )
        ),
        array(
            'param_name'   => 'limit_excerpt',
            'heading'      => esc_html__( 'Excerpt Length', 'noo-hermosa-core' ),
            'description'  => '',
            'type'         => 'textfield',
            'holder'       => 'div',
            'value'        =>  15
        ),
        array(
            "param_name"  => "style_button",
            "type"        => "dropdown",
            "heading"     => esc_html__( "Choose Button", 'noo-hermosa-core' ),
            "admin_label" => true,
            'holder'      => 'div',
            "value"       => array(
                esc_html__( 'Hide Button', 'noo-hermosa-core' )      => 'hide_button',
                esc_html__( 'Infinitescroll', 'noo-hermosa-core' )   => 'infini',
                esc_html__( 'Custom link', 'noo-hermosa-core' )      => 'custom_button'
            )
        ),
        array(
            'param_name'    => 'custom_link',
            'heading'       => esc_html__( 'Link', 'noo-hermosa-core' ),
            'description'   => '',
            'type'          => 'vc_link',
            'admin_label'   => true,
            'holder'        => 'div',
            'value'         =>  '#',
            'dependency' => array( 'element' => 'style_button', 'value' => array( 'custom_button' ) ),
        )
    )
));

/**
 * Create ShortCode: [noo_mailchimp]
 *
 * @package     Noo Library
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
vc_map(array(
    'name'          =>  esc_html__( 'Noo Mailchimp', 'noo-hermosa-core' ),
    'base'          =>  'noo_mailchimp',
    'description'   =>  esc_html__( 'Displays your MailChimp for WordPress sign-up form', 'noo-hermosa-core' ),
    'icon'          =>  'noo_icon_mailchimp',
    'category'      =>   $category_name,
    'params'        =>  array(
        array(
            'type'          =>  'textfield',
            'holder'        =>  'div',
            'heading'       =>  esc_html__( 'Title', 'noo-hermosa-core' ),
            'param_name'    =>  'title',
            'value'         =>  ''
        ),
        array(
            'type'          =>  'textfield',
            'holder'        =>  'div',
            'heading'       =>  esc_html__( 'Sub title', 'noo-hermosa-core' ),
            'param_name'    =>  'sub_title',
            'value'         =>  ''
        ),

    )
));

/**
 * Create ShortCode: [noo_testimonial]
 *
 * @package     Noo Library
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
vc_map(array(
    'name'          =>  esc_html__( 'Noo Testimonial','noo-hermosa-core' ),
    'base'          =>  'noo_testimonial',
    'description'   =>  esc_html__( 'Display Testimonial', 'noo-hermosa-core' ),
    'icon'          =>  'noo_icon_testimonial',
    'category'      =>   $category_name,
    'params'        =>  array(

        array(
            'param_name'  => 'style',
            'heading'     => esc_html__( 'Choose Style', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'std'         => 'style-1',
            'value'       =>  array(
                esc_html__( 'Style One', 'noo-hermosa-core' )   =>  'style-1',
                esc_html__( 'Style Two', 'noo-hermosa-core' )   =>  'style-2',
                esc_html__( 'Style Three', 'noo-hermosa-core' ) =>  'style-3'
            )
        ),
        array(
            'type'          => 'textfield',
            'heading'       => esc_html__( 'Title', 'noo-hermosa-core' ),
            'param_name'    => 'title',
            'admin_label'   => true,
            'holder'        => 'div',
            'dependency'    => array(
                'element'   => 'style',
                'value'     => array( 'style-3' )
            ),
        ),
        array(
            'param_name'    =>  'image',
            'heading'       =>  esc_html__( 'Upload Image', 'noo-hermosa-core' ),
            'description'   =>  '',
            'type'          =>  'attach_image',
            'holder'        =>  'div',
            'dependency'    => array(
                'element'   => 'style',
                'value'     => array( 'style-1' )
            ),
        ),

        array(
            'param_name'  => 'posts_per_page',
            'heading'     => esc_html__( 'Posts Per Page', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => 10
        ),
        array(
            'param_name'  => 'autoplay',
            'heading'     => esc_html__( 'Auto Play', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'std'         => 'true',
            'value'       =>  array(
                esc_html__( 'Yes', 'noo-hermosa-core' )   =>  'true',
                esc_html__( 'No', 'noo-hermosa-core' )    =>  'false'
            )
        ),
        array(
            'param_name'  => 'pagination',
            'heading'     => esc_html__( 'Show pagination', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
                esc_html__( 'Show', 'noo-hermosa-core' ) => 'true',
                esc_html__( 'Hide', 'noo-hermosa-core' ) => 'false',
            ),
        ),
        array(
            'param_name' => 'slider_speed',
            'heading'    => esc_html__( 'Slide Speed (ms)', 'noo-hermosa-core' ),
            'type'       => 'ui_slider',
            'holder'     => 'div',
            'value'      => '800',
            'data_min'   => '300',
            'data_max'   => '3000',
            'data_step'  => '100',
         ),
        array(
            'param_name'  => 'navigation',
            'heading'     => esc_html__( 'Show Navigation', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
                esc_html__( 'Hide', 'noo-hermosa-core' ) => 'false',
                esc_html__( 'Show', 'noo-hermosa-core' ) => 'true',
            ),
        ),
    )
));

/**
 * Create ShortCode: [noo_pricetable]
 *
 * @package     Noo Library
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
vc_map(array(
    'name'          =>  esc_html__( 'Price Table', 'noo-hermosa-core' ),
    'base'          =>  'noo_pricetable',
    'description'   =>  esc_html__( 'Display Price Table', 'noo-hermosa-core' ),
    'icon'          =>  'noo_icon_pricetable',
    'category'      =>   $category_name,
    'params'        =>  array(
        array(
            'param_name'  => 'style',
            'heading'     => esc_html__( 'Choose Style', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       =>  array(
                esc_html__( 'Style One', 'noo-hermosa-core' )    =>  'style-1',
                esc_html__( 'Style Two', 'noo-hermosa-core' )    =>  'style-2'
            )
        ),
        array(
            'param_name'  => 'title',
            'heading'     => esc_html__( 'Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'holder'      => 'div',
        ),
        array(
            'param_name'  => 'sub_title',
            'heading'     => esc_html__( 'Sub Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'holder'      => 'div',
        ),
        array(
            'param_name'  => 'price',
            'heading'     => esc_html__( 'Price', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'holder'      => 'div',
        ),
        array(
            'param_name'  => 'button',
            'heading'     => esc_html__( 'URL (Link)', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'vc_link',
            'holder'      => 'div',
        ),
        array(
            'type'       => 'param_group',
            'value'      => '',
            'param_name' => 'price_item',
            'params'     => array(
                array(
                    'type'        => 'textfield',
                    'value'       => '',
                    'holder'      => 'div',
                    'admin_label' => true,
                    'heading'     =>  esc_html__( 'Title', 'noo-hermosa-core' ),
                    'param_name'  => 'title'
                )
            )
        )

    )
));


/**
 * Create ShortCode: [noo_service]
 *
 * @package     Noo Library
 * @author      KENT <tuanlv@vietbrain.com> / Upadate <manhnv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
    'name'          =>  esc_html__( 'Noo Single Service', 'noo-hermosa-core' ),
    'base'          =>  'noo_service',
    'icon'          =>  'noo_service',
    'category'      =>   $category_name,
    'params'        =>  array(

        array(
            'param_name'  => 'style',
            'heading'     => esc_html__( 'Choose Style', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       =>  array(
                esc_html__( 'Style One', 'noo-hermosa-core' )      =>  'style-1',
                esc_html__( 'Style Two', 'noo-hermosa-core' )      =>  'style-2',
                esc_html__( 'Style Three', 'noo-hermosa-core' )    =>  'style-3',

            )
        ),
        
        array(
            'type'          =>  'textfield',
            'holder'        =>  'div',
            'heading'       =>  esc_html__( 'Title', 'noo-hermosa-core' ),
            'param_name'    =>  'title',
            'value'         =>  ''
        ),
        array(
            'type'          =>  'textarea',
            'holder'        =>  'div',
            'heading'       =>  esc_html__( 'Description', 'noo-hermosa-core' ),
            'param_name'    =>  'description',
            'value'         =>  ''
        ),

        array(
            'param_name'  => 'align',
            'heading'     => esc_html__( 'Choose Align', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'std'         => 'left',
            'value'       =>  array(
                esc_html__( 'Align Left', 'noo-hermosa-core' )  =>  'left',
                esc_html__( 'Align Right', 'noo-hermosa-core' ) =>  'right'
            ),
            
        ),

        array(
            'type'          =>  'iconpicker',
            'holder'        =>  'div',
            'heading'       =>  esc_html__( 'Icon', 'noo-hermosa-core' ),
            'param_name'    =>  'icon',
            'dependency'    => array(
                'element'   => 'style',
                'value'     => array( 'style-1','style-2'),
            ),
        ),
        array(
            'param_name'    =>  'image',
            'heading'       =>  esc_html__( 'Upload Image Icon', 'noo-hermosa-core' ),
            'description'   =>  '',
            'type'          =>  'attach_image',
            'holder'        =>  'div',
            'dependency'    => array(
                'element'   => 'style',
                'value'     => array( 'style-3' )
            ),
        ),
        array(
            'type'        => 'colorpicker',
            'heading'     => __( 'Background Icon Color', 'noo-hermosa-core' ),
            'param_name'  => 'color',
            'value'       => '#5ccbaa',
            'description' => __( 'Choose text color', 'noo-hermosa-core' ),
            'dependency'    => array(
                'element'   => 'style',
                'value'     => array( 'style-1','style-2'),
            ),
        ),
    )
));

/**
 * Create ShortCode: [noo_multiple_service]
 *
 * @package     Noo Library
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
    'name'          =>  esc_html__( 'Noo Multiple Service', 'noo-hermosa-core' ),
    'base'          =>  'noo_multiple_service',
    'icon'          =>  'noo_multiple_service',
    'category'      =>   $category_name,
    'params'        =>  array(

        array(
            'type'          =>  'textfield',
            'holder'        =>  'div',
            'heading'       =>  esc_html__( 'Title', 'noo-hermosa-core' ),
            'param_name'    =>  'title',
            'value'         =>  ''
        ),
        array(
            'type'          =>  'textfield',
            'holder'        =>  'div',
            'heading'       =>  esc_html__( 'Sub Title', 'noo-hermosa-core' ),
            'param_name'    =>  'sub_title',
            'value'         =>  ''
        ),

        array(
            'param_name'    =>  'image',
            'heading'       =>  esc_html__( 'Image', 'noo-hermosa-core' ),
            'description'   =>  '',
            'type'          =>  'attach_image',
            'holder'        =>  'div'
        ),

        array(
            'type'       => 'param_group',
            'value'      => '',
            'param_name' => 'service_item',
            'params'     => array(
                array(
                    'type'          =>  'textfield',
                    'holder'        =>  'div',
                    'heading'       =>  esc_html__( 'Title', 'noo-hermosa-core' ),
                    'param_name'    =>  'title',
                    'value'         =>  ''
                ),
                array(
                    'type'          =>  'textarea',
                    'holder'        =>  'div',
                    'heading'       =>  esc_html__( 'Description', 'noo-hermosa-core' ),
                    'param_name'    =>  'description',
                    'value'         =>  ''
                ),
                array(
                    'type'          =>  'iconpicker',
                    'holder'        =>  'div',
                    'heading'       =>  esc_html__( 'Icon', 'noo-hermosa-core' ),
                    'param_name'    =>  'icon'
                ),

                array(
                    'type'        => 'colorpicker',
                    'heading'     => esc_html__( 'Background Icon Color', 'noo-hermosa-core' ),
                    'param_name'  => 'color',
                    'value'       => '#5ccbaa',
                    'description' => esc_html__( 'Choose text color', 'noo-hermosa-core' )
                ),
            )
        )

    )
));

/**
 * Create ShortCode: [noo_info]
 *
 * @package     Noo Library
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
    'name'          =>  esc_html__( 'Noo Info', 'noo-hermosa-core' ),
    'base'          =>  'noo_info',
    'icon'          =>  'noo_info',
    'category'      =>   $category_name,
    'params'        =>  array(
        
        array(
            'type'          =>  'iconpicker',
            'holder'        =>  'div',
            'heading'       =>  esc_html__( 'Icon', 'noo-hermosa-core' ),
            'param_name'    =>  'icon'
        ),

        array(
            'type'          =>  'textarea',
            'holder'        =>  'div',
            'heading'       =>  esc_html__( 'Description', 'noo-hermosa-core' ),
            'param_name'    =>  'description',
            'value'         =>  ''
        )

    )
));


/**
 * Create ShortCode: [noo_find_event]
 *
 * @package     Noo Library
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
    'name'          =>  esc_html__( 'Noo Find Event', 'noo-hermosa-core' ),
    'base'          =>  'noo_find_event',
    'icon'          =>  'noo_find_event',
    'category'      =>   $category_name,
    'params'        =>  array(
        
        array(
            'param_name'  => 'show_date',
            'heading'     => esc_html__( 'Show Date', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'std'         => 'yes',
            'value'       =>  array(
                esc_html__( 'Yes', 'noo-hermosa-core' ) =>  'yes',
                esc_html__( 'No', 'noo-hermosa-core' )  =>  'no'
            )
        ),

        array(
            'param_name'  => 'show_search',
            'heading'     => esc_html__( 'Show Search', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'std'         => 'yes',
            'value'       =>  array(
                esc_html__( 'Yes', 'noo-hermosa-core' ) =>  'yes',
                esc_html__( 'No', 'noo-hermosa-core' )  =>  'no'
            )
        ),

        array(
            'param_name'  => 'show_address',
            'heading'     => esc_html__( 'Show Address', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'std'         => 'yes',
            'value'       =>  array(
                esc_html__( 'Yes', 'noo-hermosa-core' ) =>  'yes',
                esc_html__( 'No', 'noo-hermosa-core' )  =>  'no'
            )
        ),

        array(
            'param_name'  => 'show_category',
            'heading'     => esc_html__( 'Show Category', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'std'         => 'yes',
            'value'       =>  array(
                esc_html__( 'Yes', 'noo-hermosa-core' ) =>  'yes',
                esc_html__( 'No', 'noo-hermosa-core' )  =>  'no'
            )
        ),

    )
));


/**
 * Create ShortCode: [noo_recent_new]
 *
 * @package     Noo Library
 * @author      Manhnv <manhnv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
    'name'          =>  esc_html__( 'Noo Recent New', 'noo-hermosa-core' ),
    'base'          =>  'noo_blog_slider',
    'description'   =>  esc_html__('Display product by slider','noo-hermosa-core'),
    'icon'          =>  'noo-icon-recent-new',
    'category'      =>   $category_name,
    'params'        =>  array(
        array(
            'param_name'  => 'style_title', 
            'heading'     => esc_html__( 'Style Title', 'noo-hermosa' ), 
            'type'        => 'dropdown', 
            'std'         => '',
            'admin_label' => true,
            'value'       => array( 
                esc_html__( 'Style 1', 'noo-hermosa' )      => 'style_1',
                esc_html__( 'Style 2', 'noo-hermosa' )      => 'style_2',
                esc_html__( 'Style 3', 'noo-hermosa' )      => 'style_3'
            )
        ),
        // array(
        //     'param_name'  => 'position_title', 
        //     'heading'     => esc_html__( 'Position Title', 'noo-hermosa' ), 
        //     'type'        => 'dropdown', 
        //     'std'         => '',
        //     'admin_label' => true,
        //     'value'       => array( 
        //         esc_html__( 'Title Left', 'noo-hermosa' )      => 'left',
        //         esc_html__( 'Title Center', 'noo-hermosa' )    => 'center',
        //         esc_html__( 'Title Right', 'noo-hermosa' )     => 'right'
        //     )
        // ),
        array(
            'type'          => 'textfield',
            'heading'       => esc_html__( 'Title', 'noo-hermosa-core' ),
            'param_name'    => 'title',
            'admin_label'   => true,
            'holder'        => 'div',
        ),
        array(
            'type'          => 'textarea',
            'heading'       => esc_html__( 'Sub title', 'noo-hermosa-core' ),
            'param_name'    => 'sub_title',
            'admin_label'   => true
        ),
        array(
            'param_name'  => 'style',
            'heading'     => esc_html__( 'Choose Style', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       =>  array(
                esc_html__( 'Style One', 'noo-hermosa-core' )      =>  'style-1',
                esc_html__( 'Style Two', 'noo-hermosa-core' )      =>  'style-2',
            )
        ),
        array(
            'param_name'    => 'columns',
            'heading'       => esc_html__('Columns','noo-hermosa-core'),
            'type'          => 'dropdown',
            'admin_label'   => true,
            'value'         =>  array(
                esc_html__('2 columns','noo-hermosa-core')        =>  '2',
                esc_html__('3 columns','noo-hermosa-core')        =>  '3',
                esc_html__('4 columns','noo-hermosa-core')        =>  '4',
            ),
            'holder'        => 'div',
        ),
        array(
            'param_name'  => 'posts_per_page',
            'heading'     => esc_html__( 'Posts Per Page', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => 10
        ),
        array(
                'param_name'    => 'excerpt_length',
                'heading'       => esc_html__('Excerpt Length','noo-hermosa-core'),
                'type'          => 'textfield',
                'std'           => 20, 
                'admin_label'   => true, 
                'horder'        => 'div',
        ),
        array(
            'param_name'    => 'autoplay',
            'heading'       => esc_html__('Auto Play','noo-hermosa-core'),
            'description'   => '',
            'type'          => 'dropdown',
            'holder'        => 'div', 
            'value'         => array(
                esc_html__('Yes','noo-hermosa-core')  => 'true',
                esc_html__('No','noo-hermosa-core')  => 'false',
            ),
        ),
        array(
            'param_name' => 'slider_speed',
            'heading'    => esc_html__( 'Slide Speed (ms)', 'noo-hermosa-core' ),
            'type'       => 'ui_slider',
            'holder'     => 'div',
            'value'      => '800',
            'data_min'   => '300',
            'data_max'   => '3000',
            'data_step'  => '100',
         ),
        array(
            'param_name'  => 'show_navigation',
            'heading'     => esc_html__( 'Show Navigation', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
                esc_html__( 'Hide', 'noo-hermosa-core' ) => 'false',
                esc_html__( 'Show', 'noo-hermosa-core' ) => 'true',
            ),
        ),
        array(
            'param_name'  => 'show_pagination',
            'heading'     => esc_html__( 'Show pagination', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
                esc_html__( 'Hide', 'noo-hermosa-core' ) => 'false',
                esc_html__( 'Show', 'noo-hermosa-core' ) => 'true',
            ),
        ),
        array(
            'param_name'    => 'categories',
            'heading'       => esc_html__( 'Categories', 'noo-hermosa-core' ),
            'description'   => esc_html__('Select categories.', 'noo-hermosa-core' ),
            'type'          => 'post_categories',
            'admin_label'   => true,
            'holder'        => 'div',
            'dependency'    => array(
                'element'   => 'type_query',
                'value'     => array( 'cate' )
            ),
        ),
    )
));

/**
 * Create ShortCode: [noo_instagram]
 *
 * @package     Noo Library
 * @author      Manhnv <manhnv@vietbrain.com>
 * @version     1.0
 */

vc_map( array(
    'name'          =>  esc_html__( 'Noo Instagram', 'noo-hermosa-core' ),
    'base'          =>  'noo_instagram',
    'description'   =>  esc_html__('Display instagram by slider','noo-hermosa-core'),
    'icon'          =>  'noo_icon_instagram',
    'category'      =>   $category_name,
    'params'        =>  array(
         array( 
            'param_name'    => 'username',
            'heading'       => esc_html__( 'Instagram Username', 'noo-hermosa-core' ),
            'description'   => '',
            'type'          => 'textfield',
            'admin_label'   => true,
            'holder'        => 'div'
            ),
        array( 
            'param_name'    => 'layout_style', 
            'heading'       => esc_html__( 'Layout Style', 'noo-hermosa-core' ), 
            'type'          => 'dropdown', 
            'admin_label'   => true, 
            'value'         => array( 
                esc_html__( 'Slider', 'noo-hermosa-core' ) => 'slider',
            ), 
            'holder'        => 'div',
            'std'           => 'grid'
        ),
        array( 
            'param_name'    => 'image_size', 
            'heading'       => esc_html__( 'Image Size', 'noo-hermosa-core' ), 
            'type'          => 'dropdown', 
            'admin_label'   => true, 
            'value'         => array( 
                esc_html__( 'Small', 'noo-hermosa-core' )      => 'small', 
                esc_html__( 'Thumbnail', 'noo-hermosa-core' )  => 'thumbnail', 
                esc_html__( 'Large', 'noo-hermosa-core' )      => 'large',
                esc_html__( 'Original', 'noo-hermosa-core' )   => 'original',
            ), 
            'holder'        => 'div',
            'std'           => 'large'
        ),
        array( 
            'param_name' => 'images_display', 
            'heading' => esc_html__( 'Total Images Display', 'noo-hermosa-core' ), 
            'type' => 'textfield', 
            'admin_label' => true,
            'holder' => 'div',
            'std'    => '10'
        ),    
        array( 
            'param_name'    => 'images_columns', 
            'heading'       => esc_html__( 'Number of Column Images', 'noo-hermosa-core' ), 
            'type'          => 'dropdown', 
            'admin_label'   => true, 
            'value'         => array( 
                esc_html__( '2', 'noo-hermosa-core' ) => '2', 
                esc_html__( '3', 'noo-hermosa-core' ) => '3', 
                esc_html__( '4', 'noo-hermosa-core' ) => '4',
                esc_html__( '5', 'noo-hermosa-core' ) => '5', 
                esc_html__( '6', 'noo-hermosa-core' ) => '6', 
                esc_html__( '7', 'noo-hermosa-core' ) => '7',
                esc_html__( '8', 'noo-hermosa-core' ) => '8',
            ), 
            'holder'        => 'div',
            'std'           => '8'
        ),
        array(
            'param_name'  => 'autoplay',
            'heading'     => esc_html__( 'Auto Play', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       =>  array(
                esc_html__( 'No', 'noo-hermosa-core' )    =>  'false',
                esc_html__( 'Yes', 'noo-hermosa-core' )   =>  'true'
            )
        ),

        // array(
        //     'param_name'  => 'show_navigation',
        //     'heading'     => esc_html__( 'Show Navigation', 'noo-hermosa-core' ),
        //     'description' => '',
        //     'type'        => 'dropdown',
        //     'holder'      => 'div',
        //     'value'       => array(
        //         esc_html__( 'Hide', 'noo-hermosa-core' ) => 'false',
        //         esc_html__( 'Show', 'noo-hermosa-core' ) => 'true',
        //     ),
        // ),
        // array(
        //     'param_name'  => 'show_pagination',
        //     'heading'     => esc_html__( 'Show pagination', 'noo-hermosa-core' ),
        //     'description' => '',
        //     'type'        => 'dropdown',
        //     'holder'      => 'div',
        //     'value'       => array(
        //         esc_html__( 'Hide', 'noo-hermosa-core' ) => 'false',
        //         esc_html__( 'Show', 'noo-hermosa-core' ) => 'true',
        //     ),
        // ),
    )
));

/**
 * Create ShortCode: [noo_service_info]
 *
 * @package     Noo Library
 * @author      Manhnv <manhnv@vietbrain.com>
 * @version     1.0
 */

vc_map( array(
    'name'          =>  esc_html__( 'Noo Service Info', 'noo-hermosa-core' ),
    'base'          =>  'noo_service_info',
    'description'   =>  esc_html__('Display Service Info','noo-hermosa-core'),
    'icon'          =>  'noo_icon_service_info',
    'category'      =>   $category_name,
    'params'        => array(
        array(
            'param_name' => 'icon',
            'type'       => 'iconpicker',
            'value'      => 'fa fa-home',
            'heading'    => esc_html__( 'Icon', 'noo-hermosa-core' ),
        ),
        array(
            'param_name'  => 'icon_size',
            'heading'     => esc_html__( 'Icon Size (px)', 'noo-hermosa-core' ),
            'description' => esc_html__( 'Leave it empty or 0 to use the base size of your theme.', 'noo-hermosa-core' ),
            'type'        => 'ui_slider',
            'holder'      => 'div',
            'value'       => '',
            'data_min'    => '0',
            'data_max'    => '60',
        ),
        array(
            'param_name'  => 'icon_color',
            'heading'     => esc_html__( 'Icon Color', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'colorpicker',
            'holder'      => 'div',
        ),
        array(
            'param_name'  => 'text_same_size',
            'heading'     => esc_html__( 'Text has Same Size as Icon', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
                esc_html__( 'Yes', 'noo-hermosa-core' ) => 'true',
                esc_html__( 'No', 'noo-hermosa-core' )  => 'false',
            ),
        ),
        array(
            'param_name'  => 'text_size',
            'heading'     => esc_html__( 'Text Size (px)', 'noo-hermosa-core' ),
            'description' => esc_html__( 'Leave it empty or 0 to use the base size of your theme.', 'noo-hermosa-core' ),
            'type'        => 'ui_slider',
            'holder'      => 'div',
            'value'       => '',
            'data_min'    => '0',
            'data_max'    => '60',
            'dependency'  => array( 'element' => 'text_same_size', 'value' => array( 'false' ) ),
        ),
        array(
            'param_name'  => 'text_same_color',
            'heading'     => esc_html__( 'Text has Same Color as Icon', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
                esc_html__( 'Yes', 'noo-hermosa-core' ) => 'true',
                esc_html__( 'No', 'noo-hermosa-core' )  => 'false',
            ),
        ),
        array(
            'param_name'  => 'text_color',
            'heading'     => esc_html__( 'Text Color', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'colorpicker',
            'holder'      => 'div',
            'dependency'  => array( 'element' => 'text_same_color', 'value' => array( 'false' ) ),
        ),
        array(
            'type'          => 'textfield',
            'holder'        => 'div',
            'heading'       => esc_html__( 'Title Offered', 'noo-hermosa-core' ),
            'param_name'    => 'title',
            'value'         => esc_html__( 'Title Offered', 'noo-hermosa-core'),
        ),
        array(
            'type'          => 'textarea_html',
            'heading'       => esc_html__( 'Text Content Service Info', 'noo-hermosa-core'),
            'param_name'    => 'content',
            'holder'        => 'div',
            'description'   => '',
        ),
    )
));

/**
 * Create ShortCode: [noo_banner_images]
 *
 * @package     Noo Library
 * @author      Manhnv <manhnv@vietbrain.com>
 * @version     1.0
 */

vc_map(array(
    'name'          =>  esc_html__('Noo Banner Images ','noo-hermosa-core'),
    'base'          =>  'noo_banner_image',
    'description'   =>  esc_html__('Display Banner Image','noo-hermosa-core'),
    'class'         => '',
    'icon'          => 'noo-banner-img-icon',
    'category'      =>   $category_name,
    'params'        =>  array(
        array(
            'type'          =>  'attach_image',
            'holder'        =>  'div',
            'class'         =>  '',
            'heading'       =>  esc_html__('Image Banner','noo-hermosa-core'),
            'param_name'    =>  'image',
            'value'         =>  ''
        ),
        array(
            'type'          => 'textfield',
            'heading'       => esc_html__( 'Title', 'noo-hermosa-core' ),
            'param_name'    => 'title',
            'holder'        => 'div',
            ),
        array(
            "type"       => "textfield",
            "heading"    => esc_html__("Add link for title", "noo-hermosa-core"),
            "param_name" => "link",
            "value"      => '#'
        ),
        array(
            'type'          => 'textarea',
            'heading'       => esc_html__( 'Description', 'noo-hermosa-core' ),
            'param_name'    => 'desc',
            'holder'        => 'div',
        ),
       
    )
));

/**
 * Create ShortCode: [noo_open_hours]
 *
 * @package     Noo Library
 * @author      Manhnv <manhnv@vietbrain.com>
 * @version     1.0
 */

vc_map(array(
    'name'          =>  esc_html__('Noo Open Hours ','noo-hermosa-core'),
    'base'          =>  'noo_open_hours',
    'description'   =>  esc_html__('Display Open Hours','noo-hermosa-core'),
    'class'         => '',
    'icon'          => 'noo-icon-hours',
    'category'      =>   $category_name,
    'params'        =>  array(
       
        array(
            'param_name'  => 'title',
            'heading'     => esc_html__( 'Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'admin_label' => true,
            'holder'      => 'div',
            'value'       => ''
        ),
        array(
            'param_name'  => 'position',
            'heading'     => esc_html__( 'Choose Position Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'std'         => 'left',
            'value'       =>  array(
                esc_html__( 'Title Left', 'noo-hermosa-core' )          =>  'left',
                esc_html__( 'Title center', 'noo-hermosa-core' )        =>  'center',
                esc_html__( 'Title Right', 'noo-hermosa-core' )         =>  'right',
            ),
        ),
        array(
            'param_name'    => 'desc',
            'heading'       => esc_html__( 'Description', 'noo-hermosa-core' ),
            'description'   => '',
            'type'          => 'textarea',
            'admin_label'   => true,
            'holder'        => 'div',
            'value'         => ''
        ),
        array(
            'type'       => 'param_group',
            'value'      => '',
            'param_name' => 'open_hour_item',
            'params'     => array(
                array(
                    'type'        => 'textfield',
                    'value'       => '',
                    'holder'      => 'div',
                    'admin_label' => true,
                    'heading'     =>  esc_html__( 'Item', 'noo-hermosa-core' ),
                    'param_name'  => 'title'
                )
            )
        ),
        array(
            'param_name'  => 'button',
            'heading'     => esc_html__( 'URL BUTTON (Link)', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'vc_link',
            'holder'      => 'div',
        ),
       
    )
));

/**
 * Create ShortCode: [noo_pricing_plans]
 *
 * @package     Noo Library
 * @author      Manhnv <manhnv@vietbrain.com>
 * @version     1.0
 */

vc_map(array(
    'name'          =>  esc_html__('Noo Pricing Plans ','noo-hermosa-core'),
    'base'          =>  'noo_pricing_plan',
    'description'   =>  esc_html__('Display Pricing Plans','noo-hermosa-core'),
    'class'         => '',
    'icon'          => 'noo-pricing-icon',
    'category'      =>   $category_name,
    'params'        =>  array(
       
        array(
            'param_name'  => 'title',
            'heading'     => esc_html__( 'Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'textfield',
            'admin_label' => true,
            'holder'      => 'div',
            'value'       => ''
        ),
        array(
            'param_name'  => 'position',
            'heading'     => esc_html__( 'Choose Position Title', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'dropdown',
            'holder'      => 'div',
            'std'         => 'left',
            'value'       =>  array(
                esc_html__( 'Title Left', 'noo-hermosa-core' )          =>  'left',
                esc_html__( 'Title center', 'noo-hermosa-core' )        =>  'center',
                esc_html__( 'Title Right', 'noo-hermosa-core' )         =>  'right',
            ),
        ),
        array(
            'param_name'    => 'desc',
            'heading'       => esc_html__( 'Description', 'noo-hermosa-core' ),
            'description'   => '',
            'type'          => 'textarea',
            'admin_label'   => true,
            'holder'        => 'div',
            'value'         => ''
        ),
        array(
            'type'       => 'param_group',
            'value'      => '',
            'param_name' => 'pricing_item',
            'params'     => array(
                array(
                    'type'        => 'textfield',
                    'value'       => '',
                    'holder'      => 'div',
                    'admin_label' => true,
                    'heading'     =>  esc_html__( 'Item Title', 'noo-hermosa-core' ),
                    'param_name'  => 'title'
                ), 
                array(
                    'type'        => 'textfield',
                    'value'       => '',
                    'holder'      => 'div',
                    'admin_label' => true,
                    'heading'     =>  esc_html__( 'Item Sub Title', 'noo-hermosa-core' ),
                    'param_name'  => 'sub_title'
                ),
                array(
                    'type'        => 'textfield',
                    'value'       => '',
                    'holder'      => 'div',
                    'admin_label' => true,
                    'heading'     =>  esc_html__( 'Item Price', 'noo-hermosa-core' ),
                    'param_name'  => 'price'
                )
            )
        ),
        array(
            'param_name'  => 'button',
            'heading'     => esc_html__( 'URL BUTTON (Link)', 'noo-hermosa-core' ),
            'description' => '',
            'type'        => 'vc_link',
            'holder'      => 'div',
        ),
       
    )
));


/**
 * Create ShortCode: [noo_portfolio]
 *
 * @package     Noo Library
 * @author      Manh Nguyen <manhnv@vietbrain.com>
 * @version     1.0
 */
vc_map(array(
    'name'      =>  esc_html__('Noo Portfolio ','noo-hermosa-core'),
    'base'      =>  'noo_portfolio',
    'description'   =>  esc_html__('Display Portfolio','noo-hermosa-core'),
    'icon'      =>  'noo_icon_portfolio',
    'category'  =>   $category_name,
    'params'    =>  array(
        array(
            'type'        => 'dropdown',
            'heading'     => esc_html__('Thumbnail type','noo-hermosa-core'),
            'param_name'  => 'portfolio_thumbnail',
            'admin_label' => true,
            'value'       => array(
                esc_html__( 'Squared', 'noo-hermosa-core' )                                      => 'squared', 
                esc_html__( 'Landscape', 'noo-hermosa-core' )                                    => 'landscape', 
                esc_html__( 'Portrait', 'noo-hermosa-core' )                                     => 'portrait', 
                esc_html__( 'Packery (Thumbnail size set in item setting)', 'noo-hermosa-core' ) => 'packery',
                esc_html__( 'Masonry', 'noo-hermosa-core' )                                      => 'masonry'
            ),
            'std'         => 'landscape'
        ),
        array(
            'type'        => 'dropdown',
            'heading'     => esc_html__( 'Portfolio Title', ' ' ),
            'param_name'  => 'portfolio_title',
            'admin_label' => true,
            'value'       => array(
                esc_html__( 'None', 'noo-hermosa-core' )           => '',
                esc_html__( 'Show in Top', 'noo-hermosa-core' )    => 'top',
                esc_html__( 'Show in Bottom', 'noo-hermosa-core' ) => 'bottom'
            ),
            'std'         => '',
        ),
        array(
            'type'        => 'dropdown',
            'heading'     => esc_html__( 'Overlay Style', 'noo-hermosa-core' ),
            'param_name'  => 'overlay_style',
            'admin_label' => true,
            'value'       => array(
                esc_html__( 'Icon', 'noo-hermosa-core' )                                    => 'icon',
                esc_html__( 'Icon and Title', 'noo-hermosa-core' )                          => 'icon-title',
                esc_html__( 'Icon, Title and Category', 'noo-hermosa-core' )                => 'icon-title-category',
                esc_html__( 'Title and Category', 'noo-hermosa-core' )                      => 'title-category',
                esc_html__( 'Title, Category and Link button', 'noo-hermosa-core' )         => 'title-category-link',
            ),
            'group'              => esc_html__( 'Overlay Settings', 'noo-hermosa-core' ),
        ),
        array(
            'type'        => 'dropdown',
            'heading'     => esc_html__( 'Overlay Effect', 'noo-hermosa-core' ),
            'param_name'  => 'overlay_effect',
            'admin_label' => true,
            'value'       => array(
                esc_html__( 'Effect 1', 'noo-hermosa-core' )            => 'effect_1',
                esc_html__( 'Effect 2', 'noo-hermosa-core' )            => 'effect_2',
                esc_html__( 'Effect 3', 'noo-hermosa-core' )            => 'effect_3',
                esc_html__( 'Effect 4', 'noo-hermosa-core' )            => 'effect_4',
                esc_html__( 'Effect 5', 'noo-hermosa-core' )            => 'effect_5',

            ),
            'group'              => esc_html__( 'Overlay Settings', 'noo-hermosa-core' ),
        ),
        array(
            'type'        => 'dropdown',
            'heading'     => esc_html__( 'Hover Dir Effect', 'noo-hermosa-core' ),
            'param_name'  => 'hover_dir',
            'admin_label' => true,
            'value'       => array(
                esc_html__( 'On', 'noo-hermosa-core' )  => 'on',
                esc_html__( 'Off', 'noo-hermosa-core' ) => 'off',
            ),
            'std'          => 'on',
            'group'        => esc_html__( 'Overlay Settings', 'noo-hermosa-core' ),
        ),
         array(
            'type'        => 'dropdown',
            'heading'     => esc_html__( 'Show Filter', 'noo-hermosa-core' ),
            'param_name'  => 'show_filter',
            'admin_label' => true,
            'value'       => array(
                esc_html__( 'None', 'noo-hermosa-core' )           => 'none',
                esc_html__( 'Show in left', 'noo-hermosa-core' )   => 'left',
                esc_html__( 'Show in center', 'noo-hermosa-core' ) => 'center',
                esc_html__( 'Show in right', 'noo-hermosa-core' )  => 'right'
            ),
            'std'         => '',
            'group'              => esc_html__( 'Filter Settings', 'noo-hermosa-core' ),
        ),
        array(
            'type'        => 'dropdown',
            'heading'     => esc_html__( 'Filter By(Filter at front-end)', 'noo-hermosa-core' ),
            'param_name'  => 'filter_by',
            'admin_label' => true,
            'value'       => array(
                esc_html__( 'Tag', 'noo-hermosa-core' )      => 'tag',
                esc_html__( 'Category', 'noo-hermosa-core' ) => 'category'
            ),
            'std'         => 'tag',
            'group'              => esc_html__( 'Filter Settings', 'noo-hermosa-core' ),
        ),
        array(
            'type'        => 'portfolio_tag',
            'heading'     => esc_html__( 'Portfolio Tags', 'noo-hermosa-core' ),
            'param_name'  => 'portfolio_tag',
            'admin_label' => true,
            'dependency'    => array(
                'element'   => 'filter_by',
                'value'     => array('tag')
            ),
            'group'              => esc_html__( 'Filter Settings', 'noo-hermosa-core' ),
        ),
        array(
            'type'       => 'dropdown',
            'heading'    => esc_html__( 'Columns', 'noo-hermosa-core' ),
            'param_name' => 'columns',
            'value'      => array(
                esc_html__( '2 columns', 'noo-hermosa-core' ) => '2',
                esc_html__( '3 columns', 'noo-hermosa-core' ) => '3',
                esc_html__( '4 columns', 'noo-hermosa-core' ) => '4',
                esc_html__( '5 columns', 'noo-hermosa-core' ) => '5',
                esc_html__( '6 columns', 'noo-hermosa-core' ) => '6',
            ),
            'std'              => '4',
        ),
        array(
            'type'          => 'portfolio_category',
            'heading'       => esc_html__( 'Portfolio Categories', 'noo-hermosa-core' ),
            'param_name'    => 'category',
            'admin_label'   => true
        ),
        array(
            'type'       => 'dropdown',
            'heading'    => esc_html__( 'Show Paging', 'noo-hermosa-core' ),
            'param_name' => 'show_pagging',
            'value' => array(
                esc_html__( 'None', 'noo-hermosa-core' )      => '', 
                esc_html__( 'Load more', 'noo-hermosa-core' ) => '1'
            ),
            'std'              => '',
        ),
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__( 'Number of item (or number of item per page if choose show paging)', 'noo-hermosa-core' ),
            'param_name' => 'item',
            'value'      => '4',
        ),
        array(
            'type'        => 'dropdown',
            'heading'     => esc_html__( 'Show/hide \"All\" in filter', 'noo-hermosa' ),
            'param_name'  => 'show_all_filter',
            'description' => '',
            'value'       => array(
                esc_html__( 'Hide', 'noo-hermosa' ) => 'hide',
                esc_html__( 'Show', 'noo-hermosa' ) => 'show'
            ),
            'edit_field_class' => 'vc_col-sm-6 vc_column vc_column-with-padding',
            'group'              => esc_html__( 'Filter Settings', 'noo-hermosa-core' ),
        ),
        array(
            "param_name"  => "order",
            "type"        => "dropdown",
            "heading"     => esc_html__( "Order", 'noo-hermosa-core' ),
            "admin_label" => true,
            'holder'      => 'div',
            "std"         => 'desc',
            "value"       => array(
                 esc_html__( 'DESC', 'noo-hermosa-core' )   => 'desc',
                esc_html__( 'ASC', 'noo-hermosa-core' ) => 'asc'
            ),
            'edit_field_class' => 'vc_col-sm-6 vc_column',
        ),
        array(
            'type'       => 'dropdown',
            'heading'    => esc_html__( 'Padding', 'noo-hermosa-core' ),
            'param_name' => 'padding',
            'value'      => array(
                esc_html__( 'No padding', 'noo-hermosa-core' ) => '',
                esc_html__( '5 px', 'noo-hermosa-core' )       => 'col-padding-5',
                esc_html__( '10 px', 'noo-hermosa-core' )      => 'col-padding-10',
                esc_html__( '15 px', 'noo-hermosa-core' )      => 'col-padding-15',
                esc_html__( '20 px', 'noo-hermosa-core' )      => 'col-padding-20',
            ),
            'std' => '',
            'edit_field_class' => 'vc_col-sm-6 vc_column',
        ),
    )
));