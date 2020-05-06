<?php
/**
 * NOO Customizer Package.
 *
 * Register Options
 * This file register options used in NOO-Customizer
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
// =============================================================================


// Action generate CSS in Customizer
noo_hermosa_customizer_check_css();
function noo_hermosa_customizer_check_css() {
	if ( function_exists('noo_hermosa_customizer_css_generator') ) {
		global $wp_customize;
		if ( isset( $wp_customize ) || noo_hermosa_get_option('noo_use_inline_css', false) ) {
			add_action( 'wp_head', 'noo_hermosa_customizer_css_generator', 100, 0 );
		}
	}
}

// 0. Remove Unused WP Customizer Sections
if ( ! function_exists( 'noo_hermosa_customizer_remove_wp_native_sections' ) ) :
	function noo_hermosa_customizer_remove_wp_native_sections( $wp_customize ) {
		// $wp_customize->remove_section( 'title_tagline' );
		// $wp_customize->remove_section( 'colors' );
		// $wp_customize->remove_section( 'background_image' );
		// $wp_customize->remove_section( 'nav' );
		// $wp_customize->remove_section( 'static_front_page' );
	}

add_action( 'customize_register', 'noo_hermosa_customizer_remove_wp_native_sections' );
endif;


//
// Register NOO Customizer Sections and Options
//

// 1. Site Enhancement options.
if ( ! function_exists( 'noo_hermosa_customizer_register_options_general' ) ) :
	function noo_hermosa_customizer_register_options_general( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Site Enhancement
		$helper->add_section(
			'noo_hermosa_customizer_section_site_enhancement',
			esc_html__( 'Site Enhancement', 'noo-hermosa' ),
			esc_html__( 'Enable/Disable some features for your site.', 'noo-hermosa' )
		);

		// Control: Back to top
        $helper->add_control(
            'noo_back_to_top',
            'noo_switch',
            esc_html__( 'Back To Top Button', 'noo-hermosa' ),
            1,
            array(
				'preview_type' => 'custom'
			)
        );

        // Control: Page heading
        $helper->add_control(
            'noo_page_heading',
            'noo_switch',
            esc_html__( 'Enable Page Heading', 'noo-hermosa' ),
            1,
            array(
				'json' => array(
					'on_child_options'   => 'noo_page_description,noo_breadcrumbs,noo_page_heading_spacing',
				)
			)
        );

        // Control: Page Description
        $helper->add_control(
            'noo_page_description',
            'noo_switch',
            esc_html__( 'Enable Page Description', 'noo-hermosa' ),
            1
        );
        // Control: Breadcrumbs
        $helper->add_control(
            'noo_breadcrumbs',
            'noo_switch',
            esc_html__( 'Enable Breadcrumbs', 'noo-hermosa' ),
            1
        );

        // Control: NavBar Link Spacing (px)
		$helper->add_control(
			'noo_page_heading_spacing',
			'ui_slider',
			esc_html__( 'Page Heading Spacing (px)', 'noo-hermosa' ),
			'155',
			array(
				'json' => array(
					'data_min' => 20,
					'data_max' => 200,
				),
				'preview_type' => 'custom'
			)
		);


	}
add_action( 'customize_register', 'noo_hermosa_customizer_register_options_general' );
endif;

// 2. Design and Layout options.
if ( ! function_exists( 'noo_hermosa_customizer_register_options_layout' ) ) :
	function noo_hermosa_customizer_register_options_layout( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Layout
		$helper->add_section(
			'noo_hermosa_customizer_section_layout',
			esc_html__( 'Design and Layout', 'noo-hermosa' ),
			esc_html__( 'Set Style and Layout for your site. Boxed Layout will come with additional setting options for background color and image.', 'noo-hermosa' )
		);

		noo_customizer_add_controls(
			$wp_customize,
			array(
				'noo_site_layout' => array(
					'type' => 'noo_radio',
					'label' => esc_html__( 'Site Layout', 'noo-hermosa' ),
					'default' => 'fullwidth',
					'control' => array(
						'choices' => array( 'fullwidth' => esc_html__( 'Fullwidth', 'noo-hermosa' ), 'boxed' => esc_html__( 'Boxed', 'noo-hermosa' ) ),
						'json'  => array(
							'child_options' => array(
								'boxed' => 'noo_layout_site_width
											,noo_layout_site_max_width
											,noo_layout_bg_color
		                                    ,noo_layout_bg_image_sub_section
		                                    ,noo_layout_bg_image
		                                    ,noo_layout_bg_repeat
		                                    ,noo_layout_bg_align
		                                    ,noo_layout_bg_attachment
		                                    ,noo_layout_bg_cover'
							)
						),
						'preview_type' => 'custom'
					)
				),
				'noo_layout_site_width' => array(
					'type' => 'ui_slider',
					'label' => esc_html__( 'Site Width (%)', 'noo-hermosa' ),
					'default' => '100',
					'control' => array(
						'json' => array(
							'data_min' => 60,
							'data_max' => 100,
						),
						'preview_type' => 'custom'
					)
				),
				'noo_layout_site_max_width' => array(
					'type' => 'ui_slider',
					'label' => esc_html__( 'Site Max Width (px)', 'noo-hermosa' ),
					'default' => '1200',
					'control' => array(
						'json' => array(
							'data_min'  => 980,
							'data_max'  => 1600,
							'data_step' => 10,
						),
						'preview_type' => 'custom'
					)
				),
				'noo_layout_bg_color' => array(
					'type' => 'color_control',
					'label' => esc_html__( 'Background Color', 'noo-hermosa' ),
					'default' => '#ffffff',
					'preview_type' => 'custom'
				)
			)
		);

		// Sub-section: Background Image
		$helper->add_sub_section(
			'noo_layout_bg_image_sub_section',
			esc_html__( 'Background Image', 'noo-hermosa' ),
			noo_hermosa_kses( __( 'Upload your background image here, you have various settings for your image:<br/><strong>Repeat Image</strong>: enable repeating your image, you will need it when using patterned background.<br/><strong>Alignment</strong>: Set the position to align your background image.<br/><strong>Attachment</strong>: Make your image scroll with your site or fixed.<br/><strong>Auto resize</strong>: Enable it to ensure your background image always fit the windows.', 'noo-hermosa' ) )
		);

		// Control: Background Image
		$helper->add_control(
			'noo_layout_bg_image',
			'noo_image',
			esc_html__( 'Background Image', 'noo-hermosa' ),
			'',
			array( 'preview_type' => 'custom' )
		);

		// Control: Repeat Image
		$helper->add_control(
			'noo_layout_bg_repeat',
			'radio',
			esc_html__( 'Background Repeat', 'noo-hermosa' ),
			'no-repeat',
			array(
				'choices' => array(
					'repeat' => esc_html__( 'Repeat', 'noo-hermosa' ),
					'no-repeat' => esc_html__( 'No Repeat', 'noo-hermosa' ),
				),
				'preview_type' => 'custom'
			)
		);

		// Control: Align Image
		$helper->add_control(
			'noo_layout_bg_align',
			'select',
			esc_html__( 'BG Image Alignment', 'noo-hermosa' ),
			'left top',
			array(
				'choices' => array(
					'left top'       => esc_html__( 'Left Top', 'noo-hermosa' ),
					'left center'     => esc_html__( 'Left Center', 'noo-hermosa' ),
					'left bottom'     => esc_html__( 'Left Bottom', 'noo-hermosa' ),
					'center top'     => esc_html__( 'Center Top', 'noo-hermosa' ),
					'center center'     => esc_html__( 'Center Center', 'noo-hermosa' ),
					'center bottom'     => esc_html__( 'Center Bottom', 'noo-hermosa' ),
					'right top'     => esc_html__( 'Right Top', 'noo-hermosa' ),
					'right center'     => esc_html__( 'Right Center', 'noo-hermosa' ),
					'right bottom'     => esc_html__( 'Right Bottom', 'noo-hermosa' ),
				),
				'preview_type' => 'custom'
			)
		);

		// Control: Enable Scrolling Image
		$helper->add_control(
			'noo_layout_bg_attachment',
			'radio',
			esc_html__( 'BG Image Attachment', 'noo-hermosa' ),
			'fixed',
			array(
				'choices' => array(
					'fixed' => esc_html__( 'Fixed Image', 'noo-hermosa' ),
					'scroll' => esc_html__( 'Scroll with Site', 'noo-hermosa' ),
				),
				'preview_type' => 'custom'
			)
		);

		// Control: Auto Resize
		$helper->add_control(
			'noo_layout_bg_cover',
			'noo_switch',
			esc_html__( 'Auto Resize', 'noo-hermosa' ),
			0,
			array( 'preview_type' => 'custom' )
		);

		// Sub-Section: Icon Theme
		$helper->add_sub_section(
			'noo_general_sub_section_icon_theme',
			esc_html__( 'Icon Theme', 'noo-hermosa' ),
			esc_html__( 'Here you can show / hide or change Icon Theme', 'noo-hermosa' )
		);

		// Hide Icon Theme
		$helper->add_control(
			'noo_site_hide_icon',
			'noo_switch',
			esc_html__( 'Hide Icon Theme', 'noo-hermosa' ),
			0,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Custom Icon Theme
		$helper->add_control(
			'noo_site_custom_icon',
			'noo_image',
			esc_html__( 'Custom Icon Theme', 'noo-hermosa' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Sub-Section: Links Color
		$helper->add_sub_section(
			'noo_general_sub_section_links_color',
			esc_html__( 'Color', 'noo-hermosa' ),
			esc_html__( 'Here you can set the color for links and various elements on your site.', 'noo-hermosa' )
		);

		// Control: Site Primary Links Hover Color
		$helper->add_control(
			'noo_site_primary_color',
			'color_control',
			esc_html__( 'Primary Color', 'noo-hermosa' ),
			noo_hermosa_get_theme_default( 'primary_color' ),
			array( 'preview_type' => 'update_css', 'preview_params' => array( 'css' => 'design' ) )
		);

		// Control: Site Secondary Links Hover Color
		$helper->add_control(
			'noo_site_secondary_color',
			'color_control',
			esc_html__( 'Secondary Color', 'noo-hermosa' ),
			noo_hermosa_get_theme_default( 'secondary_color' ),
			array( 'preview_type' => 'update_css', 'preview_params' => array( 'css' => 'design' ) )
		);
	}
add_action( 'customize_register', 'noo_hermosa_customizer_register_options_layout' );
endif;

// 3. Typography options.
if ( ! function_exists( 'noo_hermosa_customizer_register_options_typo' ) ) :
	function noo_hermosa_customizer_register_options_typo( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Typography
		$helper->add_section(
			'noo_customizer_section_typo',
			esc_html__( 'Typography', 'noo-hermosa' ),
			noo_hermosa_kses( __( 'Customize your Typography settings. Merito integrated all Google Fonts. See font preview at <a target="_blank" href="http://www.google.com/fonts/">Google Fonts</a>.', 'noo-hermosa' ) )
		);

		// Sub-Section: Headings
		$helper->add_sub_section(
			'noo_typo_sub_section_headings',
			esc_html__( 'Headings', 'noo-hermosa' )
		);

		// Control: Use Custom Fonts
		$helper->add_control(
			'noo_typo_use_custom_headings_font',
			'noo_switch',
			esc_html__( 'Use Custom Headings Font?', 'noo-hermosa' ),
			0,
			array( 'json' => array( 
				'on_child_options'  => 'noo_typo_headings_font,
										noo_typo_headings_font_color,
										noo_typo_headings_uppercase'
				),
				'preview_type' => 'update_css',
				'preview_params' => array( 'css' => 'typography' )
			)
		);

		// Control: Headings font
		$helper->add_control(
			'noo_typo_headings_font',
			'google_fonts',
			esc_html__( 'Headings Font', 'noo-hermosa' ),
			noo_hermosa_get_theme_default( 'headings_font_family' ),
			array(
				'preview_type' => 'custom'
			)
		);

		// Control: Headings Font Color
		$helper->add_control(
			'noo_typo_headings_font_color',
			'color_control',
			esc_html__( 'Font Color', 'noo-hermosa' ),
			noo_hermosa_get_theme_default( 'headings_color' ),
			array( 'preview_type' => 'custom' )
		);

		// Control: Headings Font Uppercase
		$helper->add_control(
			'noo_typo_headings_uppercase',
			'checkbox',
			esc_html__( 'Transform to Uppercase', 'noo-hermosa' ),
			0,
			array( 'preview_type' => 'custom' )
		);

		// Sub-Section: Body
		$helper->add_sub_section(
			'noo_typo_sub_section_body',
			esc_html__( 'Body', 'noo-hermosa' )
		);

		// Control: Use Custom Fonts
		$helper->add_control(
			'noo_typo_use_custom_body_font',
			'noo_switch',
			esc_html__( 'Use Custom Body Font?', 'noo-hermosa' ),
			0,
			array( 'json' => array( 
				'on_child_options'  => 'noo_typo_body_font,
										noo_typo_body_font_color,
										noo_typo_body_font_size' 
				),
				'preview_type' => 'update_css',
				'preview_params' => array( 'css' => 'typography' )
			)
		);
		
		// Control: Body font
		$helper->add_control(
			'noo_typo_body_font',
			'google_fonts',
			esc_html__( 'Body Font', 'noo-hermosa' ),
			noo_hermosa_get_theme_default( 'font_family' ),
			array( 'preview_type' => 'custom' )
		);

		// Control: Body Font Color
		$helper->add_control(
			'noo_typo_body_font_color',
			'color_control',
			esc_html__( 'Font Color', 'noo-hermosa' ),
			noo_hermosa_get_theme_default( 'text_color' ),
			array(
				'preview_type' => 'custom'
			)
		);

		// Control: Body Font Size
		$helper->add_control(
			'noo_typo_body_font_size',
			'font_size',
			esc_html__( 'Font Size (px)', 'noo-hermosa' ),
			noo_hermosa_get_theme_default( 'font_size' ),
			array( 'preview_type' => 'custom' )
		);

		// Sub-Section: Headings
		$helper->add_sub_section(
			'noo_typo_sub_section_special',
			esc_html__( 'Special', 'noo-hermosa' )
		);

		// Control: Use Custom Fonts
		$helper->add_control(
			'noo_typo_use_custom_special_font',
			'noo_switch',
			esc_html__( 'Use Custom Special Font?', 'noo-hermosa' ),
			0,
			array( 'json' => array( 
				'on_child_options'  => 'noo_typo_special_font' 
				),
				'preview_type' => 'update_css',
				'preview_params' => array( 'css' => 'typography' )
			)
		);
		
		// Control: Special font
		$helper->add_control(
			'noo_typo_special_font',
			'google_fonts',
			esc_html__( 'Special Font', 'noo-hermosa' ),
			'Droid Serif'
		);

	}
add_action( 'customize_register', 'noo_hermosa_customizer_register_options_typo' );
endif;


// 4. Header options.
if ( ! function_exists( 'noo_hermosa_customizer_register_options_header' ) ) :
	function noo_hermosa_customizer_register_options_header( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Header
		$helper->add_section(
			'noo_hermosa_customizer_section_header',
			esc_html__( 'Header', 'noo-hermosa' ),
			esc_html__( 'Customize settings for your Header, including Navigation Bar (Logo and Navigation) and an optional Top Bar.', 'noo-hermosa' ),
			true
		);

		// Sub-section: General Options
		$helper->add_sub_section(
			'noo_header_sub_section_general',
			esc_html__( 'General Options', 'noo-hermosa' ),
			''
		);

		// Sub-Section: Header Bar
        $helper->add_sub_section(
            'noo_header_sub_section_style',
            esc_html__( 'Header Style', 'noo-hermosa' ),
            esc_html__( 'Choose style for header', 'noo-hermosa' )
        );

        // Control: Header Style
        $helper->add_control(
            'noo_header_nav_style',
            'noo_radio',
            esc_html__( 'Header Style', 'noo-hermosa' ),
            'header1',
            array(
                'choices' => array(
                    'header1'     => esc_html__( 'Header Default', 'noo-hermosa' ),
                    'header2'     => esc_html__( 'Header Logo Transparent (Use Logo Transparent in Logo Section if available)', 'noo-hermosa' ),
                    'header3'     => esc_html__( 'Header Transparent with Logo Center', 'noo-hermosa' ),
                    'header4'     => esc_html__( 'Header Transparent With Logo Top Center','noo-hermosa'),
                    'header5'     => esc_html__( 'Header Logo Left','noo-hermosa')
                )
            )
        );

        // Control: Header 2 Top Menu
        $helper->add_control(
            'noo_header_nav_top_menu',
            'noo_switch',
            esc_html__( 'Top Menu (Only use in Header Logo Transparent)', 'noo-hermosa' ),
            1,
            array(
				'json' => array(
					'on_child_options'   => 'noo_header_nav_top_menu_hide_scroll',
				),
				'preview_type' => 'custom'
			)
        );

        // Control: Navigation Icon Cart Hide When scroll
        $helper->add_control(
            'noo_header_nav_top_menu_hide_scroll',
            'noo_switch',
            esc_html__( 'Hide Top Menu When scroll', 'noo-hermosa' ),
            0,
            array(
                'preview_type' => 'custom'
            )
        );

		// Sub-Section: Navigation Bar
		$helper->add_sub_section(
			'noo_header_sub_section_nav',
			esc_html__( 'Navigation Bar', 'noo-hermosa' ),
			esc_html__( 'Adjust settings for Navigation Bar. You also can customize some settings for the Toggle Button on Mobile in this section.', 'noo-hermosa' )
		);

		// Control: NavBar Position
		$helper->add_control(
			'noo_header_nav_position',
			'noo_radio',
			esc_html__( 'NavBar Position', 'noo-hermosa' ),
			'fixed_top', 
			array(
				'choices' => array(
					'static_top'   => esc_html__( 'Static Top', 'noo-hermosa' ),
					// 'fixed_scroll' => esc_html__( 'Fix When Scroll To Top', 'noo-hermosa' ),
					'fixed_top'    => esc_html__( 'Fixed Top', 'noo-hermosa' ),
				),
				'json' => array(
					'child_options' => array(
						'fixed_top'   => 'noo_header_fixed_bg_color'
					)
				)
			)
		);

        // Control: Navigation background Color
        $helper->add_control(
            'noo_header_bg_color',
            'color_control',
            esc_html__( 'Background  Navigation', 'noo-hermosa' ),
            '',
            array(
                'preview_type' => 'custom'
            )
        );

        // Control: Navigation Fixed background Color
        $helper->add_control(
            'noo_header_fixed_bg_color',
            'color_control',
            esc_html__( 'Background Navigation When Scroll', 'noo-hermosa' ),
            '',
            array(
                'preview_type' => 'custom'
            )
        );

		// Control: Divider 2
		$helper->add_control( 'noo_header_nav_divider_2', 'divider', '' );

		 // Control: Navigation Icon Search
        $helper->add_control(
            'noo_header_nav_icon_search',
            'noo_switch',
            esc_html__( 'Icon Search', 'noo-hermosa' ),
            1,
            array(
				'json' => array(
					'on_child_options'   => 'noo_header_nav_icon_search_hide_scroll',
				)
			)
        );

        // Control: Navigation Icon Search Hide When scroll
        $helper->add_control(
            'noo_header_nav_icon_search_hide_scroll',
            'noo_switch',
            esc_html__( 'Hide Icon Search When scroll', 'noo-hermosa' ),
            0
        );

        // Control: Navigation Icon Cart
        $helper->add_control(
            'noo_header_nav_icon_cart',
            'noo_switch',
            esc_html__( 'Icon Cart', 'noo-hermosa' ),
            1,
            array(
				'json' => array(
					'on_child_options'   => 'noo_header_nav_icon_cart_hide_scroll',
				)
			)
        );

        // Control: Navigation Icon Cart Hide When scroll
        $helper->add_control(
            'noo_header_nav_icon_cart_hide_scroll',
            'noo_switch',
            esc_html__( 'Hide Icon Cart When scroll', 'noo-hermosa' ),
            0
        );

		// Control: Divider 2
		$helper->add_control( 'noo_header_nav_divider_2', 'divider', '' );

		// Control: NavBar Height (px)
		$helper->add_control(
			'noo_header_nav_height',
			'ui_slider',
			esc_html__( 'NavBar Height (px)', 'noo-hermosa' ),
			'120',
			array(
				'json' => array(
					'data_min' => 20,
					'data_max' => 250,
				),
				'preview_type' => 'custom'
			)
		);

		// Control: NavBar Link Spacing (px)
		$helper->add_control(
			'noo_header_nav_link_spacing',
			'ui_slider',
			esc_html__( 'NavBar Link Spacing (px)', 'noo-hermosa' ),
			'20',
			array(
				'json' => array(
					'data_min' => 0,
					'data_max' => 50,
				),
				'preview_type' => 'custom'
			)
		);

		// Control: Custom NavBar Font
		$helper->add_control(
			'noo_header_custom_nav_font',
			'noo_switch',
			esc_html__( 'Use Custom NavBar Font and Color?', 'noo-hermosa' ),
			0,
			array( 'json' => array( 
					'on_child_options'  => 'noo_header_nav_font,
											noo_header_nav_link_color,
											noo_header_nav_link_hover_color,
											noo_header_nav_font_size,
											noo_header_nav_uppercase'
				),
				'preview_type' => 'update_css',
				'preview_params' => array( 'css' => 'header' )
			)
		);

		// Control: NavBar font
		$helper->add_control(
			'noo_header_nav_font',
			'google_fonts',
			esc_html__( 'NavBar Font', 'noo-hermosa' ),
			noo_hermosa_get_theme_default( 'headings_font_family' ),
			array(
				'weight' => '',
				'style'	=> '',
				'preview_type' => 'custom',
			)
		);

		// Control: NavBar Font Size
		$helper->add_control(
			'noo_header_nav_font_size',
			'ui_slider',
			esc_html__( 'Font Size (px)', 'noo-hermosa' ),
			'15',
			array(
				'json' => array(
					'data_min' => 9,
					'data_max' => 30,
				),
				'preview_type' => 'custom'
			)
		);

		// Control: NavBar Link Color
		$helper->add_control(
			'noo_header_nav_link_color',
			'color_control',
			esc_html__( 'Link Color', 'noo-hermosa' ),
			'',
			array(
				'preview_type' => 'custom'
			)
		);

		// Control: NavBar Link Hover Color
		$helper->add_control(
			'noo_header_nav_link_hover_color',
			'color_control',
			esc_html__( 'Link Hover Color', 'noo-hermosa' ),
			'',
			array(
				'preview_type' => 'custom'
			)
		);

		// Control: NavBar Font Uppercase
		$helper->add_control(
			'noo_header_nav_uppercase',
			'checkbox',
			esc_html__( 'Transform to Uppercase', 'noo-hermosa' ),
			1,
			array(
				'preview_type' => 'custom'
			)
		);

		// Sub-Section: Logo
		$helper->add_sub_section(
			'noo_header_sub_section_logo',
			esc_html__( 'Logo', 'noo-hermosa' ),
			esc_html__( 'All the settings for Logo go here. If you do not use Image for Logo, plain text will be used.', 'noo-hermosa' )
		);

		// Control: Use Image for Logo
		$helper->add_control(
			'noo_header_use_image_logo',
			'noo_switch',
			esc_html__( 'Use Image for Logo?', 'noo-hermosa' ),
			0,
			array(
				'json' => array(
					'on_child_options'   => 'noo_header_logo_image,noo_header_logo_image_height,noo_header_logo_image_transparent,noo_header_logo_image_transparent_height,noo_header_logo_image_in_top',
					'off_child_options'  => 'blogname
										,noo_header_logo_font
                                        ,noo_header_logo_font_size
                                        ,noo_header_logo_font_color
                                        ,noo_header_logo_uppercase'
				)
			)
		);

		// Control: Blog Name
		$helper->add_control(
			'blogname',
			'text',
			esc_html__( 'Blog Name', 'noo-hermosa' ),
			get_bloginfo( 'name' ),
			array(
				'preview_type' => 'custom'
			)
		);

		// Control: Logo font
		$helper->add_control(
			'noo_header_logo_font',
			'google_fonts',
			esc_html__( 'Logo Font', 'noo-hermosa' ),
			noo_hermosa_get_theme_default( 'logo_font_family' ),
			array(
				'weight' => '700',
				'style'	=> 'normal',
				'preview_type' => 'custom'
			)
		);

		// Control: Logo Font Size
		$helper->add_control(
			'noo_header_logo_font_size',
			'ui_slider',
			esc_html__( 'Font Size (px)', 'noo-hermosa' ),
			'30',
			array(
				'json' => array(
					'data_min' => 15,
					'data_max' => 80,
				),
				'preview_type' => 'custom'
			)
		);

		// Control: Logo Font Color
		$helper->add_control(
			'noo_header_logo_font_color',
			'color_control',
			esc_html__( 'Font Color', 'noo-hermosa' ),
			noo_hermosa_get_theme_default( 'logo_color' ),
			array(
				'preview_type' => 'custom'
			)
		);

		// Control: Logo Font Uppercase
		$helper->add_control(
			'noo_header_logo_uppercase',
			'checkbox',
			esc_html__( 'Transform to Uppercase', 'noo-hermosa' ),
			0,
			array(
				'preview_type' => 'custom'
			)
		);

		// Control: Logo Image
		$helper->add_control(
			'noo_header_logo_image',
			'noo_image',
			esc_html__( 'Upload Your Logo', 'noo-hermosa' ),
			'',
			array(
				'preview_type' => 'custom'
			)
		);

		// Control: Logo Image Height
		$helper->add_control(
			'noo_header_logo_image_height',
			'ui_slider',
			esc_html__( 'Image Height (px)', 'noo-hermosa' ),
			'76',
			array(
				'json' => array(
					'data_min' => 15,
					'data_max' => 150,
				),
				'preview_type' => 'custom'
			)
		);

		// Control: Logo Image Transparent
		$helper->add_control(
			'noo_header_logo_image_transparent',
			'noo_image',
			esc_html__( 'Upload Your Logo Transparent', 'noo-hermosa' ),
			'',
			array(
				'preview_type' => 'custom'
			)
		);
		//Control: Logo Image transparent in top
        $helper->add_control(
            'noo_header_logo_image_in_top',
            'noo_image',
            esc_html__( 'Upload Your Logo Transparent In Top', 'noo-hermosa' ),
            '',
            array(
                'preview_type' => 'custom'
            )
        );

		// Control: Logo Image Transparent Height
		$helper->add_control(
			'noo_header_logo_image_transparent_height',
			'ui_slider',
			esc_html__( 'Image Transparent Height (px)', 'noo-hermosa' ),
			'114',
			array(
				'json' => array(
					'data_min' => 15,
					'data_max' => 150,
				),
				'preview_type' => 'custom'
			)
		);

		// Sub-Section: Top Bar
		$helper->add_sub_section(
			'noo_header_sub_section_topbar',
			esc_html__( 'Top Bar', 'noo-hermosa' ),
			esc_html__( 'All the settings for Top Bar go here.', 'noo-hermosa' )
		);
		// Control: Enable Top Bar
		$helper->add_control(
			'noo_header_topbar_on',
			'noo_switch',
			esc_html__( 'Show Top Bar', 'noo-hermosa' ),
			0,
			array(
				'json' => array(
					'on_child_options' => 'noo_header_topbar_phone, noo_header_topbar_email, noo_header_topbar_cart'
				)
			)
		);
		// Control: Phone
		$helper->add_control(
			'noo_header_topbar_phone',
			'text',
			esc_html__('Phone number', 'noo-hermosa')
		);
		// Control: Email
		$helper->add_control(
			'noo_header_topbar_email',
			'text',
			esc_html__('Email', 'noo-hermosa')
		);
		// Control: Show cart
		$helper->add_control(
			'noo_header_topbar_cart',
			'noo_switch',
			esc_html__('Show cart', 'noo-hermosa'),
			0
		);

	}
add_action( 'customize_register', 'noo_hermosa_customizer_register_options_header' );
endif;

// 5. Footer options.
if ( ! function_exists( 'noo_hermosa_customizer_register_options_footer' ) ) :
	function noo_hermosa_customizer_register_options_footer( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Footer
		$helper->add_section(
			'noo_hermosa_customizer_section_footer',
			esc_html__( 'Footer', 'noo-hermosa' ),
			esc_html__( 'Footer contains Widgetized area and Footer Bottom. You can change any parts.', 'noo-hermosa' )
		);

		// Control: Footer Logo
		// $helper->add_control(
		// 	'noo_footer_top_logo',
		// 	'noo_image',
		// 	esc_html__( 'Upload Footer Logo', 'noo-hermosa' ),
		// 	''
		// );

		// $menus        = wp_get_nav_menus();
		// $menu_choices = array( 0 => esc_html__( '&mdash; Select &mdash;', 'noo-hermosa' ) );
		// foreach ( $menus as $menu ) {
		// 	$menu_choices[ $menu->term_id ] = wp_html_excerpt( $menu->name, 40, '&hellip;' );
		// }

		// Control: Footer Menu
		// $helper->add_control(
		// 	'nav_menu_locations[footer-menu]',
		// 	'select',
		// 	esc_html__( 'Footer Menu', 'noo-hermosa' ),
		// 	'',
		// 	array(
		// 		'choices' => $menu_choices
		// 	)
		// );

		// Control: Show Footer Social Icons
		// $helper->add_control(
		// 	'noo_footer_top_social',
		// 	'checkbox',
		// 	esc_html__( 'Show Footer Social Icons', 'noo-hermosa' ),
		// 	1
		// );

		// // Control: Divider 1
		// $helper->add_control( 'noo_footer_divider_1', 'divider', '' );

		/**
		 * Control: Footer Style
		 */
        $helper->add_control(
			'noo_footer_style',
			'noo_radio',
			esc_html__( 'Footer Style', 'noo-hermosa' ),
			'style-1',
			array(
				'choices' => array(
					'style-1' => esc_html__( 'Footer Default', 'noo-hermosa' ),
					'style-2' => esc_html__( 'Footer With Map', 'noo-hermosa' ),
                    'style-3' => esc_html__( 'Footer Style Dark','noo-hermosa')
				),
				'json' => array(
					'child_options' => array(
						'style-2'   => 'noo_map_lat, noo_map_lng, noo_map_zoom, noo_map_icon',
					)
				)
			)
		);

		// Control: Latitude
		$helper->add_control(
			'noo_map_lat',
			'text',
			esc_html__( 'Latitude', 'noo-hermosa' ),
			'51.508742',
			array()
		);

		// Control: Longitude
		$helper->add_control(
			'noo_map_lng',
			'text',
			esc_html__( 'Longitude', 'noo-hermosa' ),
			'-0.120850',
			array()
		);

		// Control: Zoom
		$helper->add_control(
			'noo_map_zoom',
			'text',
			esc_html__( 'Zoom', 'noo-hermosa' ),
			14,
			array()
		);

		// Control: Icon Map
		$helper->add_control(
			'noo_map_icon',
			'noo_image',
			esc_html__( 'Icon Map', 'noo-hermosa' ),
			'',
			array( 'preview_type' => 'custom' )
		);

		$helper->add_control( 'noo_footer_divider_1', 'divider', '' );

		// Control: Footer Columns (Widgetized)
		$helper->add_control(
			'noo_footer_widgets',
			'select',
			esc_html__( 'Footer Columns (Widgetized)', 'noo-hermosa' ),
			'3',
			array(
				'choices' => array(
					0       => esc_html__( 'None (No Footer Main Content)', 'noo-hermosa' ),
					1     => esc_html__( 'One', 'noo-hermosa' ),
					2     => esc_html__( 'Two', 'noo-hermosa' ),
					3     => esc_html__( 'Three', 'noo-hermosa' ),
					4     => esc_html__( 'Four', 'noo-hermosa' )
				)
			)
		);

		// Control: Divider 2
		$helper->add_control( 'noo_footer_divider_2', 'divider', '' );

		// Control: Bottom Bar Content
		$helper->add_control(
			'noo_bottom_bar_content',
			'textarea',
			esc_html__( 'Footer Bottom Content (HTML)', 'noo-hermosa' ),
			'&copy; 2015. Designed with <i class="fa fa-heart text-primary" ></i> by NooTheme',
			array(
				'preview_type' => 'custom'
			)
		);

	}
