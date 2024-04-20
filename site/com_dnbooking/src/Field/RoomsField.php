<?php

/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Site\Field;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Form Field class for the Joomla Platform.
 * Displays options as a list of checkboxes.
 * Multiselect may be forced to be true.
 *
 * @see    RoomsField
 * @since  1.7.0
 */
class RoomsField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	protected $type = 'Rooms';

	/**
	 * Name of the layout being used to render the field
	 *
	 * @var    string
	 * @since  4.0.0
	 */
	protected $layout = 'joomla.form.field.rooms';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.7.0
	 */
	protected function getInput()
	{
		$data = $this->getLayoutData();

		$data['options'] = (array) $this->getOptions();

		return $this->getRenderer($this->layout)->render($data);
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  object[]  The field option objects.
	 *
	 * @since   3.7.0
	 */
	protected function getOptions()
	{
		$options = parent::getOptions();

		$factory = Factory::getApplication()->bootComponent('com_dnbooking')->getMVCFactory();
		$roomModel = $factory->createModel('Rooms', 'Administrator');

		$rooms = $roomModel->getItems();
		$roomOptions= [];

		foreach ($rooms as $room) {
			$roomOptions[] = (object) ['value' => $room->id, 'text' => $room->title];
		}

		return array_merge($options, $roomOptions);

		return $options;
	}

	/**
	 * Method to add an option to the list field.
	 *
	 * @param   string    $text        Text/Language variable of the option.
	 * @param   string[]  $attributes  Array of attributes ('name' => 'value') format
	 *
	 * @return  ListField  For chaining.
	 *
	 * @since   3.7.0
	 */
	public function addOption($text, $attributes = [])
	{
		if ($text && $this->element instanceof \SimpleXMLElement) {
			$child = $this->element->addChild('option', $text);

			foreach ($attributes as $name => $value) {
				$child->addAttribute($name, $value);
			}
		}

		return $this;
	}

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to get the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   3.7.0
	 */
	public function __get($name)
	{
		if ($name === 'options') {
			return $this->getOptions();
		}

		return parent::__get($name);
	}
}
