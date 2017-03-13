// ==== CORE ==== //


// A simple wrapper for all your custom jQuery; everything in this file will be run on every page
;(function($){
  $(document).ready(function() {
		// $('.site-header').scrollToFixed({
		// 	preFixed: function() { 
		// 		$(this).find('.site-description').css('display', 'none'); 
		// 		// $(this).find('.site-title').css('font-size', '40px'); 
		// 		$(this).find('#menu-main-navigation').css('margin-top', '10px'); 
		// 		// $(this).find('.site-header').css('background-image', ''); 
		// 		$(this).find('.wrap').css('padding', '10px 0 20px'); 
		// 		$(this).css('height', '120px');

		// 	},
		// 	postFixed: function() { 
		// 		$(this).find('.site-description').css('display', 'inline-block'); 
		// 		// $(this).find('.site-title').css('font-size', '68px'); 
		// 		$(this).find('#menu-main-navigation').css('margin-top','30px'); 
		// 		// $(this).find('.site-header').css('background-image', 'url(images/treebark.jpg), url(images/treeline.png)'); 
		// 		$(this).find('.wrap').css('padding', '40px 0 40px'); 
		// 		$(this).css('height', '243px');

		// 	}
		// });

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
			// centerMode: true,
			variableWidth: true
			});

		$('#sorter a').on('click', function(e){
			e.preventDefault();
			var groupName = e.target.getAttribute('id');
			var allEntries = getAllEntries(groupName)
			var sorted = sortEntries(allEntries, groupName);
			displayEntries(sorted);


			// console.log(allEntries);
			// console.log(names);
		})

		$(window).resize(function(){
			//TODO figure out resizing algorithm for this
			// resizeImages();
		});
		
		
	}); //end doc ready

	$(window).load(function(){
		//resize images on the static before and after entries
  		resizeImages();
	}); //end window load


	function displayEntries(sorted){
		$('.masonry.pb-wrap').fadeOut( 200, function() {
			$('.masonry.pb-wrap').empty();
			$('.masonry.pb-wrap').append(sorted);
			$('.masonry.pb-wrap').fadeIn( 200 );
		 });
	}

	function getAllEntries(groupName){
		//selecting entries by regex not necessary. just get all the plants, then we filter them later.
		// var selectClassRE = '[class*='+ groupName + ']'; //create regex
    	// var selected = $(selectClassRE); //get the entry elements
    	var selected = $('div.plant'); //get the entry elements
    	return selected;
	}

	function sortEntries(entries, groupName){

	 	var sortedEntries = [];
	 	// get the nested object for this view.
	 	// the nested object contains key/value pairs 
	 	// of all the taxonomy terms for this view
 		var filterView = taxonomy_data[groupName];

 		// loop through the taxonomies
	 	for ( var i in filterView) {
			
			var matches = [];

			// add the taxonomy term as a header
			matches.push('<h2>' + filterView[i] + '</h2>');

			for (let item of entries) {
				
				//create an array from the classes on the entry
			 	var classList = item.className.split(/\s+/); //get all classes on each entry
		 	
			 		// if our taxonomy term exists in this entry
			 		// add the entry to our array
			 		if (classList.includes(i)){
			 			matches.push( item );
			 		};
			 }
			 // if any entries matched any tax terms, then push the matches array
			 // to a holding array
			 if (matches.length > 1 ){
			 	sortedEntries.push(matches);
			 }else {
			 	matches = [];
			 }

		}
		//flatten multidimensional array here
		sortedEntries = [].concat.apply([], sortedEntries);
		console.log(sortedEntries);

		return sortedEntries;
	}

	// ES6
	// http://stackoverflow.com/questions/9229645/remove-duplicates-from-javascript-array
	function uniq(a) {
	   return Array.from(new Set(a));
	}

  function resizeImages(){
	//TODO make this work with window resize. Math isn't working right currently.
  	var iwidth,
	    	iheight,
	    	cwidth,
	    	cheight,
	    	wdiff,
	    	hdiff,
	    	proportion;

		var images = $(".two-slides .valign").toArray();
	  	// alert(verticalign.toString());

	    for (var i = 0; i < images.length; i++) {
	    	// console.log("IMAGE" + i);
			iwidth = $(images[i]).width();
	    	iheight = $(images[i]).height();

	    	proportion = iwidth/iheight;

    		if ( (iwidth <= iheight) ) {
    			$(images[i]).css("width", "100%");
    			//get new width
    			iheight = $(images[i]).height();
				iwidth = $(images[i]).width();
		    	// console.log("height: " + iheight);
		    	// console.log("width: " + iwidth);

    		} else {
    			$(images[i]).css("height", "100%");

    			//get new height
    			iheight = $(images[i]).height();

    			//fix width to match original image proportions
    			var iwidth =  iheight*proportion;
    			$(images[i]).css("width", iwidth);
    		}
    		//https://github.com/devasaur/vlign.js/blob/master/vlign.js
			vmartop = parseInt(iheight/2);
			vmarleft = parseInt(iwidth/2);
			
			$(images[i]).css("position","relative");
	        $(images[i]).css("top","50%");
			$(images[i]).css("left","50%");
			$(images[i]).css("margin-top","-"+vmartop+"px");
			$(images[i]).css("margin-left","-"+vmarleft+"px");
	    }
}
}(jQuery));

// ;(function($){
//   $(document).ready(function() {
// 		$('.site-header').scrollToFixed();
// 	});
// }(jQuery));
