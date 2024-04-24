<?php

/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Site\Field;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\RadiobasicField;

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
class RoomsField extends RadiobasicField
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
	 * Method to get the field options.
	 *
	 * @return  object[]  The field option objects.
	 *
	 * @since   3.7.0
	 */
	protected function getOptions()
	{
		$options = parent::getOptions();

		$model = Factory::getApplication()->bootComponent('com_dnbooking')->getMVCFactory()->createModel('Reservation', 'Site');
		$rooms = $model->getOrderFeatures('Rooms');

		$roomOptions = [];

		foreach ($rooms as $room)
		{
			$roomOptions[] = (object) [
				'id'           => $room->id,
				'value'        => $room->id,
				'title'        => $room->title,
				'priceregular' => $room->priceregular,
				'pricecustom'  => $room->pricecustom,
				'images'       => $room->images,
				'personsmin'   => $room->personsmin,
				'personsmax'   => $room->personsmax,
			];
		}

		return array_merge($options, $roomOptions);

		return $options;
	}

}
