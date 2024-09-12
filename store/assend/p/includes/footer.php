<footer class="footer">

</footer>

<style>
.overlay:before {
  content:"";
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: block;
  background: rgba(0, 0, 0, 0.4);
  position: fixed;
  z-index: 2;
}

.overlay2:before {
  content:"";
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: block;
  background: rgba(0, 0, 0, 0.5);
  position: fixed;
  z-index: 2;
}

</style>

<script type="text/javascript" src="ext/jquery/ui/controller.js"></script>
<script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/custom.js"></script>
<script src="js/superfish.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script>
	
	
	
$(document).ready(function() { 
$('.topheading').hover(function(){     
        $('.container-fluid').addClass('overlay');    
    },
	function(){    
        $('.container-fluid').removeClass('overlay');     
    });
});

$('.mobile-search-icon').on('click', function(e){

    $("#search").toggle();
	$(".mobile-search-icon .fa-times").toggle();
 	var searchbox = document.getElementById('keywords');
	searchbox.focus();
  $('.container-fluid').toggleClass('overlay2');    
  
    
});
	
	$('#showfilter').click(function(e){

    $("#column_left").toggle();
    
});
	
	$('#mobile-control').click(function(e){
	$('#sortBY').toggle();	
	})

	var clicker = $('#menu2 li.topheading');	
clicker.on('touchstart', function (e) {
    'use strict'; //satisfy code inspectors
    var link = $(this); //preselect the link
    if (link.hasClass('hover')) {
        return true;
    } else {
        link.addClass('hover');
  		
		clicker.not(this).removeClass('hover');
        e.preventDefault();
        return false; //extra, and to make sure the function has consistent return points
    }
});	
	
$(document).click(function(event) { 
    if(!$(event.target).closest('li.topheading').length) {
        if($('li.topheading').is(":visible")) {
            $('li.topheading').removeClass("hover");
        }
    }        
});
	
// see whether device supports touch events (a bit simplistic, but...)
var hasTouch = ("ontouchstart" in window);
var iOS5 = /iPad|iPod|iPhone/.test(navigator.platform) && "matchMedia" in window;
 
// hook touch events for drop-down menus
// NB: if has touch events, then has standards event handling too
// but we don't want to run this code on iOS5+
if (hasTouch && document.querySelectorAll && !iOS5) {
    var i, len, element,
        dropdowns = document.querySelectorAll("#log li > a");
		
 
    function menuTouch(event) {
        // toggle flag for preventing click for this link
        var i, len, noclick = !(this.dataNoclick);
 
        // reset flag on all links
        for (i = 0, len = dropdowns.length; i < len; ++i) {
            dropdowns[i].dataNoclick = false;
        }
 
        // set new flag value and focus on dropdown menu
        this.dataNoclick = noclick;
        this.focus();
    }
 
    function menuClick(event) {
        // if click isn't wanted, prevent it
        if (this.dataNoclick) {
            event.preventDefault();
        }
    }
 
    for (i = 0, len = dropdowns.length; i < len; ++i) {
        element = dropdowns[i];
        element.dataNoclick = false;
        element.addEventListener("touchstart", menuTouch, false);
        element.addEventListener("click", menuClick, false);
    }
}

</script>

