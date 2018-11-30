// --------------------------------------
// products behavior
//--------------------------------------
(function ($) {
	$(document).ready(function () {
		var target = $('.kwproducts');
		if (!target.length) return;

		// ------------------------
		// init
		// ------------------------
		var zoom = target.find('.img-zoom');
		var modal = $('#myModalPreview');

		// init Isotope
		var $grid = $('.grid').isotope({
			itemSelector: '.element-item',
			layoutMode: 'fitRows',
			// layoutMode: 'masonry',
			// stagger: 200,
			transitionDuration: '0.7s'
			// getSortData: {
			// 	name: '.name',
			// 	symbol: '.symbol',
			// 	number: '.number parseInt',
			// 	category: '[data-category]',
			// 	weight: function( itemElem ) {
			// 		var weight = $( itemElem ).find('.weight').text();
			// 		return parseFloat( weight.replace( /[\(\)]/g, '') );
			// 	}
			// }
		});

		// ------------------------
		// events
		// ------------------------
		//event for preview in modal
		zoom.on('click', function (event) {
			var introimage;
			modal.find('.modal-body').html('');
			var protocol = window.location.protocol;
			var host = window.location.host;
			var base = window.location.pathname.split('/');
			var baseurl = protocol + '//' + host + '/' + base[1];
			var img = $(this).data('src');
			var product = $(this).data('product');
			var link = $(this).data('link');
			if (window.location.pathname.split('/').length <= 3) {
				introimage = '<img class="introimage" src="' + img + '" alt="introimage">';
			} else {
				introimage = '<img class="introimage" src="' + baseurl + '/' + img + '" alt="introimage">';
			}
			modal.find('#myModalLabel').text(product);
			modal.find('.modal-body').append(introimage);
			modal.find('.btn-details').attr('href', link);
		});

		// bind filter button click
		$('#filters').on('click', 'button', function () {
			var filterValue = $(this).attr('data-filter');
			// use filterFn if matches value
			filterValue = filterFns[filterValue] || filterValue;
			$grid.isotope({
				filter: filterValue
			});
		});
		// bind filter select option
		$('#filters-mobile').on('change', '.filter', function () {
			var filterValue = $(this).val();
			// use filterFn if matches value
			filterValue = filterFns[filterValue] || filterValue;
			$grid.isotope({
				filter: filterValue
			});
		});

		// bind sort button click
		$('#sorts').on('click', 'button', function () {
			var sortByValue = $(this).attr('data-sort-by');
			$grid.isotope({
				sortBy: sortByValue
			});
		});

		// change is-checked class on buttons
		$('.button-group').each(function (i, buttonGroup) {
			var $buttonGroup = $(buttonGroup);
			$buttonGroup.on('click', 'button', function () {
				$buttonGroup.find('.is-checked').removeClass('is-checked');
				$(this).addClass('is-checked');
			});
		});

		// ------------------------
		// functions
		// ------------------------

		// filter functions
		var filterFns = {
			// show if number is greater than 50
			numberGreaterThan50: function () {
				var number = $(this).find('.number').text();
				return parseInt(number, 10) > 50;
			},
		};

		//fix bug on load document
		setTimeout(function () {
			$grid.isotope({
				filter: '*'
			});
		}, 500);


	});

})(jQuery);