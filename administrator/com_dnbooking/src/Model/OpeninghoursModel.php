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
 * Methods supporting a list of openinghours records.
 *
 * @since  1.0.0
 */
class OpeninghoursModel extends ListModel
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
		parent::__construct($config);
	}

	public function checkMonth($date)
	{

		$db = Factory::getContainer()->get('DatabaseDriver');
		$query = $db->getQuery(true);

		$query->select('*')
			->from($db->quoteName($this->getTable()->getTableName()))
			->where($db->quoteName('day') . ' LIKE ' . $db->quote('%' . $date . '%'));

		$db->setQuery($query);
		return $db->loadObjectList();
	}
    public function updateDay($data)
    {

        $data['dayID'] = $data['dayID'];
		$table = $this->getTable();
        $table->load($data['dayID']);
        $table->bind($data);
        return $table->store();
    }


    public function addDay($data)
    {
        $table = $this->getTable();

        $dayToAdd = [
            'day' => $data['date'],
            'opening_time' => '09:00:00',
            'closing_time' => '18:00:00',
        ];

        if (!$table->bind($dayToAdd)) {
            // Fehlerbehandlung
            JError::raiseWarning(500, $table->getError());
            return false;
        }

        if (!$table->store()) {
            // Fehlerbehandlung
            JError::raiseWarning(500, $table->getError());
            return false;
        }

        return true;
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
	public function getTable($type = 'Openinghour', $prefix = 'Administrator', $config = array())
	{
		return parent::getTable($type, $prefix, $config);
	}

}
