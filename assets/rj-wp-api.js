( function($){
	$(document).ready( function(){
		// Hide loading image
		$('#load').hide();
		// Site Checkbox Event
		$('#_mahajanga').click( function(){
			if( $(this).prop('checked') ){
				$('#province').val('Mahajanga');
				$('#email').val('info@mahajanga-immobilier.com');
				loadTerms('http://mahajanga-immobilier.com', 'ad_cat');
			}
		} );
		$('#_diego').click( function(){
			if( $(this).prop('checked') ){
				$('#province').val('Diego-Suarez');
				$('#email').val('info@diego-suarez-immobilier.com');
				loadTerms('http://www.diego-suarez-immobilier.com', 'ad_cat');
			}
		} );
		$('#_real').click( function(){
			if( $(this).prop('checked') ){
				loadTerms('http://real-estate-madagascar.com', 'ad_cat');
			}
		} );
		$('#_aim').click( function(){
			if( $(this).prop('checked') ){
				loadTerms('https://www.agence-immobiliere-madagascar.com', 'listing');
			}
		} );
		$('#_property').click( function(){
			if( $(this).prop('checked') ){
				loadTerms('https://property-madagascar.com', 'listing');
			}
		} );
		var loadTerms = function( site, taxonomy ){
			formdata = {
				'site': site,
				'taxonomy': taxonomy
			}
			var data = {
				'action': 'loadterm',
				'site': site,
				'taxonomy': taxonomy,
			}
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: data,
				beforeSend: function(){ 
					$('#category .row.mb-1.no-gutters').remove();
					$('#load').show(); 
				},
				success: function( response ){
					$('#category p').hide();
					$('#category').append( response );
				},
				complete: function(){
					$('#load').hide();
				}
			});
		};

		// Preview images before Upload
		var imagesPreview = function(input, placeToInsertImagePreview) {
	        if (input.files) {
	            var filesAmount = input.files.length;
	            for (i = 0; i < filesAmount; i++) {
	                var reader = new FileReader();
	                reader.onload = function(event) {
	                    $($.parseHTML('<img>')).attr('src', event.target.result).attr('class', 'img-fluid img-thumbnail').appendTo(placeToInsertImagePreview);
	                }
	                reader.readAsDataURL(input.files[i]);
	            }
	        }
	    };
	    $('#mahajanga_img').on('change', function() {
	        imagesPreview(this, '#mahajanga_img + div');
	    });
	    $('#diego_img').on('change', function() {
	        imagesPreview(this, '#diego_img + div');
	    });
	    $('#real_img').on('change', function() {
	        imagesPreview(this, '#real_img + div');
	    });
	    $('#property_img').on('change', function() {
	        imagesPreview(this, '#real_img + div');
	    });
	    $('#aim_img').on('change', function() {
	        imagesPreview(this, '#aim_img + div');
	    });

	    

	}); // ./document ready
} )(jQuery);