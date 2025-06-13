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

	/**
	 * @param $date
	 *
	 * @return mixed
	 *
	 * @since version
	 */
	public function checkMonth($year, $month)
	{

		$db = Factory::getContainer()->get('DatabaseDriver');
		$query = $db->getQuery(true);

		$query->select('*')
			->from($db->quoteName($this->getTable()->getTableName()))
			->where($db->quoteName('day') . ' LIKE ' . $db->quote($year . '-' . $month . '-%'));

		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
	public function getOpeningHours()
	{
		$db = Factory::getContainer()->get('DatabaseDriver');
		$query = $db->getQuery(true);

		$query->select('params')
			->from($db->quoteName('#__extensions'))
			->where($db->quoteName('name') . ' = ' . $db->quote('com_dnbooking'));

		$db->setQuery($query);
		$params = $db->loadObject();
		$result = json_decode($params->params, true);

		return $result;
	}
    public function updateDay($data)
    {
		$table = $this->getTable();
        $table->load($data['day']);
        $table->bind($data);
        return $table->store();
    }

    public function addDay($data)
    {
	    $db = $this->getDbo();
	    $table = $this->getTable();
		$dayToAdd = [
            'day' => $data['day'],
            'opening_time' => $data['opening_time'],
        ];

	    $columns = array('day', 'opening_time');
	    $values  = array(
		    $db->quote($data['day']),
		    $db->quote($data['opening_time'])
	    );

	    $query = $db->getQuery(true)
		    ->insert($db->quoteName('#__dnbooking_openinghours'))
		    ->columns(implode(',', $db->quoteName($columns)))
		    ->values(implode(',', $values));

		try {
			$db->setQuery($query);
			$db->execute();
			return true;
		} catch (\Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}
		/*
		$data = ArrayHelper::toObject($dayToAdd, 'stdClass'); //??funktioniert genauso nicht
        if (!$table->bind($dayToAdd)) {
	        Factory::getApplication()->enqueueMessage($table->getError(), 'warning');
            return false;
        }

        if (!$table->store()) {
	        Factory::getApplication()->enqueueMessage($table->getError(), 'warning');
            return false;
        }*/
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
