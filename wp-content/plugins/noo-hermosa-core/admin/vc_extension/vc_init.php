<?php
// Incremental ID Counter for Templates
if ( ! function_exists( 'noo_vc_elements_id_increment' ) ) :
    function noo_vc_elements_id_increment() {
        static $count = 0; $count++;
        return $count;
    }
endif;
// Function for handle element's visibility
if ( ! function_exists( 'noo_visibility_class' ) ) :
    function noo_visibility_class( $visibility = '' ) {
        switch ($visibility) {
            case 'hidden-phone':
                return ' hidden-xs';
            case 'hidden-tablet':
                return ' hidden-sm';
            case 'hidden-pc':
                return ' hidden-md hidden-lg';
            case 'visible-phone':
                return ' visible-xs';
            case 'visible-tablet':
                return ' visible-sm';
            case 'visible-pc':
                return ' visible-md visible-lg';
            default:
                return '';
        }
    }
endif;
if ( class_exists('WPBakeryVisualComposerAbstract') ):
    function nootheme_includevisual(){
        require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/map/new_params.php';
        require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/map/map.php';
        // VC Templates
        $vc_templates_dir = NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/vc_templates/';
        vc_set_shortcodes_templates_dir($vc_templates_dir);

        // require file
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-title.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-product.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-event-slider.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-trainer.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-event-calendar.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-class-grid-slider.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-class-schedule.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-find-event.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-counter.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-short-introdution.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-form7.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-video.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-gallery.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-partner.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-blog.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-mailchimp.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-testimonial.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-price-table.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-service.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-multiple-service.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-info.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-recent-new.php'; 
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-service-info.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/instagram/instagram.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-banner-image.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-open-hours.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-pricing-plan.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/portfolio/noo-portfolio.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/ntt-trainer.php';
        require NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/ntt-class.php';
        
    }
    add_action('init', 'nootheme_includevisual', 20);
endif;

