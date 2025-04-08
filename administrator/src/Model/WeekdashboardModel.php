<?php
namespace DnbookingNamespace\Component\Dnbooking\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;
use Joomla\Database\ParameterType;
use Joomla\Utilities\ArrayHelper;

class WeekdashboardModel extends ListModel
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id',
				'a.id',
				'reservation_date',
				'a.reservation_date',
				'room_title',
				'r.title',
				'firstname',
				'c.firstname',
				'lastname',
				'c.lastname',
				'published',
				'a.published'
			);
		}
		parent::__construct($config);
	}

	protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select([
			'a.*',
			'r.title AS room_title',
			'c.firstname',
			'c.lastname',
			'GROUP_CONCAT(e.title) AS extra_titles',
			'GROUP_CONCAT(e.id) AS extra_ids'
		])
			->from($db->quoteName('#__dnbooking_reservations', 'a'))
			->join('LEFT', $db->quoteName('#__dnbooking_rooms', 'r') . ' ON ' . $db->quoteName('a.room_id') . ' = ' . $db->quoteName('r.id'))
			->join('LEFT', $db->quoteName('#__dnbooking_customers', 'c') . ' ON ' . $db->quoteName('a.customer_id') . ' = ' . $db->quoteName('c.id'))
			->join('LEFT', $db->quoteName('#__dnbooking_extras', 'e') . ' ON ' . $db->quoteName('e.published') . ' = 1')
			->where('a.published IN (3,4)')
			->group('a.id');

		// Filter by week
		$calendarWeek = $this->getState('filter.calendarWeek');
		$year = $this->getState('filter.year', date('Y'));

		if ($calendarWeek) {
			$dates = $this->getWeekDates($calendarWeek, $year);
			$query->where('DATE(a.reservation_date) BETWEEN ' . $db->quote($dates['start']) . ' AND ' . $db->quote($dates['end']));
		}

		// Add the list ordering clause
		$orderCol = $this->state->get('list.ordering', 'a.reservation_date');
		$orderDirn = $this->state->get('list.direction', 'ASC');
		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}


	public function getItems()
	{
		$items = parent::getItems();

		if (!$items) {
			return [];
		}

		foreach ($items as &$item) {
			$extrasData = json_decode($item->extras_ids, true);
			$item->extras = [];

			if ($extrasData) {
				foreach ($extrasData as $extra) {
					$extraId = $extra['extra_id'];
					$amount = $extra['extra_count'];

					// Get extra title from the concatenated data
					$extraTitles = explode(',', $item->extra_titles);
					$extraIds = explode(',', $item->extra_ids);
					$key = array_search($extraId, $extraIds);

					if ($key !== false) {
						$item->extras[] = [
							'amount' => $amount,
							'name' => $extraTitles[$key]
						];
					}
				}
			}
		}

		return $items;
	}


	private function getWeekDates($week, $year)
	{
		$dto = new \DateTime();
		$dto->setISODate($year, $week, 1); // Start from Monday
		$start = $dto->format('Y-m-d');
		$dto->modify('+6 days');
		$end = $dto->format('Y-m-d');

		return ['start' => $start, 'end' => $end];
	}


	protected function populateState($ordering = 'a.reservation_date', $direction = 'ASC')
	{
		$app = Factory::getApplication();

		// List state information
		parent::populateState($ordering, $direction);

		$calendarWeek = $app->getUserStateFromRequest($this->context . '.filter.calendarWeek', 'filter_calendarWeek');
		$this->setState('filter.calendarWeek', $calendarWeek);

		$year = $app->getUserStateFromRequest($this->context . '.filter.year', 'filter_year', date('Y'));
		$this->setState('filter.year', $year);
	}
}
