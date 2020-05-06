jQuery(document).ready(function($) {
	function initialize() {
		$('.noo-maps').each(function(index, el) {
			
		  	var LatLng = { lat: parseInt( nooMaps.lat ), lng: parseInt( nooMaps.lng ) };
		  	var def_zoom = parseInt( nooMaps.zoom );
		  	var def_icon = nooMaps.icon_map;

		  	if ( $(this).data( 'lat' ) !== undefined && $(this).data( 'lat' ) !== '' && $(this).data( 'lng' ) !== undefined && $(this).data( 'lng' ) !== '' ) {
				
		  		var LatLng = { lat: parseFloat($(this).data( 'lat' )), lng: parseFloat($(this).data( 'lng' )) };


		  	}

		  	if ( $(this).data( 'zoom' ) !== undefined && $(this).data( 'zoom' ) !== '' ) {
		  		def_zoom = $(this).data( 'zoom' );
		  	}

		  	if ( $(this).data( 'icon' ) !== undefined && $(this).data( 'icon' ) !== '' ) {
		  		def_icon = $(this).data( 'icon' );
		  	}

			var map = new google.maps.Map($(this)[0], {
				    zoom: def_zoom,
				    center: LatLng
				}),

			  	marker = new google.maps.Marker({
				    position: LatLng,
				    map: map,
				    icon: def_icon
				});

				map.setOptions({
					draggable: true, 
					zoomControl: true, 
					scrollwheel: false, 
					disableDoubleClickZoom: false
				});

				marker.setMap( map );

		});
	}
	google.maps.event.addDomListener(window, 'load', initialize);
});