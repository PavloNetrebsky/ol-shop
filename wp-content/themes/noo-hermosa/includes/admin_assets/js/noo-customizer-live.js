/**
 * This file adds some LIVE to the Theme Customizer live preview.
 */

( function( $ ) {

	//
	// Site Enhancement
	// 

	// Back To Top
	wp.customize( 'noo_back_to_top', function( value ) {
		value.bind( function( newval ) {
			// Update Main CSS
			$back_to_top = ( $('.go-to-top').length > 0 ) ? $('.go-to-top') : $('<a href="#" class="go-to-top"><i class="fa fa-angle-up"></i></a>').appendTo($('body'));

			if( newval ) {
				$back_to_top.show();
			} else {
				$back_to_top.hide();
			}
		} );
	} );

	// Page Heading Spacing (px)
	wp.customize( 'noo_page_heading_spacing', function( value ) {
		value.bind( function( newval ) {
			additionalStyle = '@media (min-width: 992px) {'; // start @media
			
			// Padding-Left, Padding Right
			additionalStyle += '.noo-page-heading {padding-top: ' + newval + 'px; padding-bottom: ' + newval + 'px;}';

			additionalStyle += '}'; // end @media

			noo_update_live_css(additionalStyle);
		} );
	} );

	//
	// Design & Layout
	// 

	// Site layout
	wp.customize( 'noo_site_layout', function( value ) {
		value.bind( function( newval ) {
			$body = $('body');
			switch( newval ) {
				case 'fullwidth':
					$body.removeClass('boxed-layout').addClass('full-width-layout');
					break;
				case 'boxed':
					$body.removeClass('full-width-layout').addClass('boxed-layout');
					break;
			}

			$(document).trigger('noo-layout-changed');
		} );
	} );

	// Site Width
	wp.customize( 'noo_layout_site_width', function( value ) {
		value.bind( function( newval ) {
			$body = $('body');
			if( $body.hasClass('boxed-layout') ) {
				$( 'body > .site' ).css( 'width', newval + '%' );
				$( 'body .navbar.navbar-fixed-top' ).css( 'width', newval + '%' );

				$(document).trigger('noo-layout-changed');
			}
		} );
	} );

	// Site Max Width
	wp.customize( 'noo_layout_site_max_width', function( value ) {
		value.bind( function( newval ) {
			$body = $('body');
			if( $body.hasClass('boxed-layout') ) {
				$( 'body > .site' ).css( 'max-width', newval + 'px' );
				$( 'body .navbar.navbar-fixed-top' ).css( 'max-width', newval + 'px' );

				$(document).trigger('noo-layout-changed');
			}
		} );
	} );

	// Background Color
	wp.customize( 'noo_layout_bg_color', function( value ) {
		value.bind( function( newval ) {
			$body = $('body');
			if( $body.hasClass('boxed-layout') ) {
				$body.css( 'background-color', newval );
			}
		} );
	} );

	// Background Image
	wp.customize( 'noo_layout_bg_image', function( value ) {
		value.bind( function( newval ) {
			$body = $('body');
			if( $body.hasClass('boxed-layout') ) {
				if( newval === '' ) {
					$body.css( 'background-image', 'none' );
					return;
				}

				noo_get_attachment_url_ajax( newval, function ( data ) {
					// Background Image
					$body.css( 'background-image', 'url("' + data + '")' );
				} );
			}
		} );
	} );

	// Background Image Repeat
	wp.customize( 'noo_layout_bg_repeat', function( value ) {
		value.bind( function( newval ) {
			$body = $('body');
			if( $body.hasClass('boxed-layout') && newval !== '' ) {
				$body.css( 'background-repeat', newval );
			}
		} );
	} );

	// Background Image Position
	wp.customize( 'noo_layout_bg_align', function( value ) {
		value.bind( function( newval ) {
			$body = $('body');
			if( $body.hasClass('boxed-layout') && newval !== '' ) {
				$body.css( 'background-position', newval );
			}
		} );
	} );

	// Background Image Attachment
	wp.customize( 'noo_layout_bg_attachment', function( value ) {
		value.bind( function( newval ) {
			$body = $('body');
			if( $body.hasClass('boxed-layout') && newval !== '' ) {
				$body.css( 'background-attachment', newval );
			}
		} );
	} );

	// Background Image Auto Resize
	wp.customize( 'noo_layout_bg_cover', function( value ) {
		value.bind( function( newval ) {
			$body = $('body');
			if( $body.hasClass('boxed-layout') && newval ) {
				$body.css( '-webkit-background-size', 'cover' )
					.css( '-moz-background-size', 'cover' )
					.css( '-o-background-size', 'cover' )
					.css( 'background-size', 'cover' );
			} else {
				$body.css( '-webkit-background-size', 'auto' )
					.css( '-moz-background-size', 'auto' )
					.css( '-o-background-size', 'auto' )
					.css( 'background-size', 'auto' );
			}
		} );
	} );


	// Headings Font
	wp.customize( 'noo_typo_headings_font', function( value ) {
		value.bind( function( newval ) {
			// Update Google Font Link
			noo_update_font( 'noo_typo_headings_', '#noo-google-fonts-headings-css' );

			// Update style
			additionalStyle = 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {font-family: "' + newval + '", sans-serif;}';
			noo_update_live_css( additionalStyle );
		} );
	} );

	// Headings Font Style
	wp.customize( 'noo_typo_headings_font_style', function( value ) {
		value.bind( function( newval ) {
			// Update Google Font Link
			noo_update_font( 'noo_typo_headings_', '#noo-google-fonts-headings-css' );

			// Update style
			additionalStyle = 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {font-style: ' + newval + ';}';
			noo_update_live_css( additionalStyle );
		} );
	} );

	// Headings Font Weight
	wp.customize( 'noo_typo_headings_font_weight', function( value ) {
		value.bind( function( newval ) {
			// Update Google Font Link
			noo_update_font( 'noo_typo_headings_', '#noo-google-fonts-headings-css' );

			// Update style
			additionalStyle = 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {font-weight: ' + newval + ';}';
			noo_update_live_css( additionalStyle );
		} );
	} );

	// Headings Font Subset
	wp.customize( 'noo_typo_headings_font_subset', function( value ) {
		value.bind( function( newval ) {
			// Update Google Font Link
			noo_update_font( 'noo_typo_headings_', '#noo-google-fonts-headings-css' );
		} );
	} );

	// Headings Font Color
	wp.customize( 'noo_typo_headings_font_color', function( value ) {
		value.bind( function( newval ) {
			additionalStyle = 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {color: ' + newval + ';}';
			additionalStyle += 'h1 a, h2 a, h3 a, h4 a, h5 a, h6 a, .h1 a, .h2 a, .h3 a, .h4 a, .h5 a, .h6 a {color: ' + newval + ';}';

			// Update Style
			noo_update_live_css( additionalStyle );
		} );
	} );

	// Headings Font Uppercase
	wp.customize( 'noo_typo_headings_uppercase', function( value ) {
		value.bind( function( newval ) {
			if( newval ) {
				additionalStyle = 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {text-transform: uppercase;}';
			} else {
				additionalStyle = 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {text-transform: none;}';
			}

			// Update Style
			noo_update_live_css( additionalStyle );
		} );
	} );

	// Body Font
	wp.customize( 'noo_typo_body_font', function( value ) {
		value.bind( function( newval ) {
			// Update Google Font Link
			noo_update_font( 'noo_typo_body_', '#noo-google-fonts-body-css' );

			// Update style
			noo_update_live_css( 'body {font-family: "' + newval + '", sans-serif;}' );
		} );
	} );

	// Body Font Style
	wp.customize( 'noo_typo_body_font_style', function( value ) {
		value.bind( function( newval ) {
			// Update Google Font Link
			noo_update_font( 'noo_typo_body_', '#noo-google-fonts-body-css' );

			// Update style
			noo_update_live_css( 'body {font-style: ' + newval + ';}' );
		} );
	} );

	// Body Font Weight
	wp.customize( 'noo_typo_body_font_weight', function( value ) {
		value.bind( function( newval ) {
			// Update Google Font Link
			noo_update_font( 'noo_typo_body_', '#noo-google-fonts-body-css' );

			// Update style
			noo_update_live_css( 'body {font-weight: ' + newval + ';}' );
		} );
	} );

	// Body Font Subset
	wp.customize( 'noo_typo_body_font_subset', function( value ) {
		value.bind( function( newval ) {
			// Update Google Font Link
			noo_update_font( 'noo_typo_body_', '#noo-google-fonts-body-css' );
		} );
	} );

	// Body Font Size
	wp.customize( 'noo_typo_body_font_size', function( value ) {
		value.bind( function( newval ) {
			// Update Style
			noo_update_customizer_css( 'typography' );
		} );
	} );
    wp.customize( 'noo_typo_body_font_color', function( value ) {
		value.bind( function( newval ) {
			// Update Style
			noo_update_customizer_css( 'typography' );
		} );
	} );

	//
	// Header
	// 

	// Header Background Color
	wp.customize( 'noo_header_bg_color', function( value ) {
		value.bind( function( newval ) {
			if( newval === '' ) {
				newval = '#fff';
			}

            additionalStyle = 'header.noo-header .navbar-wrapper{background-color: ' + newval + ';}';

			// Update Style
			noo_update_live_css( additionalStyle );
		} );
	} );

	// Header Background Fixed Color
	wp.customize( 'noo_header_fixed_bg_color', function( value ) {
		value.bind( function( newval ) {
			if( newval === '' ) {
				newval = '#fff';
			}

            additionalStyle = 'header.noo-header.fixed_top.fixed-top-eff .navbar-wrapper{background-color: ' + newval + ';}';

			// Update Style
			noo_update_live_css( additionalStyle );
		} );
	} );

	// // NavBar Position
	// wp.customize( 'noo_header_nav_position', function( value ) {
	// 	value.bind( function( newval ) {
	// 		noo_refresh_preview();
	// 	} );
	// } );

	// Header Top Menu
	wp.customize( 'noo_header_nav_top_menu', function( value ) {
		value.bind( function( newval ) {

			if( newval ) {
            	additionalStyle = 'header.noo-header.header-logo-transparent .noo-top-left-widget{display: block ;}';
			} else {
            	additionalStyle = 'header.noo-header.header-logo-transparent .noo-top-left-widget{display: none ;}';
			}

			// Update Style
			noo_update_live_css( additionalStyle );
		} );
	} );

	// Header Top Menu Hide Scroll
	wp.customize( 'noo_header_nav_top_menu_hide_scroll', function( value ) {
		value.bind( function( newval ) {

			if( newval ) {
            	$('header.noo-header.header-logo-transparent .noo-top-left-widget').addClass('top-menu-hide-fix');
			} else {
            	$('header.noo-header.header-logo-transparent .noo-top-left-widget').removeClass('top-menu-hide-fix');
			}

			// Update Style
			noo_update_live_css( additionalStyle );
		} );
	} );


	// Smart Scroll
	wp.customize( 'noo_header_nav_smart_scroll', function( value ) {
		value.bind( function( newval ) {
			$navbar = $('.navbar');
			if( $navbar.hasClass( 'fixed-top' ) && newval ) {
				$navbar.addClass( 'smart_scroll' );
			} else {
				$navbar.removeClass( 'smart_scroll' );
			}
		} );
	} );

	// NavBar Font
	wp.customize( 'noo_header_nav_font', function( value ) {
		value.bind( function( newval ) {
			// Update Google Font Link
			noo_update_font( 'noo_header_nav_', '#noo-google-fonts-nav-css' );

			// Update Style
			noo_update_live_css( 'header .noo-main-menu .navbar-nav li > a {font-family: "' + newval + '", sans-serif;}' );
		} );
	} );

	// NavBar Font Style
	wp.customize( 'noo_header_nav_font_style', function( value ) {
		value.bind( function( newval ) {
			// Update Google Font Link
			noo_update_font( 'noo_header_nav_', '#noo-google-fonts-nav-css' );

			// Update Style
			noo_update_live_css( 'header .noo-main-menu .navbar-nav li > a {font-style: ' + newval + ';}' );
		} );
	} );

	// NavBar Font Weight
	wp.customize( 'noo_header_nav_font_weight', function( value ) {
		value.bind( function( newval ) {
			// Update Google Font Link
			noo_update_font( 'noo_header_nav_', '#noo-google-fonts-nav-css' );

			// Update Style
			noo_update_live_css( 'header .noo-main-menu .navbar-nav li > a{font-weight: ' + newval + ';}' );
		} );
	} );

	// NavBar Font Subset
	wp.customize( 'noo_header_nav_font_subset', function( value ) {
		value.bind( function( newval ) {
			// Update Google Font Link
			noo_update_font( 'noo_header_nav_', '#noo-google-fonts-nav-css' );
		} );
	} );

	// NavBar Font Size
	wp.customize( 'noo_header_nav_font_size', function( value ) {
		value.bind( function( newval ) {
			additionalStyle = 'header .noo-main-menu .navbar-nav li > a{font-size: ' + newval + 'px;}';

			// Update Style
			noo_update_live_css( additionalStyle );
		} );
	} );

	// NavBar Link Color
	wp.customize( 'noo_header_nav_link_color', function( value ) {
		value.bind( function( newval ) {

			// NavBar style
			additionalStyle = 'header .noo-main-menu .navbar-nav li > a {color: ' + newval + ';}';
			// Dropdown style
            additionalStyle += 'header .noo-main-menu .navbar-nav ul.sub-menu li > a {color: ' + newval + ';}';

			// Update Style
			noo_update_live_css( additionalStyle );
		} );
	} );

	// NavBar Link Hover Color
	wp.customize( 'noo_header_nav_link_hover_color', function( value ) {
		value.bind( function( newval ) {

			// NavBar style
			additionalStyle = 'header .noo-main-menu .navbar-nav li > a:hover,.noo-main-menu .navbar-nav li > a:focus,.noo-main-menu .navbar-nav li:hover > a {color: ' + newval + ';}';

			// Dropdown style
            additionalStyle += 'header .noo-main-menu .navbar-nav ul.sub-menu li > a:hover,.noo-main-menu .navbar-nav ul.sub-menu li > a:focus,.noo-main-menu .navbar-nav ul.sub-menu li:hover > a,.noo-main-menu .navbar-nav ul.sub-menu li.sfHover > a,.noo-main-menu .navbar-nav ul.sub-menu li.current-menu-item > a {color: ' + newval + ';}';
			
			// Update Style
			noo_update_live_css( additionalStyle );
		} );
	} );

	// NavBar Font Uppercase
	wp.customize( 'noo_header_nav_uppercase', function( value ) {
		value.bind( function( newval ) {

			if( newval ) {
				additionalStyle = 'header .noo-main-menu .navbar-nav > li > a {text-transform: uppercase;}';
			} else {
				additionalStyle = 'header .noo-main-menu .navbar-nav > li > a {text-transform: none;}';
			}

			// Update Style
			noo_update_live_css( additionalStyle );
		} );
	} );

	// Blog Name
	wp.customize( 'blogname', function( value ) {
		value.bind( function( newval ) {
			$('.navbar-brand').text( newval );
		} );
	} );

	// Logo Font
	wp.customize( 'noo_header_logo_font', function( value ) {
		value.bind( function( newval ) {
			// Update Google Font Link
			noo_update_font( 'noo_header_logo_', '#noo-google-fonts-logo-css' );

			// Update Style
			noo_update_live_css( 'header .navbar-brand {font-family: "' + newval + '", sans-serif;}' );
		} );
	} );

	// Logo Font Style
	wp.customize( 'noo_header_logo_font_style', function( value ) {
		value.bind( function( newval ) {
			// Update Google Font Link
			noo_update_font( 'noo_header_logo_', '#noo-google-fonts-logo-css' );

			// Update Style
			noo_update_live_css( 'header .navbar-brand {font-style: ' + newval + ';}' );
		} );
	} );

	// Logo Font Weight
	wp.customize( 'noo_header_logo_font_weight', function( value ) {
		value.bind( function( newval ) {
			// Update Google Font Link
			noo_update_font( 'noo_header_logo_', '#noo-google-fonts-logo-css' );

			// Update Style
			noo_update_live_css( 'header .navbar-brand {font-weight: ' + newval + ';}' );
		} );
	} );

	// Logo Font Subset
	wp.customize( 'noo_header_logo_font_subset', function( value ) {
		value.bind( function( newval ) {
			// Update Google Font Link
			noo_update_font( 'noo_header_logo_', '#noo-google-fonts-logo-css' );
		} );
	} );

	// Logo Font Size
	wp.customize( 'noo_header_logo_font_size', function( value ) {
		value.bind( function( newval ) {
			additionalStyle = 'header .navbar-brand {font-size: ' + newval + 'px;}';

			noo_update_live_css( additionalStyle );
		} );
	} );

	// Logo Font Color
	wp.customize( 'noo_header_logo_font_color', function( value ) {
		value.bind( function( newval ) {
			if( newval === '' ) {
				return;
			}
			noo_update_live_css( 'header .navbar-brand {color: ' + newval + ';}' );
		} );
	} );

	// Logo Font Uppercase
	wp.customize( 'noo_header_logo_uppercase', function( value ) {
		value.bind( function( newval ) {
			if( newval ) {
				additionalStyle = 'header .navbar-brand {text-transform: uppercase;}';
			} else {
				additionalStyle = 'header .navbar-brand {text-transform: none;}';
			}
			noo_update_live_css( additionalStyle );
		} );
	} );

	// Logo Image
	wp.customize( 'noo_header_logo_image', function( value ) {
		value.bind( function( newval ) {
			if( newval === '' ) {
				$('.navbar-brand .noo-logo-img.noo-logo-normal').remove();
				return;
			}

			noo_get_attachment_url_ajax( newval, function ( data ) {
				// Image Logo
				$('.navbar-brand .noo-logo-img.noo-logo-normal').remove();
				$('.navbar-brand').append('<img class="noo-logo-img noo-logo-normal" src="' + data + '">');
			} );
		} );
	} );


	// Logo Image Height
	wp.customize( 'noo_header_logo_image_height', function( value ) {
		value.bind( function( newval ) {
			additionalStyle  = 'header .navbar-brand .noo-logo-img{';
			additionalStyle += 'height: ' + newval + 'px;}';

			noo_update_live_css(additionalStyle);
		} );
	} );

	// Logo Image Transparent Height
	wp.customize( 'noo_header_logo_image_transparent_height', function( value ) {
		value.bind( function( newval ) {
			additionalStyle  = 'header .navbar-brand .noo-logo-img-transparent{';
			additionalStyle += 'height: ' + newval + 'px;}';

			noo_update_live_css(additionalStyle);
		} );
	} );

	// NavBar Height
	wp.customize( 'noo_header_nav_height', function( value ) {
		value.bind( function( newval ) {
			additionalStyle = 'header .navbar {min-height: ' + newval + 'px;} .navbar:not(.navbar-shrink) .navbar-brand { height: ' + newval + 'px;line-height: ' + newval + 'px;}';

			additionalStyle += '@media (min-width: 992px) {'; // start @media
			
			// Line-Height
			additionalStyle += '.navbar:not(.navbar-shrink) .navbar-nav > li > a { height: ' + (newval - 20) + 'px;line-height: ' + (newval - 20) + 'px;}';

			additionalStyle += '}'; // end @media

			// Toggle Height
			additionalStyle += '.navbar-toggle, .mobile-minicart-icon {height: ' + newval + 'px;}';

			noo_update_live_css(additionalStyle);
		} );
	} );

	// NavBar Link Spacing (px)
	wp.customize( 'noo_header_nav_link_spacing', function( value ) {
		value.bind( function( newval ) {
			additionalStyle = '@media (min-width: 992px) {'; // start @media
			
			// Padding-Left, Padding Right
			additionalStyle += 'header .noo-main-menu .navbar-nav > li {padding-left: ' + newval + 'px; padding-right: ' + newval + 'px;}';

			additionalStyle += '}'; // end @media

			noo_update_live_css(additionalStyle);
		} );
	} );



	//
	// Footer
	//

	// Bottom Bar Content
	wp.customize( 'noo_bottom_bar_content', function( value ) {
		value.bind( function( newval ) {
			$footer = $('footer.colophon.site-info').length > 0  ?
						$('footer.colophon.site-info') :
						$('<footer/>').addClass('colophon site-info').attr( 'role', 'contentinfo')
							.append($('<div/>').addClass('.container-full'))
							.appendTo('body > .site');
			$footer = $footer.find('.container-full');

			if( $footer.find('.footer-more').length === 0 ) {
				$footer.append(
					$('<div/>').addClass('footer-more')
						.append($('<div/>').addClass('container-boxed')
							.append($('<div/>').addClass('row')
								.append($('<div/>').addClass('col-md-12')))));
			}

			$footerMore     = $footer.find('.footer-more');
			$footerMoreCol1 = $footer.find('.footer-more .row .col-md-12');

			$footerContent = $footerMoreCol1.find('.noo-bottom-bar-content').length ? $footerMoreCol1.find('.noo-bottom-bar-content') : $('<div class="noo-bottom-bar-content"></div>').appendTo( $footerMoreCol1 );
			$footerContent.html( newval );
			if( newval === '' ) {
				$footerMore.hide();
			} else {
				$footerMore.show();
			}

		} );
	} );

	//
	// Custom Code
	//

	wp.customize( 'noo_custom_javascript', function( value ) {
		value.bind( function( newval ) {
			// showCannotPreviewMsg();
		} );
	} );

	wp.customize( 'noo_custom_css', function( value ) {
		value.bind( function( newval ) {
			// showCannotPreviewMsg();
		} );
	} );

} ) ( jQuery );