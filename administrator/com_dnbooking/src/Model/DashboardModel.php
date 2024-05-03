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
use Joomla\Utilities\ArrayHelper;

/**
 * Methods supporting a list of reservations records.
 *
 * @since  1.0.0
 */
class DashboardModel extends ReservationsModel
{
	use ReservationSoldTrait;



}
