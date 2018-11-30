<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Kwproducts
 * @author     KoshucasWeb <koshucasweb@gmail.com>
 * @copyright  2018 - KoshucasWeb
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Products list controller class.
 *
 * @since  1.6
 */
class KwproductsControllerProducts extends KwproductsController
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional
	 * @param   array   $config  Configuration array for model. Optional
	 *
	 * @return object	The model
	 *
	 * @since	1.6
	 */
	public function &getModel($name = 'Products', $prefix = 'KwproductsModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}
}
