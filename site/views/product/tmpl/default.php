<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Kwproducts
 * @author     KoshucasWeb <koshucasweb@gmail.com>
 * @copyright  2018 - KoshucasWeb
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport( 'joomla.database.table' );

JLoader::register('KwproductsHelpersKwproducts', JPATH_COMPONENT_SITE . '/helpers/kwproducts.php');

$item = $this->item;
$images_items = json_decode($item->images);
$media_gallery = $item->media_gallery;

//get params
$app = JFactory::getApplication();
$params = $app->getParams();
$copyright = $params->get('copyright');

//add metadada
if ($item->product)
{
	$this->document->setMetadata('title', $item->product);
}

if ($item->metadesc)
{
	$this->document->setDescription($item->metadesc);
}

if ($item->metakey)
{
	$this->document->setMetadata('keywords', $item->metakey);
}

if ($item->author)
{
	$this->document->setMetadata('author', $item->author);
}

//get publishdate
$publishdate = $item->publishdate;
$published = KwproductsHelpersKwproducts::calculateElapsedTime($publishdate);

//get video
$urlvideo = $item->video;
$re = '/v=.+/u';
preg_match($re, $urlvideo, $matches);
$idvideo = $matches[0];
$idvideo = str_replace('v=','', $idvideo);

// $srcimage = "https://img.youtube.com/vi/$idvideo/hqdefault.jpg";
$video = '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$idvideo.'?rel=0" frameborder="0" allowfullscreen></iframe>';

//Update hits//
$hits = $item->hits;
if($hits == 0) {$newHit = 1;}else{$newHit = $hits+1;}
if($newHit == 1) {
	$textHits = JText::_('COM_KWPRODUCTS_PRODUCT_HIT');
}else {
	$textHits = JText::_('COM_KWPRODUCTS_PRODUCT_HITS');
}
$db = JFactory::getDbo();
$query = $db->getQuery(true);
// Fields to update.
$fields = array($db->quoteName('hits') . ' = '.$newHit);
// Conditions for which records should be updated.
$conditions = array($db->quoteName('id') . ' = '.$item->id);
$query->update($db->quoteName('#__kwproducts_products'))->set($fields)->where($conditions);
$db->setQuery($query);
$result = $db->query();
//////////////////////////

// Obtiene el Itemid del menu
$app = JFactory::getApplication();
$menu = $app->getMenu();
$Alias = $app->getMenu()->getActive()->alias;
$Route = $app->getMenu()->getActive()->route;


?>

<div class="kwproducts<?php echo $this->pageclass_sfx; ?>">
<a class="btn-style-1" href="<?php echo $Route; ?>"><span class="fa fa-chevron-left"></span> Volver</a>
	<div class="product-item">
			<?php // page header ?>
			<h1 class="page-header"><?php echo $item->product; ?></h1>
			<div class="product-container">
				<div class="row-fluid">
					<div class="span6">
						<?php switch ($media_gallery):
							case 1: ?>
							<?php foreach ($images_items as $key => $images): ?>
								<?php
									$image = $images->image;
									$alt = $images->alt_image;
								 ?>
								<?php $options["items"][] = array("image" => $image, "alt" => $alt); ?>
							<?php endforeach; ?>
							<?php echo JLayoutHelper::render('slider', $options); ?>
							<?php break;
							case 2: ?>
							 	<?php echo $video; ?>
						 	<?php break; ?>
							<?php default: ?>
								<img src="<?php echo $item->introimage; ?>" alt="Intro Image" class="jn-holder jn-holder-block" data-ratio="wide">
							<?php break; ?>
						<?php endswitch;  ?>
					</div>
					<div class="span6">
						<?php //Description ?>
						<h3 class="product-description"><?php echo JText::_('COM_KWPRODUCTS_PRODUCT_DESCRIPTION');?></h3>
						<p class="published"><i class="fa fa-calendar"></i> <?php echo $published; ?> / <i class="fa fa-eye"></i> <?php echo $newHit.' '.$textHits; ?></p>
						<p class="description"><?php echo $item->description; ?></p>
					</div>
				</div>
			</div>
	</div>
</div>

<?php if($copyright == '1'): ?>
	<div class="copyright" style="text-align:center;">
		<copyright><small><a href="https://koshucasweb.com.ve" target="_blank" rel="author">By KoshucasWeb</a></small></copyright>
	</div>
<?php endif; ?>
