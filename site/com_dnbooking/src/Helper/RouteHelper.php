<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryNode;
use Joomla\CMS\Language\Multilanguage;

/**
 * Dnbooking Component Route Helper
 *
 * @static
 * @package     Joomla.Site
 * @subpackage  com_dnbooking * @since       1.0.0
 */
abstract class RouteHelper
{
	/**
	 * Get the URL route for a reservation from a reservation ID, reservations category ID and language
	 *
	 * @param   integer  $id        The id of the reservations
	 * @param   mixed    $language  The id of the language being used.
	 *
	 * @return  string  The link to the reservations
	 *
	 * @since   1.0.0
	 */
/*	public static function getReservationRoute($id, $catid = 0, $language = 0)
	{
		// Create the link
		$link = 'index.php?option=com_dnbooking&view=reservation&id=' . $id;
        
		if ($catid > 1)
		{
			$link .= '&catid=' . $catid;
		}
        
		if ($language && $language !== '*' && Multilanguage::isEnabled())
		{
			$link .= '&lang=' . $language;
		}

		return $link;
	}*/

}
