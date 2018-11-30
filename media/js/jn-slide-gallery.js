//***************************

//photo gallery in modal

//***************************

(function ($) {

	$.fn.jn_gallery = function () {

		// ------------------------
		// init
		// ------------------------
		var target = $('.gallery');
		if (!target.length) return;
		var counter = 0;
		var baseurl = $('body').data('base-url');

		target.each(function () {
			counter++;
			if ($('#modalGallery-' + counter + '').length) {
				$('#modalGallery-' + counter + '').remove();
			}

			//Modal Structure
			var modalContent = $('<div id="modalGallery-' + counter + '" class="modal modalGallery hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>');

			var modalBody = $('<div class="modal-body"></div>');
			modalContent.append(modalBody);
			modalContent.modal('hide');
			//End Modal Structure

			//Carousel Structure
			var modalCarousel = $('<div id="modalCarousel-' + counter + '" class="carousel slide"></div>');
			carouselInner = $('<div class="carousel-inner"></div>');
			modalCarousel.append(carouselInner);
			//End carousel structure

			modalBody.append(modalCarousel);

			//Add items to carousel
			var itemId = 0;
			var els = $(this).find('.gallery-item.img, .gallery-item .img, .gallery-item .vid, .rotator .gallery-item, .masonry .gallery-item, .rotator-items .gallery-item');
			var item;

			els.each(function (event) {
				itemId++;
				$(this).attr('id', 'item-' + itemId);

				if ($(this).hasClass('vid')) {
					item = $('<div class="item" id=item-' + itemId + '>' + $(this).data('src') + '</div>');
					carouselInner.append(item);
				} else {
					item = $('<div class="item" id=item-' + itemId + '><img src="' + baseurl + $(this).data('src') + '" alt="img-gallery"></div>');
					carouselInner.append(item);
				}
			});

			carouselInner.after($('<a class="carousel-control left" href="#modalCarousel-' + counter + '" data-slide="prev"><i class="fa fa-angle-left"></i></a><a class="carousel-control right" href="#modalCarousel-' + counter + '" data-slide="next"><i class="fa fa-angle-right"></i></a>'));
			//End add items


			// event for click item
			els.on('click', function () {
				$('.modalGallery').remove();
				if ($('#modalGallery-' + counter + '').length) {
					$('#modalGallery-' + counter + '').remove();
				}

				modalCarousel.find('.active').removeClass('active');
				modalCarousel.find('.next').removeClass('next');
				modalCarousel.find('.item.left').removeClass('left');
				modalCarousel.find('.item#' + $(this).attr('id')).addClass('active');
				modalContent.modal('show');


			});


			setInterval(function () {
				// stop automatic carousel
				$('.carousel').carousel({
					interval: 0
				});
				// $('.carousel').each(function(index, element) {
				// 	$(this)[index].slide = null;
				// });
			}, 1000);

		}); //end each

	};


	$(document).ready(function () {

		// ------------------------
		// init
		// ------------------------
		//initialize gallery slide
		$('.gallery').jn_gallery();

		// ------------------------
		// events
		// ------------------------
		// detecting modal hide
		$('body').on('hidden', '.modalGallery', function () {
			$(this).remove();
		});

		// detecting modal slide
		$('body').on('slide', '.modalGallery .carousel', function () {
			var iframe = $(this).find('.item.active iframe');
			var src = iframe.attr('src');
			iframe.attr('src', src);
		});

	});

})(jQuery);