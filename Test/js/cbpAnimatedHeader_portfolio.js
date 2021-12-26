/**
 * cbpAnimatedHeader.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2013, Codrops
 * http://www.codrops.com
 */
var cbpAnimatedHeader = (function() {

	var docElem = document.documentElement,
		header = document.querySelector( '.navbar-default' ),
		changeHeaderOn = 300;

    $(document).ready(function(){
      setTimeout( scrollPage, 250 );

        $('.gallery').bootstrapGallery({
            iconset: "fontawesome"
        });

    });

	function scrollPage() {
		var sy = changeHeaderOn;
        classie.add( header, 'navbar-shrink' );
//		if ( sy >= changeHeaderOn ) {
//			classie.add( header, 'navbar-shrink' );
//		}
//		else {
//			classie.remove( header, 'navbar-shrink' );
//		}
//		didScroll = false;
	}

	function scrollY() {
		return window.pageYOffset || docElem.scrollTop;
	}

	init();

})();