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
			var id = e.target.getAttribute('id');
			var allEntries = getPlantEntries(id)
			var taxNames = getTaxonomyNames(allEntries, id);
			getSortedEntries(allEntries,taxNames);

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


	function getSortedEntries(entries, taxNames){

		var sectArray = [];
		for (let name of taxNames){
			sectArray.push(name);
			console.log(name);
			for (let item of entries){
				var classList = item.className; //get all classes in one string
				// console.log(classList);
				if( classList.includes(name) ){
					console.log(item + ' has the class '+ name);
				}else{console.log('item does not contain ' + name)}
			}
		}
	}

	function getPlantEntries(id){
		//selecting entries by regex not necessary. just get all the plants, then we filter them later.
		// var selectClassRE = '[class*='+ id + ']'; //create regex
    	// var selected = $(selectClassRE); //get the entry elements
    	var selected = $('div.plant'); //get the entry elements
    	return selected;
	}

	function getTaxonomyNames(entries, id){

		var matches = [];

		for (let item of entries) {
		 	var classList = item.className.split(/\s+/); //get all classes on each entry
		 	
		 	//extract the taxonomy name from the class,
		 	//add to an array  
		 	for (let oneClass of classList) {
		 		if (oneClass.includes(id)){
		 			matches.push( oneClass.substring(id.length) );
		 		};
		 	}
		}

		//dedupe the array
	 	matches = uniq(matches); // remove dupes
	 	// console.log(matches);
	 	return matches;
	}



		/*
		1. create an array containing all the classes that start with light-requirement- from all nodes that contain light-requirement-
		2. remove duplicates from array
		3. strip light-requirement- from strings
		4. return array containing the clean strings - these are our section titles.

		for each clean string in array,
			- print out string (h2 section header)
			- select nodes in object where the class contains 'light-requirement-' + clean string
			- output nodes
		*/
    	// var
    	// for (let lightReq of selected){

    	// }

		//TODO use jquery.unique to sort out duplicate elements

	// ES6
	// http://stackoverflow.com/questions/9229645/remove-duplicates-from-javascript-array
	function uniq(a) {
	   return Array.from(new Set(a));
	}




  function resizeImages(){
	console.log("resized");
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
