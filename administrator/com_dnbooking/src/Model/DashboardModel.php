<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace DnbookingNamespace\Component\Dnbooking\Administrator\Model;

\defined('_JEXEC') or die;

use DnbookingNamespace\Component\Dnbooking\Administrator\Extension\ReservationSoldTrait;
use Joomla\CMS\Factory;
use Joomla\Database\DatabaseInterface;
use Joomla\Utilities\ArrayHelper;

/**
 * Methods supporting a list of reservations records.
 *
 * @since  1.0.0
 */
class DashboardModel extends ReservationsModel
{
	use ReservationSoldTrait;

	public function getStatistic()
	{
		$db = Factory::getContainer()->get(DatabaseInterface::class);
		$query = $db->getQuery(\true);
		$query->select('(SELECT COUNT(*) FROM #__dnbooking_reservations) as reservations');
		$query->select('(SELECT COUNT(*) FROM #__dnbooking_customers WHERE published=1) as customers');
		$db->setQuery($query);
		$statistic = $db->loadObject();
		return $statistic;
	}

}
