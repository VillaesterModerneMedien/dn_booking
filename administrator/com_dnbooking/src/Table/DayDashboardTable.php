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
		$app    = Factory::getApplication();
		$date   = Factory::getDate()->toSql();
		$userId = Factory::getApplication()->getIdentity()->id;


		$db = $this->getDbo();

		// Set created date if not set.
		if (!(int) $this->created)
		{
			$this->created = $date;
		}

		//$this->extras_ids = json_encode($this->extras_ids);

		$this->additional_info   = json_encode($this->additional_info);
		$this->additional_infos2 = json_encode($this->additional_infos2);


		foreach ($this->extras_ids as $key => $value)
		{
			if (empty($value['extra_count']))
			{
				unset($this->extras_ids[$key]);
			}
		}

		$this->extras_ids         = json_encode($this->extras_ids);

		$this->reservation_date = Factory::getDate($this->reservation_date, 'UTC')->toSql();

		$this->reservation_price = $this->_calcPrice($this->additional_info, $this->room_id, $this->extras_ids);

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

	protected function _calcPrice($infos, $room, $extras)
	{
		$isHolidayOrWeekend = false;
		if($this->holiday == '1')
		{
			$isHolidayOrWeekend = true;
		}

		//TODO: Guido anpassen

		$factory   = Factory::getApplication()->bootComponent('com_dnbooking')->getMVCFactory();
		$roomModel = $factory->createModel('Room', 'Administrator');
		$roomParams     = $roomModel->getItem($room);
		$extrasModel = $factory->createModel('Extras', 'Administrator');
		$extrasParams     =  $extrasModel->getItems();
		$infos = json_decode($infos);
		$visitorsPackage = (int) $infos->visitorsPackage;
		$visitorsAdmission = (int) $infos->visitors;

		$extraInfos = [];
		$totalCosts = 0;

		foreach ($extrasParams as $extra) {
			$extraInfos[$extra->id]['price'] = $extra->price;
		}

		foreach (json_decode($extras) as $extra) {
			$totalCosts += $extraInfos[$extra->extra_id]['price'] * $extra->extra_count;
		}

		$params = ComponentHelper::getParams('com_dnbooking');

		if(!$isHolidayOrWeekend)
		{
			$roomPriceRegular      = $roomParams->priceregular;
			$totalCosts += $roomPriceRegular;

			$packagePriceRegular   = $params->get('packagepriceregular');
			$totalCosts += $packagePriceRegular * $visitorsPackage;

			$admissionPriceRegular = $params->get('admissionpriceregular');
			$totalCosts += $admissionPriceRegular * $visitorsAdmission;
		}
		else{
			$roomPriceCustom = $roomParams->pricecustom;
			$totalCosts += $roomPriceCustom;

			$packagePriceCustom = $params->get('packagepricecustom');
			$totalCosts += $packagePriceCustom * $visitorsPackage;

			$admissionPriceCustom = $params->get('admissionpricecustom');
			$totalCosts += $admissionPriceCustom * $visitorsAdmission;
		}

		return $totalCosts;

	}

}
