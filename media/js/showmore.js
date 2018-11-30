//---------------------------------------
// show-more
//---------------------------------------
(function ($) {
	$(document).ready(function () {
		var els = $('.kwproducts .show-more');
		if (!els.length) return;

		// ------------------------
		// init
		// ------------------------
		var marker;
		var showmoreText = $('.kwproducts').data('lang');
		var num_intros = $('.kwproducts').data('intro');
		var shtext = 'Show More';
		if (showmoreText == 'span') {
			shtext = 'Cargar Más';
		}
		var protocol = window.location.protocol;
		var host = window.location.host;
		var base = window.location.pathname.split('/');
		var baseurl = protocol + '//' + host + '/' + base[1];
		if (window.location.pathname.split('/').length <= 3) {
			marker = $('<div class="page-autoload-marker"><a href="#" class="btn btn-show-more btn-primary">' + shtext + '</a></div><div class="loading"><img src="media/com_kwproducts/images/loading.gif" alt="LOADING..."></div>');
		} else {
			marker = $('<div class="page-autoload-marker"><a href="#" class="btn btn-show-more btn-primary">' + shtext + '</a></div><div class="loading"><img src="' + baseurl + '/media/com_kwproducts/images/loading.gif" alt="LOADING..."></div>');
		}
		$('.kwproducts').after(marker);
		$('.loading').hide();


		// init Isotope
		var $grid = $('.grid').isotope({
			itemSelector: '.element-item',
			layoutMode: 'fitRows',
			transitionDuration: '0.7s'
		});

		//****************************
		// Isotope Load more button
		//****************************
		var initShow = num_intros; //number of items loaded on init & onclick load more button
		var counter = initShow; //counter for load more button
		var iso = $grid.data('isotope'); // get Isotope instance

		loadMore(initShow); //execute function onload

		function loadMore(toShow) {
			$grid.find(".hidden").removeClass("hidden");

			var hiddenElems = iso.filteredItems.slice(toShow, iso.filteredItems.length).map(function (item) {
				return item.element;
			});
			$(hiddenElems).addClass('hidden');
			$grid.isotope('layout');

			//when no more to load, hide show more button
			if (hiddenElems.length == 0) {
				$('.btn-show-more').fadeOut(200);
			} else {
				$('.btn-show-more').fadeIn();
			};

		}

		//when load more button clicked
		$(".btn-show-more").on('click', function (event) {
			event.preventDefault();
			$('.btn-show-more').fadeOut(200);
			$('.loading').fadeIn(300);
			setTimeout(function () {
				$('.loading').fadeOut(200);
				if ($('#filters').data('clicked')) {
					//when filter button clicked, set initial value for counter
					counter = initShow;
					$('#filters').data('clicked', false);
				} else {
					counter = counter;
				};

				counter = counter + initShow;

				loadMore(counter);
			}, 800);
		});

		//when filter button clicked
		$("#filters").click(function () {
			$(this).data('clicked', true);

			// loadMore(initShow);
		});

	});
})(jQuery);