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
		
		// Options for the before and after carousels
		$('.variable-width.multiple-slides').slick({
			// dots: true,
			infinite: true,
			speed: 300,
			arrows: true,
			slidesToShow: 2,
			// centerMode: true,
			variableWidth: true
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

 		//if we've marked items as clones in the previous sorting,
 		//remove them now.
 		// for (let item of entries){
			// item['picked'] = false;
			// if(item.hasOwnProperty('myClone')){
			// 	console.log(item);
			// 	item.remove();
			// }
		// }

 		// loop through the taxonomies
	 	for ( var i in filterView) {
			
			var article;
			article = buildArticleContainer(i,groupName,filterView);

			var tempContainer = [];

			for (let item of entries) {
				//create an array from the classes on the entry
			 	var classList = item.className.split(/\s+/); //get all classes on each entry
		 		
		 		// clone item if we've already placed it in another section
			 	// if our taxonomy term exists in this entry
		 		// add the entry to our array
		 		// if(item.picked){
			 	// 	item = item.cloneNode(true);
			 	// 	console.log(item);
			 	// 	item.myClone = true;
			 	// 	item.className += ' cloned';
		 		// }
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
		// console.log(sortedEntries);

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
