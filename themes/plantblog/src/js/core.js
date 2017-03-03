// ==== CORE ==== //


// A simple wrapper for all your custom jQuery; everything in this file will be run on every page
;(function($){
  $(document).ready(function() {
		$('.site-header').scrollToFixed({
			preFixed: function() { 
				$(this).find('.site-description').css('display', 'none'); 
				// $(this).find('.site-title').css('font-size', '40px'); 
				$(this).find('#menu-main-navigation').css('margin-top', '10px'); 
				// $(this).find('.site-header').css('background-image', ''); 
				$(this).find('.wrap').css('padding', '10px 0 20px'); 
				$(this).css('height', '120px');

			},
			postFixed: function() { 
				$(this).find('.site-description').css('display', 'inline-block'); 
				// $(this).find('.site-title').css('font-size', '68px'); 
				$(this).find('#menu-main-navigation').css('margin-top','30px'); 
				// $(this).find('.site-header').css('background-image', 'url(images/treebark.jpg), url(images/treeline.png)'); 
				$(this).find('.wrap').css('padding', '40px 0 40px'); 
				$(this).css('height', '243px');

			}
		});

		// journal notes accordion
		$('.accordion .inside').hide();
		$('h4:before').css('content','\\f0d7');   
		$('.accordion .journal-title').click(function(e) {
			e.preventDefault();
	    	$('.journal-title h4').toggleClass('open');
		    $('.accordion > .inside').slideToggle('default');
		    // return false;
		  });
		  // before and after carousels
		$('.variable-width.multiple-slides').slick({
			// dots: true,
			infinite: true,
			speed: 300,
			arrows: true,
			slidesToShow: 2,
			centerMode: true,
			variableWidth: true
			});
	});
  $(window).load(function(){

  		var images = $(".two-slides .valign").toArray();
		  	// alert(verticalign.toString());

		    for (var i = 0; i < images.length; i++) {
		    	
		    	(function(i){
		    		var iwidth,
				    	iheight,
				    	cwidth,
				    	cheight,
				    	wdiff,
				    	hdiff;

					iwidth = $(images[i]).width();
			    	iheight = $(images[i]).height();

					cwidth = $(images[i]).parent().width();
			    	cheight = $(images[i]).parent().height();

			    	wdiff = cwidth - iwidth;
		    		hdiff = cheight - iheight;

		    		console.log("wdiff: " + wdiff + ", hdiff: " + hdiff);

		    	})(i)
				
				//return boolean
				// var is_img_too_narrow = diff(container_width, container_height, img_width, img_height);

				// console.log("container width: " + container_width);
				// if(container_width > img_width){
				// 	$(images[i]).css("width", "100%");
				// 	$(images[i]).css("height", "auto");
				// }else if(container_width > img_width{

				// }

		    }
		    function diff(cwidth, cheight, iwidth, iheight){
		    	
		    }
  	
	  		// Align images in before and after
		  // 	var verticalign = $(".two-slides .valign").toArray();
		  // 	// alert(verticalign.toString());

		  //   for (var i = 0; i < verticalign.length; i++) {

				// vwide = $(verticalign[i]).width();
		  //   	vtall = $(verticalign[i]).height();
		  //   	// alert(vwide);
				// vmartop = parseInt(vtall/2);
				// vmarleft = parseInt(vwide/2);
				
				// $(verticalign[i]).css("position","relative");
		  //       $(verticalign[i]).css("top","50%");
				// $(verticalign[i]).css("left","50%");
				// $(verticalign[i]).css("margin-top","-"+vmartop+"px");
				// $(verticalign[i]).css("margin-left","-"+vmarleft+"px");
		  //   }
	});
}(jQuery));

// ;(function($){
//   $(document).ready(function() {
// 		$('.site-header').scrollToFixed();
// 	});
// }(jQuery));
