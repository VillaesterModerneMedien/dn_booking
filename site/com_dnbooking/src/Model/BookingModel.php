<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseInterface;

/**
 * Booking model for the Joomla Dnbooking component.
 *
 * @since  1.0.0
 */
class BookingModel extends BaseDatabaseModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = Factory::getContainer()->get(DatabaseInterface::class);
        parent::__construct();
    }

    /**
     * Method to get all rooms from the database.
     *
     * @return  array
     *
     * @since   1.0.0
     */
    public function getRooms(): array
    {
        $query = $this->db->getQuery(true);

        $query->select('*')
            ->from($this->db->quoteName('#__dnbooking_rooms'));

        $this->db->setQuery($query);

        return $this->db->loadObjectList();
    }


	/**
	 * Method to get a specific room from the database.
	 *
	 * @param   int  $id  The ID of the room.
	 *
	 * @return  array  The room details.
	 *
	 * @since   1.0.0
	 */
	public function getRoom($id): array
    {
        $query = $this->db->getQuery(true);
		$id = $this->db->escape($id);

        $query->select('*')
            ->from($this->db->quoteName('#__dnbooking_rooms'))
            ->where($this->db->quoteName('id') . ' = ' . $id);

        $this->db->setQuery($query);

        return $this->db->loadAssoc();
    }

	public function updateRooms(): array
	{
		$query = $this->db->getQuery(true);

		$query->select('id, personsmax')
			->from($this->db->quoteName('#__dnbooking_rooms'));

		$this->db->setQuery($query);

		return $this->db->loadAssocList();
	}

	/**
	 * Method to get all reservations from the database.
	 *
	 * @return  array  The list of reservations as an associative array.
	 *
	 * @since   1.0.0
	 */
	public function getReservations(): array
	{
		$query = $this->db->getQuery(true);

		$query->select('*')
			->from($this->db->quoteName('#__dnbooking_reservations'));

		$this->db->setQuery($query);

		return $this->db->loadAssocList();
	}

	/**
	 * Method to get all extras from the database.
	 *
	 * @return  array
	 *
	 * @since   1.0.0
	 */
	public function getExtras(): array
	{
		$query = $this->db->getQuery(true);

		$query->select('*')
			->from($this->db->quoteName('#__dnbooking_extras'));

		$this->db->setQuery($query);

		return $this->db->loadAssocList();
	}

	public function saveReservation($data){
		echo "<pre>";
		var_dump($data);
		echo "</pre>";

		return true;
	}

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return  void
     *
     * @since   1.0.0
     */
    protected function populateState()
    {
        $app = Factory::getApplication();

		$this->setState('booking.id', $app->input->getInt('id'));
		$this->setState('params', $app->getParams());
	}
}
