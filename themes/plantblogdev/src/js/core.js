// ==== CORE ==== //

;(function($){
	var swiper = new Swiper(".mySwiper", {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
	});
	
	let filterableItems;
	let checkboxes;
	let bool = false;
	let plantView = 'plant-type';
	let checkedCount = 0;
	let clearFilterButton;
 
	$(document).ready(function() {

		
  	
		//scroll button
  		$('.back-to-top').on('click', function(e){
  			e.target.preventDefault();
  			$('body').animate({'scrollTop': '0px'}, 700);
  		})
  	
		// Grid change vars
		const viewText = document.getElementById('grid-size');
		const viewToggle = document.getElementById('view-toggle');
		const bodyElem = document.getElementsByTagName('body');
		
		// temp for development
		if( viewToggle !== null ){
			viewToggle.addEventListener('click', (e) => {
				bodyElem[0].classList.toggle('big-view');
				let viewState;
				viewState = bool ? 'Large' : 'Small'
				viewText.textContent = viewState;
				bool = !bool;
			});
		}

		//Copy the first planted date to the top of post
		var txt = $('.inside p:first-child .date').text();
		txt = 'First planted: ' + txt.slice(0,-2);
		$('.single-plant #date-first-planted').text(txt);

		$(window).scroll(function(){

			var opcty = $('.back-to-top').css('opacity');

	  		if(window.scrollY > 1000 && opcty === '0'){
	  			$('.back-to-top').toggleClass('off');
	  		}
	  		if(window.scrollY < 1000 && opcty === '1'){
	  			$('.back-to-top').toggleClass('off');
	  		}
  		})

		// journal notes accordion
		$('.accordion-1 .inside').hide();
		$('h4:before').css('content','\\f0d7');   
		$('.accordion-1 .accordion-title').click(function(e) {
			e.preventDefault();
	    	$('.accordion-1 .accordion-title h4').toggleClass('open');
		    $('.accordion-1 > .inside').slideToggle('default');
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
					sortEntriesBySubcategory(e, allEntries, groupName);
				});
			}
			
			$('#sorter li').removeClass('active-link');
			e.target.parentNode.classList.toggle('active-link');
		});
		
		// Build sidebar
		init_sidebar();

		//Plant List: On page load, get all the entries from the Plant List page. 
		if ( document.body.className.match('post-type-archive-plant') ){
			var allEntries = $('div.plant');
		}

	}); //end doc ready
	
	function init_sidebar(e) {
		filterableItems = document.querySelectorAll('.filterable-items');
		clearFilterButton = document.getElementById('clearSelections');
		bool = false;

		if (arguments.length >0 && e.target.classList.contains('sorter-link') ) {
			
			if (e.target.id !== plantView) { //don't run if clicking the same sorter tab
				remove_filter_sidebar();
				add_filter_sidebar();
				plantView = e.target.id;
			}
		}else {
			add_filter_sidebar();
			clear_all();
		}
	}

	function remove_filter_sidebar(){
		document.getElementById('checkboxContainer').replaceChildren();
		checkboxes = null;
	}

	//Build sidebar filter on plant list big view
	function add_filter_sidebar() {
		let filterContainer = document.getElementById('checkboxContainer');
		for (let i = 0; i < filterableItems.length; i++) {
			let div = document.createElement('div');
			let nameUC = filterableItems[i].firstChild.innerText;
			let name = nameUC.toLowerCase();
			let checkbox = document.createElement('input');
			let id = filterableItems[i].getAttribute('data-id');
			checkbox.type = "checkbox";
			checkbox.name = name;
			checkbox.value = name;
			checkbox.id = id;
			checkbox.checked = false;

			checkbox.addEventListener('click', run_filter);

			var label = document.createElement('label')
			label.htmlFor = name;
			label.appendChild(document.createTextNode(nameUC));

			div.appendChild(checkbox);
			div.appendChild(label);
			filterContainer.appendChild(div);

			checkboxes = document.querySelectorAll('#checkboxContainer div input');
		}
		
	}
	function clear_all() {
		if ( clearFilterButton !== null ){
			clearFilterButton.addEventListener('click', (e) => {
				e.preventDefault();
				uncheckCheckboxes(e);
			});
		}
	}
	function uncheckCheckboxes(){
		checkboxes.forEach((elem) => {
			let id = elem.id + '-section';
			let section = document.getElementById(id);
			elem.removeAttribute('checked');
			elem.checked = false;
			section.classList.remove('hide-section');
		});
		checkedCount = 0;
		if (!clearFilterButton.classList.contains('hidden') ){
			clearFilterButton.classList.add('hidden');
		};
	}
	function run_filter(e) {
		let id;
		let section;

		console.log('checkboxes length: '+ checkboxes.length );
	
		if (e.target.type === 'checkbox') {

			id = e.target.id + '-section';
			section = document.getElementById(id);
			
			//handle checkbox
			if (!e.target.hasAttribute('checked')){
				//check the selected box
				checkedCount++;
				e.target.setAttribute('checked', 'checked');
				//find and reveal the corresponding content
				id = e.target.id + '-section';
				section.classList.remove('hide-section')
				//loop through all the other checkboxes
				//TODO set state to capture whether this has been looped through already
				checkboxes.forEach((elem) => {
					console.log('is element ' + elem.name + ' checked? :' + elem.checked);
					if(!elem.checked && elem.id !== e.target.id){
						// console.log(elem.name + 'is not checked, content will be hidden');
						id = elem.id + '-section';
						section = document.getElementById(id);
						if(!section.classList.contains('hide-section')){
							section.classList.add('hide-section');
						}
					}
					console.log('Checkbox count: '+ checkedCount);
				});
				clearFilterButton.classList.remove('hidden');
			}else if (e.target.hasAttribute('checked')){
				// if this is the only checkbox that is checked,
				// then clear filter when it is unchecked 
				e.target.removeAttribute('checked');
				checkedCount--;
				if(!section.classList.contains('hide-section')){
					section.classList.add('hide-section');
				}
				console.log('Checkbox count: '+ checkedCount);
			}
			if (checkedCount == 0){
				uncheckCheckboxes();
				if (!clearFilterButton.classList.contains('hidden') ){
					clearFilterButton.classList.add('hidden');
				};
			}
		}
	}

	function sortEntriesBySubcategory(e, entries, groupName){

	 	var sortedEntries = [];
	 	
	 	// get the nested object for this view.
	 	// the nested object contains key/value pairs 
	 	// of all the taxonomy terms for this view
	 	//taxonomy_data is localized via functions.php
	 	//filterview = array off terms associated with a taxonomy (eg location)
 		var filterView = taxonomy_data[groupName];
		var cloned = null;
		 
 		// loop through the taxonomies
	 	for ( var i in filterView) {
			//  console.log(i);
			var article;
			let div = document.createElement('div');
			
			article = buildArticleContainer(i,groupName,filterView);
			article.id = i + '-section';
			article.setAttribute('data-id', i);
			div.classList.add('plant-cards');
			article.appendChild(div);



			var tempContainer = [];

			for (let item of entries) {
				//create an array from the classes on the entry
			 	var classList = item.className.split(/\s+/); //get all classes on each entry
		 		
		 		// if (classList.includes(i)){

		 		// 	// clone items for duplicates in light needs view
		 		// 	var $jItem = $(item);
		 		// 	var $cloned = $jItem.clone(true);
		 		// 	cloned = $cloned.get(0);
		 		// 	tempContainer.push(cloned);

		 		// 	// tempContainer.push(item);

		 		// 	//flag item so we know we've picked it
		 		// 	item['picked'] = true;
		 		// };
			}

			if ( tempContainer.length > 0){

			 	//sort the entries based on first alpha character (skip quote marks)
			 	// tempContainer.filter( (item, index, array) => {
				// 	//return true or fals
				// 	let name = item.querySelector('h3');
				// 	return true;
				// 	//if this is the 0 index of results from tempContainer.querySelectorAll('item.id'),
				// 	//then return true
				// 	//if tempContainer.querySelectorAll('item.id').length >1 then return false

				// }).sort(function(a,b){
			 	// 	var aFirst = (a.textContent.match(/[a-zA-Z]/) || []).pop();
			 	// 	var bFirst = (b.textContent.match(/[a-zA-Z]/) || []).pop();
			 	// 	if (aFirst < bFirst) return -1;
			 	// 	if (aFirst > bFirst) return 1;

			 	// 	return 0;
			 	// });
				tempContainer.sort(function(a,b){
			 		var aFirst = (a.textContent.match(/[a-zA-Z]/) || []).pop();
			 		var bFirst = (b.textContent.match(/[a-zA-Z]/) || []).pop();
			 		if (aFirst < bFirst) return -1;
			 		if (aFirst === bFirst) return -1;
			 		if (aFirst > bFirst) return 1;

			 		return 0;
			 	});

				// populate the article node with sorted entries;
				for(var i = 0; i < tempContainer.length; i++ ) {
					div.appendChild(tempContainer[i]);
				}
			 	sortedEntries.push(article);
			 }else {
			 	article = null;
			 }
		}		
		//TODO store selected data to local storage to persist 
		// and retrieve later
		displayEntries(sortedEntries);
		init_sidebar(e);
	}

	//Plant List: provide a container in which to place the sorted items
	function buildArticleContainer(taxonomy,groupName,filterView){
		// build the DOM elements for each section container
		var article;
		var anchor;
		var headline;
		var title;
		var container;
		
		// console.log(filterView);

		article = document.createElement("article");
		article.className += "item filterable-items";
		// article.id = filterView[taxonomy];
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

