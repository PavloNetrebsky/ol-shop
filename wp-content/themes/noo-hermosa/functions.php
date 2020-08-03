<?php
/**
 * Theme functions for NOO Framework.
 * This file include the framework functions, it should remain intact between themes.
 * For theme specified functions, see file functions-<theme name>.php
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

// Set global constance


if ( !defined( 'NOO_ASSETS' ) ) {
	define( 'NOO_ASSETS', get_template_directory() . '/assets' );
}

if ( !defined( 'NOO_ASSETS_URI' ) ) {
	define( 'NOO_ASSETS_URI', get_template_directory_uri() . '/assets' );
}

if ( !defined( 'NOO_VENDOR_URI' ) ) {
	define( 'NOO_VENDOR_URI', NOO_ASSETS_URI . '/vendor' );
}

define( 'NOO_INCLUDES', get_template_directory() . '/includes' );
define( 'NOO_INCLUDES_URI', get_template_directory_uri() . '/includes' );
define( 'NOO_FUNCTIONS', NOO_INCLUDES . '/functions' );

define( 'NOO_ADMIN_ASSETS', NOO_INCLUDES . '/admin_assets' );
define( 'NOO_ADMIN_ASSETS_URI', NOO_INCLUDES_URI . '/admin_assets' );



if ( !defined( 'NOO_THEME_NAME' ) ) {
	define( 'NOO_THEME_NAME', 'noo-hermosa' );
}

if ( !defined( 'NOO_WOOCOMMERCE_EXIST' ) ) define( 'NOO_WOOCOMMERCE_EXIST', class_exists( 'WC_API' ) );

// Initialize NOO Libraries
if ( ! class_exists( 'Noo_Hermosa_Core' ) ) :

require_once NOO_INCLUDES . '/libs/noo-theme.php';
require_once NOO_INCLUDES . '/libs/noo-layout.php';
require_once NOO_INCLUDES . '/libs/noo-post-type.php';
require_once NOO_INCLUDES . '/libs/noo-css.php';
require_once NOO_INCLUDES . '/libs/noo-customize.php';

endif;

// Theme setup
require_once NOO_INCLUDES . '/theme_setup.php';

//
// Customize
//
if ( class_exists( 'Noo_Hermosa_Core' ) ) :

require_once NOO_INCLUDES . '/customizer/options.php';

endif;

//
// Plugins
// First we'll check if there's any plugins inluded
//
$plugin_path = get_template_directory() . '/plugins';
if ( file_exists( $plugin_path . '/tgmpa_register.php' ) ) {
	require_once NOO_INCLUDES . '/class-tgm-plugin-activation.php';
	require_once $plugin_path . '/tgmpa_register.php';
}

if( ! function_exists('is_plugin_active') ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );	
}

//
// Enqueue assets
//

function noo_hermosa_fonts_url() {
    // Enqueue Fonts.


    $body_font_family     = noo_hermosa_get_theme_default( 'font_family' );
    $headings_font_family = noo_hermosa_get_theme_default( 'headings_font_family' );
    $special_font_family = 'Droid Serif';
    $nav_font_family      = noo_hermosa_get_theme_default( 'nav_font_family' );
    $logo_font_family     = noo_hermosa_get_theme_default( 'logo_font_family' );
    $fonts_url = '';
    $subsets   = 'latin,latin-ext';

    $font_families = array();



	$noo_typo_use_custom_headings_font = noo_hermosa_get_option( 'noo_typo_use_custom_headings_font', false );
	$noo_typo_use_custom_body_font     = noo_hermosa_get_option( 'noo_typo_use_custom_body_font', false );
	$noo_typo_use_custom_special_font  = noo_hermosa_get_option( 'noo_typo_use_custom_special_font', false );
	$nav_custom_font                   = noo_hermosa_get_option( 'noo_header_custom_nav_font', false );
	$use_image_logo                    = noo_hermosa_get_option( 'noo_header_use_image_logo', false );



    $body_trans     =   _x('on', 'Body font: on or off','noo-hermosa');

    $special_trans  =   _x('on', 'Special font: on or off','noo-hermosa');

    $heading_trans  =   _x('on', 'Heading font: on or off','noo-hermosa');

    $nav_trans      =   _x('on', 'Nav font: on or off','noo-hermosa');

    $logo_trans     =   _x('on', 'Logo font: on or off','noo-hermosa');

    if( $noo_typo_use_custom_headings_font != false) {
        $headings_font_family   = noo_hermosa_get_option( 'noo_typo_headings_font', $headings_font_family );
    }

    if( $noo_typo_use_custom_body_font != false) {
        $body_font_family		= noo_hermosa_get_option( 'noo_typo_body_font', $body_font_family );
    }

    if( $noo_typo_use_custom_special_font != false) {
        $special_font_family		= noo_hermosa_get_option( 'noo_typo_special_font', $special_font_family );
    }

    if( $nav_custom_font != false) {
        $nav_font_family    = noo_hermosa_get_option( 'noo_header_nav_font', $nav_font_family );
    }

    if( $use_image_logo == false) {
        $logo_font_family   = noo_hermosa_get_option( 'noo_header_logo_font', $logo_font_family );
    }


    if ( 'off' !== $body_trans ) {
        $font_families[] = $body_font_family . ':' . '100,300,400,500,600,700,900,300italic,400italic,700italic,900italic';

    }

    if ( 'off' !== $special_trans ) {
        $font_families[] = $special_font_family . ':' . '100,300,400,500,600,700,900,300italic,400italic,700italic,900italic';

    }

    if ( 'off' !== $heading_trans ) {

        $font_families[] = $headings_font_family . ':' . '100,300,400,500,600,700,900,300italic,400italic,700italic,900italic';

    }

    if ( 'off' !== $nav_trans && $nav_custom_font != false) {

        $font_families[] = $nav_font_family . ':' . '100,300,400,500,600,700,900,300italic,400italic,700italic,900italic';

    }

    if ( 'off' !== $logo_trans && $use_image_logo == false && !empty($logo_font_family)) {

        $font_families[] = $logo_font_family . ':' . '100,300,400,500,600,700,900,300italic,400italic,700italic,900italic';

    }

    $subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'noo-hermosa' );

    if ( 'cyrillic' == $subset ) {
        $subsets .= ',cyrillic,cyrillic-ext';
    } elseif ( 'greek' == $subset ) {
        $subsets .= ',greek,greek-ext';
    } elseif ( 'devanagari' == $subset ) {
        $subsets .= ',devanagari';
    } elseif ( 'vietnamese' == $subset ) {
        $subsets .= ',vietnamese';
    }

    if ( $font_families ) {
        $fonts_url = add_query_arg( array(
            'family' => urlencode( implode( '|', $font_families ) ),
            'subset' => urlencode( $subsets ),
        ), 'https://fonts.googleapis.com/css' );
    }

    return esc_url_raw( $fonts_url );

}

function noo_hermosa_enqueue_scripts() {

	if ( ! is_admin() ) {
			
		/**
		 * Enqueue library carousel
		 */
		wp_register_style( 'carousel', NOO_VENDOR_URI . '/owl.carousel.css', array(), '1.0.0' );
        wp_register_style( 'noo-carousel', NOO_VENDOR_URI . '/owl.carousel.css', array(), '1.0.0' );

		/**
		 * Enqueue library swiper
		 */
		wp_register_style( 'swiper', NOO_VENDOR_URI . '/swiper/css/swiper.min.css', array(), '1.0.0' );


		if( is_file( noo_hermosa_upload_dir() . '/custom.css' ) ) {
			wp_register_style( 'noo-custom-style', noo_hermosa_upload_url() . '/custom.css', NULL, NULL, 'all' );
		}


		// Vendors
		// Font Awesome
		if ( class_exists('YITH_WCWL_Init') ) {
			wp_dequeue_style ( 'yith-wcwl-font-awesome' );
		}


        wp_register_style( 'timepicker', NOO_VENDOR_URI . '/datetimepicker/jquery.ui.timepicker.css');
        wp_enqueue_style('timepicker');

		wp_register_style( 'font-awesome-css', NOO_VENDOR_URI . '/fontawesome/css/font-awesome.min.css', array(), '4.6.1' );
		wp_enqueue_style( 'font-awesome-css' );

		wp_register_style('noo-event-calendar', NOO_VENDOR_URI. '/fullcalendar/fullcalendar.css');
		wp_enqueue_style('noo-event-calendar');

		// Font ionicons
		wp_register_style( 'ionicons-css', NOO_VENDOR_URI . '/ionicons/css/ionicons.min.css', array(), '4.2.0' );
		wp_enqueue_style( 'ionicons-css' );

		wp_enqueue_style( 'noo-hermosa-fonts', noo_hermosa_fonts_url(), array(), null );

        wp_enqueue_style( 'noo-css', NOO_ASSETS_URI . '/css/noo.css', array(), NULL, NULL);
        wp_register_style('ladda-css', NOO_VENDOR_URI . '/ladda/dist/ladda-themeless.min.css', array(),false);

        if( ! noo_hermosa_get_option('noo_use_inline_css', false) && wp_style_is( 'noo-custom-style', 'registered' ) ) {
            global $wp_customize;
            if ( !isset( $wp_customize ) ) {
                wp_enqueue_style( 'noo-custom-style' );
            }
        }

        // Main style
        wp_enqueue_style( 'noo-style', get_stylesheet_directory_uri() . '/style.css', NULL, NULL, 'all' );
	}


	// Main script

	// vendor script
	wp_register_script( 'modernizr', NOO_VENDOR_URI . '/modernizr-2.7.1.min.js', null, null, false );

	wp_register_script( 'imagesloaded', NOO_VENDOR_URI . '/imagesloaded.pkgd.min.js', null, null, true );
	wp_register_script( 'isotope', NOO_VENDOR_URI . '/jquery.isotope.min.js', array('imagesloaded'), null, true );
    wp_register_script( 'isotope-new', NOO_VENDOR_URI . '/isotope-2.0.0.min.js', array('imagesloaded'), null, true );
	wp_register_script( 'masonry', NOO_VENDOR_URI . '/masonry.pkgd.min.js', array('imagesloaded'), null, true );
	wp_register_script( 'infinitescroll', NOO_VENDOR_URI . '/infinitescroll-2.0.2.min.js', null, null, true );
	
	wp_register_script( 'touchSwipe', NOO_VENDOR_URI . '/jquery.touchSwipe.js', array( 'jquery' ), null, true );
	wp_register_script( 'carouFredSel', NOO_VENDOR_URI . '/carouFredSel/jquery.carouFredSel-6.2.1-packed.js', array( 'jquery', 'touchSwipe','imagesloaded' ), null, true );
	
	wp_register_script( 'jplayer', NOO_VENDOR_URI . '/jplayer/jplayer-2.5.0.min.js', array( 'jquery' ), null, true );
	wp_register_script( 'nivo-lightbox-js', NOO_VENDOR_URI . '/nivo-lightbox/nivo-lightbox.min.js', array( 'jquery' ), null, true );
	wp_register_script( 'fancybox-lightbox-js', NOO_VENDOR_URI . '/fancybox-lightbox/source/jquery.fancybox.pack.js', array( 'jquery' ), null, true );

    // counter
    wp_register_script( 'easing', get_template_directory_uri() . '/assets/vendor/easing-1.3.0.min.js', array( 'jquery' ), null, true );
    wp_register_script( 'appear', get_template_directory_uri() . '/assets/vendor/jquery.appear.js', array( 'jquery','easing' ), null, true );
    wp_register_script( 'countTo', get_template_directory_uri() . '/assets/vendor/jquery.countTo.js', array( 'jquery', 'appear' ), null, true );

    /**
     * Enqueue map google
     */

    $google_api = '';
    if ( class_exists('Noo__Timetable__Main') ) {
    	$google_api = NOO_Settings()->get_option( 'noo_google_map_api_key', '' );
    }

    wp_register_script( 'maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp' . ( !empty( $google_api ) ? '&key=' .$google_api : '' ), array( 'jquery' ), null, false );
    wp_register_script( 'noo-maps', NOO_ASSETS_URI . '/js/noo-maps.js', array( 'maps' ), null, false );
    wp_localize_script( 'noo-maps', 'nooMaps', array(
		'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ),
		'security' => wp_create_nonce( 'noo-map' ),
		'lat'		=> noo_hermosa_get_option( 'noo_map_lat', '51.508742' ),
		'lng'		=> noo_hermosa_get_option( 'noo_map_lng', '-0.120850' ),
		'zoom'		=> noo_hermosa_get_option( 'noo_map_zoom', '14' ),
		'icon_map'	=> noo_hermosa_get_option( 'noo_map_icon', NOO_ASSETS_URI . '/images/Map-marker.png' )
	));

    // timepicker
    wp_register_script( 'jquery-ui-custom', NOO_VENDOR_URI . '/datetimepicker/jquery-ui.js', array(), null, false );

    wp_register_script( 'jquery.ui.timepicker', NOO_VENDOR_URI . '/datetimepicker/jquery.ui.timepicker.js', array(), null, true );

    // gallery
    wp_register_script( 'modernizr.custom', NOO_VENDOR_URI . '/grid-gallery/modernizr.custom.js',null, null, true );
    wp_register_script( 'classie', NOO_VENDOR_URI . '/grid-gallery/classie.js',null, null, true );
    wp_register_script( 'cbpGridGallery', NOO_VENDOR_URI . '/grid-gallery/cbpGridGallery.js', array( 'modernizr.custom', 'classie' ), null, true );
    wp_register_script( 'noo-gallery', NOO_VENDOR_URI . '/grid-gallery/noo-gallery.js', null, null, true );

	// portfolio
    wp_register_script( 'modernizr.custom', NOO_VENDOR_URI . '/grid-portfolio/modernizr.custom.js',null, null, true );
    wp_register_script( 'classie', NOO_VENDOR_URI . '/grid-portfolio/classie.js',null, null, true );
    wp_register_script( 'cbpGridPortfolio', NOO_VENDOR_URI . '/grid-portfolio/cbpGridPortfolio.js', array( 'modernizr.custom', 'classie' ), null, true );
    wp_register_script( 'noo-portfolio', NOO_VENDOR_URI . '/grid-portfolio/noo-portfolio.js', null, null, true );

    wp_register_script('ladda-spin', NOO_VENDOR_URI . '/ladda/dist/spin.min.js', false, true);
    wp_register_script('ladda', NOO_VENDOR_URI . '/ladda/dist/ladda.min.js', false, true);
    wp_register_script('jquery-hoverdir', NOO_VENDOR_URI . '/hoverdir/jquery.hoverdir.js', array('jquery'),null, true);
    wp_register_script('packery-mode',  NOO_VENDOR_URI. '/packery/packery-mode.pkgd.min.js', array(),null, true);

	wp_register_script( 'noo-portfolio-ajax-action', NOO_VENDOR_URI . '/grid-portfolio/ajax-action.js', null, null, true);


	
	wp_register_script( 'wow', NOO_VENDOR_URI . '/wow/wow.min.js', array( 'jquery'), null, true );
	
	wp_register_script( 'parallax', NOO_VENDOR_URI . '/jquery.parallax-1.1.3.js', array( 'jquery'), null, true );

    wp_register_script( 'noo-category', NOO_ASSETS_URI . '/js/noo_category.js', array( 'jquery'), null, true );
    wp_register_script( 'noo-carousel', NOO_VENDOR_URI . '/owl.carousel.min.js', array( 'jquery'), null, true );
    wp_register_script( 'swiper', NOO_VENDOR_URI . '/swiper/js/swiper.min.js', array( 'jquery'), null, true );

	wp_register_script( 'noo-script', NOO_ASSETS_URI . '/js/noo.js', array( 'jquery' ), null, true );
	// wp_register_script( 'noo-event', NOO_ASSETS_URI . '/js/noo_event.js', array( 'jquery' ), null, true );
  



	if ( ! is_admin() ) {
		wp_enqueue_script( 'modernizr' );
		
		// Required for nested reply function that moves reply inline with JS
		if ( is_singular() ) wp_enqueue_script( 'comment-reply' );

		$is_shop				= NOO_WOOCOMMERCE_EXIST && is_shop();
		$nooL10n = array(
			'ajax_url'        => admin_url( 'admin-ajax.php', 'relative' ),
			'ajax_finishedMsg'=> esc_html__('All posts displayed', 'noo-hermosa'),
			'home_url'        => home_url( '/' ),
			'is_blog'         => is_home() ? 'true' : 'false',
			'is_archive'      => is_post_type_archive('post') ? 'true' : 'false',
			'is_single'       => is_single() ? 'true' : 'false',
			'is_shop'         => NOO_WOOCOMMERCE_EXIST && is_shop() ? 'true' : 'false',
			'is_product'      => NOO_WOOCOMMERCE_EXIST && is_product() ? 'true' : 'false',
			'infinite_scroll_end_msg' => esc_html__( 'All posts displayed', 'noo-hermosa')
		);

		global $noo_post_types;
		if( !empty( $noo_post_types ) ) {
			foreach ($noo_post_types as $post_type => $args) {
				$nooL10n['is_' . $post_type . '_archive'] = is_post_type_archive( $post_type ) ? 'true' : 'false';
				$nooL10n['is_' . $post_type . '_single'] = is_singular( $post_type ) ? 'true' : 'false';
			}
		}
		
		
		wp_localize_script('noo-script', 'nooL10n', $nooL10n);
		wp_enqueue_script( 'infinitescroll' );


        wp_enqueue_script( 'noo-cabas', NOO_ASSETS_URI . '/js/off-cavnass.js', array(), null, true );
        wp_enqueue_script( 'noo-new', NOO_ASSETS_URI . '/js/noo_new.js', array(), null, true );
        wp_enqueue_script( 'noo-class_theme', NOO_ASSETS_URI . '/js/noo_class.js', array(), null, true );
		wp_enqueue_script( 'noo-script' );

		wp_localize_script('noo-new', 'nooNew', array(
			'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ),
			'security' => wp_create_nonce( 'noo-new' ),
            'image_loading'   => get_template_directory_uri() . '/assets/images/blog-loading.gif'
		));

		if ( class_exists( 'Noo__Timetable__Event' ) ) :
			wp_enqueue_script( 'noo-event' );
			wp_localize_script('noo-event', 'nooEvent', array(
				'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ),
				'security' => wp_create_nonce( 'noo-event' )
			));
		endif;
	}

}

