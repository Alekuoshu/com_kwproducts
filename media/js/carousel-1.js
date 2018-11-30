
//carousel-1 behavior
(function($) {
	$(document).ready(function() {
		var gallery = $('.jnilla-gallery .carousel-1');
		if (!gallery.length) return;

		// init
		var indicator = gallery.find('.carousel-indicators');
		var flag = false;
		var y = 0;
		var top = 0 ;
		var h = 0;
		var offset = 0;
		var thumb = indicator.find('.thumb').eq(0);
		var step = 0;
		var ww = 0;

		// set carousel height on resize
		var carousel = gallery.find('.carousel').eq(0);
		$(window).resize(function() {
			var h = carousel.find('.carousel-inner').eq(0).height();
			carousel.height(h);
		}).resize();
		$(window).trigger('resize');

		// thumbnails scroll behavior
		indicator.parent().mousemove(function(event) {
			y = event.pageY - $(this).offset().top;
		});
		indicator.mouseenter(function(event) {
			flag = true;
		})
		.mouseleave(function(event) {
			flag = false;
		});

		indicator.parent().mousedown(function(event) {
			//
		});
		indicator.parent().mouseup(function(event) {
			//
		});

		var timer = setInterval(function() {
			if(!flag) return;
			h = indicator.height();
			ph = indicator.parent().height();
			offset = ph - h;
			top = parseInt(indicator.css('top'));
			step = thumb.height();
			// define step direction
			if(y < (ph*30/100)){
				top += step;
			}else if(y > (ph*70/100)){
				top -= step;
			}
			// limit movement
			if(top > 0){
				top = 0;
			}else if(top < offset){
				top = offset;
			}
			if(h <= ph){
				top = 0;
			}
			indicator.css('top', top+"px");
		}, 500);
		//clearInterval(timer);

	});
})(jQuery);
