/* Add Custom Code Jquery
 ========================================================*/
$(document).ready(function(){
	// Messenger posmotion
	$( "#so_popup_countdown .list-cates .customer a.login" ).click(function() {
		$('body').toggleClass('hidden-popup-countdown');
	});

	$( ".header-form .button-header" ).click(function() {
		$('.toogle_content').slideToggle(200);
		$(this).toggleClass('active');
	});
	// Fix hover on IOS
	$('body').bind('touchstart', function() {}); 
	
	//Smooth scrolling and smart navigation
	$(function(){
		$('#nav-scroll').onePageNav({
			currentClass: 'active',
			changeHash: false,
			scrollSpeed: 750,
			scrollThreshold: 0.5,
			filter: '',
			easing: 'swing',
			
		});	
		
		var windowswidth = $(window).width();
		var containerwidth = 1170;
		var widthcss = (windowswidth-containerwidth)/2-60;
		var rtl = $( 'body' ).hasClass( 'rtl' );
		if( !rtl ) {
			$(".custom-scoll").css("left",widthcss);
		}else{
			$(".custom-scoll").css("right",widthcss);
		}
		if ($('#box-link1').length > 0) {
			$(".custom-scoll").fadeOut();
			$(window).scroll(function() {
				if( $(window).scrollTop() > $('#box-link1').offset().top - 50 ) {
					$(".custom-scoll").fadeIn();
				} else {
					$(".custom-scoll").fadeOut();
				}
		
			});

        }
		
	});

	

	// Messenger Top Link
	// $('.home1-sevices ul').owlCarousel2({
	// 	pagination: false,
	// 	center: false,
	// 	nav: false,
	// 	dots: false,
	// 	loop: true,
	// 	slideBy: 1,
	// 	autoplay: true,
	// 	margin: 0,
	// 	autoplayTimeout: 8500,
	// 	autoplayHoverPause: true,
	// 	autoplaySpeed: 1200,
	// 	startPosition: 0, 
	// 	responsive:{
	// 		0:{
	// 			items:1
	// 		},
	// 		480:{
	// 			items:2
	// 		},
	// 		768:{
	// 			items:3
	// 		},
	// 		1200:{
	// 			items:4
	// 		}
	// 	}
	// });

	 
    $(document).ready(function(){
        $(".topbar-close").click(function(){
            $(".coupon-code").slideToggle();
        });
        $(".button").on('click',function(){
                if($('.button').hasClass('active')){
                    $('.button').removeClass('active');
                }else{
                    $('.button').removeClass('active');
                    $('.button').addClass('active');
                }
         });
    });
    
    
   
    $(document).ready(function($) {
        if($(window).width() < 1199){
            $(function(){
                $('.bonus-menu ul').addClass('test');
                $('.test').owlCarousel2({
                    pagination: false,
                    center: false,
                    nav: false,
                    dots: false,
                    loop: true,
                    margin: 0,
                    navText: [ '<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>' ],
                    slideBy: 1,
                    autoplay: true,
                    autoplayTimeout: 2500,
                    autoplayHoverPause: true,
                    autoplaySpeed: 800,
                    startPosition: 0, 
                    responsive:{
                        0:{
                            items:1
                        },
                        481:{
                            items:2
                        },
                        992:{
                            items:3
                        }
                    }
                });

            });
        }
    });
    

});
