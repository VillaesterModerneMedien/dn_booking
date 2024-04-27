<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Administrator\Table;

\defined('_JEXEC') or die;

use DnbookingNamespace\Component\Dnbooking\Administrator\Extension\ReservationSoldTrait;
use DnbookingNamespace\Component\Dnbooking\Administrator\Helper\DnbookingHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Tag\TaggableTableInterface;
use Joomla\CMS\Tag\TaggableTableTrait;
use Joomla\CMS\Versioning\VersionableTableInterface;
use Joomla\Database\DatabaseDriver;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

/**
 * Reservation Table class.
 *
 * @since  1.0.0
 */
class ReservationTable extends Table implements VersionableTableInterface, TaggableTableInterface
{
	use TaggableTableTrait;

	/**
	 * Indicates that columns fully support the NULL value in the database
	 *
	 * @var    boolean
	 * @since  1.0.0
	 */
	protected $_supportNullValue = true;

	/**
	 * Ensure the params and metadata in json encoded in the bind method
	 *
	 * @var    array
	 * @since  1.0.0
	 */
	//protected $_jsonEncode = array('params', 'metadata');

	/**
	 * Constructor
	 *
	 * @param   DatabaseDriver  $db  Database connector object
	 *
	 * @since   1.0.0
	 */
	public function __construct(DatabaseDriver $db)
	{
		$this->typeAlias = 'com_dnbooking.reservation';

		parent::__construct('#__dnbooking_reservations', 'id', $db);
	}

	/**
	 * Overloaded check function
	 *
	 * @return  boolean  True on success, false on failure
	 *
	 * @see     \JTable::check
	 * @since   1.0.0
	 */
	public function check()
	{
		try
		{
			parent::check();
		}
		catch (\Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		if (!$this->modified)
		{
			$this->modified = $this->created;
		}

		return true;
	}

	/**
	 * Method to store a row
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success, false on failure.
	 */
	public function store($updateNulls = false)
	{
		$date   = Factory::getDate()->toSql();
		$db = $this->getDbo();

		$this->holiday = DnbookingHelper::checkHolidays($this->reservation_date);

		// Set created date if not set.
		if (!(int) $this->created)
		{
			$this->created = $date;
		}

		//$this->extras_ids = json_encode($this->extras_ids);

		foreach ($this->extras_ids as $key => $value)
		{
			if (empty($value['extra_count']))
			{
				unset($this->extras_ids[$key]);
			}
		}

		$this->reservation_date = Factory::getDate($this->reservation_date, 'UTC')->toSql();

		$reservation_price = $this->_calcPrice();

		$this->reservation_price = $reservation_price;

		$this->extras_ids         = json_encode($this->extras_ids);
		$this->additional_info   = json_encode($this->additional_info);
		$this->additional_infos2 = json_encode($this->additional_infos2);

		if ($this->id)
		{
			$this->modified = $date;
		}
		else
		{

			if (!(int) $this->modified)
			{
				$this->modified = $date;
			}

		}

		// Verify that the alias is unique
		$table = Table::getInstance('ReservationTable', __NAMESPACE__ . '\\', array('dbo' => $db));

		return parent::store($updateNulls);
	}


	/**
	 * Get the type alias for the history table
	 *
	 * @return  string  The alias as described above
	 *
	 * @since   1.0.0
	 */
	public function getTypeAlias()
	{
		return $this->typeAlias;
	}

	protected function _calcPrice()
	{
		//TODO: Guido anpassen

		$extrasIds = ArrayHelper::getColumn($this->extras_ids, 'extra_id');
		$extrasCount = ArrayHelper::getColumn($this->extras_ids, 'extra_count');

		$factory   = Factory::getApplication()->bootComponent('com_dnbooking')->getMVCFactory();
		$roomModel = $factory->createModel('Room', 'Administrator');
		$roomParams     = $roomModel->getItem($this->room_id);
		$roomParams     = ArrayHelper::fromObject($roomParams);
		$extrasModel = $factory->createModel('Extras', 'Administrator');
		$extrasParams     =  $extrasModel->getItems($extrasIds);

		$extrasTotal = 0;

		foreach ($extrasParams as $key => $extra) {
			$extraInfos[$extra->id]['price'] = $extra->price;
			$extrasTotal += $extra->price * $extrasCount[$key];
		}

		$totalCosts = DnbookingHelper::calcPrice($this->additional_info, $roomParams, $extrasTotal, $this->holiday);

		return $totalCosts;
	}

}
