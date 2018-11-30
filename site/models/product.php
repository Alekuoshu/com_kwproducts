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

jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');
jimport( 'joomla.database.table' );

use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;

/**
* Kwproducts model.
*
* @since  1.6
*/
class KwproductsModelProduct extends JModelItem
{
	/**
	* Method to auto-populate the model state.
	*
	* Note. Calling getState in this method will result in recursion.
	*
	* @return void
	*
	* @since    1.6
	*
	*/
	protected function populateState()
	{
		$app  = JFactory::getApplication('com_kwproducts');
		$user = JFactory::getUser();

		// Load state from the request userState on edit or from the passed variable on default
		if (JFactory::getApplication()->input->get('layout') == 'edit')
		{
			$id = JFactory::getApplication()->getUserState('com_kwproducts.edit.product.id');
		}
		else
		{
			$id = JFactory::getApplication()->input->get('id');
			JFactory::getApplication()->setUserState('com_kwproducts.edit.product.id', $id);
		}

		$this->setState('product.id', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('product.id', $params_array['item_id']);
		}

		$this->setState('params', $params);
	}

	/**
	* Method to get an object.
	*
	* @param   integer $id The id of the object to get.
	*
	* @return  mixed    Object on success, false on failure.
	*/
	public function getItem($pk = null)
	{
		$user = JFactory::getUser();

		$pk = (!empty($pk)) ? $pk : (int) $this->getState('product.id');

		if ($this->_item === null)
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$pk]))
		{
			try
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->select(
						$this->getState(
							'item.select', 'a.id, a.asset_id, a.product, a.alias, a.description, ' .
							'a.state, a.catid, a.introimage, a.media_gallery, a.created_by, a.images, a.video, a.url, a.social, ' .
							'a.modified_by, a.checked_out, a.checked_out_time, a.publishdate, ' .
							'a.ordering, a.metakey, a.metadesc, a.access, a.hits'
						)
					);
				$query->from('#__kwproducts_products AS a')
					->where('a.id = ' . (int) $pk);

				// Join on category table.
				$query->select('c.title AS category_title, c.alias AS category_alias, c.access AS category_access')
					->innerJoin('#__categories AS c on c.id = a.catid')
					->where('c.published > 0');

				// Join on user table.
				$query->select('u.name AS author')
					->join('LEFT', '#__users AS u on u.id = a.created_by');

				// Filter by language
				// if ($this->getState('filter.language'))
				// {
				// 	$query->where('a.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
				// }

				// Join on voting table
				// $query->select('ROUND(v.rating_sum / v.rating_count, 0) AS rating, v.rating_count as rating_count')
				// 	->join('LEFT', '#__content_rating AS v ON a.id = v.content_id');

				// if ((!$user->authorise('core.edit.state', 'com_kwproducts')) && (!$user->authorise('core.edit', 'com_kwproducts')))
				// {
				// 	// Filter by start and end dates.
				// 	$nullDate = $db->quote($db->getNullDate());
				// 	$date = JFactory::getDate();
				//
				// 	$nowDate = $db->quote($date->toSql());
				//
				// 	$query->where('(a.publishdate = ' . $nullDate . ')');
				// }

				// Filter by published state.
				$query->where('(a.state = 1)');

				$db->setQuery($query);

				$data = $db->loadObject();

				// if (empty($data))
				// {
				// 	return JError::raiseError(404, JText::_('COM_KWPRODUCTS_ERROR_PRODUCT_NOT_FOUND'));
				// }
				//
				// // Check for published state if filter set.
				// if ((is_numeric($published)) && (($data->state != $published)))
				// {
				// 	return JError::raiseError(404, JText::_('COM_KWPRODUCTS_ERROR_PRODUCT_NOT_FOUND'));
				// }

				// // Convert parameter fields to objects.
				// $registry = new Registry($data->attribs);
				//
				// $data->params = clone $this->getState('params');
				// $data->params->merge($registry);
				//
				// $data->metadata = new Registry($data->metadata);
				//
				// // Technically guest could edit an product, but lets not check that to improve performance a little.
				// if (!$user->get('guest'))
				// {
				// 	$userId = $user->get('id');
				// 	$asset = 'com_kwproducts.product.' . $data->id;
				//
				// 	// Check general edit permission first.
				// 	if ($user->authorise('core.edit', $asset))
				// 	{
				// 		$data->params->set('access-edit', true);
				// 	}
				//
				// 	// Now check if edit.own is available.
				// 	elseif (!empty($userId) && $user->authorise('core.edit.own', $asset))
				// 	{
				// 		// Check for a valid user and that they are the owner.
				// 		if ($userId == $data->created_by)
				// 		{
				// 			$data->params->set('access-edit', true);
				// 		}
				// 	}
				// }
				//
				// // Compute view access permissions.
				// if ($access = $this->getState('filter.access'))
				// {
				// 	// If the access filter has been set, we already know this user can view.
				// 	$data->params->set('access-view', true);
				// }
				// else
				// {
				// 	// If no access filter is set, the layout takes some responsibility for display of limited information.
				// 	$user = JFactory::getUser();
				// 	$groups = $user->getAuthorisedViewLevels();
				//
				// 	if ($data->catid == 0 || $data->category_access === null)
				// 	{
				// 		$data->params->set('access-view', in_array($data->access, $groups));
				// 	}
				// 	else
				// 	{
				// 		$data->params->set('access-view', in_array($data->access, $groups) && in_array($data->category_access, $groups));
				// 	}
				// }

				$this->_item[$pk] = $data;
			}
			catch (Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError(404, $e->getMessage());
				}
				else
				{
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}

		return $this->_item[$pk];
	}

	/**
	* Get an instance of JTable class
	*
	* @param   string $type   Name of the JTable class to get an instance of.
	* @param   string $prefix Prefix for the table class name. Optional.
	* @param   array  $config Array of configuration values for the JTable object. Optional.
	*
	* @return  JTable|bool JTable if success, false on failure.
	*/
	public function getTable($type = 'Product', $prefix = 'KwproductsTable', $config = array())
	{
		$this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_kwproducts/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Increment the hit counter for the product.
	 *
	 * @param   integer  $pk  Optional primary key of the product to increment.
	 *
	 * @return  boolean  True if successful; false otherwise and internal error set.
	 */
	public function hit($pk = 0)
	{
		$input = JFactory::getApplication()->input;
		$hitcount = $input->getInt('hitcount', 1);

		if ($hitcount)
		{
			$pk = (!empty($pk)) ? $pk : (int) $this->getState('product.id');

			$table = JTable::getInstance('Product', 'JTable');
			$table->load($pk);
			$table->hit($pk);
		}

		return true;
	}



	/**
	* Get the id of an item by alias
	*
	* @param   string $alias Item alias
	*
	* @return  mixed
	*/
	public function getItemIdByAlias($alias)
	{
		$table      = $this->getTable();
		$properties = $table->getProperties();
		$result     = null;

		if (key_exists('alias', $properties))
		{
			$table->load(array('alias' => $alias));
			$result = $table->id;
		}

		return $result;
	}


}
