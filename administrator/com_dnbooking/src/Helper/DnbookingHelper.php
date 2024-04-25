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
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;

/**
 * Dnbooking component helper.
 *
 * @since  1.0.0
 */
class DnbookingHelper extends ContentHelper
{

	public function calcPrice($infos, $room, $extras, $holiday)
	{
		$isHolidayOrWeekend = $holiday;

		//TODO: Guido anpassen

		$factory   = Factory::getApplication()->bootComponent('com_dnbooking')->getMVCFactory();
		$roomModel = $factory->createModel('Room', 'Administrator');
		$roomParams     = $room;
		$extrasParams     =  $extras;
		$infos = json_decode($infos);
		$visitorsPackage = (int) $infos->visitorsPackage;
		$visitorsAdmission = (int) $infos->visitors;

		$extraInfos = [];
		$totalCosts = 0;

		foreach ($extrasParams as $extra) {
			$totalCosts += $extra['price_total'];
		}

		$params = ComponentHelper::getParams('com_dnbooking');

		if(!$isHolidayOrWeekend)
		{
			$roomPriceRegular      = (float) $roomParams['priceregular'];
			$totalCosts += $roomPriceRegular;

			$packagePriceRegular   = $params->get('packagepriceregular');
			$totalCosts += $packagePriceRegular * $visitorsPackage;

			$admissionPriceRegular = $params->get('admissionpriceregular');
			$totalCosts += $admissionPriceRegular * $visitorsAdmission;
		}
		else{
			$roomPriceCustom = (float) $roomParams['pricecustom'];
			$totalCosts += $roomPriceCustom;

			$packagePriceCustom = $params->get('packagepricecustom');
			$totalCosts += $packagePriceCustom * $visitorsPackage;

			$admissionPriceCustom = $params->get('admissionpricecustom');
			$totalCosts += $admissionPriceCustom * $visitorsAdmission;
		}

		return $totalCosts;

	}

}
