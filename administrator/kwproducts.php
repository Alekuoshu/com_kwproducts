<?php
/**
 * @version    CVS: 1.0.0
 * @package    com_kwproducts
 * @author     KoshucasWeb <koshucasweb@gmail.com>
 * @copyright  2018 - KoshucasWeb
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_kwproducts'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Kwprojects', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('KwproductsHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'kwproducts.php');

$controller = JControllerLegacy::getInstance('kwproducts');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
