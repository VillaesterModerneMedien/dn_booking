<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace DnbookingNamespace\Component\Dnbooking\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Factory;
use Joomla\Database\ParameterType;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Table\Table;

/**
 * Methods supporting a list of reservations records.
 *
 * @since  1.0.0
 */
class ReservationsModel extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.0.0
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
                'reservation_date', 'a.reservation_date',
                'published', 'a.published',
                'created', 'a.created',
			);
		}

		parent::__construct($config);
	}


	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.6
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId();


		// Try to load the data from internal storage.
		if (isset($this->cache[$store])) {
			return $this->cache[$store];
		}

		try {

			// Load the list items and add the items to the internal cache.
			$this->cache[$store] = $this->_getList($this->_getListQuery(), $this->getStart(), $this->getState('list.limit'));
		} catch (\RuntimeException $e) {
			$this->setError($e->getMessage());
			return false;
		}

		return $this->cache[$store];
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A database object
	 *
	 * @since   1.0.0
	 */
	public function getTable($type = 'Reservation', $prefix = 'Administrator', $config = array())
	{
		return parent::getTable($type, $prefix, $config);
	}

	/**
	 * Returns an object list
	 *
	 * @param   string  $query       The query
	 * @param   int     $limitstart  Offset
	 * @param   int     $limit       The number of records
	 *
	 * @return  array
	 */
	protected function _getList($query, $limitstart = 0, $limit = 0)
	{
		$listOrder = $this->getState('list.ordering', 'a.id');
		$listDirn  = $this->getState('list.direction', 'desc');

		$query->order($this->_db->quoteName($listOrder) . ' ' . $this->_db->escape($listDirn));

		// Process pagination.
		$result = parent::_getList($query, $limitstart, 0);
		return $result;
	}


	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDatabase();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.*, c.id AS customer_id, c.firstname, c.lastname, d.title AS room_title');
		$query->from($db->quoteName('#__dnbooking_reservations', 'a'));

		// Join over the customers table.
		$query->join('LEFT', $db->quoteName('#__dnbooking_customers', 'c') . ' ON ' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.customer_id'));

		$query->join('LEFT', $db->quoteName('#__dnbooking_rooms', 'd') . ' ON ' . $db->quoteName('a.room_id') . ' = ' . $db->quoteName('d.id'));

		// Filter by published state
		$published = (string) $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where($db->quoteName('a.published') . ' = :published');
			$query->bind(':published', $published, ParameterType::INTEGER);
		}
		elseif ($published === '')
		{
			//$query->where('(' . $db->quoteName('a.published') . ' = 0 OR ' . $db->quoteName('a.published') . ' = 1)');
			$query->where('(' . $db->quoteName('a.published') . ' != -2 )');
		}

		// Filter by search in title or note or id:.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$search = substr($search, 3);
				$query->where($db->quoteName('a.id') . ' = :id');
				$query->bind(':id', $search, ParameterType::INTEGER);
			}
			else
			{
				$search = '%' . trim($search) . '%';
				$query->where('(' .
					'CONCAT(DATE_FORMAT(' . $db->quoteName('a.reservation_date') . ', "%d.%m.%Y"), ' . $db->quoteName('c.firstname') . ', ' . $db->quoteName('c.lastname') . ', ' . $db->quoteName('a.id') . ', ' . $db->quoteName('d.title') . ') LIKE :combined' .
					' OR ' . $db->quoteName('a.admin_notes') . ' LIKE :admin_notes' .
					')');
				$query->bind(':combined', $search);
				$query->bind(':admin_notes', $search);
			}
		}

        // Add the list ordering clause
        $orderCol = $this->state->get('list.ordering', 'a.reservation_date');
        $orderDirn = $this->state->get('list.direction', 'DESC');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}



	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function populateState($ordering = 'a.reservation_date', $direction = 'DESC')
	{
		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		// List state information.
		parent::populateState($ordering, $direction);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');

		return parent::getStoreId($id);
	}

	public function getModel() {
		return $this;
	}

}
