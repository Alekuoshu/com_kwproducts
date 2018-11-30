<?php

/**
* @version    CVS: 1.0.0
* @package    Com_Kwproducts
* @author     KoshucasWeb <koshucasweb@gmail.com>
* @copyright  2018 - KoshucasWeb
* @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
*/
defined('_JEXEC') or die;

JLoader::register('KwproductsHelper', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_kwproducts' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'kwproducts.php');

/**
* Class KwproductsFrontendHelper
*
* @since  1.6
*/
class KwproductsHelpersKwproducts
{
	/**
	* Get category name using category ID
	* @param integer $category_id Category ID
	* @return mixed category name if the category was found, null otherwise
	*/
	public static function getCategoryNameByCategoryId($category_id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
		->select('title')
		->from('#__categories')
		->where('id = ' . intval($category_id));

		$db->setQuery($query);
		return $db->loadResult();
	}
	/**
	* Get an instance of the named model
	*
	* @param   string  $name  Model name
	*
	* @return null|object
	*/
	public static function getModel($name)
	{
		$model = null;

		// If the file exists, let's
		if (file_exists(JPATH_SITE . '/components/com_kwproducts/models/' . strtolower($name) . '.php'))
		{
			require_once JPATH_SITE . '/components/com_kwproducts/models/' . strtolower($name) . '.php';
			$model = JModelLegacy::getInstance($name, 'KwproductsModel');
		}

		return $model;
	}

	/**
	* Gets the files attached to an item
	*
	* @param   int     $pk     The item's id
	*
	* @param   string  $table  The table's name
	*
	* @param   string  $field  The field's name
	*
	* @return  array  The files
	*/
	public static function getFiles($pk, $table, $field)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
		->select($field)
		->from($table)
		->where('id = ' . (int) $pk);

		$db->setQuery($query);

		return explode(',', $db->loadResult());
	}

	/**
	* Gets the edit permission for an user
	*
	* @param   mixed  $item  The item
	*
	* @return  bool
	*/
	public static function canUserEdit($item)
	{
		$permission = false;
		$user       = JFactory::getUser();

		if ($user->authorise('core.edit', 'com_kwproducts'))
		{
			$permission = true;
		}
		else
		{
			if (isset($item->created_by))
			{
				if ($user->authorise('core.edit.own', 'com_kwproducts') && $item->created_by == $user->id)
				{
					$permission = true;
				}
			}
			else
			{
				$permission = true;
			}
		}

		return $permission;
	}

	/**
	* Gets tags for filter
	*
	*/
	// public static function getTags($tags)
	// {
	// 	$db = JFactory::getDbo();
	// 	$query = $db->getQuery(true);
	// 	$query
	// 	->select('tag')
	// 	->from('#__kwproducts_tags')
	// 	->where('state = 1 AND tag IN ('.$tags.')')
	// 	->group($db->quoteName('tag'))
	// 	->order('ordering ASC');

	// 	$db->setQuery($query);
	// 	return $db->loadAssoclist();
	// }

	/**
	* Calculate elapsed time
	*
	* @param   string  $datetime  mysql date format string.
	* @param   bool  $full  retunr full elapsed time description.
	*
	* @return  string
	*/
	public static function calculateElapsedTime($date, $full = false){
		$now = new DateTime();
		$ago = new DateTime($date);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => JText::_('COM_KWPRODUCTS_ELAPSEDTIME_YEAR'),
			'm' => JText::_('COM_KWPRODUCTS_ELAPSEDTIME_MONTH'),
			'w' => JText::_('COM_KWPRODUCTS_ELAPSEDTIME_WEEK'),
			'd' => JText::_('COM_KWPRODUCTS_ELAPSEDTIME_DAY'),
			'h' => JText::_('COM_KWPRODUCTS_ELAPSEDTIME_HOUR'),
			'i' => JText::_('COM_KWPRODUCTS_ELAPSEDTIME_MINUTE'),
			's' => JText::_('COM_KWPRODUCTS_ELAPSEDTIME_SECOND'),
		);
		foreach($string as $k => &$v){
			if($diff->$k){
				$lang = JFactory::getLanguage();
				if ($lang->getTag() == "es-ES"){
					$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 'es' : '');
				}else {
					$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
				}
			}else{
				unset($string[$k]);
			}
		}

		if(!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . JText::_('COM_KWPRODUCTS_ELAPSEDTIME_AGO') : JText::_('COM_KWPRODUCTS_ELAPSEDTIME_NOW');
	}
}
