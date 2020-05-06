<?php
/**
 * Create class Noo_Galley
 * Function support gallery to Noo Hermosa
 *
 * @package     Noo_Hermosa_Core
 * @author      Tu Nguyen <tunguyen@vietbrain.com>
 * @version     1.0
 */

if ( !class_exists( 'Noo_Gallery' ) ) :

    class Noo_Gallery{

        /**
         * A reference to an instance of this class.
         */
        private static $instance;

        /**
         * The array of templates that this plugin using.
         */
        protected $noo_gallery;

        /**
         * Returns an instance of this class.
         */
        public static function get_instance() {

            if( null == self::$instance ) {
                self::$instance = new Noo_Gallery();
            }
            return self::$instance;

        }

        /**
         * Initializes the plugin by setting filters and administration functions.
         */
        private function __construct() {

            /**
             * VAR
             */
            $this->noo_gallery = array(
                'slug'                  => 'noo_gallery',
                'slug_cat'              => 'gallery_category',
                'rewrite_slug'          => 'noo-gallery',
                'rewrite_slug_cat'      => 'gallery-category',
                'icon'                  => 'dashicons-format-gallery',
                'prefix'                => '_noo_gallery'
            );

            /**
             * Load action/filter
             */
            add_action( 'init', array( $this, 'register_post_type' ) );



            if ( is_admin() ) :

                add_action( 'add_meta_boxes', array( $this, 'register_metabox' ) );

            endif;

        }
        

        /**
         * Register post type: noo_gallery
         * Register taxonomy: gallery_category
         */
        public function register_post_type() {

            /**
             * Creating post type: noo_gallery
             * @var array
             */
            $team_labels = array(
                'name'               => esc_html__( 'Gallery', 'noo-hermosa-core' ),
                'singular_name'      => esc_html__( 'Gallery', 'noo-hermosa-core' ),
                'menu_name'          => esc_html__( 'Gallery', 'noo-hermosa-core' ),
                'add_new'            => esc_html__( 'Add New Gallery', 'noo-hermosa-core' ),
                'add_new_item'       => esc_html__( 'Add New Gallery Item', 'noo-hermosa-core' ),
                'edit_item'          => esc_html__( 'Edit Gallery Item', 'noo-hermosa-core' ),
                'new_item'           => esc_html__( 'Add New Gallery Item', 'noo-hermosa-core' ),
                'view_item'          => esc_html__( 'View Gallery', 'noo-hermosa-core' ),
                'search_items'       => esc_html__( 'Search Gallery', 'noo-hermosa-core' ),
                'not_found'          => esc_html__( 'No Gallery items found', 'noo-hermosa-core' ),
                'not_found_in_trash' => esc_html__( 'No Gallery items found in trash', 'noo-hermosa-core' ),
                'parent_item_colon'  => ''
            );

            // Options
            $team_args = array(
                'labels'             => $team_labels,
                'public'             => false,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'menu_position'      => 5,
                'menu_icon'          => $this->noo_gallery['icon'],
                'capability_type'    => 'post',
                'hierarchical'       => false,
                'supports'           => array(
                    'title',
                    'author',
                    'thumbnail',

                ),
                'has_archive'        => true,
                'rewrite'            => array(
                    'slug'       => $this->noo_gallery['rewrite_slug'],
                    'with_front' => true
                )
            );

            register_post_type( $this->noo_gallery['slug'], $team_args );

            /**
             * Creating taxomony: Gallery_category
             * @var array
             */
            $category_labels = array(
                'name'                       => esc_html__( 'Categories', 'noo-hermosa-core' ),
                'singular_name'              => esc_html__( 'Category', 'noo-hermosa-core' ),
                'menu_name'                  => esc_html__( 'Gallery Categories', 'noo-hermosa-core' ),
                'all_items'                  => esc_html__( 'All Categories', 'noo-hermosa-core' ),
                'edit_item'                  => esc_html__( 'Edit Category', 'noo-hermosa-core' ),
                'view_item'                  => esc_html__( 'View Category', 'noo-hermosa-core' ),
                'update_item'                => esc_html__( 'Update Category', 'noo-hermosa-core' ),
                'add_new_item'               => esc_html__( 'Add New Category', 'noo-hermosa-core' ),
                'new_item_name'              => esc_html__( 'New Category Name', 'noo-hermosa-core' ),
                'parent_item'                => esc_html__( 'Parent Category', 'noo-hermosa-core' ),
                'parent_item_colon'          => esc_html__( 'Parent Category:', 'noo-hermosa-core' ),
                'search_items'               => esc_html__( 'Search Categories', 'noo-hermosa-core' ),
                'popular_items'              => esc_html__( 'Popular Categories', 'noo-hermosa-core' ),
                'separate_items_with_commas' => esc_html__( 'Separate Categories with commas', 'noo-hermosa-core' ),
                'add_or_remove_items'        => esc_html__( 'Add or remove Categories', 'noo-hermosa-core' ),
                'choose_from_most_used'      => esc_html__( 'Choose from the most used Categories', 'noo-hermosa-core' ),
                'not_found'                  => esc_html__( 'Categories found', 'noo-hermosa-core' ),
            );

            $category_args = array(
                'labels'            => $category_labels,
                'public'            => true,
                'show_ui'           => true,
                'show_in_nav_menus' => false,
                'show_tagcloud'     => false,
                'show_admin_column' => true,
                'hierarchical'      => true,
                'query_var'         => true,
                'rewrite'           => array(
                    'slug'       =>  $this->noo_gallery['rewrite_slug_cat'],
                    'with_front' => true
                ) ,
            );

            register_taxonomy( $this->noo_gallery['slug_cat'], array( $this->noo_gallery['slug'] ), $category_args );
            
        }

        /**
         * Register metabox to post type noo_gallery
         *
         */
        public function register_metabox() {

            /**
             * VAR
             * @var string
             */
            $helper = new NOO_Meta_Boxes_Helper( $this->noo_gallery['prefix'], array(
                'page' => $this->noo_gallery['slug']
            ));

            /**
             * Creating box: Gallery Settings
             * @var array
             */
            $meta_box = array(
                'id' => $this->noo_gallery['prefix'] . '_gallery_settings',
                'title' => esc_html__( 'Gallery Settings', 'noo-hermosa-core' ),
                'fields' => array(
                    array(
                        'id' => $this->noo_gallery['prefix'] .'_feafured',
                        'label' => esc_html__( 'Feafured', 'noo-hermosa-core' ),
                        'type' => 'checkbox'
                    ),
                )
            );
            $helper->add_meta_box($meta_box);

        }

    }

    add_action( 'plugins_loaded', array( 'Noo_Gallery', 'get_instance' ) );

endif;

/**
 * End class noo_gallery
 */