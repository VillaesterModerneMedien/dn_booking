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
		$personscount = $this->input->get('visitors', null, 'int');
		$model        = $this->getModel();
		$rooms        = $model->updateRooms();
		$reservations = $model->getReservations();

		$blockedRooms = [];
		foreach ($rooms as $room)
		{
			foreach ($reservations as $reservation)
			{
				$reservation['reservation_date'] = date('Y-m-d', strtotime($reservation['reservation_date']));
				if ($reservation['room_id'] == $room['id'] && $reservation['reservation_date'] == $date)
				{
					$blockedRooms[] = $room['id'];
					break;
				}
			}
			if ($room['personsmax'] < $personscount)
			{
				$blockedRooms[] = $room['id'];
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