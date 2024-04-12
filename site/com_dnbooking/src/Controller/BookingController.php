<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Site\Controller;

\defined('_JEXEC') or die;

use DateTime;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Service\Provider\Console;
use Joomla\CMS\Uri\Uri;
use Joomla\Plugin\Fields\Text\Extension\Text;
use Joomla\Utilities\ArrayHelper;

/**
 * Controller for single booking view
 *
 * @since  1.0.0
 */



class BookingController extends FormController
{
	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $view_item = 'booking';
	protected $input = '';

	/**
	 * Method to get the weekday number of a date.
	 *
	 * @param $date
	 *
	 * @return int
	 *
	 * @since version
	 */
	protected function _getWeekdayNumber($date)
	{
		$weekdayNumber = (date('w', strtotime($date)) + 6) % 7;
		return $weekdayNumber;
	}
	protected function _checkTime($time, $startTime, $endTime)
	{
		$timeObj = DateTime::createFromFormat('H:i', $time);
		$startTimeObj = DateTime::createFromFormat('H:i', $startTime);
		$endTimeObj = DateTime::createFromFormat('H:i', $endTime);

		$isOpen = false;
		if ($timeObj >= $startTimeObj && $timeObj <= $endTimeObj)
		{
			$isOpen = true;
		}
		return $isOpen;
	}

	/**
	 * Constructor.
	 *
	 * @param   array                $config   An optional associative array of configuration settings.
	 *                                         Recognized key values include 'name', 'default_task', 'model_path', and
	 *                                         'view_path' (this list is not meant to be comprehensive).
	 * @param   MVCFactoryInterface  $factory  The factory.
	 * @param   CMSApplication       $app      The JApplication for the dispatcher
	 * @param   \JInput              $input    Input
	 *
	 * @since   1.0.0
	 */
	public function __construct($config = [], MVCFactoryInterface $factory = null, $app = null, $input = null)
	{
		$this->input = Factory::getApplication()->input;
		parent::__construct($config, $factory, $app, $input);
	}


	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel  The model.
	 *
	 * @since   1.0.0
	 */
	public function getModel($name = 'Booking', $prefix = '', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, ['ignore_request' => false]);
	}


	/**
	 * Method to get available rooms.
	 *
	 * @return  string  JSON encoded array of available rooms.
	 *
	 * @since   1.0.0
	 */

	public function getBlockedRooms()
	{
		$app = Factory::getApplication();

		header('Content-Type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: *'); //Erlaube CORS nur für diese Domain
		header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); // Erlaubte Methoden
		header('Access-Control-Allow-Headers: Content-Type'); // Erlaubte Header

		$date         = $this->input->get('date', null, 'string');
		$weekdayNumber = !empty($date) ? $this->_getWeekdayNumber($date) : -1;
		$time         = $this->input->get('time', null, 'string');
		$personscount = $this->input->get('visitors', null, 'int');
		$model        = $this->getModel();
		$rooms        = $model->updateRooms();
		$reservations = $model->getReservations();
		$openingHours = $model->getOpeningHours($date, $time);
		$isOpen = true;
		//$weekdayNumber = !empty($date) ? $this->_getWeekdayNumber($date) : null;

		$customOpeningHour = isset($openingHours['opening_hours'][0]) ? $openingHours['opening_hours'][0] : false;
		$regularOpeningHour = $openingHours['regular_opening_hours'];
		$weeklyOpeningHour = $openingHours['weekly_opening_hours'];


		$weeklyOpeningHour = $weeklyOpeningHour;
		$weeklyOpeningHourKeys = array_keys($weeklyOpeningHour);

		$blockedRooms = [];
		$blockedDays = [];

		if($time){
			if ($customOpeningHour) {
				$a = $customOpeningHour['opening_time'];
				$b = $regularOpeningHour['regular_opening_hours' . $a];
				$startTime = $b['starttime'];
				$endTime = $b['endtime'];
				$isOpen = $this->_checkTime($time, $startTime, $endTime);
				$blockedRooms['times'] = $isOpen ? '' : 'timeclosed';
			}

			$a = ($weekdayNumber != -1) ? $weeklyOpeningHour[$weeklyOpeningHourKeys[$weekdayNumber]] : false;
			if ($a && !$customOpeningHour) {
				$b = json_decode($a, true);
				$c = array_keys($b);
				$openingTime = $b[$c[0]];
				if(str_contains($openingTime, 'closed'))
				{
					$isOpen = false;
					$blockedRooms['times'] = $isOpen ? '' : 'dayclosed';
				}
				else
				{
					$d = $regularOpeningHour[$openingTime];
					$startTime = $d['starttime'];
					$endTime = $d['endtime'];
					$isOpen = $this->_checkTime($time, $startTime, $endTime);
					$blockedRooms['times'] = $isOpen ? '' : 'timeclosed';
				}
			}
		}
		if($isOpen) {
			foreach ($rooms as $room)
			{
				foreach ($reservations as $reservation)
				{
					$reservation['reservation_date'] = date('Y-m-d', strtotime($reservation['reservation_date']));
					if ($reservation['room_id'] == $room['id'] && $reservation['reservation_date'] == $date)
					{
						$blockedRooms['rooms'][] = $room['id'];
						break;
					}
				}
				if ($room['personsmax'] < $personscount)
				{
					$blockedRooms['rooms'][] = $room['id'];
				}
			}
		}
		else{
			foreach ($rooms as $room) {
				$blockedRooms['rooms'][] = $room['id'];
			}
		}
		echo json_encode($blockedRooms, JSON_PRETTY_PRINT);

		$app->close();

	}

	public function getOrderHTML(){
		header('Content-Type: text/html');

		$formFields = $this->input->post->getArray();
		$orderData = [];

		$app = Factory::getApplication();
		$input = $app->input;
		$model = $this->getModel();
		$layout = new FileLayout('booking.modal', JPATH_ROOT .'/components/com_dnbooking/layouts');

		foreach ($formFields as $key => $value){
			if($key == "room"){
				$orderData[$key] = $model->getRoom($value);
			}
			else {
				$orderData[$key] = $value;
			}
		}

		$html = $layout->render($orderData);

		echo $html;

		$app->close();
	}


	public function sendForm()
	{
		$model = $this->getModel();
		$data  = $this->input->post->getArray();

		if ($model->saveReservation($data))
		{
			$params = ComponentHelper::getParams('com_dnbooking');
			$menuItem    = $params->get('returnurl');
			if(!isset($menuItem)){
				$this->setRedirect("/");
			}
			else{
				$this->setRedirect(Route::_("index.php?Itemid=".$menuItem, false));
			}
		}
		else
		{
			Factory::getApplication()->enqueueMessage(
				Text::_('COM_DNBOOKING_FORMSUBMIT_ERROR'),
				'error'
			);
		}
	}
}