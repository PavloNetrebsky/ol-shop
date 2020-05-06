/**
 * This file adds some LIVE to the Theme Customizer live preview.
 */

var g_ajax_counter = 0;
alertify.set({
	labels: {
		ok    : nooCustomizerL10n.ok,
		cancel: nooCustomizerL10n.cancel
	},
	buttonReverse: true,
	buttonFocus: 'none'
});

function showCannotPreviewMsg() {
	if( jQuery('.alertify-log-cannot_preview_msg').length === 0 ) {
		// Display updating message
		alertify.log(nooCustomizerL10n.cannot_preview_msg, 'cannot_preview_msg', 3000);
	}
}

function showUpdatingMsg() {
	g_ajax_counter ++;
	if( g_ajax_counter > 1 ) {
		return;
	}

	// Display updating message
	alertify.log(nooCustomizerL10n.ajax_update_msg, 'ajax_update_msg', 0);
}

function hideUpdatingMsg() {
	g_ajax_counter = Math.max( 0, g_ajax_counter - 1 );
	if( g_ajax_counter > 0 ) {
		return;
	}

	// Hide updating message
	jQuery('.alertify-log-ajax_update_msg').remove();
}

function noo_redirect_url ( url ) {
	if( url === '' )
		return;

	var noo_preview = new wp.customize.Preview({
		url: url,
		channel: wp.customize.settings.channel
	});

	showUpdatingMsg();
	noo_preview.send( 'scroll', 0 );
	noo_preview.send( 'url', url );
}

function noo_redirect_preview( type ) {
	if( typeof wp.customize === "undefined" )
		return;

	var url     = '';
	var message = '';

	switch ( type ) {
		case 'blog':
			url = nooCustomizerL10n.blog_page;
			message = nooCustomizerL10n.redirect_msg.replace( '%s', nooCustomizerL10n.blog_text);
			break;
		case 'shop':
			url = nooCustomizerL10n.shop_page;
			message = nooCustomizerL10n.redirect_msg.replace( '%s', nooCustomizerL10n.shop_text);
			break;
		case 'archive':
			url = nooCustomizerL10n.archive_page;
			message = nooCustomizerL10n.redirect_msg.replace( '%s', nooCustomizerL10n.archive_text);
			break;
		case 'post':
			url = nooCustomizerL10n.post_page;
			message = nooCustomizerL10n.redirect_msg.replace( '%s', nooCustomizerL10n.post_text);
			break;
		case 'product':
			url = nooCustomizerL10n.product_page;
			message = nooCustomizerL10n.redirect_msg.replace( '%s', nooCustomizerL10n.product_text);
			break;
		default:
			url = nooCustomizerL10n.hasOwnProperty(type + '_page') ? nooCustomizerL10n[type + '_page'] : '';
			message = nooCustomizerL10n.hasOwnProperty(type + '_text') ? nooCustomizerL10n[type + '_text'] : '';
			message = nooCustomizerL10n.redirect_msg.replace( '%s', message );
	}

	if( url === '' )
		return;

	// Display updating message
	alertify.alert(message, function( e ) {
		noo_redirect_url( url );
		return false;
	});
}

function noo_refresh_preview() {
	showUpdatingMsg();
	parent.wp.customize.instance('noo_blog_layout').previewer.refresh();
}

function noo_refresh_preview_blog() {
	if( nooL10n.is_blog === "true" ) {
		noo_refresh_preview( );
	} else {
		noo_redirect_preview( 'blog' );
	}
}

function noo_refresh_preview_post() {
	if( nooL10n.is_single === "true" ) {
		noo_refresh_preview( );
	} else {
		noo_redirect_preview( 'post' );
	}
}

function noo_refresh_preview_shop() {
	if( nooL10n.is_shop === "true" ) {
		noo_refresh_preview( );
	} else {
		noo_redirect_preview( 'shop' );
	}
}

function noo_refresh_preview_product() {
	if( nooL10n.is_product === "true" ) {
		noo_refresh_preview( );
	} else {
		noo_redirect_preview( 'product' );
	}
}

