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
class DaydashboardsModel extends ReservationsModel
{
	use ReservationSoldTrait;

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.6
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (!isset($this->cache[$store]))
		{
			try
			{
				// Load the list items and add the items to the internal cache.
				$this->cache[$store] = $this->_getList($this->_getListQuery(), $this->getStart(), $this->getState('list.limit'));
			}
			catch (\RuntimeException $e)
			{
				$this->setError($e->getMessage());

				return false;
			}
		}

		foreach($this->cache[$store] as $key => $item)
		{
			$formData = ArrayHelper::fromObject($item);
			$orderFeatures = $this->getReservationSoldData($formData, $item->holiday);

			if(isset($orderFeatures['room_id'])){
				$this->cache[$store][$key]->room = ArrayHelper::fromObject($orderFeatures['room_id']);
			}

			if(isset($orderFeatures['customer_id'])){
				$this->cache[$store][$key]->customer = ArrayHelper::fromObject($orderFeatures['customer_id']);
			}

			if(isset($orderFeatures['extras'])){
				$this->cache[$store][$key]->extras = $orderFeatures['extras'];
				$this->cache[$store][$key]->extras_price_total = $orderFeatures['extras_price_total'];
			}
		}

		return $this->cache[$store];
	}

}
