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

use Joomla\CMS\Factory;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Tag\TaggableTableInterface;
use Joomla\CMS\Tag\TaggableTableTrait;
use Joomla\CMS\Versioning\VersionableTableInterface;
use Joomla\Database\DatabaseDriver;
use Joomla\CMS\Language\Text;

/**
 * Room Table class.
 *
 * @since  1.0.0
 */
class RoomTable extends Table implements VersionableTableInterface, TaggableTableInterface
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
		$this->typeAlias = 'com_dnbooking.room';

		parent::__construct('#__dnbooking_rooms', 'id', $db);
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

		// Add your checks here


        // Generate a valid alias
		$this->generateAlias();


        if (!$this->modified)
		{
			$this->modified = $this->created;
		}

		return true;
	}
	private function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
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
		$userId = Factory::getApplication()->getIdentity()->id;

        $db     = $this->getDbo();

		if(is_array($this->images)){
			$this->images = json_encode($this->images);
		}

		// Set created date if not set.
		if (!(int) $this->created)
		{
			$this->created = $date;
		}

		if ($this->id)
		{
			$this->modified    = $date;
		}
		else
		{

			if (!(int) $this->modified)
			{
				$this->modified = $date;
			}

		}

        // Verify that the alias is unique
		$table = Table::getInstance('RoomTable', __NAMESPACE__ . '\\', array('dbo' => $db));
		if ($table->load(array('alias' => $this->alias)) && ($table->id != $this->id || $this->id == 0))

		{
			$this->setError(Text::_('COM_DNBOOKING_ERROR_UNIQUE_ALIAS'));

			return false;
		}
        return parent::store($updateNulls);
	}

	/**
	 * Generate a valid alias from title / date.
	 * Remains public to be able to check for duplicated alias before saving
	 *
	 * @return  string
	 */
	public function generateAlias()
	{
		if (empty($this->alias))
		{
			$this->alias = $this->title;
		}

		$this->alias = ApplicationHelper::stringURLSafe($this->alias, $this->language);

		if (trim(str_replace('-', '', $this->alias)) == '')
		{
			$this->alias = Factory::getDate()->format('Y-m-d-H-i-s');
		}

		return $this->alias;
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



}
