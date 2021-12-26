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
var cbpAnimatedTex = (function() {

	var docElem = document.documentElement,
		header1 = document.querySelector( '.clearfix' ),
		didScroll = false,
		textFadeOn = 11;

	function init() {
		window.addEventListener( 'scroll', function( event ) {
			if( !didScroll ) {
				didScroll = true;
				setTimeout( scrollPage, 250 );
			}
		}, false );
	}

    $(document).ready(function(){
      setTimeout( scrollPage, 250 );
    });


	function scrollPage() {
		var sy = scrollY();

        if ( sy >= textFadeOn ) {
            classie.add( header1, 'lead-text_fade' );
        }
        else {
            classie.remove( header1, 'lead-text_fade' );
        }



        didScroll = false;



	}

	function scrollY() {
		return window.pageYOffset || docElem.scrollTop;
	}

	init();

})();


