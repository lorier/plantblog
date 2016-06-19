// ==== CORE ==== //


// A simple wrapper for all your custom jQuery; everything in this file will be run on every page
;(function($){
  $(document).ready(function() {
		$('.site-header').scrollToFixed({
			preFixed: function() { 
				$(this).find('.site-description').css('display', 'none'); 
				$(this).find('.site-title').css('font-size', '40px'); 
				$(this).find('#menu-main-navigation').css('margin-top', '10px'); 
				// $(this).find('.site-header').css('background-image', ''); 
				$(this).find('.wrap').css('padding', '10px 0 20px'); 
				$(this).css('height', '120px');

			},
			postFixed: function() { 
				$(this).find('.site-description').css('display', 'inline-block'); 
				$(this).find('.site-title').css('font-size', '68px'); 
				$(this).find('#menu-main-navigation').css('margin-top','30px'); 
				// $(this).find('.site-header').css('background-image', 'url(images/treebark.jpg), url(images/treeline.png)'); 
				$(this).find('.wrap').css('padding', '40px 0 40px'); 
				$(this).css('height', '258px');

			}
		});
	});
}(jQuery));

// ;(function($){
//   $(document).ready(function() {
// 		$('.site-header').scrollToFixed();
// 	});
// }(jQuery));