add_action( 'wp_enqueue_scripts', 'noo_hermosa_enqueue_scripts' );

// Helper functions
require_once NOO_FUNCTIONS . '/noo-html.php';
require_once NOO_FUNCTIONS . '/noo-utilities.php';
require_once NOO_FUNCTIONS . '/noo-style.php';
require_once NOO_FUNCTIONS . '/noo-wp-style.php';
require_once NOO_FUNCTIONS . '/noo-user.php';
require_once NOO_FUNCTIONS . '/resize-image.php';


// Mega Menu
require_once NOO_INCLUDES . '/mega-menu/noo_mega_menu.php';

// WooCommerce

 require_once NOO_INCLUDES . '/woocommerce.php';

//
// Widgets
//
$widget_path = get_template_directory() . '/widgets';

if ( file_exists( $widget_path . '/widgets_init.php' ) ) {
	require_once $widget_path . '/widgets_init.php';
	require_once $widget_path . '/widgets.php';
}

if ( class_exists('Hc_Insert_Html_Widget') ) {
	
	if( !function_exists('noo_hermosa_enqueue_healcode_scripts') ) {

		function noo_hermosa_enqueue_healcode_scripts() {
			wp_enqueue_script( 'healcode-widget', 'https://widgets.healcode.com/javascripts/healcode.js', array(), null, false );
			wp_enqueue_style( 'noo-healcode', NOO_ASSETS_URI . "/css/healcode.css", null, null, 'all' );
		}
		add_action( 'wp_enqueue_scripts', 'noo_hermosa_enqueue_healcode_scripts', 9 );

	}

}

