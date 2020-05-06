jQuery(document).ready(function($) {
	function initialize() {
		$('.noo-event-maps').each(function(index, el) {
			
		  	var LatLng = { lat: 51.508742, lng: -0.120850 };

		  	if ( $(this).data( 'lat' ) !== undefined && $(this).data( 'lat' ) !== '' && $(this).data( 'lng' ) !== undefined && $(this).data( 'lng' ) !== '' ) {
				
		  		var LatLng = { lat: parseFloat( $(this).data( 'lat' ) ) , lng: parseFloat( $(this).data( 'lng' ) ) };

		  	}

		  	var def_zoom = 11;
		  	if ( $(this).data( 'zoom' ) !== undefined && $(this).data( 'zoom' ) !== '' ) {
		  		def_zoom = $(this).data( 'zoom' );
		  	}

			var map = new google.maps.Map($(this)[0], {
				    zoom: def_zoom,
				    center: LatLng
				}),

			  	marker = new google.maps.Marker({
				    position: LatLng,
				    map: map,
				    icon: nooEventMaps.assets_url + 'images/map-maker.png'
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