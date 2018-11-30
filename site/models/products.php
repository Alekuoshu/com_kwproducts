<?php

/**
* @version    CVS: 1.0.0
* @package    Com_Kwproducts
* @author     KoshucasWeb <koshucasweb@gmail.com>
* @copyright  2018 - KoshucasWeb
* @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
*/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

jimport('joomla.application.component.modellist');
jimport('joomla.application.component.model');
jimport('joomla.html.pagination');

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

/**
* Methods supporting a list of Kwproducts records.
*
* @since  1.6
*/
class KwproductsModelProducts extends JModelList
{
	/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.6
	*/
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id',
				'product',
				'state'
			);
		}

		parent::__construct($config);
	}

	/**
	* Method to auto-populate the model state.
	*
	* Note. Calling getState in this method will result in recursion.
	*
	* @param   string  $ordering   Elements order
	* @param   string  $direction  Order direction
	*
	* @return void
	*
	* @throws Exception
	*
	* @since    1.6
	*/
	protected function populateState($ordering = null, $direction = null)
	{
		$app  = Factory::getApplication();
		$list = $app->getUserState($this->context . '.list');
		// filter by catid
		$catid = JRequest::getInt('catid');
		$this->setState('catid', $catid);

		$ordering  = isset($list['filter_order'])     ? $list['filter_order']     : null;
		$direction = isset($list['filter_order_Dir']) ? $list['filter_order_Dir'] : null;

		$list['limit']     = (int) Factory::getConfig()->get('list_limit', 20);
		$list['start']     = $app->input->getInt('start', 0);
		$list['ordering']  = $ordering;
		$list['direction'] = $direction;

		$app->setUserState($this->context . '.list', $list);
		$app->input->set('list', null);

		// List state information.
		parent::populateState($ordering, $direction);

		$app = Factory::getApplication();

		$ordering  = $app->getUserStateFromRequest($this->context . '.ordercol', 'filter_order', $ordering);
		$direction = $app->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', $ordering);

		$this->setState('list.ordering', $ordering);
		$this->setState('list.direction', $direction);

		$start = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0, 'int');
		// $limit = $app->getUserStateFromRequest($this->context . '.limit', 'limit', 0, 'int');

		//get params
		$params = $app->getParams();
		$limit = $params->get('num_intro_products');
		$showMore = $params->get('infinite_scroll');

		if ($limit == 0)
		{
			$limit = $app->get('list_limit', 0);
		}
		if ($showMore == '0') {
			$this->setState('list.limit', $limit);
		}else {
			$this->setState('list.limit', 1000);
		}
		$this->setState('list.start', $start);
	}

	/**
	* Build an SQL query to load the list data.
	*
	* @return   JDatabaseQuery
	*
	* @since    1.6
	*/
	protected function getListQuery()
	{
		// Initialize variables.
		$app = JFactory::getApplication();
		$category = $this->getState('catid');
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('*')
		->from($db->quoteName('#__kwproducts_products'));

		// Filter: like / search
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$like = $db->quote('%' . $search . '%');
			$query->where('product LIKE ' . $like . 'OR tags LIKE ' . $like);
		}

		// Filter by published state
		$query->where('state = 1');
		// Filtering by catid
		if ($category)
		{
			$query->where("catid = ".(int) $category);
		}
		// $query->limit('0,100');
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'id');
		$orderDirn 	= $this->state->get('list.direction', 'desc');

		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

		return $query;
	}

	/**
	* Method to get an array of data items
	*
	* @return  mixed An array of data on success, false on failure.
	*/
	public function getItems()
	{
		$items = parent::getItems();

		foreach ($items as $item)
		{

			if (isset($item->catid) && $item->catid != '')
			{

				$db    = Factory::getDbo();
				$query = $db->getQuery(true);

				$query
				->select($db->quoteName('title'))
				->from($db->quoteName('#__categories'))
				->where('FIND_IN_SET(' . $db->quoteName('id') . ', ' . $db->quote($item->catid) . ')');

				$db->setQuery($query);

				$result = $db->loadColumn();

				$item->catid = !empty($result) ? implode(', ', $result) : '';
			}

			// if (isset($item->tags))
			// {
			// 	$values    = explode(',', $item->tags);
			// 	$textValue = array();

			// 	foreach ($values as $value)
			// 	{
			// 		if (!empty($value))
			// 		{
			// 			$db    = Factory::getDbo();
			// 			$query = "SELECT tag FROM #__kwproducts_tags WHERE state=1 AND tag LIKE '" . $value . "' ORDER BY tag";

			// 			$db->setQuery($query);
			// 			$results = $db->loadObject();

			// 			if ($results)
			// 			{
			// 				$textValue[] = $results->tag;
			// 			}
			// 		}
			// 	}

			// 	$item->tags = !empty($textValue) ? implode(', ', $textValue) : $item->tags;
			// }
		}

		return $items;
	}

	/**
	* Overrides the default function to check Date fields format, identified by
	* "_dateformat" suffix, and erases the field if it's not correct.
	*
	* @return void
	*/
	protected function loadFormData()
	{
		$app              = Factory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_KWPRODUCTS_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	* Checks if a given date is valid and in a specified format (YYYY-MM-DD)
	*
	* @param   string  $date  Date to be checked
	*
	* @return bool
	*/
	private function isValidDate($date)
	{
		$date = str_replace('/', '-', $date);
		return (date_create($date)) ? Factory::getDate($date)->format("Y-m-d") : null;
	}
}