if( !function_exists('noo_hermosa_woocommerce_support') ) {

	function noo_hermosa_woocommerce_support() {
	    add_theme_support( 'woocommerce' );
	}

	add_action( 'after_setup_theme', 'noo_hermosa_woocommerce_support' );

}

if( !function_exists('noo_hermosa_getJqueryUII18nLocale') ) {
	function noo_hermosa_getJqueryUII18nLocale() {

	    $locale = str_replace( '_', '-', get_locale() );
	    $locale = substr($locale, 0, strpos($locale, '-'));

	    if ( $locale == '' ) {
	    	$locale = 'en';
	    }

	    return $locale;
	}
}


/**
 * Setting for plugin timetable if active
 */
if ( class_exists('Noo__Timetable__Main') )
{
	// Deregister Script
	if ( ! function_exists( 'noo_hermosa_deregister_script' ) ) {
		function noo_hermosa_deregister_script() {
			if ( ! is_admin() ) {
				wp_deregister_style( 'noo-timetable' );
				wp_deregister_style( 'calendar' );
			}
		}
		add_action( 'wp_enqueue_scripts', 'noo_hermosa_deregister_script', 11, 3 );
	}

	// NooTimetable
 	require_once NOO_INCLUDES . '/inc-noo-timetable.php';

 	//  Shortcode NooTimetable
 	require_once NOO_INCLUDES . '/noo-timetable-map.php';

} else {

	if ( ! function_exists( 'noo_hermosa_default_script' ) ) {
		function noo_hermosa_default_script() {
			/**
			 * Enqueue calendar
			 */
			wp_register_script( 'calendar-moment', NOO_VENDOR_URI . '/fullcalendar/lib/moment.min.js',null, null, true );
			wp_register_script( 'calendar-lang', NOO_VENDOR_URI . '/fullcalendar/lang-all.js',null, null, true );
			wp_register_script( 'calendar', NOO_VENDOR_URI . '/fullcalendar/fullcalendar.custom.js', array( 'calendar-moment', 'jquery' ), null, true );
			wp_register_script( 'ics', NOO_VENDOR_URI . '/ics/ics.js', array( 'jquery'), null, true );
			wp_register_script( 'ics-deps', NOO_VENDOR_URI . '/ics/ics.deps.min.js', array( 'jquery'), null, true );

		}
		add_action( 'wp_enqueue_scripts', 'noo_hermosa_default_script', 11, 3 );
	}

}

