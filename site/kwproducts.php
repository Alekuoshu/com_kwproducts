<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Kwproducts
 * @author     KoshucasWeb <koshucasweb@gmail.com>
 * @copyright  2018 - KoshucasWeb
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Kwproducts', JPATH_COMPONENT);
JLoader::register('KwproductsController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Kwproducts');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
