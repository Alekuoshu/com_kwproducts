<?php
// no direct access
defined ( '_JEXEC' ) or die ();
// $introcount = (count($list));
$uid = "slider-".uniqid();

$document = JFactory::getDocument();
$document->addStyleSheet('media/com_kwproducts/css/carousel-1.css');
$document->addStyleSheet('media/com_kwproducts/css/jn-slide-gallery.css');
$document->addScript('media/com_kwproducts/js/carousel-1.js');
$document->addScript('media/com_kwproducts/js/jn-slide-gallery.js');

//get interval
// $interval = intval($interval)*1000;
$interval = 8000;

//get items
$options = $displayData;
$items = $options["items"];


?>
<!-- LAYOUT CAROUSEL 1 -->
<div class="jnilla-gallery">
	<div class="carousel-1 gallery">
		<div id="<?php echo $uid; ?>" class="carousel slide">
			<div class="carousel-indicators">
				<?php $n = -1; ?>
				<?php foreach ($items as $item) : ?>
					<?php $n++; ?>
					<div class="thumb <?php if($n == 0) echo "active"; ?>">
						<div data-target="<?php echo "#$uid"; ?>" data-slide-to="<?php echo $n; ?>" <?php if($n==0) echo "class=\"active\""?> style="cursor:pointer;">
							<img class="jn-gallery-thumb jn-holder" data-ratio="box" src="<?php echo $item['image']; ?>" alt="<?php echo $item['alt']; ?>">
						</div>
						<div class="rollover"></div>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Carousel items -->
			<div class="carousel-inner img-container">
				<?php $n = -1; ?>
				<?php foreach ($items as $item) : ?>
					<?php $n++;
					//get image size for value
					$size = getimagesize($item['image']);
					// get name from file
					$info = pathinfo($item['image']);
					$file_name =  basename($item['image'],'.'.$info['extension']);
					$file_name = str_replace('-', ' ', $file_name);
					?>
					<div class="item gallery-item <?php if($n==0) echo "active"; ?>">
						<?php if ($size[0] > 700 && $size[1] < 650) : ?>
							<img class="jn-gallery-thumb jn-holder img" data-ratio="wide" src="<?php echo $item['image']; ?>" data-src="<?php echo $item['image']; ?>" alt="<?php echo $item['alt']; ?>">
						<?php else: ?>
							<img class="jn-gallery-thumb no-block jn-holder img" data-ratio="wide" src="<?php echo $item['image']; ?>" data-src="<?php echo $item['image']; ?>" alt="<?php echo $item['alt']; ?>">
						<?php endif; ?>
						<?php if($caption == 'true') : ?>
							<?php if($stylecaption == 'inside'): ?>
								<div class="carousel-caption">
									<h4><?php echo $file_name; ?></h4>
								</div>
							<?php else: ?>
								<div class="outside-caption">
									<h4><?php echo $file_name; ?></h4>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					</div>

				<?php endforeach; ?>
			</div>

			<a class="left carousel-control" href="<?php echo "#$uid"; ?>" data-slide="prev">‹</a>
			<a class="right carousel-control" href="<?php echo "#$uid"; ?>" data-slide="next">›</a>
		</div>
	</div>
</div>
<div class="clearfix"></div>

<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		$('<?php echo "#$uid"; ?>').carousel({
			interval: <?php echo $interval; ?>
		});
		$('.carousel').each(function(index, element) {
			$(this)[index].slide = null;
		});
	});
})(jQuery);
</script>
