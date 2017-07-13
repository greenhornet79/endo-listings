( function( $ )  {


	$(document).ajaxSend( function( event, xhr ) {
		
	});

	$(document).ready( function() {
		
		$('#gform_1').submit(function(e) {
			
			var city = $('#input_1_4').val();
			var state = $('#input_1_3').val();
			var address = city + ' ' + state;
			var latlng = $('#input_1_28').val();

			if ( !latlng ) {
				e.preventDefault();
				var geocoder = new google.maps.Geocoder();

				geocoder.geocode( { 'address': address }, function(results, status) {
				    if (status == google.maps.GeocoderStatus.OK) {
				        var latlng = results[0].geometry.location;
				       
				        $('#input_1_28').attr('value', latlng);
				    }
				    else {
				        alert("Geocode was not successful for the following reason: " + status);
				    }
				});
				$('#gform_1').submit();
			}

			

		});
	});

	$(window).load( function() {
		
	});


})( jQuery );