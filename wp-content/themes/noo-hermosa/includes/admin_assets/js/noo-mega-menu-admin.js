jQuery( function( $ ) {

	$( 'body' ).on( 'change', 'input.noo-mega-menu-enable', function() {
		var $this   = $( this );
		var $container = $this.closest( '.noo-menu-form' );
		if( $this.prop( 'checked' ) ) {
			$container.find('.megamenu-child-options').show();
			var column = $container.find('.noo-mega-menu-columns select option:selected').val();
			column = parseInt( column.substr(column.length - 1) );
			if( column > 4 ) {
				$container.find('.noo-submenu-alignment select option:selected').removeAttr('selected');
				$container.find('.noo-submenu-alignment select option[value=full-width]').attr("selected","selected");
			}

			// Find menu columns
//			var $menu_item = $this.closest('.menu-item').next('.menu-item');
//			while( !$menu_item.hasClass('menu-item-depth-0') ) {
//				if( $menu_item.hasClass('menu-item-depth-1') ) {
//					$menu_item.find('.noo-menu-form').addClass('menu-column');
//
//					$menu_item.find('.noo-menu-form .noo-mega-menu-column-span option').each(function() {
//						if( parseInt( $(this).val() ) > column ) {
//							$(this).hide();
//						} else {
//							$(this).show();
//						}
//					});
//				}
//
//				$menu_item = $menu_item.next('.menu-item');
//			}
		} else {
			$container.find('.megamenu-child-options').hide();
			var currentAlignment = $container.find('.noo-submenu-alignment select option:selected');
			if( currentAlignment.val() == 'full-width' ) {
				currentAlignment.removeAttr('selected');
				$container.find('.noo-submenu-alignment select option:first-child').attr("selected","selected");
			}

//			// Find menu columns
//			var $menu_item = $this.closest('.menu-item').next('.menu-item');
//			while( !$menu_item.hasClass('menu-item-depth-0') ) {
//				if( $menu_item.hasClass('menu-item-depth-1') ) {
//					$menu_item.find('.noo-menu-form').removeClass('menu-column');
//				}
//
//				$menu_item = $menu_item.next('.menu-item');
//			}
		}
	} );

	$( 'body' ).on( 'change', 'select.noo-mega-menu-submenu', function() {
		var $this   = $( this );
		var $container = $this.closest( '.noo-menu-form' );
		if( $this.find( 'option:selected' ).val() == 'normal' ) {
			$container.find('.submenu-type-child-options').hide();
		} else {
			$container.find('.submenu-type-child-options').show();
		}
	} );

	$( 'body' ).on( 'change', 'select.noo-mega-menu-column', function() {
		var $this   = $( this );
		var column = $this.find('option:selected').val();
			column = parseInt( column.substr(column.length - 1) );

//		// Find menu columns
//		var $menu_item = $this.closest('.menu-item').next('.menu-item');
//		while( !$menu_item.hasClass('menu-item-depth-0') ) {
//			if( $menu_item.hasClass('menu-item-depth-1') ) {
//				$menu_item.find('.noo-menu-form .noo-mega-menu-column-span option').each(function() {
//					if( parseInt( $(this).val() ) > column ) {
//						$(this).hide();
//					} else {
//						$(this).show();
//					}
//				});
//			}
//
//			$menu_item = $menu_item.next('.menu-item');
//		}
	} );

	$( 'document' ).ready(function () {
		$('input.noo-mega-menu-enable').each(function() {
			var $this   = $( this );
			var $container = $this.closest( '.noo-menu-form' );
			if( $this.prop( 'checked' ) ) {
				var column = $container.find('.noo-mega-menu-columns select option:selected').val();
					column = parseInt( column.substr(column.length - 1) );

//				// Find menu columns
//				var $menu_item = $this.closest('.menu-item').next('.menu-item');
//				while( !$menu_item.hasClass('menu-item-depth-0') ) {
//					if( $menu_item.hasClass('menu-item-depth-1') ) {
//						$menu_item.find('.noo-menu-form').addClass('menu-column');
//
//						$menu_item.find('.noo-menu-form .noo-mega-menu-column-span option').each(function() {
//							if( parseInt( $(this).val() ) > column ) {
//								$(this).hide();
//							} else {
//								$(this).show();
//							}
//						});
//					}
//
//					$menu_item = $menu_item.next('.menu-item');
//				}
			}
		});
	});
} );