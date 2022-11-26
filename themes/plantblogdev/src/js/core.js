// ==== CORE ==== //


;(function($){
  $(document).ready(function() {
  		//scroll button
  		$('.back-to-top').on('click', function(e){
  			e.target.preventDefault();
  			$('body').animate({'scrollTop': '0px'}, 700);
  		})
  	
		//View changer on plant list
		const viewToggle = document.getElementById('view-toggle');
		const bodyElem = document.getElementsByTagName('body');

		viewToggle.addEventListener('click', (e) => {
			console.log('clicked');
			bodyElem[0].classList.toggle('big-view');
			add_filter_sidebar();
		});

		let filterableItems;
		// let filterItems;
		let checkboxes;
		
		function triggerEvent (element, eventName) {
			var event = new Event(eventName);
			element.dispatchEvent(event);
		}

		//Build sidebar filter on plant list big view
		function add_filter_sidebar(){
			filterableItems = document.querySelectorAll('.filterable-items');
			// let checkboxes = [];
			let filterContainer = document.getElementById('filterList');
			for (let i = 0; i < filterableItems.length; i++){
				let div = document.createElement('div');
				let nameUC = filterableItems[i].firstChild.innerText;
				let name = nameUC.toLowerCase();
				let checkbox = document.createElement('input');
					checkbox.type = "checkbox";
					checkbox.name = name;
					checkbox.value = name;
					checkbox.id = name;
					checkbox.checked = true;
					checkbox.setAttribute('checked','checked');
				
				checkbox.addEventListener('change', run_filter );
				checkbox.addEventListener('click', (e) =>{
					if(e.target.hasAttribute('checked')){
						e.target.removeAttribute('checked');
					}else{
						e.target.setAttribute('checked','checked');
					}
				});
				
				var label = document.createElement('label')
					label.htmlFor = name;
					label.appendChild(document.createTextNode(nameUC));
				
				div.appendChild(checkbox);
				div.appendChild(label);
				filterContainer.appendChild(div);

				checkboxes = document.querySelectorAll('#filterList div input');
			}
			select_or_deselect_all();
			run_filter();
		}
		function select_or_deselect_all(){
			let selectAll = document.getElementById('selectAll');
			let allSelected = false;
			let filterItems = 
			
			selectAll.addEventListener('click', (e) => {
				e.preventDefault(); 
				allSelected = !allSelected;
				checkboxes.forEach( (elem) => {
					elem.checked = !elem.checked;
					if(allSelected){
						elem.removeAttribute('checked');
					}else{
						elem.setAttribute('checked','checked');
					}
				} );
				//TODO figure out custom even firing
				// triggerEvent(elem, 'change');
			});
		}
		function run_filter(e){
			console.log('filter called');
			// checkboxes.forEach( (elem) => {
			// 	elem.addEventListener('click', (elem) => {
			// 		elem.checked = elem.checked ? false : true; 
			// 		console.log('checkbox is clicked');
			// 	})
			// });
		}
		//TODO add init
		
		//Copy the first planted date to the top of post
		var txt = $('.inside p:first-child .date').text();
		txt = 'First planted: ' + txt.slice(0,-2);
		console.log('txt:' + txt);
		$('.single-plant #date-first-planted').text(txt);

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

		// journal notes accordion
		$('.accordion-1 .inside').hide();
		$('h4:before').css('content','\\f0d7');   
		$('.accordion-1 .accordion-title').click(function(e) {
			e.preventDefault();
	    	$('.accordion-1 .accordion-title h4').toggleClass('open');
		    $('.accordion-1 > .inside').slideToggle('default');
		    // return false;
		  });
		

		
		// Hook up sorter functionality for Plant List page
		// toggle active state for css
		$('#sorter a').on('click', function(e){
			e.preventDefault();

			var groupName = e.target.getAttribute('id');
			$('.note').removeClass('show-note');
			$('.note-'+groupName).addClass('show-note');
		

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

		
		//Plant List: On page load, get all the entries from the Plant List page. 
		if ( document.body.className.match('post-type-archive-plant') ){
			var allEntries = $('div.plant');
		}



	}); //end doc ready
	

	function sortEntriesBySubcategory(entries, groupName){

	 	var sortedEntries = [];
	 	
	 	// get the nested object for this view.
	 	// the nested object contains key/value pairs 
	 	// of all the taxonomy terms for this view
	 	//taxonomy_data is localized via functions.php
	 	//filterview = array off terms associated with a taxonomy (eg location)
 		var filterView = taxonomy_data[groupName];
 		console.log(filterView);

 		// loop through the taxonomies
		var cloned = null;
	 	for ( var i in filterView) {
			var article;
			article = buildArticleContainer(i,groupName,filterView);

			var tempContainer = [];

			for (let item of entries) {
				//create an array from the classes on the entry
			 	var classList = item.className.split(/\s+/); //get all classes on each entry
		 		
		 		if (classList.includes(i)){
		 			console.log(jQuery.type(item));

		 			// clone items for duplicates in light needs view
		 			var $jItem = $(item);
		 			var $cloned = $jItem.clone(true);
		 			cloned = $cloned.get(0);
		 			tempContainer.push(cloned);

		 			// tempContainer.push(item);

		 			//flag item so we know we've picked it
		 			item['picked'] = true;
		 		};
			 }

			if ( tempContainer.length > 0){

			 	//sort the entries based on first alpha character (skip quote marks)
			 	tempContainer.sort(function(a,b){
			 		var aFirst = (a.textContent.match(/[a-zA-Z]/) || []).pop();
			 		var bFirst = (b.textContent.match(/[a-zA-Z]/) || []).pop();
			 		// console.log(aFirst)
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
		//TODO store selected data to local storage to persist 
		// and retrieve later
		displayEntries(sortedEntries);
	}

	//Plant List: provide a container in which to place the sorted items
	function buildArticleContainer(taxonomy,groupName,filterView){
		// build the DOM elements for each section container
		var article;
		var anchor;
		var headline;
		var title;
		
		article = document.createElement("article");
		article.className += " item entry-section";
		anchor = document.createElement("a");

		// //temporary for localhost
		// http://atreegarden.dev/light-requirement/part-shade/
			anchor.href = '/' + groupName + '/' + taxonomy;
		
		headline = document.createElement("h2");
		title = document.createTextNode(filterView[taxonomy]);
		headline.append(title); 
		anchor.append(headline);
		article.insertBefore(anchor, null);

		return article;
	}

	function displayEntries(sorted){
		$('.masonry.pb-wrap').css('opacity', '0');
		$('.masonry.pb-wrap').append(sorted);
		$('.masonry.pb-wrap').fadeTo( 200, '1' );
	}

}(jQuery));

