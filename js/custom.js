jQuery(document).ready(function($) {
	
	remove_header_click();
	
	mob_submenu();
	
});

// Remove header click events
function remove_header_click() {
	jQuery(".mk-dashboard-trigger").unbind( "click" );
}



jQuery(document).ready(function($) {

	jQuery('.mk-product-holder .product-loop-thumb .product-item-footer a').on( "click", function() {
		event.preventDefault();

		var parent = jQuery(this).parent().parent().parent();
		var prod_name = jQuery("h3.product-title a",parent).text();
		//console.log(prod_name);

		jQuery('#popmake-596').popmake('open');

		jQuery('#popmake-596 li.inq_form_product input').val(prod_name);

		if(jQuery('#popmake-596 #product_details_wrapper')){
			jQuery('#popmake-596 #product_details_wrapper').remove();
		}

		jQuery('#popmake-596 .popmake-content .inquiry_form_data').prepend("<div id=\"product_details_wrapper\"><br><p style=\"text-align:center;\">For more information about:</p><h2>"+prod_name+"</h2></div>" );

		console.log(jQuery('#popmake-596 li.inq_form_product input').val());
	});


	jQuery('#popmake-596').on('pumBeforeClose', function () {
		alert('tests');
		if(jQuery('#popmake-596 #product_details_wrapper')){
			jQuery('#popmake-596 #product_details_wrapper').remove();
		}
	});

	jQuery(function() {
	    //caches a jQuery object containing the header element
	    var body = jQuery("body");
	    jQuery(window).scroll(function() {
	        var scroll = jQuery(window).scrollTop();
	
	        if (scroll >= 85) {
	            body.addClass("is-mobile-scrolled");
	        } else {
	            body.removeClass("is-mobile-scrolled");
	        }
	    });
	});
	
});

function mob_submenu() {
	jQuery( '.home_services_columns .vc_col-sm-4' ) .each(function () { 
		jQuery( this ).addClass( 'blah' );
		this_link = jQuery( this ).find( 'a' ).attr( 'href' );
// 		jQuery( this ).children( '.mk-text-block:first-child' ).wrapInner( '<a href="' + this_link + '" /a>' )
		//jQuery( this ).children( 'div:nth-child(2)' ).children( '.mk-text-block' ).wrapInner( '<a href="' + this_link + '" /a>' )
		jQuery( this ).children( 'div' ).children( '.mk-text-block' ).wrapInner( '<a href="' + this_link + '" /a>' )
	});
}







