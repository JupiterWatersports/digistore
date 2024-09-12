/*global SelectBox, PureGrid */
/* ----------------- Start Document ----------------- */
(function($){
	"use strict";

	$(document).ready(function(){

	


		// Mobile Nav 

	jQuery( document ).ready( function( $ ) {


	var $menu = $('#menu2'),
	  $menulink = $('.menu-link'),
	  $menuTrigger = $('.has-submenu > a');

	$menulink.click(function(e) {
		e.preventDefault();
		$menulink.toggleClass('active');
		$menu.toggleClass('active');
	});

	$menuTrigger.click(function(e) {
		e.preventDefault();
		var $this = $(this);
		$this.toggleClass('active').next('ul, div.sub-menu').toggleClass('active');
	});

});

	// Mobile Footer Accordion
	
	jQuery( document ).ready( function( $ ) {

	var $menu = $('#footer-menu'),
	  $menulink = $('.men-link'),
	  $menuTrigger = $('.has-submen > h3');

	$menulink.click(function(e) {
		e.preventDefault();
		$menulink.toggleClass('active');
		$menu.toggleClass('active');
	});

	$menuTrigger.click(function(e) {
		e.preventDefault();
		var $this = $(this);
		$this.toggleClass('active').next('ul').toggleClass('active');
	});

});
 


		// Initialise Superfish
		//----------------------------------------//

		

$(function () {
        var outer = $('.xsell-carousel');

        $('#xsell-left').click(function () {
           var leftPos = outer.scrollLeft();
           outer.animate({ scrollLeft: leftPos - 853 }, 700);
        });

        $('#xsell-right').click(function () {
           var leftPos = outer.scrollLeft();
           outer.animate({ scrollLeft: leftPos + 853 }, 700);
        });
     });

  
  $(function () {
        var outer = $('.also-purchased-carousel');

        $('#also-left').click(function () {
           var leftPos = outer.scrollLeft();
           outer.animate({ scrollLeft: leftPos - 853 }, 700);
        });

        $('#also-right').click(function () {
           var leftPos = outer.scrollLeft();
           outer.animate({ scrollLeft: leftPos + 853 }, 700);
        });
     });



		//----------------------------------------//
		$('#categories > li a').click(function(e){
			if($(this).parent().hasClass('has-sublist')) {
				e.preventDefault();
			}
			if ($(this).attr('class') != 'active'){
				$(this).parent().siblings().find('ul').slideUp();
				$(this).next().slideToggle();
				if($(this).parent().hasClass("has-sublist")){

					$(this).parent().siblings().find('a').removeClass('active');
					$(this).addClass('active');
				} else {
					var curlvl = $(this).parent().data('level');
					if(curlvl){
						$('#categories li.child-'+curlvl+' a').removeClass('active');
					}
				}

			} else {
				console.log('tu jestem');
				$(this).next().slideToggle();
				$(this).parent().find('ul').slideUp();
				var curlvl = $(this).parent().data('level');
				console.log(curlvl);
				if(curlvl){
					$('#categories li.child-'+curlvl+' a').removeClass('active');
				}
			}
		});






		// Retina Images
		//----------------------------------------//

		var pixelRatio = !!window.devicePixelRatio ? window.devicePixelRatio : 1;

		$(window).on("load", function() {
			if (pixelRatio > 1) {
				$('#logo img').each(function() {
					$(this).attr('src', $(this).attr('src').replace(".","@2x."));
				});
			}
		});



		// Share Buttons
		//----------------------------------------//

		var $Filter = $('.share-buttons');
		var FilterTimeOut;
		$Filter.find('ul li:first').addClass('active');
		$Filter.find('ul li:not(.active)').hide();
		$Filter.hover(function(){
			clearTimeout(FilterTimeOut);
			if( $(window).width() < 959 )
			{
				return;
			}
			FilterTimeOut=setTimeout(function(){
				$Filter.find('ul li:not(.active)').stop(true, true).animate({width: 'show' }, 250, 'swing');
				$Filter.find('ul li:first-child a').addClass('share-hovered');
			}, 100);

		},function(){
			if( $(window).width() < 960 )
			{
				return;
			}
			clearTimeout(FilterTimeOut);
			FilterTimeOut=setTimeout(function(){
				$Filter.find('ul li:not(.active)').stop(true, true).animate({width: 'hide'}, 250, 'swing');
				$Filter.find('ul li:first-child a').removeClass('share-hovered');

			}, 250);
		});
		$(window).resize(function() {
			if( $(window).width() < 960 )
			{
				$Filter.find('ul li:not(.active)').show();
			}
			else
			{
				$Filter.find('ul li:not(.active)').hide();
			}
		});
		$(window).resize();




		//	Back To Top Button
		//----------------------------------------//

		var pxShow = 600; // height on which the button will show
		var fadeInTime = 400; // how slow / fast you want the button to show
		var fadeOutTime = 400; // how slow / fast you want the button to hide
		var scrollSpeed = 400; // how slow / fast you want the button to scroll to top.

		jQuery(window).scroll(function(){
			if(jQuery(window).scrollTop() >= pxShow){
				jQuery("#backtotop").fadeIn(fadeInTime);
			} else {
				jQuery("#backtotop").fadeOut(fadeOutTime);
			}
		});
			 
		jQuery('#backtotop a').click(function(){
			jQuery('html, body').animate({scrollTop:0}, scrollSpeed); 
			return false; 
		}); 



		// Contact Form
		//----------------------------------------//

		$("#contactform .submit").click(function(e) {


			e.preventDefault();
			var user_name       = $('input[name=name]').val();
      var user_email      = $('input[name=email]').val();
      var user_comment    = $('textarea[name=comment]').val();

      //simple validation at client's end
      //we simply change border color to red if empty field using .css()
      var proceed = true;
      if(user_name===""){
					$('input[name=name]').addClass('error');
						proceed = false;
					}
					if(user_email===""){
						$('input[name=email]').addClass('error');
						proceed = false;
					}
					if(user_comment==="") {
						$('textarea[name=comment]').addClass('error');
						proceed = false;
					}

					//everything looks good! proceed...
					if(proceed) {
						$('.hide').fadeIn();
						$("#contactform .submit").fadeOut();
							//data to be sent to server
							var post_data = {'userName':user_name, 'userEmail':user_email, 'userComment':user_comment};

							//Ajax post data to server
							$.post('contact.php', post_data, function(response){
								var output;
								//load json data from server and output comment
								if(response.type == 'error')
									{
										output = '<div class="error">'+response.text+'</div>';
										$('.hide').fadeOut();
										$("#contactform .submit").fadeIn();
									} else {

										output = '<div class="success">'+response.text+'</div>';
										//reset values in all input fields
										$('#contact div input').val('');
										$('#contact textarea').val('');
										$('.hide').fadeOut();
										$("#contactform .submit").fadeIn().attr("disabled", "disabled").css({'backgroundColor':'#c0c0c0', 'cursor': 'default' });
									}

									$("#result").hide().html(output).slideDown();
								}, 'json');
						}
			});

			//reset previously set border colors and hide all comment on .keyup()
			$("#contactform input, #contactform textarea").keyup(function() {
				$("#contactform input, #contactform textarea").removeClass('error');
				$("#result").slideUp();
			});





   // ------------------ End Document ------------------ //
});

})(this.jQuery);