if ( ! function_exists( 'noo_hermosa_getCheckBox' ) ) {
	function noo_hermosa_getCheckBox($string, $text_explode) {
		$day = array();
		$posDay = noo_hermosa_strpos_all( $string, $text_explode );
		foreach ($posDay as $pos) {
			$day[] = substr($string, $pos + strlen($text_explode), 1);
		}

		return $day;
	}
}

if ( ! function_exists( 'noo_hermosa_getSelect' ) ) {
	function noo_hermosa_getSelect($string, $text_explode) {
		$pos = strpos( $string, $text_explode );
		$kq = '';
		
		if ($pos !== false) {
			$i = 0;
			while( $i < strlen($string) ) {
				$i++;
				$kq = substr($string, $pos + strlen($text_explode), $i);
				if ( !is_numeric($kq) ){
					$kq = substr($kq, 0, strlen($kq) - 1);
					break;
				}
			}
		}
		return $kq;
	}
}

if ( ! function_exists( 'noo_hermosa_strpos_all' ) ) {
	function noo_hermosa_strpos_all($haystack, $needle) {
	    $offset = 0;
	    $allpos = array();
	    while (($pos = strpos($haystack, $needle, $offset)) !== FALSE) {
	        $offset   = $pos + 1;
	        $allpos[] = $pos;
	    }
	    return $allpos;
	}
}