add_action( 'customize_register', 'noo_hermosa_customizer_register_options_footer' );
endif;

// 6. WP Sidebar options.
if ( ! function_exists( 'noo_hermosa_customizer_register_options_sidebar' ) ) :
	function noo_hermosa_customizer_register_options_sidebar( $wp_customize ) {

		global $wp_version;
		if ( $wp_version >= 4.0 ) {
			// declare helper object.
			$helper = new NOO_Customizer_Helper( $wp_customize );

			// Change the sidebar panel priority
			$widget_panel = $wp_customize->get_panel('widgets');
			if(!empty($widget_panel)) {
				$widget_panel->priority = $helper->get_new_section_priority();
			}
		}
	}
add_action( 'customize_register', 'noo_hermosa_customizer_register_options_sidebar' );
endif;

// 7. Blog options.
if ( ! function_exists( 'noo_hermosa_customizer_register_options_blog' ) ) :
	function noo_hermosa_customizer_register_options_blog( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Blog
		$helper->add_section(
			'noo_hermosa_customizer_section_blog',
			esc_html__( 'Blog', 'noo-hermosa' ),
			esc_html__( 'In this section you have settings for your Blog page, Archive page and Single Post page.', 'noo-hermosa' ),
			true
		);

		// Sub-section: Blog Page (Index Page)
		$helper->add_sub_section(
			'noo_blog_sub_section_blog_page',
			esc_html__( 'Post List', 'noo-hermosa' ),
			esc_html__( 'Choose Layout settings for your Post List', 'noo-hermosa' )
		);

		// Control: Blog Layout
		$helper->add_control(
			'noo_blog_layout',
			'noo_radio',
			esc_html__( 'Blog Layout', 'noo-hermosa' ),
			'sidebar',
			array(
				'choices' => array(
					'fullwidth'   => esc_html__( 'Full-Width', 'noo-hermosa' ),
					'sidebar'   => esc_html__( 'With Right Sidebar', 'noo-hermosa' ),
					'left_sidebar'   => esc_html__( 'With Left Sidebar', 'noo-hermosa' )
				),
				'json' => array(
					'child_options' => array(
						'fullwidth'   => '',
						'sidebar'   => 'noo_blog_sidebar',
						'left_sidebar'   => 'noo_blog_sidebar'
					)
				)
			)
		);

		// Control: Blog Sidebar
		$helper->add_control(
			'noo_blog_sidebar',
			'widgets_select',
			esc_html__( 'Blog Sidebar', 'noo-hermosa' ),
			'sidebar-main'
		);

		// Control: Divider 1
		$helper->add_control( 'noo_blog_divider_1', 'divider', '' );

		// Control: Heading Title
		$helper->add_control(
			'noo_blog_heading_title',
			'text',
			esc_html__( 'Heading Title', 'noo-hermosa' ),
			esc_html__('Blog', 'noo-hermosa')
		);

		// Control: Heading Title
		$helper->add_control(
			'noo_blog_heading_desc',
			'text',
			esc_html__( 'Heading Description', 'noo-hermosa' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Heading Image
		$helper->add_control(
			'noo_blog_heading_image',
			'noo_image',
			esc_html__( 'Heading Background Image', 'noo-hermosa' ),
			''
		);

		// Control: Divider 2
		$helper->add_control( 'noo_blog_divider_2', 'divider', '' );

		// Control: Excerpt Length
		$helper->add_control(
			'noo_blog_excerpt_length',
			'text',
			esc_html__( 'Excerpt Length', 'noo-hermosa' ),
			'60'
		);

		// Sub-section: Single Post
		$helper->add_sub_section(
			'noo_blog_sub_section_post',
			esc_html__( 'Single Post', 'noo-hermosa' )
		);

		// Control: Post Layout
		$helper->add_control(
			'noo_blog_post_layout',
			'noo_same_as_radio',
			esc_html__( 'Post Layout', 'noo-hermosa' ),
			'same_as_blog',
			array(
				'choices' => array(
					'same_as_blog'   => esc_html__( 'Same as Blog Layout', 'noo-hermosa' ),
					'fullwidth'   => esc_html__( 'Full-Width', 'noo-hermosa' ),
					'sidebar'   => esc_html__( 'With Right Sidebar', 'noo-hermosa' ),
					'left_sidebar'   => esc_html__( 'With Left Sidebar', 'noo-hermosa' ),
				),
				'json' => array(
					'child_options' => array(
						'fullwidth'   => '',
						'sidebar'   => 'noo_blog_post_sidebar',
						'left_sidebar'   => 'noo_blog_post_sidebar',
					)
				)
			)
		);

		// Control: Post Sidebar
		$helper->add_control(
			'noo_blog_post_sidebar',
			'widgets_select',
			esc_html__( 'Post Sidebar', 'noo-hermosa' ),
			'sidebar-main'
		);
		
		// Control: Divider 1
		$helper->add_control( 'noo_blog_post_divider_1', 'divider', '' );
		// COntrol: Related Post
		// $helper->add_control(
		// 	'noo_blog_post_related',
		// 	'noo_switch',
		// 	esc_html__( 'Enable Related Post', 'noo-hermosa' ),
		// 	1
		// );
		
		// Control: Divider 1
		$helper->add_control( 'noo_blog_post_divider_1', 'divider', '' );
		
		// Control: Show Post Meta
		$helper->add_control(
			'noo_blog_post_show_post_meta',
			'checkbox',
			esc_html__( 'Show Post Meta', 'noo-hermosa' ),
			1
		);


		// Control: Show Author Bio
		$helper->add_control(
			'noo_blog_post_author_bio',
			'checkbox',
			esc_html__( 'Show Author\'s Bio', 'noo-hermosa' ),
			1
		);

		// Control: Divider 2
		$helper->add_control( 'noo_blog_post_divider_2', 'divider', '' );

		// Control: Enable Social Sharing
		$helper->add_control(
			'noo_blog_social',
			'noo_switch',
			esc_html__( 'Enable Social Sharing', 'noo-hermosa' ),
			1,
			array(
				'json' => array( 'on_child_options' => 'noo_blog_social_facebook,
		                                                noo_blog_social_twitter,
		                                                noo_blog_social_google,
		                                                noo_blog_social_pinterest,
		                                                noo_blog_social_linkedin'
				)
			)
		);

		// Control: Sharing Title
		// $helper->add_control(
		// 	'noo_blog_social_title',
		// 	'text',
		// 	esc_html__( 'Sharing Title', 'noo-hermosa' ),
		// 	esc_html__( 'Share This Post', 'noo-hermosa' )
		// );

		// Control: Facebook Share
		$helper->add_control(
			'noo_blog_social_facebook',
			'checkbox',
			esc_html__( 'Facebook Share', 'noo-hermosa' ),
			1
		);

		// Control: Twitter Share
		$helper->add_control(
			'noo_blog_social_twitter',
			'checkbox',
			esc_html__( 'Twitter Share', 'noo-hermosa' ),
			1
		);

		// Control: Google+ Share
		$helper->add_control(
			'noo_blog_social_google',
			'checkbox',
			esc_html__( 'Google+ Share', 'noo-hermosa' ),
			1
		);

		// Control: Pinterest Share
		$helper->add_control(
			'noo_blog_social_pinterest',
			'checkbox',
			esc_html__( 'Pinterest Share', 'noo-hermosa' ),
			0
		);

		// Control: LinkedIn Share
		$helper->add_control(
			'noo_blog_social_linkedin',
			'checkbox',
			esc_html__( 'LinkedIn Share', 'noo-hermosa' ),
			0
		);
	}
add_action( 'customize_register', 'noo_hermosa_customizer_register_options_blog' );
endif;

// 8. Custom Post Type options
if ( ! function_exists( 'noo_hermosa_customizer_register_options_post_type' ) ) :
	function noo_hermosa_customizer_register_options_post_type( $wp_customize ) {
		global $noo_post_types;
		if( empty( $noo_post_types ) ) return;

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		foreach ($noo_post_types as $post_type => $args) {
			if( !isset( $args['customizer'] ) || empty( $args['customizer'] ) )
				continue;

			$pt_customizer = $args['customizer'];

			$pt_customizer['panel'] = isset( $pt_customizer['panel'] ) ? $pt_customizer['panel'] : array( 'single' );

			$helper->add_section(
				array(
					'id' => "noo_hermosa_customizer_section_{$post_type}",
					'label' => $args['name'],
					'description' => sprintf( esc_html__( 'Firstly assign a page as your %s page from dropdown list. %s page can be any page. Once you chose a page as %s Page, its slug will be your %s\'s main slug.', 'noo-hermosa' ), $args['name'], $args['name'], $args['name'], $args['name'] ),
					'is_panel' => count( $pt_customizer['panel'] ) > 1
				)
			);

			if( in_array('list', $pt_customizer['panel'] ) ) {
				// Sub-section: List
				$helper->add_sub_section(
					"{$post_type}_archive_sub_section",
					sprintf( esc_html__( 'List %s', 'noo-hermosa' ), $args['name'] )
				);
			}

			if( in_array('page', $pt_customizer) ) {
				// Control: Post type Page
				$helper->add_control(
					array(
						'id' => "{$post_type}_archive_page",
						'type' => 'pages_select',
						'label' => sprintf( esc_html__( '%s Page', 'noo-hermosa' ), $args['name'] ),
						'default' => '',
					)
				);
			}

			if( in_array('heading-title', $pt_customizer) ) {
				$default = isset( $args['heading-title'] ) ? $args['heading-title'] : sprintf( esc_html__( '%s List', 'noo-hermosa' ), $args['name'] );

				// Control: Heading Title
				$helper->add_control(
					array(
						'id' => "{$post_type}_heading_title",
						'type' => 'text',
						'label' => sprintf( esc_html__( '%s Heading Title', 'noo-hermosa' ), $args['name'] ),
						'default' => $default,
					)
				);
			}

			if( in_array('heading-image', $pt_customizer) ) {
				// Control: Heading Title
				$helper->add_control(
					array(
						'id' => "{$post_type}_heading_image",
						'type' => 'noo_image',
						'label' => sprintf( esc_html__( '%s Heading Background Image', 'noo-hermosa' ), $args['name'] ),
						'default' => '',
					)
				);
			}

			if( in_array('list-layout', $pt_customizer) ) {
				// Control: List Layout
				$helper->add_control(
					array(
						'id' => "{$post_type}_archive_layout",
						'type' => 'noo_radio',
						'label' => sprintf( esc_html__( '%s List Layout', 'noo-hermosa' ), $args['name'] ),
						'default' => 'sidebar',
						'control' => array(
								'choices' => array(
									'fullwidth'   => esc_html__( 'Full-Width', 'noo-hermosa' ),
									'sidebar'   => esc_html__( 'With Right Sidebar', 'noo-hermosa' ),
									'left_sidebar'   => esc_html__( 'With Left Sidebar', 'noo-hermosa' )
								),
								'json' => array(
									'child_options' => array(
										'fullwidth'   => '',
										'sidebar'   => "{$post_type}_archive_sidebar",
										'left_sidebar'   => "{$post_type}_archive_sidebar"
									)
								)
							),
					)
				);

				// Control: Event List Sidebar
				$helper->add_control(
					array(
						'id' => "{$post_type}_archive_sidebar",
						'type' => 'widgets_select',
						'label' => sprintf( esc_html__( '%s List Sidebar', 'noo-hermosa' ), $args['name'] ),
						'default' => 'sidebar-main',
					)
				);
			}

			if( in_array('layout', $pt_customizer) ) {
				// Control: List Layout
				$helper->add_control(
					array(
						'id' => "{$post_type}_archive_layout",
						'type' => 'noo_radio',
						'label' => sprintf( esc_html__( '%s Layout', 'noo-hermosa' ), $args['name'] ),
						'default' => 'sidebar',
						'control' => array(
								'choices' => array(
									'fullwidth'   => esc_html__( 'Full-Width', 'noo-hermosa' ),
									'sidebar'   => esc_html__( 'With Right Sidebar', 'noo-hermosa' ),
									'left_sidebar'   => esc_html__( 'With Left Sidebar', 'noo-hermosa' )
								),
								'json' => array(
									'child_options' => array(
										'fullwidth'   => '',
										'sidebar'   => "{$post_type}_archive_sidebar",
										'left_sidebar'   => "{$post_type}_archive_sidebar"
									)
								)
							),
					)
				);

				// Control: Event List Sidebar
				$helper->add_control(
					array(
						'id' => "{$post_type}_archive_sidebar",
						'type' => 'widgets_select',
						'label' => sprintf( esc_html__( '%s Sidebar', 'noo-hermosa' ), $args['name'] ),
						'default' => 'sidebar-main',
					)
				);
			}

			do_action( "{$post_type}_archive_customizer", $wp_customize );

			if( in_array('list_num', $pt_customizer) ) {
				// Control: Number of Item per Page
				$helper->add_control(
					array(
						'id' => "{$post_type}_num",
						'type' => 'ui_slider',
						'label' => esc_html__( 'Items Per Page', 'noo-hermosa' ),
						'8',
		 				'control' => array(
		 					'json' => array(
		 						'data_min'  => '4',
		 						'data_max'  => '50',
		 						'data_step' => '2'
		 					)
		 				),
					)
				);
			}

			if( in_array('single', $pt_customizer['panel'] ) ) {
				// Sub-section: Single
				$helper->add_sub_section(
					"{$post_type}_single_sub_section",
					sprintf( esc_html__( 'Single %s', 'noo-hermosa' ), $args['singular_name'] )
				);
			}

			if( in_array('single-layout', $pt_customizer) ) {
				// Control: Single Layout
				$helper->add_control(
					array(
						'id' => "{$post_type}_single_layout",
						'type' => 'noo_same_as_radio',
						'label' => sprintf( esc_html__( 'Single %s Layout', 'noo-hermosa' ), $args['singular_name'] ),
						'default' => "same_as_archive",
						'control' => array(
								'choices' => array(
									"same_as_archive"   => sprintf( esc_html__( 'Same as %s List Layout', 'noo-hermosa' ), $args['name'] ),
									'fullwidth'   => esc_html__( 'Full-Width', 'noo-hermosa' ),
									'sidebar'   => esc_html__( 'With Right Sidebar', 'noo-hermosa' ),
									'left_sidebar'   => esc_html__( 'With Left Sidebar', 'noo-hermosa' ),
								),
								'json' => array(
									'child_options' => array(
										'fullwidth'   => '',
										'sidebar'   => "{$post_type}_single_sidebar",
										'left_sidebar'   => "{$post_type}_single_sidebar",
									)
								)
							),
					)
				);

				// Control: Single Sidebar
				$helper->add_control(
					array(
						'id' => "{$post_type}_single_sidebar",
						'type' => 'widgets_select',
						'label' => sprintf( esc_html__( '%s Sidebar', 'noo-hermosa' ), $args['singular_name'] ),
						'default' => 'sidebar-main',
					)
				);
			}



			do_action( "{$post_type}_single_customizer", $wp_customize );

			if( in_array('single-social', $pt_customizer) ) {
				$helper->add_control(
				 	array(
						'id' => "{$post_type}_single_divider_1",
						'type' => 'divider'
					)
				);

				// Control: Enable Social Sharing
		        $helper->add_control(
		        	array(
						'id' => "{$post_type}_single_social",
						'type' => 'noo_switch',
						'label' => esc_html__( 'Enable Social Sharing', 'noo-hermosa' ),
						'default' => 1,
						'control' => array(
			                'json' => array( 'on_child_options' => "{$post_type}_single_social_facebook,
					                                                {$post_type}_single_social_twitter,
					                                                {$post_type}_single_social_google,
					                                                {$post_type}_single_social_pinterest,
					                                                {$post_type}_single_social_linkedin"
			                )
			            )
					)
		        );

				// Control: Facebook Share
				$helper->add_control(
					array(
						'id' => "{$post_type}_single_social_facebook",
						'type' => 'noo_switch',
						'label' => esc_html__( 'Facebook Share', 'noo-hermosa' ),
						'default' => 1,
					)
				);

				// Control: Twitter Share
				$helper->add_control(
					array(
						'id' => "{$post_type}_single_social_twitter",
						'type' => 'noo_switch',
						'label' => esc_html__( 'Twitter Share', 'noo-hermosa' ),
						'default' => 1,
					)
				);

				// Control: Google+ Share
				$helper->add_control(
					array(
						'id' => "{$post_type}_single_social_google",
						'type' => 'noo_switch',
						'label' => esc_html__( 'Google+ Share', 'noo-hermosa' ),
						'default' => 1,
					)
				);

				// Control: Pinterest Share
				$helper->add_control(
					array(
						'id' => "{$post_type}_single_social_pinterest",
						'type' => 'noo_switch',
						'label' => esc_html__( 'Pinterest Share', 'noo-hermosa' ),
						'default' => 1,
					)
				);

				// Control: LinkedIn Share
				$helper->add_control(
					array(
						'id' => "{$post_type}_single_social_linkedin",
						'type' => 'noo_switch',
						'label' => esc_html__( 'LinkedIn Share', 'noo-hermosa' ),
						'default' => 1,
					)
				);
			}
		}
        
	}
	add_action( 'customize_register', 'noo_hermosa_customizer_register_options_post_type' );
endif;

// 9. Classes options.
if ( ! function_exists( 'noo_hermosa_customizer_register_options_class' ) ) :
	function noo_hermosa_customizer_register_options_class( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Class
		$helper->add_section(
			'noo_hermosa_customizer_section_class',
			esc_html__( 'Class', 'noo-hermosa' ),
			esc_html__( 'Firstly assign a page as your class page from dropdown list. Class page can be any page. Once you chose a page as Class Page, its slug will be your Class\'s main slug.', 'noo-hermosa' ),
			true
		);

		// Sub-section: Classes
		$helper->add_sub_section(
			'noo_class_sub_section_classes',
			esc_html__( 'Classes Listing', 'noo-hermosa' )
		);

		// Control: Heading Title
		$helper->add_control(
			'noo_class_heading_title',
			'text',
			esc_html__( 'Class Heading Title', 'noo-hermosa' ),
			esc_html__('Class List', 'noo-hermosa'),
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Heading Title
		$helper->add_control(
			'noo_class_heading_desc',
			'text',
			esc_html__( 'Class Heading Description', 'noo-hermosa' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Heading Image
		$helper->add_control(
			'noo_classes_heading_image',
			'noo_image',
			esc_html__( 'Classes Heading Background Image', 'noo-hermosa' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Class List Layout
		$helper->add_control(
			'noo_classes_layout',
			'noo_radio',
			esc_html__( 'Classes List Layout', 'noo-hermosa' ),
			'sidebar',
			array(
				'choices' => array(
					'fullwidth'    => esc_html__( 'Full-Width', 'noo-hermosa' ),
					'sidebar'      => esc_html__( 'With Right Sidebar', 'noo-hermosa' ),
					'left_sidebar' => esc_html__( 'With Left Sidebar', 'noo-hermosa' )
				),
				'json' => array(
					'child_options' => array(
						'sidebar'   => 'noo_classes_sidebar',
						'left_sidebar'   => 'noo_classes_sidebar'
					)
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Class List Sidebar
		$helper->add_control(
			'noo_classes_sidebar',
			'widgets_select',
			esc_html__( 'Class List Sidebar', 'noo-hermosa' ),
			'sidebar-main',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Divider
		$helper->add_control( 'noo_classes_divider_0', 'divider', '' );

		// Control: Meta Date
		$helper->add_control(
			'noo_classes_meta_date',
			'checkbox',
			esc_html__( 'Show Class Date', 'noo-hermosa' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Meta Time
		$helper->add_control(
			'noo_classes_meta_time',
			'checkbox',
			esc_html__( 'Show Class Time', 'noo-hermosa' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Meta Trainer
		$helper->add_control(
			'noo_classes_meta_trainer',
			'checkbox',
			esc_html__( 'Show Class Trainer', 'noo-hermosa' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Meta Address
		$helper->add_control(
			'noo_classes_meta_address',
			'checkbox',
			esc_html__( 'Show Class Address', 'noo-hermosa' ),
			0,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Divider
		$helper->add_control( 'noo_classes_divider_1', 'divider', '' );

		// Control: Class Filter Title
		$helper->add_control(
			'noo_classes_filter_title',
			'text',
			esc_html__( 'Filter Title', 'noo-hermosa' ),
			esc_html__( 'Search Classes', 'noo-hermosa' ),
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Show Level Filter
		$helper->add_control(
			'noo_classes_show_level_filter',
			'noo_switch',
			esc_html__( 'Show Level Filter', 'noo-hermosa' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Show Category Filter
		$helper->add_control(
			'noo_classes_show_category_filter',
			'noo_switch',
			esc_html__( 'Show Category Filter', 'noo-hermosa' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Show Trainer Filter
		$helper->add_control(
			'noo_classes_show_trainer_filter',
			'noo_switch',
			esc_html__( 'Show Trainer Filter', 'noo-hermosa' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Show Trainer Filter
		$helper->add_control(
			'noo_classes_show_days_filter',
			'noo_switch',
			esc_html__( 'Show Filter By Days', 'noo-hermosa' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Sub-section: Single Class
		$helper->add_sub_section(
			'noo_class_sub_section_single_class',
			esc_html__( 'Single Class', 'noo-hermosa' )
		);

		// Control: Heading Image
		$helper->add_control(
			'noo_class_heading_image',
			'noo_image',
			esc_html__( 'Class Detail Heading Background Image', 'noo-hermosa' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Class Layout
		$helper->add_control(
			'noo_class_layout',
			'noo_radio',
			esc_html__( 'Single Class Layout', 'noo-hermosa' ),
			'sidebar',
			array(
				'choices' => array(
					// 'same_as_classes'   => esc_html__( 'Same as Classes List Layout', 'noo-hermosa' ),
					// 'fullwidth'   => esc_html__( 'Full-Width', 'noo-hermosa' ),
					'sidebar'   => esc_html__( 'With Right Sidebar', 'noo-hermosa' ),
					'left_sidebar'   => esc_html__( 'With Left Sidebar', 'noo-hermosa' ),
				),
				// 'json' => array(
				// 	'child_options' => array(
				// 		// 'fullwidth'   => '',
				// 		'sidebar'   => 'noo_class_sidebar',
				// 		'left_sidebar'   => 'noo_class_sidebar',
				// 	)
				// )
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Class Sidebar
		$helper->add_control(
			'noo_class_sidebar',
			'widgets_select',
			esc_html__( 'Classes Sidebar', 'noo-hermosa' ),
			'sidebar-main',
			array(),
			array( 'transport' => 'postMessage' )
		);
		// Control: Class Open Date Display
		$helper->add_control(
			'noo_class_open_date',
			'select',
			esc_html__( 'Open Date Display', 'noo-hermosa' ),
			'open',
			array(
				'choices' => array(
					'open' => esc_html__( 'Only Open Date', 'noo-hermosa' ),
					'next' => esc_html__( 'Only Next Class Date', 'noo-hermosa' ),
					'all'  => esc_html__( 'Both Open Date & Next Class Date', 'noo-hermosa' ),
				)
			),
			array( 'transport' => 'postMessage' )
		);
        // Control: Enable Social Sharing
        $helper->add_control(
            'noo_class_social',
            'noo_switch',
            esc_html__( 'Enable Social Sharing', 'noo-hermosa' ),
            1,
            array(
                'json' => array( 'on_child_options' => 'noo_class_social_facebook,
		                                                noo_class_social_twitter,
		                                                noo_class_social_google,
		                                                noo_class_social_pinterest,
		                                                noo_class_social_linkedin'
                )
            ),
            array( 'transport' => 'postMessage' )
        );
        // Control: Sharing Title
        $helper->add_control(
            'noo_class_social_title',
            'text',
            esc_html__( 'Sharing Title', 'noo-hermosa' ),
            esc_html__( 'Share This Post', 'noo-hermosa' ),
            array(),
            array( 'transport' => 'postMessage' )
        );
		// Control: Facebook Share
		$helper->add_control(
			'noo_class_social_facebook',
			'checkbox',
			esc_html__( 'Facebook Share', 'noo-hermosa' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Twitter Share
		$helper->add_control(
			'noo_class_social_twitter',
			'checkbox',
			esc_html__( 'Twitter Share', 'noo-hermosa' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Google+ Share
		$helper->add_control(
			'noo_class_social_google',
			'checkbox',
			esc_html__( 'Google+ Share', 'noo-hermosa' ),
			1,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Pinterest Share
		$helper->add_control(
			'noo_class_social_pinterest',
			'checkbox',
			esc_html__( 'Pinterest Share', 'noo-hermosa' ),
			0,
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: LinkedIn Share
		$helper->add_control(
			'noo_class_social_linkedin',
			'checkbox',
			esc_html__( 'LinkedIn Share', 'noo-hermosa' ),
			0,
			array(),
			array( 'transport' => 'postMessage' )
		);
	}
	add_action( 'customize_register', 'noo_hermosa_customizer_register_options_class' );
endif;

// 10. Trainer options.
if ( ! function_exists( 'noo_hermosa_customizer_register_options_trainer' ) ) :
	function noo_hermosa_customizer_register_options_trainer( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: trainer
		$helper->add_section(
			'noo_customizer_section_trainer',
			esc_html__( 'Trainer', 'noo-hermosa' ),
			esc_html__( 'Firstly assign a page as your trainer page from dropdown list. Once you chose a page as Trainer Page, its slug will be your Trainer\'s main slug.', 'noo-hermosa' )
		);

		// Control: Heading Title
		$helper->add_control(
			'noo_trainer_heading_title',
			'text',
			esc_html__( 'Trainer Heading Title', 'noo-hermosa' ),
			esc_html__('Trainer List', 'noo-hermosa'),
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Heading Title
		$helper->add_control(
			'noo_trainer_heading_desc',
			'text',
			esc_html__( 'Trainer Heading Description', 'noo-hermosa' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Heading Image
		$helper->add_control(
			'noo_trainer_heading_image',
			'noo_image',
			esc_html__( 'Trainer Heading Background Image', 'noo-hermosa' ),
			'',
			array(),
			array( 'transport' => 'postMessage' )
		);

		// Control: Trainer Layout
		$helper->add_control(
			'noo_trainer_layout',
			'noo_radio',
			esc_html__( 'Trainer Page Layout', 'noo-hermosa' ),
			'fullwidth',
			array(
				'choices' => array(
					'fullwidth'    => esc_html__( 'Full-Width', 'noo-hermosa' ),
					'sidebar'      => esc_html__( 'With Right Sidebar', 'noo-hermosa' ),
					'left_sidebar' => esc_html__( 'With Left Sidebar', 'noo-hermosa' ),
				),
				'json' => array(
					'child_options' => array(
						'fullwidth'    => '',
						'sidebar'      => 'noo_trainer_sidebar',
						'left_sidebar' => 'noo_trainer_sidebar',
					)
				)
			),
			array( 'transport' => 'postMessage' )
		);

		// Control: Trainer Sidebar
		$helper->add_control(
			'noo_trainer_sidebar',
			'widgets_select',
			esc_html__( 'Trainer Sidebar', 'noo-hermosa' ),
			'sidebar-main',
			array(),
			array( 'transport' => 'postMessage' )
		);
		
	}
	add_action( 'customize_register', 'noo_hermosa_customizer_register_options_trainer' );
endif;

// 11. WooCommerce options.
 if( NOO_WOOCOMMERCE_EXIST ) :
 	if ( ! function_exists( 'noo_hermosa_customizer_register_options_woocommerce' ) ) :
 		function noo_hermosa_customizer_register_options_woocommerce( $wp_customize ) {

 			// declare helper object.
 			$helper = new NOO_Customizer_Helper( $wp_customize );

 			// Section: Revolution Slider
 			$helper->add_section(
 				'noo_hermosa_customizer_section_shop',
 				esc_html__( 'WooCommerce', 'noo-hermosa' ),
 				'',
 				true
 			);

 			// Sub-section: Shop Page
 			$helper->add_sub_section(
 				'noo_woocommerce_sub_section_shop_page',
 				esc_html__( 'Shop Page', 'noo-hermosa' ),
 				esc_html__( 'Choose Layout and Headline Settings for your Shop Page.', 'noo-hermosa' )
 			);

 			// Control: Shop Layout
 			$helper->add_control(
 				'noo_shop_layout',
 				'noo_radio',
 				esc_html__( 'Shop Layout', 'noo-hermosa' ),
 				'fullwidth',
 				array(
 					'choices' => array(
 						'fullwidth'   => esc_html__( 'Full-Width', 'noo-hermosa' ),
 						'sidebar'   => esc_html__( 'With Right Sidebar', 'noo-hermosa' ),
 						'left_sidebar'   => esc_html__( 'With Left Sidebar', 'noo-hermosa' )
 					),
 					'json' => array(
 						'child_options' => array(
 							'fullwidth'   => '',
 							'sidebar'   => 'noo_shop_sidebar',
 							'left_sidebar'   => 'noo_shop_sidebar',
 						)
 					)
 				)
 			);

 			// Control: Shop Sidebar
 			$helper->add_control(
 				'noo_shop_sidebar',
 				'widgets_select',
 				esc_html__( 'Shop Sidebar', 'noo-hermosa' ),
 				''
 			);

 			// Control: Divider 1
 			$helper->add_control( 'noo_shop_divider_1', 'divider', '' );

 			// Control: Heading Title
 			$helper->add_control(
 				'noo_shop_heading_title',
 				'text',
 				esc_html__( 'Shop Heading Title', 'noo-hermosa' ),
 				esc_html__('Shop', 'noo-hermosa')
 			);

 			// Control: Heading Title
			$helper->add_control(
				'noo_shop_heading_desc',
				'text',
				esc_html__( 'Shop Heading Description', 'noo-hermosa' ),
				'',
				array(),
				array( 'transport' => 'postMessage' )
			);

 			// Control: Heading Image
 			$helper->add_control(
 				'noo_shop_heading_image',
 				'noo_image',
 				esc_html__( 'Heading Background Image', 'noo-hermosa' ),
 				''
 			);

 			$helper->add_control(
 				'noo_shop_default_layout',
 				'noo_radio',
 				esc_html__( 'Shop Default View Style', 'noo-hermosa' ),
 				'grid',
 				array(
 					'choices' => array(
 						'grid'   => esc_html__( 'Grid', 'noo-hermosa' ),
 						'list'   => esc_html__( 'List', 'noo-hermosa' ),
 					),
 				)
 			);

 			 $helper->add_control(
 			 	'noo_shop_grid_column',
 			 	'ui_slider',
 			 	esc_html__( 'Products Grid Columns', 'noo-hermosa' ),
 			 	'4',
 			 	array(
 			 		'json' => array(
 			 			'data_min'  => 1,
 			 			'data_max'  => 4,
 			 			'data_step' => 1
 			 		)
 			 	)
 			 );

 			// Control: Number of Product per Page
 			$helper->add_control(
 				'noo_shop_num',
 				'ui_slider',
 				esc_html__( 'Products Per Page', 'noo-hermosa' ),
 				'12',
 				array(
 					'json' => array(
 						'data_min'  => 4,
 						'data_max'  => 50,
 						'data_step' => 2
 					)
 				)
 			);

 			// Sub-section: Single Product
 			$helper->add_sub_section(
 				'noo_woocommerce_sub_section_product',
 				esc_html__( 'Single Product', 'noo-hermosa' )
 			);

 			// Control: Heading Image
 			$helper->add_control(
 				'noo_woocommerce_product_heading_image',
 				'noo_image',
 				esc_html__( 'Heading Background Image', 'noo-hermosa' ),
 				''
 			);

 			// Control: Product Layout
 			$helper->add_control(
 				'noo_woocommerce_product_layout',
 				'noo_same_as_radio',
 				esc_html__( 'Product Layout', 'noo-hermosa' ),
 				'same_as_shop',
 				array(
 					'choices' => array(
 						'same_as_shop'   => esc_html__( 'Same as Shop Layout', 'noo-hermosa' ),
 						'fullwidth'   => esc_html__( 'Full-Width', 'noo-hermosa' ),
 						'sidebar'   => esc_html__( 'With Right Sidebar', 'noo-hermosa' ),
 						'left_sidebar'   => esc_html__( 'With Left Sidebar', 'noo-hermosa' ),
 					),
 					'json' => array(
 						'child_options' => array(
 							'fullwidth'   => '',
 							'sidebar'   => 'noo_woocommerce_product_sidebar',
 							'left_sidebar'   => 'noo_woocommerce_product_sidebar',
 						)
 					)
 				)
 			);

 			// Control: Product Sidebar
 			$helper->add_control(
 				'noo_woocommerce_product_sidebar',
 				'widgets_select',
 				esc_html__( 'Product Sidebar', 'noo-hermosa' ),
 				''
 			);

 			// Control: Products related
 		    $helper->add_control(
 			    'noo_woocommerce_product_related',
 			    'text',
 			    esc_html__( 'Related Products Count', 'noo-hermosa' ),
 			    ''
 		    );

 		}
 	add_action( 'customize_register', 'noo_hermosa_customizer_register_options_woocommerce' );
 	endif;
 endif;

 if( class_exists( 'Noo__Timetable__Event' ) ) :
 	if ( ! function_exists( 'noo_hermosa_customizer_register_options_event' ) ) :
 		function noo_hermosa_customizer_register_options_event( $wp_customize ) {

 			// declare helper object.
 			$helper = new NOO_Customizer_Helper( $wp_customize );

 			// Section: Revolution Slider
 			$helper->add_section(
 				'noo_hermosa_customizer_section_event',
 				esc_html__( 'Event', 'noo-hermosa' ),
 				'',
 				true
 			);

 			// Sub-section: Event Listing
 			$helper->add_sub_section(
 				'noo_sub_section_event_page',
 				esc_html__( 'Event Listing', 'noo-hermosa' )
 			);

 			// Control: Event Layout
 			$helper->add_control(
 				'noo_event_layout',
 				'noo_radio',
 				esc_html__( 'Event Layout', 'noo-hermosa' ),
 				'fullwidth',
 				array(
 					'choices' => array(
						'fullwidth'    => esc_html__( 'Full-Width', 'noo-hermosa' ),
						'sidebar'      => esc_html__( 'With Right Sidebar', 'noo-hermosa' ),
						'left_sidebar' => esc_html__( 'With Left Sidebar', 'noo-hermosa' )
 					),
 					'json' => array(
 						'child_options' => array(
							'fullwidth'    => '',
							'sidebar'      => 'noo_event_sidebar',
							'left_sidebar' => 'noo_event_sidebar',
 						)
 					)
 				)
 			);

 			// Control: Event Sidebar
 			$helper->add_control(
 				'noo_event_sidebar',
 				'widgets_select',
 				esc_html__( 'Event Sidebar', 'noo-hermosa' ),
 				''
 			);

 			// Control: Divider 1
 			$helper->add_control( 'noo_event_divider_1', 'divider', '' );

 			// Control: Heading Title
 			$helper->add_control(
 				'noo_event_heading_title',
 				'text',
 				esc_html__( 'Event Heading', 'noo-hermosa' ),
 				esc_html__('Event', 'noo-hermosa')
 			);

 			// Control: Heading Title
			$helper->add_control(
				'noo_event_heading_desc',
				'text',
				esc_html__( 'Event Heading Description', 'noo-hermosa' ),
				'',
				array(),
				array( 'transport' => 'postMessage' )
			);

 			// Control: Heading Image
 			$helper->add_control(
 				'noo_event_heading_image',
 				'noo_image',
 				esc_html__( 'Heading Background Image', 'noo-hermosa' ),
 				''
 			);

 			 // $helper->add_control(
 			 // 	'noo_event_grid_column',
 			 // 	'ui_slider',
 			 // 	esc_html__( 'Event Grid Columns', 'noo-hermosa' ),
 			 // 	'2',
 			 // 	array(
 			 // 		'json' => array(
 			 // 			'data_min'  => 1,
 			 // 			'data_max'  => 4,
 			 // 			'data_step' => 1
 			 // 		)
 			 // 	)
 			 // );
			
			// Control: Show Event Category
 			$helper->add_control(
				'noo_event_category',
				'checkbox',
				esc_html__( 'Show Event Category', 'noo-hermosa' ),
				0
			);

			// Control: Show Event Time Start
 			$helper->add_control(
				'noo_event_time_start',
				'checkbox',
				esc_html__( 'Show Event Time Start', 'noo-hermosa' ),
				1
			);

			// Control: Show Event Time End
 			$helper->add_control(
				'noo_event_time_end',
				'checkbox',
				esc_html__( 'Show Event Time End', 'noo-hermosa' ),
				1
			);

			// Control: Show Event Address
 			$helper->add_control(
				'noo_event_address_event',
				'checkbox',
				esc_html__( 'Show Event Address', 'noo-hermosa' ),
				1
			);

 			// Sub-section: Single Event
 			$helper->add_sub_section(
 				'noo_sub_section_post_event',
 				esc_html__( 'Single Event', 'noo-hermosa' )
 			);

 			// Control: Show Event Finter
 			$helper->add_control(
				'noo_post_event_filter',
				'checkbox',
				esc_html__( 'Show Event Filter', 'noo-hermosa' ),
				1
			);

 			// Control: Event Layout
 			$helper->add_control(
 				'noo_post_event_layout',
 				'noo_same_as_radio',
 				esc_html__( 'Event Layout', 'noo-hermosa' ),
 				'same_as_event',
 				array(
 					'choices' => array(
 						'same_as_event'   => esc_html__( 'Same as Event Layout', 'noo-hermosa' ),
 						'fullwidth'   => esc_html__( 'Full-Width', 'noo-hermosa' ),
 						'sidebar'   => esc_html__( 'With Right Sidebar', 'noo-hermosa' ),
 						'left_sidebar'   => esc_html__( 'With Left Sidebar', 'noo-hermosa' ),
 					),
 					'json' => array(
 						'child_options' => array(
 							'fullwidth'   => '',
 							'sidebar'   => 'noo_post_event_sidebar',
 							'left_sidebar'   => 'noo_post_event_sidebar',
 						)
 					)
 				)
 			);

 			// Control: Event Sidebar
 			$helper->add_control(
 				'noo_post_event_sidebar',
 				'widgets_select',
 				esc_html__( 'Event Sidebar', 'noo-hermosa' ),
 				''
 			);

 			// Control: Event related
 		    $helper->add_control(
 			    'noo_post_event_related',
 			    'text',
 			    esc_html__( 'Related Event Count', 'noo-hermosa' ),
 			    ''
 		    );

 		}
 	add_action( 'customize_register', 'noo_hermosa_customizer_register_options_event' );
 	endif;
 endif;

// 12. Custom Code
if ( ! function_exists( 'noo_hermosa_customizer_register_options_custom_code' ) ) :
	function noo_hermosa_customizer_register_options_custom_code( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Custom Code
		$helper->add_section(
			'noo_hermosa_customizer_section_custom_code',
			esc_html__( 'Custom Code', 'noo-hermosa' ),
			esc_html__( 'In this section you can add custom JavaScript and CSS to your site.<br/>Your Google analytics tracking code should be added to Custom JavaScript field.', 'noo-hermosa' )
		);

		// Control: Custom JS (Google Analytics)
		$helper->add_control(
			'noo_custom_javascript',
			'textarea',
			esc_html__( 'Custom JavaScript', 'noo-hermosa' ),
			'',
			array( 'preview_type' => 'custom' )
		);

		// Control: Custom CSS
		$helper->add_control(
			'noo_custom_css',
			'textarea',
			esc_html__( 'Custom CSS', 'noo-hermosa' ),
			'',
			array( 'preview_type' => 'custom' )
		);
	}
add_action( 'customize_register', 'noo_hermosa_customizer_register_options_custom_code' );
endif;

// 13. Import/Export Settings.
if ( ! function_exists( 'noo_hermosa_customizer_register_options_tools' ) ) :
	function noo_hermosa_customizer_register_options_tools( $wp_customize ) {

		// declare helper object.
		$helper = new NOO_Customizer_Helper( $wp_customize );

		// Section: Custom Code
		$helper->add_section(
			'noo_hermosa_customizer_section_tools',
			esc_html__( 'Import/Export Settings', 'noo-hermosa' ),
			esc_html__( 'All themes from NooTheme share the same theme setting structure so you can export then import settings from one theme to another conveniently without any problem.', 'noo-hermosa' )
		);

		// Sub-section: Import Settings
		$helper->add_sub_section(
			'noo_tools_sub_section_import',
			esc_html__( 'Import Settings', 'noo-hermosa' ),
			noo_hermosa_kses( __( 'Click Upload button then choose a JSON file (.json) from your computer to import settings to this theme.<br/>All the settings will be loaded for preview here and will not be saved until you click button "Save and Publish".', 'noo-hermosa' ) )
		);

		// Control: Upload Settings
		$helper->add_control(
			'noo_tools_import',
			'import_settings',
			esc_html__( 'Upload', 'noo-hermosa' )
		);

		// Sub-section: Export Settings
		$helper->add_sub_section(
			'noo_tools_sub_section_export',
			esc_html__( 'Export Settings', 'noo-hermosa' ),
			noo_hermosa_kses( __( 'Simply click Download button to export all your settings to a JSON file (.json).<br/>You then can use that file to restore theme settings to any theme of NooTheme.', 'noo-hermosa' ) )
		);

		// Control: Download Settings
		$helper->add_control(
			'noo_tools_export',
			'export_settings',
			esc_html__( 'Download', 'noo-hermosa' )
		);

	}
add_action( 'customize_register', 'noo_hermosa_customizer_register_options_tools' );
endif;
// Portfolio
if ( ! function_exists( 'noo_hermosa_customizer_register_options_portfolio' ) ) :
    function noo_hermosa_customizer_register_options_portfolio( $wp_customize ) {

        // declare helper object.
        $helper = new NOO_Customizer_Helper( $wp_customize );

        $helper->add_section(
            'noo_hermosa_customizer_section_portfolio',
            esc_html__( 'Portfolio', 'noo-hermosa' ),
            esc_html__( 'In this section you have settings for your Blog page, Archive page and Single Post page.', 'noo-hermosa' ),
            true
        );

        // Sub-section:  Page (Index Page)
        $helper->add_sub_section(
            'noo_sub_section_portfolio_page',
            esc_html__( 'Portfolio Layout', 'noo-hermosa' ),
            esc_html__( 'Choose Layout settings for your Portfolio page', 'noo-hermosa' )
        );

        // Control:  Layout
        $helper->add_control(
            'noo_portfolio_layout',
            'noo_radio',
            esc_html__( 'Portfolio Layout', 'noo-hermosa' ),
            'sidebar',
            array(
                'choices' => array(
                    'fullwidth'   => esc_html__( 'Full-Width', 'noo-hermosa' ),
                    'sidebar'   => esc_html__( 'With Right Sidebar', 'noo-hermosa' ),
                    'left_sidebar'   => esc_html__( 'With Left Sidebar', 'noo-hermosa' )
                ),
                'json' => array(
                    'child_options' => array(
                        'fullwidth'   => '',
                        'sidebar'   => 'noo_portfolio_sidebar',
                        'left_sidebar'   => 'noo_portfolio_sidebar'
                    )
                )
            )
        );

        // Control: Portfolio Sidebar
        $helper->add_control(
            'noo_portfolio_sidebar',
            'widgets_select',
            esc_html__( 'Portfolio Sidebar', 'noo-hermosa' ),
            'sidebar-main'
        );

        // Control: Divider 1
        $helper->add_sub_section(
            'noo_sub_section_portfolio_heading',
            esc_html__('Portfolio Heading','noo-hermosa'),
            esc_html__('setting for portfolio heading','noo-hermosa')
        );

        // Control: Heading Title
        $helper->add_control(
            'noo_portfolio_heading_title',
            'text',
            esc_html__( 'Heading Title', 'noo-hermosa' ),
            esc_html__('Portfolio', 'noo-hermosa')
        );

        // Control: Heading Title
        $helper->add_control(
            'noo_portfolio_heading_desc',
            'text',
            esc_html__( 'Heading Description', 'noo-hermosa' ),
            '',
            array(),
            array( 'transport' => 'postMessage' )
        );

        // Control: Heading Image
        $helper->add_control(
            'noo_portfolio_heading_image',
            'noo_image',
            esc_html__( 'Heading Background Image', 'noo-hermosa' ),
            ''
        );

        // Control: Divider 2
        $helper->add_control( 'noo_portfolio_divider_2', 'divider', '' );

        // Control: Excerpt Length
        $helper->add_control(
            'noo_portfolio_excerpt_length',
            'text',
            esc_html__( 'Excerpt Length', 'noo-hermosa' ),
            '60'
        );

        // Sub-section:  social profile
        $helper->add_sub_section(
            'noo_sub_section_portfolio_social_profile',
            esc_html__( 'Social Profile', 'noo-hermosa' )
        );

        $helper->add_control(
            'noo_portfolio_social',
            'noo_switch',
            esc_html__('Enable Social Sharing', 'noo-hermosa'),
            1,
            array(
                'json' => array('on_child_options' => 'noo_portfolio_social_facebook,
		                                                noo_portfolio_social_twitter,
		                                                noo_portfolio_social_google,
		                                                noo_portfolio_social_pinterest,
		                                                noo_portfolio_social_linkedin,
		                                                noo_portfolio_social_googleplus,
		                                                noo_portfolio_social_youtube,
		                                                noo_portfolio_social_instagram,
		                                                noo_portfolio_social_email,
		                                                noo_portfolio_social_github'
                )
            )
        );
        // Control: Facebook Share
        $helper->add_control(
            'noo_portfolio_social_facebook',
            'checkbox',
            esc_html__('Facebook ', 'noo-hermosa'),
            1,
			array( 'facebook' => 'Facebook' )
        );

        // Control: Twitter Share
        $helper->add_control(
            'noo_portfolio_social_twitter',
            'checkbox',
            esc_html__('Twitter ', 'noo-hermosa'),
            1,
            array( 'twitter' => 'Twitter' )
        );

        // Control: Google+ Share
        $helper->add_control(
            'noo_portfolio_social_google',
            'checkbox',
            esc_html__('Google+ ', 'noo-hermosa'),
            1,
            array( 'google' => 'Google' )
        );

        // Control: Pinterest Share
        $helper->add_control(
            'noo_portfolio_social_pinterest',
            'checkbox',
            esc_html__('Pinterest ', 'noo-hermosa'),
            0,
            array( 'pinterest' => 'PinteRest' )
        );

        // Control: LinkedIn Share
        $helper->add_control(
            'noo_portfolio_social_linkedin',
            'checkbox',
            esc_html__('LinkedIn ', 'noo-hermosa'),
            0,
            array( 'linkedin' => 'Linkedin' )
        );
        $helper->add_control(
            'noo_portfolio_social_googleplus',
            'checkbox',
            esc_html__('Google+ ', 'noo-hermosa'),
            0,
            array( 'googleplus' => 'Googleplus' )
        );
        $helper->add_control(
            'noo_portfolio_social_instagram',
            'checkbox',
            esc_html__('Instagram', 'noo-hermosa'),
            0,
            array( 'instagram' => 'instagram' )
        );
        $helper->add_control(
            'noo_portfolio_social_youtube',
            'checkbox',
            esc_html__('YouTube', 'noo-hermosa'),
            0,
            array( 'youtube' => 'youtube' )
        );
        $helper->add_control(
            'noo_portfolio_social_github',
            'checkbox',
            esc_html__('Github', 'noo-hermosa'),
            0,
            array( 'github' => 'Github' )
        );
        $helper->add_control(
            'noo_portfolio_social_email',
            'checkbox',
            esc_html__('Email address', 'noo-hermosa'),
            0,
            array( 'Email' => 'Email' )
        );
        // sub section Single Page
        $helper->add_sub_section(
            'noo_sub_section_portfolio_single_page',
            esc_html__( 'Single Portfolio Setting', 'noo-hermosa' )
        );
        $helper->add_control(
            'noo_show_portfolio_link',
            'noo_switch',
            esc_html__( 'Enable/Disable link to detail in portfolio', 'noo-hermosa' ),
            1
        );

        $helper->add_control(
            'noo_show_portfolio_title',
            'noo_switch',
            esc_html__( 'Enable/Disable Single Portfolio', 'noo-hermosa' ),
            1
        );
        $helper->add_control(
            'noo_show_portfolio_breadcrumbs',
            'noo_switch',
            esc_html__( 'Enable/Disable Breadcrumbs Single', 'noo-hermosa' ),
            1
        );
        $helper->add_control(
            'noo_portfolio_single_title_text_align',
            'select',
            esc_html__('Select Single Title Layout','noo-hermosa'),
            'left',
            array(
                'choices' => array(
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right'
                ),
                'preview_type' => 'custom'
            )
        );
        $helper->add_control(
            'noo_portfolio_single_title_length',
            'text',
            esc_html__( ' Single Title Length', 'noo-hermosa' ),
            '500'
        );
        $helper->add_control(
            'noo_portfolio-single-style',
            'select',
            esc_html__('Select Single  Layout','noo-hermosa'),
            'detail-01',
            array(
                'choices' => array(
                    'detail-01' => 'Detail 1',
                    'detail-02' => 'Detail 2',
                    'detail-03' => 'Detail 3',
                    'detail-04' => 'Detail 4',
                    'detail-05' => 'Detail 5',
                ),
                'preview_type' => 'custom'
            )
        );
        $helper->add_sub_section(
            'noo_sub_section_portfolio_related',
            esc_html__('Portfolio Related ','noo-hermosa')
        );
        $helper->add_control(
            'noo_portfolio_show_related',
            'noo_switch',
            esc_html__('Show or Hide related in single portfolio','noo-hermosa'),
            1
        );
        $helper->add_control(
            'noo_portfolio_related_style',
            'select',
            esc_html__('Select portfolio related style','noo-hermosa'),
            'default',
            array(
                'choices'=>array(
                    'default'   => esc_html__('Default', 'noo-hermosa'),
                    'squared'   => esc_html__('Squared', 'noo-hermosa'),
                    'landscape' => esc_html__('Landscape', 'noo-hermosa'),
                    'portrait'  => esc_html__('Portrait', 'noo-hermosa')
                ),
                'preview_type' =>'custom'
            )
        );
        $helper->add_control(
            'noo_portfolio_related_column',
            'select',
            esc_html__('Select portfolio related column','noo-hermosa'),
            '2',
            array(
                'choices'=>array(
                    '2'          => esc_html__('2 Column', 'noo-hermosa'),
                    '3'          => esc_html__('3 Column', 'noo-hermosa'),
                    '4'          => esc_html__('4 Column', 'noo-hermosa'),
                ),
                'preview_type' =>'custom'
            )
        );
        $helper->add_control(
            'noo_portfolio_related_overlay',
            'select',
            esc_html__('Select portfolio related overlay style','noo-hermosa'),
            'icon-title-category',
            array(
                'choices'=>array(
                    'icon'                          => esc_html__('Icon', 'noo-hermosa'),
                    'icon-title'                    => esc_html__('Icon Title', 'noo-hermosa'),
                    'icon-title-category'           => esc_html__('Icon Title Category', 'noo-hermosa'),
                    'title-category'                => esc_html__('Title Category', 'noo-hermosa'),
                    'title-category-link'           => esc_html__('Title Category Link', 'noo-hermosa'),
                ),
                'preview_type' =>'custom'
            )
        );
        $helper->add_control(
            'noo_portfolio_related_effect',
            'select',
            esc_html__('Select portfolio related hover effect','noo-hermosa'),
            'effect_1',
            array(
                'choices'=>array(
                    'effect_1'   => esc_html__('Effect 1', 'noo-hermosa'),
                    'effect_2'   => esc_html__('Effect 2', 'noo-hermosa'),
                    'effect_3'   => esc_html__('Effect 3', 'noo-hermosa'),
                    'effect_4'   => esc_html__('Effect 4', 'noo-hermosa'),
                    'effect_5'   => esc_html__('Effect 5', 'noo-hermosa'),
                ),
                'preview_type' =>'custom'
            )
        );
    }
    add_action( 'customize_register', 'noo_hermosa_customizer_register_options_portfolio' );
endif;