function noo_update_customizer_css( type ) {
	query = {
			'noo_customize_ajax': 'on',
			'customized'        : JSON.stringify( wp.customize.get() ),
			'action'            : 'noo_get_customizer_css_' + type,
			'nonce'             : nooCustomizerL10n.customize_live_css
		};
	showUpdatingMsg();
	jQuery.ajax( nooL10n.ajax_url, {
		type: 'POST',
		data: query
	}).done(function ( data ) {
		// Clear live css
		jQuery('#noo-customizer-live-css').empty();

		// Place new css to customizer css
		var $customizeCSS = jQuery( '#noo-customizer-css-' + type).length ? jQuery( '#noo-customizer-css-' + type) : jQuery('<style id="noo-customizer-css-' + type + '" type="text/css" />').appendTo('head');
		$customizeCSS.text( data );

		g_ajax_counter = Math.max( 0, g_ajax_counter - 1 );
	} ).always( function() {
		hideUpdatingMsg();
	} );
}

function noo_get_attachment_url_ajax( image, doneFn ) {
	if(Math.floor(image) == image && jQuery.isNumeric(image)) {
		showUpdatingMsg();
		return jQuery.ajax( nooL10n.ajax_url, {
			type: 'POST',
			data: {
				'attachment_id': image,
				'action'       : 'noo_ajax_get_attachment_url',
				'nonce'             : nooCustomizerL10n.customize_attachment
			}
		} ).done( doneFn ).fail( function() {
			noo_redirect_url( window.location.href );
		} ).always( function() {
			hideUpdatingMsg();
		} );
	} else {
		doneFn( image );
	}
}

function noo_get_menu( menu_location ) {
	showUpdatingMsg();
	return jQuery.ajax( nooL10n.ajax_url, {
		type: 'POST',
		data: {
			'menu_location': menu_location,
			'action'       : 'noo_ajax_get_menu',
			'nonce'        : nooCustomizerL10n.customize_menu
		}
	} ).fail( function() {
		noo_redirect_url( window.location.href );
	} ).always( function() {
		hideUpdatingMsg();
	} );
}

function noo_get_social() {
	showUpdatingMsg();
	return jQuery.ajax( nooL10n.ajax_url, {
		type: 'POST',
		data: {
			'noo_customize_ajax': 'on',
			'customized'        : JSON.stringify( wp.customize.get() ),
			'action'            : 'noo_ajax_get_social_icons',
			'nonce'             : nooCustomizerL10n.customize_social_icons
		}
	} ).fail( function() {
		noo_redirect_url( window.location.href );
	} ).always( function() {
		hideUpdatingMsg();
	} );
}

function noo_update_live_css( additionalCSS ) {
	var $tempCSS = jQuery('#noo-customizer-live-css').length ? jQuery('#noo-customizer-live-css') : jQuery('<style id="noo-customizer-live-css" type="text/css" />').appendTo('head');
	currentStyle = $tempCSS.text();
	$tempCSS.text(currentStyle + additionalCSS);
}

function noo_update_font( prefix, linkID ) {
	font        = wp.customize.value( prefix + 'font' )();
	if( font !== '' ) {
		fontLink = jQuery(linkID).length ? jQuery(linkID) : jQuery('<link rel="stylesheet" id="' + linkID + '" type="text/css" media="all" />').appendTo('head');

		font_style  = wp.customize.value( prefix + 'font_style' )();
		font_weight = wp.customize.value( prefix + 'font_weight' )();
		font_subset = wp.customize.value( prefix + 'font_subset' )();

		font        = font.replace( ' ', '+' );
		font_style  = ( font_style === '' ) ? 'normal' : font_style;
		font_weight = ( font_weight === '' ) ? '400' : font_weight;
		font_subset = ( font_subset === '' ) ? 'latin' : font_subset;

		fontHref = '//fonts.googleapis.com/css?family=' + font + ':' + font_weight + font_style;
		if( font_subset !== 'latin' ) {
			fontHref += '&subset' + font_subset;
		}

		fontLink.attr( 'href', fontHref );
	}
}
