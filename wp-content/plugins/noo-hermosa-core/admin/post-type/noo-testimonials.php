<?php
/**
 * Create class Noo_Testimonial
 * Function support testimonial to Noo Hermosa
 *
 * @package     Noo_Hermosa_Core
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

/**
 * Create class Noo_Testimonial
 * 
 * @author KENT <this>
 * @since this
 *
 */

if ( !class_exists( 'Noo_Testimonial' ) ) :

    class Noo_Testimonial {

        /**
         * A reference to an instance of this class.
         */
        private static $instance;
        
        /**
         * The array of templates that this plugin tracks.
         */
        //protected ;

        /**
         * Returns an instance of this class. 
         */
        public static function get_instance() {

            if( null == self::$instance ) {
                    self::$instance = new Noo_Testimonial();
            } 
            return self::$instance;

        } 

        /**
         * Initializes the plugin by setting filters and administration functions.
         */
        private function __construct() {
            
            /**
             * Load action/filter
             */
                add_action( 'init', array( &$this, 'register_post_type' ) );
                add_action( 'add_meta_boxes', array( &$this, 'register_metabox' ) );

        }

        /**
         * Create post type: noo_testimonial
         *
         * @package     Noo_Hermosa_Core
         * @author      KENT <tuanlv@vietbrain.com>
         * @version     1.0
         */
        public function register_post_type() {

            // Text for NOO testimonial.
            $testimonial_labels = array(
                'name'                  => esc_html__( 'Testimonial', 'noo-hermosa-core' ) ,
                'singular_name'         => esc_html__( 'Testimonial', 'noo-hermosa-core' ) ,
                'menu_name'             => esc_html__( 'Testimonial', 'noo-hermosa-core' ) ,
                'add_new'               => esc_html__( 'Add New', 'noo-hermosa-core' ) ,
                'add_new_item'          => esc_html__( 'Add New testimonial Item', 'noo-hermosa-core' ) ,
                'edit_item'             => esc_html__( 'Edit testimonial Item', 'noo-hermosa-core' ) ,
                'new_item'              => esc_html__( 'Add New testimonial Item', 'noo-hermosa-core' ) ,
                'view_item'             => esc_html__( 'View testimonial', 'noo-hermosa-core' ) ,
                'search_items'          => esc_html__( 'Search testimonial', 'noo-hermosa-core' ) ,
                'not_found'             => esc_html__( 'No testimonial items found', 'noo-hermosa-core' ) ,
                'not_found_in_trash'    => esc_html__( 'No testimonial items found in trash', 'noo-hermosa-core' ) ,
                'parent_item_colon'     => ''
            );

            $admin_icon = '';
            if ( floatval( get_bloginfo( 'version' ) ) >= 3.8 ) {
                $admin_icon = 'dashicons-testimonial';
            }

            $testimonial_page = get_theme_mod( 'noo_testimonial_page', '');
            $testimonial_slug = !empty($testimonial_page) ? get_post( $testimonial_page )->post_name : 'noo-testimonial';

            // Options
            $testimonial_args = array(
                'labels'            => $testimonial_labels,
                'public'            => false,
                'show_ui'           => true,
                'show_in_menu'      => true,
                'menu_position'     => 5,
                'menu_icon'         => $admin_icon,
                'capability_type'   => 'post',
                'hierarchical'      => false,
                'supports' => array(
                    'title',
                    'editor',
                    'revisions'
                ) ,
                'has_archive'       => true,
                'rewrite'           => array(
                    'slug'          => $testimonial_slug,
                    'with_front'    => false
                )
            );

            register_post_type( 'testimonial', $testimonial_args);

            // Register a taxonomy for Project Categories.
            $category_labels = array(
                'name'                          => esc_html__( 'Testimonial Categories', 'noo-hermosa-core' ) ,
                'singular_name'                 => esc_html__( 'Testimonial Category', 'noo-hermosa-core' ) ,
                'menu_name'                     => esc_html__( 'Testimonial Categories', 'noo-hermosa-core' ) ,
                'all_items'                     => esc_html__( 'All Testimonial Categories', 'noo-hermosa-core' ) ,
                'edit_item'                     => esc_html__( 'Edit Testimonial Category', 'noo-hermosa-core' ) ,
                'view_item'                     => esc_html__( 'View Testimonial Category', 'noo-hermosa-core' ) ,
                'update_item'                   => esc_html__( 'Update Testimonial Category', 'noo-hermosa-core' ) ,
                'add_new_item'                  => esc_html__( 'Add New Testimonial Category', 'noo-hermosa-core' ) ,
                'new_item_name'                 => esc_html__( 'New Testimonial Category Name', 'noo-hermosa-core' ) ,
                'parent_item'                   => esc_html__( 'Parent Testimonial Category', 'noo-hermosa-core' ) ,
                'parent_item_colon'             => esc_html__( 'Parent Testimonial Category:', 'noo-hermosa-core' ) ,
                'search_items'                  => esc_html__( 'Search Testimonial Categories', 'noo-hermosa-core' ) ,
                'popular_items'                 => esc_html__( 'Popular Testimonial Categories', 'noo-hermosa-core' ) ,
                'separate_items_with_commas'    => esc_html__( 'Separate Testimonial Categories with commas', 'noo-hermosa-core' ) ,
                'add_or_remove_items'           => esc_html__( 'Add or remove Testimonial Categories', 'noo-hermosa-core' ) ,
                'choose_from_most_used'         => esc_html__( 'Choose from the most used Testimonial Categories', 'noo-hermosa-core' ) ,
                'not_found'                     => esc_html__( 'No Testimonial Categories found', 'noo-hermosa-core' ) ,
            );

            $category_args = array(
                'labels'            => $category_labels,
                'public'            => false,
                'show_ui'           => true,
                'show_in_nav_menus' => false,
                'show_tagcloud'     => true,
                'show_admin_column' => true,
                'hierarchical'      => true,
                'query_var'         => true,
                'rewrite'           => array(
                    'slug'          => 'testimonial_category',
                    'with_front'    => false
                ) ,
            );

            register_taxonomy( 'testimonial_category', array(
                'testimonial'
            ) , $category_args);

        } 

        /**
         * Create metabox to testimonial page
         *
         * @package     Noo_Hermosa_Core
         * @author      KENT <tuanlv@vietbrain.com>
         * @version     1.0
         */
        public function register_metabox() {
            // Declare helper object
            $prefix = '_noo_wp_testimonial';
            $helper = new NOO_Meta_Boxes_Helper( $prefix, array(
                'page' => 'testimonial'
            ));
            // Post type: Gallery
            $meta_box = array(
                'id' => "{$prefix}_meta_box_testimonial",
                'title' => esc_html__('Testimonial options', 'noo-hermosa'),
                'fields' => array(
                    array(
                        'id' => "{$prefix}_image",
                         'label' => esc_html__( 'Your Image', 'noo-hermosa' ),
                        'type' => 'image',
                    ),
                    array(
                        'id' => "{$prefix}_name",
                         'label' => esc_html__( 'Your Name', 'noo-hermosa' ),
                        'type' => 'text',
                    ),
                    array(
                        'id' => "{$prefix}_position",
                         'label' => esc_html__( 'Your Position', 'noo-hermosa' ),
                        'type' => 'text',
                    ),
                )
            );

            $helper->add_meta_box($meta_box);
        }

    }

    add_action( 'after_setup_theme', array( 'Noo_Testimonial', 'get_instance' ) );

endif;

/**
 * End class Noo_Testimonial
 */