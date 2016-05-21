// ==== CORE ==== //


// A simple wrapper for all your custom jQuery; everything in this file will be run on every page
;(function($){
  $(document).ready(function() {
	console.log('scroll');
		$('.site-header').scrollToFixed();
	});
  $(function(){
 
  });
}(jQuery));
