<?php
/**
 * Post Types Trainer
 *
 * Registers post types and taxonomies.
 * 
 * @author      NooTheme
 * @category    Admin
 * @package     NooTimetable/PostTypes
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( !class_exists('Noo__Timetable__Trainer') ):

    class Noo__Timetable__Trainer {

        public function __construct(){
            add_action( 'init', array( $this, 'register_post_type' ) );
            add_filter( 'template_include', array( $this, 'template_loader' ) );

            if ( ! is_admin() ) :
                add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ), 100);
            endif;

            if ( is_admin() ) :
                add_action( 'customize_save', array($this,'customizer_set_transients_before_save') );
                add_action( 'customize_save_after', array($this,'customizer_set_transients_after_save') );
            endif;
        }

        public function pre_get_posts($q){
            if ( ! $q->is_main_query() ) {
                return;
            }

            if ( is_post_type_archive('noo_trainer') && $q->get('post_type') == 'noo_trainer' ) {
                $number = NOO_Settings()->get_option('noo_trainer_num', 12);

                if ( is_numeric($number) ){
                    $q->set('posts_per_page', $number);
                }
            }
        }

        public function template_loader( $template ) {

            $find = array();
            $file = '';

            if ( is_single() && get_post_type() == 'noo_trainer' ) {

                $file   = 'single-noo_trainer.php';
                $find[] = $file;
                $find[] = Noo__Timetable__Main::template_path() . $file;

            } elseif ( is_post_type_archive( 'noo_trainer' ) ) {

                $file   = 'archive-noo_trainer.php';
                $find[] = $file;
                $find[] = Noo__Timetable__Main::template_path() . $file;
            }

            if ( $file ) {
                $template = locate_template( array_unique( $find ) );
                
                if ( ! $template ) {
                    $template = Noo__Timetable__Main::plugin_path() . '/templates/' . $file;
                }
            }

            return $template;

        }

        public function register_post_type(){

            // Check post type exists
            if ( post_type_exists( 'noo_trainer' ) )
                return;

            // Clear transient
            if ( get_transient( 'noo_trainer_slug_before' ) != get_transient( 'noo_trainer_slug_after' ) ) {
                flush_rewrite_rules();
                delete_transient( 'noo_trainer_slug_before' );
                delete_transient( 'noo_trainer_slug_after' );
            }

            $trainer_slug = NOO_Settings()->get_option('noo_trainer_page', 'trainers');
            // $trainer_slug = !empty($trainer_page) ? get_post( $trainer_page )->post_name : 'trainers';

            register_post_type( 
                'noo_trainer', 
                array( 
                    'labels' => array( 
                            'name'               => esc_html__( 'Trainers', 'noo-timetable' ), 
                            'singular_name'      => esc_html__( 'Trainer', 'noo-timetable' ), 
                            'add_new'            => esc_html__( 'Add New Trainer', 'noo-timetable' ), 
                            'add_new_item'       => esc_html__( 'Add Trainer', 'noo-timetable' ), 
                            'edit'               => esc_html__( 'Edit', 'noo-timetable' ), 
                            'edit_item'          => esc_html__( 'Edit Trainer', 'noo-timetable' ), 
                            'new_item'           => esc_html__( 'New Trainer', 'noo-timetable' ), 
                            'view'               => esc_html__( 'View', 'noo-timetable' ), 
                            'view_item'          => esc_html__( 'View Trainer', 'noo-timetable' ), 
                            'search_items'       => esc_html__( 'Search Trainer', 'noo-timetable' ), 
                            'not_found'          => esc_html__( 'No Trainers found', 'noo-timetable' ), 
                            'not_found_in_trash' => esc_html__( 'No Trainers found in Trash', 'noo-timetable' ), 
                            'parent'             => esc_html__( 'Parent Trainer', 'noo-timetable' )
                        ), 
                    'public'            => true,
                    'show_in_menu'      => 'edit.php?post_type=noo_class',
                    'show_in_nav_menus' => true,
                    'has_archive'       => true,
                    'menu_icon'         => 'dashicons-businessman',
                    'rewrite'           => array( 'slug' => $trainer_slug, 'with_front' => false ),
                    'supports'          => array( 'title', 'editor', 'thumbnail' ),
                    'can_export'        => true
                )
            );

        }

        public function customizer_set_transients_before_save() {
            set_transient( 'noo_trainer_slug_before', NOO_Settings()->get_option( 'noo_trainer_page', 'trainers' ), 60 );
        }

        public function customizer_set_transients_after_save() {
            set_transient( 'noo_trainer_slug_after', NOO_Settings()->get_option( 'noo_trainer_page', 'trainers' ), 60 );
        }


    }
    new Noo__Timetable__Trainer();

endif;