if ( ! function_exists( 'noo_hermosa_class_get_count_arrange' ) ) {
	function noo_hermosa_class_get_count_arrange() {
		$filter = isset( $_POST['filter'] ) ? $_POST['filter'] : '*';

		$filter_days    = noo_hermosa_getCheckBox($filter, 'filter-day-');
		$filter_trainer = noo_hermosa_getSelect($filter, 'filter-trainer-');
		$filter_level   = noo_hermosa_getSelect($filter, 'filter-level-');
		$filter_cat     = noo_hermosa_getSelect($filter, 'filter-cat-');

		$args = array(
			'posts_per_page'   =>'-1',
			'post_type'        =>'noo_class',
			'suppress_filters' => 0
		);

		if ( $filter_level ) {

			$args['tax_query'][] = array(
				'taxonomy'  => 'class_level',
				'terms'   	=> $filter_level,
			);
		}

		if ( $filter_cat ) {		
			$args['tax_query'][] = array(
				'taxonomy'  => 'class_category',
				'terms'   	=> $filter_cat,
			);
		}

		$classes = get_posts($args);

		$new_c = array();
		if ( $classes ) {

	        foreach ($classes as $cl){
	        	$flag = 1;
	        	if ( $filter_days ) {
	        		$flag = 0;
					$number_days = (array) noo_hermosa_json_decode( noo_hermosa_get_post_meta( $cl->ID, "_number_day", '' ) );

					foreach ($number_days as $day) {
						if ( in_array( $day, $filter_days ) ) {
							$flag = 1;
						}
					}
				}

				if ( $filter_trainer ) {
	        		$flag = 0;
					$trainers = (array) noo_hermosa_json_decode( noo_hermosa_get_post_meta($cl->ID, '_trainer') );

					if ( in_array( $filter_trainer, $trainers ) ) {
						$flag = 1;
					}
				}

				if ( $flag == 1 ) {
					$new_c[] = $cl;
				}
	        }
	    }

	    echo count($new_c);

		exit();
	}
}

