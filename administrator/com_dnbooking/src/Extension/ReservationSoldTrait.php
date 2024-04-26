<?php

/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Administrator\Extension;



// phpcs:disable PSR1.Files.SideEffects
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;

\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Trait to implement AssociationServiceInterface
 *
 * @since  4.0.0
 */
trait ReservationSoldTrait
{

	protected static $orderFeatures;

	public function getOrderFeatures($model, $id = null)
	{
		if(!empty(self::$orderFeatures[$model])) {
			if(!empty($id) && !empty(self::$orderFeatures[$model][$id])){
				return self::$orderFeatures[$model][$id];
			}

			if(empty($id)){
				return self::$orderFeatures[$model];
			}

		}

		$adminModel = $this->getMVCFactory()->createModel($model, 'Administrator', ['ignore_request' => true]);
		if($id || $model == 'Room' || $model == 'Extra'){
			self::$orderFeatures[$model][$id] = $adminModel->getItem($id);
			return self::$orderFeatures[$model][$id];
		}
		else{
			self::$orderFeatures[$model] = $adminModel->getItems();
			return self::$orderFeatures[$model];
		}

	}

	public function getReservationSoldData($formFields, $isHolidayOrWeekend){
		$component = ComponentHelper::getParams('com_dnbooking');
		$model = $this->getModel();
		$orderData = [];
		$orderData['extras_price_total'] = 0;

		foreach ($formFields as $key => $value)
		{
			if (!empty($value)){
				if($key == "room_id"){
					$orderData[$key] = $model->getOrderFeatures('Room', $value);

					continue;
				}

				if($key == "customer_id"){
					$orderData[$key] = $model->getOrderFeatures('Customer', $value);

					continue;
				}

				if(str_contains($key, 'extra')){
					if(!is_array($value)){
						$value = json_decode($value, true);
					}

					if(is_array($value)){
						foreach ($value as $key => $extra){
							if($extra['extra_count'] > 0){
								$extras = (array) $model->getOrderFeatures('Extra', $extra['extra_id']);
								$orderData['extras'][$extras['alias']]['name'] = $extras['title'];
								$orderData['extras'][$extras['alias']]['price_single'] = $extras['price'];
								$orderData['extras'][$extras['alias']]['amount'] =  (int) $extra['extra_count'];
								$orderData['extras'][$extras['alias']]['price_total'] = $extras['price'] * $extra['extra_count'];
								$orderData['extras_price_total'] += $extras['price'] * $extra['extra_count'];
							}
						}
					}

					continue;
				}

				$orderData[$key] = $value;
			}
		}

		$orderData['isHolidayOrWeekend'] = $isHolidayOrWeekend;

		foreach ($component as $key => $value){
			$orderData['params'][$key] = $component->get($key);
		}

		return $orderData;
	}

}
