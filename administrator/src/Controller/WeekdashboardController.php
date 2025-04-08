<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Administrator\Controller;

\defined('_JEXEC') or die;

use DnbookingNamespace\Component\Dnbooking\Administrator\Helper\DnbookingHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Controller\BaseController;


/**
 * The Rooms list controller class.
 *
 * @since  1.0.0
 */
class WeekdashboardController extends AdminController
{
	protected $text_prefix = 'COM_DNBOOKING_WEEKDASHBOARD';

	public function getModel($name = 'Weekdashboard', $prefix = 'Administrator', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}



	public function printWeeksheet()
	{
		$app = Factory::getApplication();
		$model = $this->getModel();
		$items = $model->getItems();
		$itemsWeek = DnbookingHelper::filterReservationsWeek($items);

		DnbookingHelper::printWeeksheet($itemsWeek);

		$app->close();
	}

}
