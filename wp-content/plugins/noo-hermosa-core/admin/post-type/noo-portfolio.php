<?php
/**
 * Create class Noo_Portfolio
 * Function support portfolio to Noo Hermosa
 *
 * @package     Noo_Hermosa_Core
 * @author      Manhnv <manhnv@vietbrain.com>
 * @version     1.0
 */

if ( !class_exists( 'Noo_Portfolio' ) ) :

    class Noo_Portfolio{

        /**
         * A reference to an instance of this class.
         */
        private static $instance;

        /**
         * The array of templates that this plugin using.
         */
        protected $noo_portfolio;

        protected $prefix;

        /**
         * Returns an instance of this class.
         */
        public static function get_instance() {

            if( null == self::$instance ) {
                self::$instance = new Noo_Portfolio();
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
            $this->noo_portfolio = array(
                'slug'                  => 'noo_portfolio',
                'slug_cat'              => 'portfolio_category',
                'rewrite_slug'          => 'noo-portfolio',
                'rewrite_slug_cat'      => 'portfolio-category',
                'portfolio_slug_tag'	=> 'portfolio_tag',
                'icon'                  => 'dashicons-admin-post',
                'prefix'                => '_noo_portfolio'
            );

            /**
             * Load action/filter
             */
            add_action( 'init', array( $this, 'register_post_type' ) );



            if ( is_admin() ) :

                add_action( 'add_meta_boxes', array( $this, 'register_metabox' ) );

            endif;

            if ( is_admin() ) :
               
                add_filter( 'manage_noo_portfolio_posts_columns', array( $this, 'add_columns' ) );
                add_action( 'manage_noo_portfolio_posts_custom_column', array( $this, 'set_columns_value'), 10, 2);

            endif;

        }
        

        /**
         * Register taxonomy: portfolio_category
         */
        public function register_post_type() {

            /**
             * Creating post type: noo_portfolio
             * @var array
             */
            $team_labels = array(
                'menu_name'          => esc_html__( 'Portfolio', 'noo-hermosa-core' ),
                'singular_name'      => esc_html__( 'Single Portfolio', 'noo-hermosa-core' ),
                'name'               => esc_html__( 'Portfolio', 'noo-hermosa-core' ),
                'add_new'            => esc_html__( 'Add New', 'noo-hermosa-core' ) ,
                'add_new_item'       => esc_html__( 'Add New Portfolio', 'noo-hermosa-core' ) ,
                'edit_item'          => esc_html__( 'Edit Portfolio', 'noo-hermosa-core' ) ,
                'new_item'           => esc_html__( 'Add New Portfolio', 'noo-hermosa-core' ) ,
                'view_item'          => esc_html__( 'View Portfolio', 'noo-hermosa-core' ) ,
                'search_items'       => esc_html__( 'Search Portfolio', 'noo-hermosa-core' ) ,
                'not_found'          => esc_html__( 'No Portfolio items found', 'noo-hermosa-core' ) ,
                'not_found_in_trash' => esc_html__( 'No Portfolio items found in trash', 'noo-hermosa-core' ) ,
                'parent_item_colon'  => ''
            );


            $team_args = array(
                'labels'             => $team_labels,
                'description'           => esc_html__( 'Display portfolio', 'noo-hermosa-core' ),
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'show_in_admin_bar'  => true,
                'show_in_nav_menus'  => true,
                'can_export'         => true,
                'menu_position'      => 5,
                'menu_icon'          => $this->noo_portfolio['icon'],
                'capability_type'    => 'post',
                'hierarchical'       => false,
                'supports'           => array(
                    'title',
                    'editor',
                    'author',
                    'thumbnail',
                    'excerpt',
                ),
                'has_archive'        => true,
                'exclude_from_search'   => false,
                'rewrite'            => array(
                    'slug'       => $this->noo_portfolio['rewrite_slug'],
                    'with_front' => false
                ),
               
            );
            register_post_type( $this->noo_portfolio['slug'], $team_args );

            /**
             * Creating taxomony: Portfolio_category
             * @var array
             */
            $category_labels = array(
                'name'                       => esc_html__( 'Categories', 'noo-hermosa-core' ),
                'singular_name'              => esc_html__( 'Category', 'noo-hermosa-core' ),
                'menu_name'                  => esc_html__( 'Portfolio Categories', 'noo-hermosa-core' ),
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
                    'slug'       =>  $this->noo_portfolio['rewrite_slug_cat'],
                    'with_front' => true
                ) ,
            );

            register_taxonomy( $this->noo_portfolio['slug_cat'], array( $this->noo_portfolio['slug'] ), $category_args );


             // Register a taxonomy for Portfolio Tags.
            $tag_labels = array(
                'name'              => esc_html__( 'Portfolio Tags','noo-hermosa-core' ),
                'singular_name'     => esc_html__( 'Tag', 'noo-hermosa-core' ),
                'search_items'      => esc_html__( 'Search Types', 'noo-hermosa-core' ),
                'all_items'         => esc_html__( 'All Tags', 'noo-hermosa-core' ),
                'parent_item'       => esc_html__( 'Parent Tag', 'noo-hermosa-core' ),
                'parent_item_colon' => esc_html__( 'Parent Tag:', 'noo-hermosa-core' ),
                'edit_item'         => esc_html__( 'Edit Tags', 'noo-hermosa-core' ),
                'update_item'       => esc_html__( 'Update Tag', 'noo-hermosa-core' ),
                'add_new_item'      => esc_html__( 'Add New Portfolio Tag', 'noo-hermosa-core' ),
                'new_item_name'     => esc_html__( 'New Tag Name', 'noo-hermosa-core' ),
            );

            $tag_args = array(
                'labels'       => $tag_labels,
                'public'       => true,
                'hierarchical' => true,
                'show_ui'      => true,
                'query_var'    => true,
                'rewrite'      => array( 
                    'slug'       =>  $this->noo_portfolio['portfolio_slug_tag'],
                    'with_front' => false
                ),
            );

            // Custom taxonomy for Portfolio Tags
            register_taxonomy( $this->noo_portfolio['portfolio_slug_tag'], array( $this->noo_portfolio['slug'] ), $tag_args );
            
        }

        // Add columns to Team Members
        function add_columns($columns) {
            unset(
                $columns['post-format'],
                $columns['title'],
                $columns['date']
            );
            $cols = array_merge(array('cb' => ('')), $columns);
            $cols = array_merge($cols, array('thumb' => '<span class="wc-image tips">Image</span>'));
            $cols = array_merge($cols, array('title' => esc_html__( 'Title', 'noo-hermosa-core' )));
           // $cols = array_merge($cols, array('category' => esc_html__( 'Category', 'noo-hermosa-core' )));
            $cols = array_merge($cols, array('tag' => esc_html__( 'Tag', 'noo-hermosa-core' )));
            //$cols = array_merge($cols, array('media_type' => esc_html__( 'Media Type', 'noo-hermosa-core' )));
            $cols = array_merge($cols, array('date' => esc_html__( 'Date', 'noo-hermosa-core' )));

            return $cols;
        }

        // Set values for columns
        function set_columns_value($column, $post_id) {
            $prefix = $this->prefix;

            switch ($column) {
                case 'id': {
                    echo wp_kses_post($post_id);
                    break;
                }
                case 'media_type': {
                    //$media_type = get_post_meta($post_id, "{$prefix}_media_type", true);
                    $media_type = get_post_meta($post_id, "noo_media_type", true);
                    switch( $media_type ) {
                        case 'image':
                            echo '<label for="post-format-image" class="post-format-icon post-format-image"></label>';
                            break; 
                        case 'video':
                            echo '<label for="post-format-video" class="post-format-icon post-format-video"></label>';
                            break;
                        case 'link':
                            echo '<label for="post-format-link" class="post-format-icon post-format-link"></label>';
                            break;
                        case 'gallery':
                            echo '<label for="post-format-gallery" class="post-format-icon post-format-gallery"></label>';
                            break;
                        default:

                            break;
                    }
                    break;
                }
                case 'thumb': {
                    echo get_the_post_thumbnail($post_id, 'thumbnail');
                    break;
                }
                case 'category': {
                    $terms = get_the_terms( $post_id, 'portfolio_category' );
                    if ($terms != false) {
                        foreach($terms as $term) {
                            echo $term->name . ', ';
                        }
                        break;
                    }
                }
                case 'tag': {
                    $terms = get_the_terms( $post_id, 'portfolio_tag' );
                    if ($terms != false) {
                        foreach($terms as $term) {
                            echo $term->name . ', ';
                        }
                        break;
                    }
                }

            }
        }

         


        /**
         * Register metabox to post type noo_portfolio
         *
         */
       public function register_metabox() {

            /**
             * VAR
             * @var string
             */
            $prefix = 'noo';
            $helper = new NOO_Meta_Boxes_Helper( $this->noo_portfolio['prefix'], array(
                'page' => $this->noo_portfolio['slug']
            ));

            /**
             * Creating box: Portfolio Settings
             * @var array
             */
            
            $meta_box = array(
                'id' => "{$prefix}_portfolio_settings",
                'title' => esc_html__( 'Portfolio Settings', 'noo-hermosa-core' ),
                'fields' => array(
                    array(
                        'id' => "{$prefix}__feafured'",
                        'label' => esc_html__( 'Feafured', 'noo-hermosa-core' ),
                        'type' => 'checkbox'
                    ),
                )
            );
            $helper->add_meta_box($meta_box);

            //POST FORMAT: LINK
            $meta_box = array(
                'id' => "{$prefix}_post_format_link",
                'title'      => esc_html__( 'Post Format: Link', 'noo-hermosa-core' ),
                'post_types' => array('noo_portfolio'),
                'fields'     => array(
                    array(
                        'name' => esc_html__('Url','noo-hermosa-core'),
                        'label' => esc_html__( 'link', 'noo-hermosa-core' ),
                        'id' => "{$prefix}_data_format_link_url'",
                        'type' => 'text',
                    ),
                ),
            );
            $helper->add_meta_box($meta_box);

            // POST FORMAT: GALLERY
            $meta_box = array(
                'id' => "{$prefix}_post_format_gallery",
                'title' => esc_html__( 'Post Format : Gallery', 'noo-hermosa-core'),
                'post_types' => array('noo_portfolio'),
                'fields' => array(
                    array(
                        'id'   => "{$prefix}_data_format_gallery",
                        'label' => esc_html__( 'Your Gallery', 'noo-hermosa-core' ),
                        'type' => 'gallery',
                    ),
                    array(
                        'type' => 'divider',
                    ),
                    // array(
                    //     'id' => "{$prefix}_gallery_preview",
                    //     'label' => esc_html__( 'Preview Content', 'noo-hermosa-core'),
                    //     'type' => 'radio',
                    //     'std' => 'featured',
                    //     'options' => array(
                    //         array(
                    //             'label' => esc_html__( 'Featured Image', 'noo-hermosa-core'),
                    //             'value' => 'featured',
                    //         ),
                    //         array(
                    //             'label' => esc_html__( 'First Image on Gallery', 'noo-hermosa-core'),
                    //             'value' => 'first_image',
                    //         ),
                    //         array(
                    //             'label' => esc_html__( 'Image Slideshow', 'noo-hermosa-core'),
                    //             'value' => 'slideshow',
                    //         ),
                    //     )
                    // )
                )
            );

            $helper->add_meta_box($meta_box);

            // POST FORMAT: VIDEO
            $meta_box  = array(
                'title'      => esc_html__( 'Post Format: Video', 'noo-hermosa-core' ),
                'id'         => "{$prefix}_post_format_video",
                'post_types' => array('noo_portfolio'),
                'fields'     => array(
                    array(
                        'label' => esc_html__( 'Video URL or Embeded Code', 'noo-hermosa-core' ),
                        'id'   => "{$prefix}_data_format_video",
                        'type' => 'text',
                    ),
                ),
            );
            $helper->add_meta_box($meta_box);

            $meta_box = array(
                'id'          => "{$prefix}_meta_box_media_type",
                'title'       => esc_html__( 'Media Type', 'noo-hermosa-core' ),
                'context'      => 'side',
                'priority'     => 'high',
                'description' => esc_html__( 'Choose the media type for this Portfolio Item.', 'noo-hermosa-core' ),
                'fields'      => array(
                    array(
                        'id'    => "{$prefix}_media_type",
                        'type'  => 'radio',
                        'std'   => 'image',
                        'options' => array(
                            array(
                            'label' => esc_html__( 'Image', 'noo-hermosa-core'),
                            'value' => 'image',
                            ),
                            array(
                                'label' => esc_html__( 'Gallery', 'noo-hermosa-core'),
                                'value' => 'gallery',
                            ),
                            array(
                                'label' => esc_html__( 'Video', 'noo-hermosa-core'),
                                'value' => 'video',
                            )
                        ),
                    ),
                    array(
                        'label' => esc_html__( 'Thumbnail size', 'noo-hermosa-core'),
                        'name'    => esc_html__( 'Thumbnail size', 'noo-hermosa-core' ),
                        'id'      => "{$prefix}_thumbnail_size",
                        'title'       => esc_html__( 'Thumbnail size', 'noo-hermosa-core' ),
                        'type'    => 'select',
                        'class'   => 'noo-portfolio-thumbnail-size',
                        'options' => array(
                            array('value'=>'','label'=>'Default'),
                            array('value'=>'small_squared','label'=>'Small Squared'),
                            array('value'=>'big_squared','label'=>'Big Squared'),
                            array('value'=>'landscape','label'=>'Landscape'),
                            array('value'=>'portrait','label'=>'Portrait'),
                        ),
                        'std'      => '',
                    ),
                    array(
                        'label' => esc_html__( 'View Detail Style', 'noo-hermosa-core'),
                        'name'    => esc_html__( 'View Detail Style', 'noo-hermosa-core' ),
                        'id'      => 'portfolio_detail_style',
                        'type'    => 'select',
                        'class'   => 'noo-portfolio-view-detail',
                        'options' => array(
                            array('value'=>'none','label'=>'Inherit from theme options'),
                            array('value'=>'detail-01','label'=>'Fullwidth slide'),
                            array('value'=>'detail-02','label'=>'Vertical images'),
                            array('value'=>'detail-03','label'=>'Small slide'),
                            array('value'=>'detail-04','label'=>'Grid images 2 Columns'),
                            array('value'=>'detail-05','label'=>'Grid images 1 Columns')
                        ),
                        'std'         => 'none',
                    )
                ),
            );
            $helper->add_meta_box($meta_box);

        }

    }

    add_action( 'plugins_loaded', array( 'Noo_Portfolio', 'get_instance' ) );

endif;

/**
 * End class noo_portfolio
 */