add_action( 'wp_ajax_noo_class_get_count_arrange', 'noo_hermosa_class_get_count_arrange' );
add_action( 'wp_ajax_nopriv_noo_class_get_count_arrange', 'noo_hermosa_class_get_count_arrange' );

/**
 * @snippet       Automatically Update Cart on Quantity Change - WooCommerce
 * @how-to        Get CustomizeWoo.com FREE
 * @sourcecode    https://businessbloomer.com/?p=73470
 * @author        Rodolfo Melogli
 * @compatible    Woo 3.5.1
 */
 
add_filter( 'woocommerce_currencies', 'add_my_currency' );

function add_my_currency( $currencies ) {

     $currencies['UAH'] = __( 'Українська гривня', 'woocommerce' );

     return $currencies;

}

add_filter('woocommerce_currency_symbol', 'add_my_currency_symbol', 10, 2);

function add_my_currency_symbol( $currency_symbol, $currency ) {

     switch( $currency ) {

         case 'UAH': $currency_symbol = 'грн'; break;

     }

     return $currency_symbol;

}

add_filter( 'woocommerce_checkout_fields' , 'virtual_products_less_fields' );
 
function virtual_products_less_fields( $fields ) { 
    unset($fields['billing']['billing_email']);
     
    return $fields;
}

