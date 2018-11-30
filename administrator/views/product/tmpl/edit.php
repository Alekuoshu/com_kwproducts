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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/com_kwproducts/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {

	js('input:hidden.catid').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('catidhidden')){
			js('#jform_catid option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_catid").trigger("liszt:updated");
	js('input:hidden.tags').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('tagshidden')){
			js('#jform_tags option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_tags").trigger("liszt:updated");
	});

	Joomla.submitbutton = function (task) {
		if (task == 'product.cancel') {
			Joomla.submitform(task, document.getElementById('product-form'));
		}
		else {

			if (task != 'product.cancel' && document.formvalidator.isValid(document.id('product-form'))) {

	if(js('#jform_tags option:selected').length == 0){
		js("#jform_tags option[value=0]").attr('selected','selected');
	}
				Joomla.submitform(task, document.getElementById('product-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_kwproducts&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="product-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_KWPRODUCTS_TITLE_PRODUCT', true)); ?>
		<div class="row-fluid">
			<div class="span12 form-horizontal">
				<fieldset class="adminform">

				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->renderField('created_by'); ?>
				<?php echo $this->form->renderField('modified_by'); ?>
				<?php echo $this->form->renderField('product'); ?>
				<?php echo $this->form->renderField('alias'); ?>
				<?php echo $this->form->renderField('catid'); ?>

			<?php
				foreach((array)$this->item->catid as $value):
					if(!is_array($value)):
						echo '<input type="hidden" class="catid" name="jform[catidhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>
			<?php echo $this->form->renderField('publishdate'); ?>

				<?php echo $this->form->renderField('introimage'); ?>
				<?php echo $this->form->renderField('media_gallery'); ?>
				<?php echo $this->form->renderField('images'); ?>
				<?php echo $this->form->renderField('video'); ?>
				<?php echo $this->form->renderField('description'); ?>
				<?php echo $this->form->renderField('url'); ?>
				<?php echo $this->form->renderField('social'); ?>

					<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
					<?php endif; ?>
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'metadata', JText::_('Metadata', true)); ?>
		<div class="row-fluid">
			<div class="span12 form-horizontal">
				<fieldset class="adminform">

				<?php echo $this->form->renderField('metakey'); ?>
				<?php echo $this->form->renderField('metadesc'); ?>
				<?php echo $this->form->renderField('hits'); ?>

				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>
