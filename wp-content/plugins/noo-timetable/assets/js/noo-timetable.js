function htmlEscape(s) {
    return (s + '').replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/'/g, '&#039;')
        .replace(/"/g, '&quot;')
        .replace('|', '<span>')
        .replace('~', '</span>')
        .replace(/\n/g, '<br />');
}

function setTodayListView() {
	jQuery('.fc-list-heading').each(function(i, el) {
		var list_heading = jQuery(el);
		var date  = list_heading.data('date');
        date = moment(date).format( 'YYYY-MM-DD' );
		var current_date = moment().format( 'YYYY-MM-DD' );
        if(date === current_date) {
            list_heading.find('.fc-widget-header').addClass('fc-today');
        }
	});
}

function holidayRestyle() {
    [].forEach.call(document.querySelectorAll( '.fc-noo-class-holiday' ), function (el, i) {
    	var currentEventHeight = parseFloat(el.clientHeight);
        var currentEventWidth = parseFloat(el.clientWidth);
        var eventsTotalHeight = parseFloat(el.closest('.fc-row').clientHeight);
        var dayHeader = el.closest('table').firstChild;
		var dayHeaderheight = parseFloat(dayHeader.clientHeight);

        /* Style for day header */
        dayHeader.style.position = 'relative';
        dayHeader.style.zIndex = '5';
		/* end */

        el.style.position = 'absolute';
        el.style.zIndex = '98';
        el.style.margin = 0;
        el.style.padding = 0;
        // el.style.top = (dayHeaderheight + 1) + 'px';
        // el.style.height = ((eventsTotalHeight - dayHeaderheight) - 3) + 'px';
        el.style.top = 0;
        el.style.height = (eventsTotalHeight - 2) + 'px';
        el.style.width = (currentEventWidth + 1) + 'px';
    })
}

function ModalEffectsInit() {

    [].forEach.call(document.querySelectorAll( '.md-trigger' ), function (el, i) {
	/*[].slice.call( document.querySelectorAll( '.md-trigger' ) ).forEach( function( el, i ) {*/
		var modal = document.querySelector( '#' + el.getAttribute( 'data-modal' ) );
		if ( ! modal ) return false;

		var close = modal.querySelector('.md-close');

		function removeModal( hasPerspective ) {
			classie.remove( modal, 'md-show' );

			if( hasPerspective ) {
				classie.remove( document.documentElement, 'md-perspective' );
			}
		}

		function removeModalHandler() {
			removeModal( classie.has( el, 'md-setperspective' ) );
		}

		function decodeEntities(encodedString) {
		    var textArea = document.createElement('textarea');
		    textArea.innerHTML = encodedString;
		    return textArea.value;
		}
		el.addEventListener( 'click', function( ev ) {
			ev.preventDefault();
			classie.add( modal, 'md-show' );
			jQuery(modal).next().unbind( 'click', removeModalHandler );
			jQuery(modal).next().bind( 'click', removeModalHandler );
			if( classie.has( el, 'md-setperspective' ) ) {
				setTimeout( function() {
					classie.add( document.documentElement, 'md-perspective' );
				}, 25 );
			}

			$input = jQuery(el).find('[type="hidden"]').val();
			$event = jQuery.extend({}, JSON.parse($input));

			if ( $event.excerpt === "undefined" ) {
				$event.excerpt = '';
			} else {
				$event.excerpt = decodeEntities($event.excerpt);
			}
			if($event.categoryName !== undefined && $event.categoryName !== '') {
                jQuery(modal).find('h3')
                    .text($event.categoryName)
                    .css('background', $event.catColor)
                    .css('color', '#fff')
                    .css('opacity', '1')
                    .css('display', 'block');
            } else {
                jQuery(modal).find('h3').css('display', 'none');
			}

            if($event.popup_bgImage != null) {
                jQuery(modal).find('.fc-thumb')
                    .css('background-image', 'url(' + $event.popup_bgImage + ')');
            } else {
                jQuery(modal).find('.fc-thumb')
                    .css('background-image', 'none');
			}

            var time = jQuery(el).find('.fc-time').text();
            if(time === '') {
                time = jQuery(el).find('.fc-list-item-time').text();
			}
			jQuery(modal).find('.fc-time').text( time );
			//}
			if ( $event.url === "undefined" || $event.url === '' ) {
				jQuery(modal).find('.fc-title a')
					.attr('href', '#')
					.attr('onclick', 'return false');
			} else {
				jQuery(modal).find('.fc-title a')
					.attr('href', $event.url);
			}

            if($event.title != null) {
                jQuery(modal).find('.fc-title a')
                    .text($event.title);
            } else {
                jQuery(modal).find('.fc-title a')
                    .text('');
			}

            if($event.trainer != null) {
                jQuery(modal).find('.fc-trainer')
                    .html($event.trainer);
            } else {
                jQuery(modal).find('.fc-trainer')
                    .html('');
			}

            if($event.level != null) {
                jQuery(modal).find('.fc-level')
                    .html($event.level);
            } else {
                jQuery(modal).find('.fc-level')
                    .html('');
			}

            if($event.address != null) {
                jQuery(modal).find('.fc-address')
                    .html($event.address);
            } else {
                jQuery(modal).find('.fc-address')
                    .html('');
			}

            if($event.excerpt != null) {
                jQuery(modal).find('.fc-excerpt')
                    .html($event.excerpt);
            } else {
                jQuery(modal).find('.fc-excerpt')
                    .html('');
			}
			if ( $event.register_link === "undefined" || $event.register_link === '') {
				jQuery(modal).find('.fc-register a')
					.attr('href', '#')
					.attr('onclick', 'return false');

				// Remove register link
				jQuery(modal).find('.fc-register')
					.css ('display', 'none');
			} else {
				jQuery(modal).find('.fc-register a')
					.attr('href', $event.register_link);
			}

			jQuery(modal).removeClass('event-content');
			if ( classie.has( el, 'fc-noo-event' ) )
			{
				jQuery(modal).addClass('event-content');

				jQuery(modal).find('.fc-thumb').hide();

				jQuery(modal).find('.div_content')
					.addClass('overlay')
					.css( 'background-image', 'url('+ $event.popup_bgImage +')' );
			}

			else
			{
				jQuery(modal).find('.fc-thumb').show();

				jQuery(modal).find('.div_content')
					.removeClass('overlay')
					.css( 'background-image', 'none' );
			}


			ev.preventDefault();
		});

		close.addEventListener( 'click', function( ev ) {
			ev.stopPropagation();
			removeModalHandler();
		});

	} );

}
