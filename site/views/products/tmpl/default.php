<?php
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
// Require helper file
JLoader::register('GridifyHelper', JPATH_COMPONENT_SITE . '/helpers/gridify.php');
JLoader::register('KwproductsHelpersKwproducts', JPATH_COMPONENT_SITE . '/helpers/kwproducts.php');
JLoader::register('KwproductsHelperRoute', JPATH_COMPONENT_SITE . '/helpers/route.php');

// Obtiene el Itemid del menu
$app = JFactory::getApplication();
$menu = $app->getMenu();
$Alias = $app->getMenu()->getActive()->alias;
$Route = $app->getMenu()->getActive()->route;
$Itemid = $app->getMenu()->getActive()->id;

//get params
$params = $app->getParams();
$showMore = $params->get('infinite_scroll');
$num_intros = $params->get('num_intro_products');
$num_columns = $params->get('num_columns');
$copyright = $params->get('copyright');

//add libraries and styles if show more is active
if ($showMore == '1') {
	$document = JFactory::getDocument();
	$document->addStyleSheet('media/com_kwproducts/css/show-more.css');
	$document->addScript('media/com_kwproducts/js/showmore.js');
}

// value columns
switch ($num_columns) {
	case '2':
		$width = '47%';
		break;
	case '3':
		$width = '31%';
		break;
	case '4':
		$width = '22.7%';
		break;
	default:
		$width = '31%';
		break;
}

$n = 0;

$buffer = array();

//get current language
$lang = JFactory::getLanguage();
$idioma = $lang->getName();
if ($idioma == 'Spanish (español)'){
	$idiom = 'span';
}else {
	$idiom = 'eng';
}

?>

<div class="kwproducts <?php echo $this->pageclass_sfx; ?>" data-lang="<?php echo $idiom; ?>" data-intro="<?php echo $num_intros; ?>">

	<?php // page header ?>
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<h1 class="page-header"> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	<?php endif; ?>

	<?php //filters ?>
	<div id="filters" class="button-group hidden-phone hidden">
		<button class="button is-checked" data-filter="*"><?php echo JText::_('COM_KWPRODUCTS_FILTER_ALL');?></button>
		<!-- <?php //foreach ($tags as $tag) : ?>
			<?php
			//$tagClass = $tag['tag'];
			//$tagClass = str_replace(' ','', $tagClass);
			?>
			<button class="button" data-filter=".<?php //echo $tagClass; ?>"><?php //echo $tag['tag']; ?></button>
		<?php //endforeach; ?> -->
	</div>
	<?php //filters on phones ?>
	<div id="filters-mobile" class="visible-phone hidden">
		<select class="filter" name="filter">
			<option value="*" selected="selected"><?php echo JText::_('COM_KWPRODUCTS_FILTER_ALL');?></option>
			<!-- <?php //foreach ($tags as $tag) : ?>
				<?php
				//$tagClass = $tag['tag'];
				//$tagClass = str_replace(' ','', $tagClass);
				?>
				<option value=".<?php //echo $tagClass; ?>"><?php //echo $tag['tag']; ?></option>
			<?php //endforeach; ?> -->
		</select>
	</div>
	<hr>

	<?php // Product items ?>
	<?php if (!empty($this->items)) : ?>
		<?php if($showMore == '1'): ?>
		<div class="product-items grid show-more" data-cols="<?php echo $num_columns; ?>">
		<?php else: ?>
		<div class="product-items grid" data-cols="<?php echo $num_columns; ?>">
		<?php endif; ?>
			<?php foreach ($this->items as $key => $item): ?>
				<?php //ob_start(); ?>
				<?php $link = JRoute::_(KwproductsHelperRoute::getProductRoute($item->alias, $item->catid)); ?>
				<?php //$link = JRoute::_('index.php?option=com_kwproducts&view=product&id='.(int)$item->id); ?>
				<?php $n++; ?>
					<?php // product item ?>
					<?php
					// $tagClassItem = $item->tags;
					// $tagClassItem = str_replace(' ','', $tagClassItem);
					// $tagClassItem = str_replace(',',' ', $tagClassItem);
					//hits
					$hits = $item->hits;
					if(!$hits) $hits = 0;
					if($hits == 1) {
						$textHits = JText::_('COM_KWPRODUCTS_PRODUCT_HIT');
					}else {
						$textHits = JText::_('COM_KWPRODUCTS_PRODUCT_HITS');
					}
					?>
					<div class="element-item  <?php //echo $tagClassItem; ?>" data-category="<?php echo $item->catid; ?>" style="width:<?php echo $width; ?>">
						<?php // item image ?>
						<div class="product-item-image">
							<img class="jn-holder jn-holder-block img-zoom" data-ratio="box" src="<?php echo $item->introimage; ?>" alt="intro-image" data-src="<?php echo $item->introimage; ?>" data-product="<?php echo $item->product; ?>" data-link="<?php echo $link; ?>" data-toggle="modal" data-target="#myModalPreview">
							<div class="rollover visible-desktop">
								<span class="hits"><i class="fa fa-eye"></i> <?php echo $hits.' '.$textHits; ?></span>
								<a class="img-zoom" href="#myModalPreview" data-src="<?php echo $item->introimage; ?>" data-product="<?php echo $item->product; ?>" data-link="<?php echo $link; ?>" data-toggle="modal" title="<?php echo JText::_('COM_KWPRODUCTS_PRODUCTS_TITLE_ZOOM');?>">
									<i class="fa fa-zoom-in"></i>
								</a>
								<a class="linkdetail" href="<?php echo $link; ?>" title="<?php echo JText::_('COM_KWPRODUCTS_PRODUCTS_LINK_DETAIL');?>">
									<i class="fa fa-eye"></i>
								</a>
								<?php // item title ?>
								<h4 class="product-item-title">
									<a href="<?php echo $link; ?>">
										<?php echo $item->product; ?>
									</a>
								</h4>
							</div>
						</div>
					</div>
				<?php //$buffer[] = ob_get_clean(); ?>
			<?php endforeach; ?>
			<?php //echo GridifyHelper::setGrid(2, array('items' => $buffer)); ?>
		</div>
	<?php endif; ?>

	<?php // pagination ?>
	<?php if ($showMore == '0') : ?>
		<div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)) : ?>
				<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
			<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
	<?php endif; ?>
</div>

<?php if($copyright == '1'): ?>
	<div class="copyright" style="text-align:center;">
		<copyright><small><a href="https://koshucasweb.com.ve" target="_blank" rel="author">By KoshucasWeb</a></small></copyright>
	</div>
<?php endif; ?>

<?php //modal for preview ?>
<div id="myModalPreview" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel"></h3>
  </div>
  <div class="modal-body"></div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_KWPRODUCTS_PRODUCT_MODAL_CLOSE');?></button>
    <a href="#" class="btn btn-primary btn-details"><?php echo JText::_('COM_KWPRODUCTS_PRODUCTS_MODAL_DETAILS');?></a>
  </div>
</div>
