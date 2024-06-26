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

use DnbookingNamespace\Component\Dnbooking\Administrator\Extension\ReservationSoldTrait;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormFactoryInterface;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Factory\MVCFactoryServiceInterface;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Table\Table;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;

use Joomla\CMS\Versioning\VersionableModelTrait;
use Joomla\Utilities\ArrayHelper;


/**
 * Item Model for a Reservation.
 *
 * @since  1.0.0
 */
class ReservationModel extends AdminModel
{
	use VersionableModelTrait;
	use ReservationSoldTrait;


	/**
	 * The type alias for this content type.
	 *
	 * @var      string
	 * @since    1.0.0
	 */
	public $typeAlias = 'com_dnbooking.reservation';

	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.0.0
	 */
	protected $text_prefix = 'COM_DNBOOKING';

    /**
	 * Name of the form
	 *
	 * @var string
	 * @since  4.0.0
	 */
	protected $formName = 'reservation';


	/**
	 * @var    string  The help screen base URL for the component.
	 * @since  1.0.0
	 */
	// protected $helpURL;

	/**
	 * Constructor.
	 *
	 * @param   array                 $config       An array of configuration options (name, state, dbo, table_path, ignore_request).
	 * @param   MVCFactoryInterface   $factory      The factory.
	 * @param   FormFactoryInterface  $formFactory  The form factory.
	 *
	 * @since   1.0.0
	 * @throws  \Exception
	 */
	public function __construct($config = array(), MVCFactoryInterface $factory = null, FormFactoryInterface $formFactory = null)
	{
		$this->factory = $factory;
		parent::__construct($config, $factory, $formFactory);
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  Table  A Table object
	 *
	 * @since   1.0.0
	 * @throws  \Exception
	 */
	public function getTable($type = 'Reservation', $prefix = 'Administrator', $config = array())
	{
		return parent::getTable($type, $prefix, $config);
	}

	/**
	 * Method to get the row form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  \JForm|boolean  A \JForm object on success, false on failure
	 *
	 * @since   1.0.0
	 */
	public function getForm($data = [], $loadData = true)
	{
		// Get the form.
        $form = $this->loadForm(
            'com_dnbooking.' . $this->formName,
            $this->formName,
            array(
                'control' => 'jform',
                'load_data' => $loadData
            )
        );

        if (empty($form))
        {
            return false;
        }

        // Modify the form based on access controls.
		if (!$this->canEditState((object) $data))
        {
            $form->setFieldAttribute('published', 'disabled', 'true');

            // Disable fields while saving.
			// The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('published', 'filter', 'unset');
        }

        return $form;
	}

	/**
	 * Stock method to auto-populate the model state.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState()
	{
		$table = $this->getTable();
		$key   = $table->getKeyName();

		// Get the pk of the record from the request.
		$pk = Factory::getApplication()->getInput()->getInt($key);
		$this->setState($this->getName() . '.id', $pk);

		// Load the parameters.
		$value = ComponentHelper::getParams($this->option);
		$this->setState('params', $value);
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  CMSObject|boolean  Object on success, false on failure.
	 *
	 * @since   1.6
	 */
	public function getItem($pk = null)
	{
		$pk    = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
		$table = $this->getTable();

		if ($pk > 0)
		{
			// Attempt to load the row.
			$return = $table->load($pk);

			// Check for a table object error.
			if ($return === false)
			{
				return false;
			}
		}

		$reservation = $table;
		$formData = ArrayHelper::fromObject($table);
		$orderFeatures = $this->getReservationSoldData($formData, $table->holiday);

		if(isset($orderFeatures['room_id'])){
			$reservation->room = ArrayHelper::fromObject($orderFeatures['room_id']);
		}

		if(isset($orderFeatures['customer_id'])){
			$reservation->customer = ArrayHelper::fromObject($orderFeatures['customer_id']);
		}

		if(isset($orderFeatures['extras'])){
			$reservation->extras = $orderFeatures['extras'];
			$reservation->extras_price_total = $orderFeatures['extras_price_total'];
		}

		return $reservation;
	}


	/**
	 * Preprocess the form.
	 *
	 * @param   Form    $form   Form object.
	 * @param   object  $data   Data object.
	 * @param   string  $group  Group name.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function preprocessForm(Form $form, $data, $group = 'content')
	{
        parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.0.0
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState('com_dnbooking.edit.reservation.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}
		$this->preprocessData($this->typeAlias, $data, 'reservation');

		return $data;
	}

    /**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   \Joomla\CMS\Table\Table  $table  The Table object
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function prepareTable($table)
	{
		$date = Factory::getDate()->toSql();

        if (empty($table->id))
		{
			// Set the values
			$table->created = $date;
        }
        else
		{
			// Set the values
			$table->modified = $date;
		}
	}

    /**
     * Method to get a single record.
     *
     * @param   integer  $pk  The id of the primary key.
     *
     * @return \Joomla\CMS\Object\CMSObject|boolean Object on success, false on failure.
     *
     * @since   1.6
     */
    public function getCustomer($pk = \null)
    {
		$form = $this->getForm();

		$customerId = $form->getField('customer_id')->value;
        $table = $this->getTable('Customer');
        $table->load($customerId);

        return $table;
    }

	public function getModel() {
		return $this;
	}


	public function save($data)
	{
		$table      = $this->getTable();
		$context    = $this->option . '.' . $this->name;
		$key        = $table->getKeyName();
		$pk         = (isset($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');
		$isNew      = true;

		try {
			// Load the row if saving an existing record.
			if ($pk > 0) {
				$table->load($pk);
				$isNew = false;
			}

			// Bind the data.
			if (!$table->bind($data)) {
				$this->setError($table->getError());
				return false;
			}

			// Überprüfung des Besuchers und des Raums für Reservierungen
			if ($context === 'com_dnbooking.reservation') {
				$customerId = $data['customer_id'] ?? 0;
				$roomId = $data['room_id'] ?? 0;

				if (empty($customerId)) {
					$this->setError(Text::_('COM_DNBOOKING_ERROR_INVALID_CUSTOMER'));
					return false;
				}

				if (empty($roomId)) {
					$this->setError(Text::_('COM_DNBOOKING_ERROR_INVALID_ROOM'));
					return false;
				}
			}

			// Store the data.
			if (!$table->store()) {
				$this->setError($table->getError());
				return false;
			}

			// Set the new item ID on the model state if necessary
			if (isset($table->$key)) {
				$this->setState($this->getName() . '.id', $table->$key);
			}

			$this->setState($this->getName() . '.new', $isNew);

			return true;
		} catch (\Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}
	}

}
