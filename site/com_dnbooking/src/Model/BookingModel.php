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

use DnbookingNamespace\Component\Dnbooking\Administrator\Model\ReservationModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Associations;
use Joomla\Database\DatabaseInterface;

/**
 * Booking model for the Joomla Dnbooking component.
 *
 * @since  1.0.0
 */
class BookingModel extends ReservationModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = Factory::getContainer()->get(DatabaseInterface::class);
        parent::__construct();
    }

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  Form|boolean  A Form object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = [], $loadData = true)
	{
		$form = parent::getForm($data, $loadData);

		if (empty($form)) {
			return false;
		}

		$app  = Factory::getApplication();
		$user = $app->getIdentity();

		// On edit article, we get ID of article from article.id state, but on save, we use data from input
		$id = (int) $this->getState('article.id', $app->getInput()->getInt('a_id'));

		// Existing record. We can't edit the category in frontend if not edit.state.
		if ($id > 0 && !$user->authorise('core.edit.state', 'com_content.article.' . $id)) {
			$form->setFieldAttribute('catid', 'readonly', 'true');
			$form->setFieldAttribute('catid', 'required', 'false');
			$form->setFieldAttribute('catid', 'filter', 'unset');
		}

		// Prevent messing with article language and category when editing existing article with associations
		if ($this->getState('article.id') && Associations::isEnabled()) {
			$associations = Associations::getAssociations('com_content', '#__content', 'com_content.item', $id);

			// Make fields read only
			if (!empty($associations)) {
				$form->setFieldAttribute('language', 'readonly', 'true');
				$form->setFieldAttribute('catid', 'readonly', 'true');
				$form->setFieldAttribute('language', 'filter', 'unset');
				$form->setFieldAttribute('catid', 'filter', 'unset');
			}
		}

		return $form;
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
	public function getOpeningHours($date, $time): array
	{

		$query = $this->db->getQuery(true);

		// Abfrage für die Tabelle #__dnbooking_openinghours
		$query->select('*')
			->from($this->db->quoteName('#__dnbooking_openinghours'))
			->where($this->db->quoteName('day') . ' = ' . $this->db->quote($date));

		// Führe die Abfrage für die Tabelle #__dnbooking_openinghours aus
		$this->db->setQuery($query);
		$openingHours = $this->db->loadAssocList();
		// Abfrage für das Feld params des Eintrags mit dem Namen com_dnbooking in der Tabelle #__dnbooking_extensions
		$query->clear();
		$query->select('params')
			->from($this->db->quoteName('#__extensions'))
			->where($this->db->quoteName('name') . ' = ' . $this->db->quote('com_dnbooking'));

		// Führe die Abfrage für das Feld params aus
		$this->db->setQuery($query);
		$params = $this->db->loadResult();

		//$regularOpeningHours = $params->regular_opening_hours;
		//$weeklyOpeningHours = json_decode($params['weekly_opening_hours']);

		$paramsArray = json_decode($params, true);
		$regularOpeningHours = $paramsArray['regular_opening_hours'];
		$weeklyOpeningHours = $paramsArray['weekly_opening_hours'];
		// Rückgabe der Ergebnisse
		return array(
			'opening_hours' => $openingHours,
			'regular_opening_hours' => $regularOpeningHours,
			'weekly_opening_hours' => $weeklyOpeningHours
		);
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

	/**
	 * Method to get all extras from the database.
	 *
	 * @return  array
	 *
	 * @since   1.0.0
	 */
	public function getExtra($inputData = null): array
	{
		if($inputData){
			$query = $this->db->getQuery(true);
			$inputData = $this->db->escape($inputData);
			$extra = explode('-', $inputData);
			$id = $extra[1];

			$query->select('title, price')
				->from($this->db->quoteName('#__dnbooking_extras'))
				->where($this->db->quoteName('id') . ' = ' . $id);

			$this->db->setQuery($query);

			return $this->db->loadAssoc();
		}
		else {
			$a[] = 'extras';
			return $a;
		}
	}

	public function saveReservation($data){


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
