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
class DaydashboardsController extends AdminController
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_DNBOOKING_DAYDASHBOARDS';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link \JFilterInput::clean()}.
	 *
	 * @return  BaseController|bool  This object to support chaining.
	 *
	 * @throws  \Exception
	 * @since   1.0.0
	 *
	 */
	public function display($cachable = false, $urlparams = array())
	{
		$app   = Factory::getApplication();
		$input = $app->input;

		$currentDate = $app->getUserState('com_dnbooking.daydashboards.currentDate', date('Y-m-d'));

		$model = $this->getModel();
		$model->setState('filter.currentDate', $currentDate);

		return parent::display();
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The name of the model.
	 * @param   string  $prefix  The prefix for the PHP class name.
	 * @param   array   $config  Array of configuration parameters.
	 *
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel
	 *
	 * @since   1.0.0
	 */
	public function getModel($name = 'Daydashboards', $prefix = 'Administrator', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}


	public function printDaysheet()
	{
		$app = Factory::getApplication();

		$model      = $this->getModel();
		$modelName       = $model->getName();
		$items      = $model->getItems();
		$itemsToday = DnbookingHelper::filterReservationsToday($items);

		DnbookingHelper::printDaysheet($itemsToday, $modelName, 'L');

		$app->close();
	}


	public function chooseDay()
	{
		$app   = Factory::getApplication();
		$input = $app->input;

		$currentDate = $input->getString('chooseDay');

		$app->setUserState('com_dnbooking.daydashboards.currentDate', $currentDate);

		return parent::display();
	}


}
