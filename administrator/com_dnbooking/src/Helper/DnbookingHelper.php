<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Administrator\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\ContentHelper;

/**
 * Dnbooking component helper.
 *
 * @since  1.0.0
 */
class DnbookingHelper extends ContentHelper
{

	/**
	 * Calculates the total price for a booking.
	 *
	 * This function takes the booking information, room details, extras, and a flag indicating whether it's a holiday or weekend,
	 * and calculates the total cost of the booking.
	 *
	 * @param   mixed  $infos    Information about the booking. This can be an array or a JSON string. It should have 'visitorsPackage' and 'visitors' properties.
	 * @param   array  $room     An array containing the room details. It should have 'priceregular' and 'pricecustom' properties.
	 * @param   array  $extras   An array of extras. Each extra should be an array with a 'price_total' property.
	 * @param   bool   $holiday  A flag indicating whether the booking is for a holiday or weekend.
	 *
	 * @return float The total cost of the booking.
	 */
	public function calcPrice($infos, $room, $extras, $holiday)
	{
		$isHolidayOrWeekend = $holiday;
		$roomParams         = $room;
		$extrasParams       = $extras;
		if (!is_array($infos))
		{
			$infos = json_decode($infos, true);
		}
		$visitorsPackage   = (int) $infos['visitorsPackage'];
		$visitorsAdmission = (int) $infos['visitors'];

		$totalCosts = 0;

		foreach ($extrasParams as $extra)
		{
			$totalCosts += $extra['price_total'];
		}

		$params = ComponentHelper::getParams('com_dnbooking');

		if (!$isHolidayOrWeekend)
		{
			$roomPriceRegular = (float) $roomParams['priceregular'];
			$totalCosts       += $roomPriceRegular;

			$packagePriceRegular = $params->get('packagepriceregular');
			$totalCosts          += $packagePriceRegular * $visitorsPackage;

			$admissionPriceRegular = $params->get('admissionpriceregular');
			$totalCosts            += $admissionPriceRegular * $visitorsAdmission;
		}
		else
		{
			$roomPriceCustom = (float) $roomParams['pricecustom'];
			$totalCosts      += $roomPriceCustom;

			$packagePriceCustom = $params->get('packagepricecustom');
			$totalCosts         += $packagePriceCustom * $visitorsPackage;

			$admissionPriceCustom = $params->get('admissionpricecustom');
			$totalCosts           += $admissionPriceCustom * $visitorsAdmission;
		}

		return $totalCosts;
	}

	/**
	 * Filters the reservations for today.
	 *
	 * This function takes an array of reservation objects and returns a new array that only contains the reservations for today.
	 * Each reservation object should have a 'reservation_date' property that contains the date of the reservation in 'Y-m-d H:i:s' format.
	 *
	 * @param   array  $reservations  An array of reservation objects. Each object should have a 'reservation_date' property.
	 *
	 * @return array An array of reservation objects for today.
	 */
	function filterReservationsToday($reservations)
	{
		// Get today's date in 'Y-m-d' format
		$today = date('Y-m-d');

		// Initialize an empty array to hold the reservations for today
		$reservationsToday = [];

		// Loop through each reservation object in the array
		foreach ($reservations as $reservation)
		{
			// Extract the date from the 'reservation_date' and compare it with today's date
			if (substr($reservation->reservation_date, 0, 10) == $today)
			{
				// If the reservation date is today, add it to the list
				$reservationsToday[] = $reservation;
			}
		}

		// Return the list of reservations for today
		return $reservationsToday;
	}

}
