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
use Joomla\CMS\Factory;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Service\Provider\Console;
use Joomla\CMS\Uri\Uri;
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
	 * Recognized key values include 'name', 'default_task', 'model_path', and
	 * 'view_path' (this list is not meant to be comprehensive).
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

		$date = $this->input->get('date', null, 'string');
		$model = $this->getModel();
		$rooms = $model->updateRooms();
		$reservations = $model->getReservations();

		$blockedRooms = [];

		foreach ($rooms as $room)
		{
			$room['available'] = true;
			foreach ($reservations as $reservation)
			{
				$reservation['reservation_date'] = date('Y-m-d', strtotime($reservation['reservation_date']));
				if ($reservation['room_id'] == $room['id'] && $reservation['reservation_date'] == $date)
				{
					$blockedRooms[] = $room['id'];
					break;
				}
			}
		}
		echo json_encode($blockedRooms, JSON_PRETTY_PRINT);

		$app->close();

	}

/**
     * Method to set the customer form.
     *
     * This method sets the content type header to 'text/html', gets the available rooms,
     * creates a new layout for the room list, renders the layout with the available rooms,
     * and outputs the resulting HTML. Finally, it closes the application.
     *
     * @return  void
     *
     * @since   1.0.0
     */

    public function setCustomerForm()
    {
        header('Content-Type: text/html');
        $rooms = $this->_getAvailableRooms();

        $app = Factory::getApplication();
        $layout = new FileLayout('booking.customer', JPATH_ROOT .'/components/com_dnbooking/layouts');
        $html = $layout->render();
        echo $html;

        $app->close();
    }

	/**
	 * Get the return URL.
	 *
	 * If a "return" variable has been passed in the request
	 *
	 * @return  string    The return URL.
	 *
	 * @since   1.0.0
	 */
	protected function getReturnPage()
	{
		$return = $this->input->get('return', null, 'base64');

		if (empty($return) || !Uri::isInternal(base64_decode($return)))
		{
			return Uri::base();
		}

		return base64_decode($return);
	}
}
