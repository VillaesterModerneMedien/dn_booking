<?php

/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Site\Field;

use DnbookingNamespace\Component\Dnbooking\Site\Model\BookingModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\SubformField;


// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * The Field to load the form inside current form
 *
 * @Example with all attributes:
 *  <field name="field-name" type="subform"
 *      formsource="path/to/form.xml" min="1" max="3" multiple="true" buttons="add,remove,move"
 *      layout="joomla.form.field.subform.repeatable-table" groupByFieldset="false" component="com_example" client="site"
 *      label="Field Label" description="Field Description" />
 *
 * @since   3.6
 */
class ExtrasField extends SubformField
{
	/**
	 * The form field type.
	 * @var    string
	 */
	protected $type = 'Extras';

	/**
	 * Layout to render the form
	 * @var  string
	 */
	protected $layout = 'joomla.form.field.subform.extras';

	/**
	 * Method to attach a Form object to the field.
	 *
	 * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed              $value    The form field value to validate.
	 * @param   string             $group    The field name group control value.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.6
	 */
	public function setup(\SimpleXMLElement $element, $value, $group = null)
	{
		$factory = Factory::getApplication()->bootComponent('com_dnbooking')->getMVCFactory();

		/** @var  BookingModel $bookingModel */
		$bookingModel = $factory->createModel('Booking', 'Site');
		$min       = count($bookingModel->getExtras());

		if (!parent::setup($element, $value, $group))
		{
			return false;
		}

		$this->__set('min', $min);

		foreach (['formsource', 'max', 'layout', 'groupByFieldset', 'buttons'] as $attributeName)
		{
			$this->__set($attributeName, $element[$attributeName]);
		}

		if ((string) $element['fieldname'])
		{
			$this->__set('fieldname', $element['fieldname']);
		}

		if ($this->value && \is_string($this->value))
		{
			// Guess here is the JSON string from 'default' attribute
			$this->value = json_decode($this->value, true);
		}

		if (!$this->formsource && $element->form)
		{
			// Set the formsource parameter from the content of the node
			$this->formsource = $element->form->saveXML();
		}

		return true;
	}

}
