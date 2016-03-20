/*!
 * Start Bootstrap - Agnecy Bootstrap Theme (http://startbootstrap.com)
 * Code licensed under the Apache License v2.0.
 * For details, see http://www.apache.org/licenses/LICENSE-2.0.
 */

// jQuery for page scrolling feature - requires jQuery Easing plugin
$(function() {
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
});

// Highlight the top nav as scrolling occurs
$('body').scrollspy({
    target: '.navbar-fixed-top'
})


// Animated hamburger icon
var anchor = document.querySelectorAll('button.lines-button');

[].forEach.call(anchor, function(anchor){
    var open = false;
    anchor.onclick = function(event){
        event.preventDefault();
        if(!open){
            this.classList.add('close');
            open = true;
        }
        else{
            this.classList.remove('close');
            open = false;
        }
    }
});