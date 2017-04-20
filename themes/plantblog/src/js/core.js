// ==== CORE ==== //


// A simple wrapper for all your custom jQuery; everything in this file will be run on every page
;(function($){
  $(document).ready(function() {
		

  		//scroll button
  		$('.back-to-top').on('click', function(e){
  			e.target.preventDefault;
  			$('body').animate({'scrollTop': '0px'}, 700);
  		})
  		$(window).scroll(function(){
	  		// console.log('button scrolled: ' + ofst.top );
	  		// console.log('window y: ' + window.scrollY);
			var opcty = $('.back-to-top').css('opacity');

	  		if(window.scrollY > 1000 && opcty === '0'){
	  			// console.log('make visible');
	  			// $('.back-to-top').css({'opacity': '1', 'transition': 'opacity 0.5s ease' });
	  			$('.back-to-top').toggleClass('off');
	  		}
	  		if(window.scrollY < 1000 && opcty === '1'){
	  			// console.log('make invisible');
	  			$('.back-to-top').toggleClass('off');

	  			// $('.back-to-top').css({'opacity': '0', 'transition': 'opacity 0.5s ease' });
	  		}
  		})

  		//REFACTOR
		// journal notes accordion
		$('.accordion-1 .inside').hide();
		$('h4:before').css('content','\\f0d7');   
		$('.accordion-1 .accordion-title').click(function(e) {
			e.preventDefault();
	    	$('.accordion-1 .accordion-title h4').toggleClass('open');
		    $('.accordion-1 > .inside').slideToggle('default');
		    // return false;
		  });

		$('.accordion-2 .inside').hide();
		$('h4:before').css('content','\\f0d7');   
		$('.accordion-2 .accordion-title').click(function(e) {
			e.preventDefault();
	    	$('.accordion-2 .accordion-title h4').toggleClass('open');
		    $('.accordion-2 > .inside').slideToggle('default');
		    // return false;
		  });
		
		// Options for the before and after carousels
		$('.variable-width.multiple-slides').slick({
			// dots: true,
			infinite: false,
			speed: 300,
			arrows: true,
			slidesToShow: 1,
			centerMode: false,
			variableWidth: false,
			adaptiveHeight: true
			});

		//get all the entries from the Plant List page
		if ( document.body.className.match('post-type-archive-plant') ){
			var allEntries = getAllEntries();
		}

		// Hook up sorter functionality for Plant List page
		$('#sorter a').on('click', function(e){
			e.preventDefault();

			var groupName = e.target.getAttribute('id');

			//disable action for the active state
			if(e.target.parentNode.classList.contains('active-link')){
				return;
			}else{
				$('.masonry.pb-wrap').fadeTo( 200, '0', function() {
					$('.masonry.pb-wrap').empty();
					sortEntriesBySubcategory(allEntries, groupName);
				});
			}

			$('#sorter li').removeClass('active-link');
			e.target.parentNode.classList.toggle('active-link');
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

	function getAllEntries(){
    	var selected = $('div.plant'); //get the entry elements
    	return selected;
	}

	function buildArticleContainer(i,groupName,filterView){
		// build the DOM elements for each section container
		var article;
		var anchor;
		var headline;
		var title;
		
		article = document.createElement("article");
		article.className += " item entry-section";
		anchor = document.createElement("a");

		// //temporary for localhost
			anchor.href = '/plantblog/' + groupName + '/' + i;
		
		headline = document.createElement("h2");
		title = document.createTextNode(filterView[i]);
		headline.append(title); 
		anchor.append(headline);
		article.insertBefore(anchor, null);

		return article;
	}

	function sortEntriesBySubcategory(entries, groupName){

	 	var sortedEntries = [];
	 	
	 	// get the nested object for this view.
	 	// the nested object contains key/value pairs 
	 	// of all the taxonomy terms for this view
 		var filterView = taxonomy_data[groupName];

 		// loop through the taxonomies
	 	for ( var i in filterView) {
			
			var article;
			article = buildArticleContainer(i,groupName,filterView);

			var tempContainer = [];

			for (let item of entries) {
				//create an array from the classes on the entry
			 	var classList = item.className.split(/\s+/); //get all classes on each entry
		 		
		 		if (classList.includes(i)){
		 			// article.appendChild(item);
		 			tempContainer.push(item);

		 			//flag item so we know we've picked it
		 			item['picked'] = true;
		 		};
			 }

			if ( tempContainer.length > 0){

			 	//sort the entries based on first alpha character (skip quote marks)
			 	tempContainer.sort(function(a,b){
			 		var aFirst = (a.textContent.match(/[a-zA-Z]/) || []).pop();
			 		var bFirst = (b.textContent.match(/[a-zA-Z]/) || []).pop();
			 		console.log(aFirst)
			 		if (aFirst < bFirst) return -1;
			 		if (aFirst > bFirst) return 1;

			 		return 0;
			 	});

				// populate the article node with sorted entries;
				for(var i = 0; i < tempContainer.length; i++ ) {
					article.appendChild(tempContainer[i]);
				}

			 	sortedEntries.push(article);
			 }else {
			 	article = null;
			 }
		}
		//reset picked property for next round
		
		//flatten multidimensional array here
		// sortedEntries = [].concat.apply([], sortedEntries);
		displayEntries(sortedEntries);
	}

	function displayEntries(sorted){
		$('.masonry.pb-wrap').css('opacity', '0');
		$('.masonry.pb-wrap').append(sorted);
		$('.masonry.pb-wrap').fadeTo( 200, '1' );
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
