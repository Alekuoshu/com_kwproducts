<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_kwproducts
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Content Component Route Helper.
 *
 * @since  1.5
 */
class KwproductsHelperRoute
{
	/**
	 * Get the product route.
	 *
	 * @param   integer  $id        The route of the content item.
	 * @param   integer  $catid     The category ID.
	 * @param   integer  $language  The language code.
	 *
	 * @return  string  The product route.
	 *
	 * @since   1.5
	 */
	public static function getProductRoute($id, $catid = 0, $language = 0)
	{
		// Create the link
		$link = 'index.php?option=com_kwproducts&view=product&id=' . $id;

		if ((int) $catid > 1)
		{
			$link .= '&catid=' . $catid;
		}

		if ($language && $language !== '*' && JLanguageMultilang::isEnabled())
		{
			$link .= '&lang=' . $language;
		}

		return $link;
	}

	/**
	 * Get the category route.
	 *
	 * @param   integer  $catid     The category ID.
	 * @param   integer  $language  The language code.
	 *
	 * @return  string  The product route.
	 *
	 * @since   1.5
	 */
	public static function getCategoryRoute($catid, $language = 0)
	{
		if ($catid instanceof JCategoryNode)
		{
			$id = $catid->id;
		}
		else
		{
			$id = (int) $catid;
		}

		if ($id < 1)
		{
			$link = '';
		}
		else
		{
			$link = 'index.php?option=com_kwproducts&view=category&id=' . $id;

			if ($language && $language !== '*' && JLanguageMultilang::isEnabled())
			{
				$link .= '&lang=' . $language;
			}
		}

		return $link;
	}

	/**
	 * Get the form route.
	 *
	 * @param   integer  $id  The form ID.
	 *
	 * @return  string  The product route.
	 *
	 * @since   1.5
	 */
	public static function getFormRoute($id)
	{
		return 'index.php?option=com_kwproducts&task=product.edit&a_id=' . (int) $id;
	}
}
