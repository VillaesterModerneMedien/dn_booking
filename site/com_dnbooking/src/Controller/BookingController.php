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
use DnbookingNamespace\Component\Dnbooking\Administrator\Controller\ReservationController;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Service\Provider\Console;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

/**
 * Controller for single booking view
 *
 * @since  1.0.0
 */



class BookingController extends ReservationController
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
		$timeObj = DateTime::createFromFormat('H:i:s', $time);
		$startTimeObj = DateTime::createFromFormat('H:i:s', $startTime);
		$endTimeObj = DateTime::createFromFormat('H:i:s', $endTime);

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
	 *
	 * @return Registry
	 *
	 * @since version
	 */
	private function _getComponentParams()
	{
		$params = ComponentHelper::getParams('com_dnbooking');
		return $params;
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

		//$date         = $this->input->get('date', null, 'string');
		$date         = HTMLHelper::_('date', $this->input->get('date', null, 'string'), 'Y-m-d');
		$weekdayNumber = !empty($date) ? $this->_getWeekdayNumber($date) : -1;

		$time           = $this->input->get('time', null, 'string');
		//$time         = HTMLHelper::_('date', $this->input->get('time', null, 'string'), 'H:i');
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
				$startTime = $b['starttime'] . ':00';
				$endTime = $b['endtime'] . ':00';
				$isOpen = $this->_checkTime($time, $startTime, $endTime);
				$blockedRooms['times'] = $isOpen ? '' : 'timeclosed';
				$blockedRooms['start'] = $startTime;
				$blockedRooms['end'] = $endTime;
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
					$startTime = $d['starttime'] . ':00';
					$endTime = $d['endtime'] . ':00';
					$isOpen = $this->_checkTime($time, $startTime, $endTime);
					$blockedRooms['times'] = $isOpen ? '' : 'timeclosed';
					$blockedRooms['start'] = $startTime;
					$blockedRooms['end'] = $endTime;
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
		if (empty($blockedRooms['rooms'])) {
			$blockedRooms['rooms'][] = 'all available';
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
		$component = $this->_getComponentParams();


		foreach ($formFields as $key => $value){
			if($key == "room"){
				$orderData[$key] = $model->getRoom($value);
			}
			else if(str_contains($key, 'extra')){
				if($value > 0){
					$extras = $model->getExtra($key);

					$orderData['extras'][$extras['title']]['name'] = $extras['title'];
					$orderData['extras'][$extras['title']]['price_single'] = $extras['price'];
					$orderData['extras'][$extras['title']]['amount'] = $value;
					$orderData['extras'][$extras['title']]['price_total'] = $extras['price'] * $value;
				}
			}
			else {
				$orderData[$key] = $value;
			}

		}
		foreach ($component as $key => $value){
			$orderData['params'][$key] = $component->get($key);
		}
		$html = $layout->render($orderData);

		echo $html;

		$app->close();
	}


	public function sendForm()
	{
		$model = $this->getModel();
		$input = $this->input;
		$formData = $input->get('jform', null, 'array');
		$formData = $model->saveReservation($formData);
		$input->set('jform', $formData);

		if ($this->save())
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
