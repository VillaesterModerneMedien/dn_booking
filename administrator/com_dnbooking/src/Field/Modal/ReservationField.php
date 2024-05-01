<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Administrator\Field\Modal;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\Session\Session;
use Joomla\Database\ParameterType;

/**
 * Supports a modal reservation picker.
 *
 * @since  1.0.0
 */
class ReservationField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 * @since   1.0.0
	 */
	protected $type = 'Modal_Reservation';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0.0
	 */
	protected function getInput()
	{
		$allowNew       = ((string) $this->element['new'] == 'true');
		$allowEdit      = ((string) $this->element['edit'] == 'true');
		$allowClear  = ((string) $this->element['clear'] != 'false');
		$allowSelect = ((string) $this->element['select'] != 'false');
		$allowPropagate = ((string) $this->element['propagate'] == 'true');

		// Load language
		$languages = LanguageHelper::getContentLanguages(array(0, 1), false);
		Factory::getLanguage()->load('com_dnbooking', JPATH_ADMINISTRATOR);

		// The active reservation id field.
		$value = (int) $this->value > 0 ? (int) $this->value : '';

		// Create the modal id.
		$modalId = 'Reservation_' . $this->id;

		// Add the modal field script to the document head.
		/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

		// Add the modal field script to the document head.
		$wa->useScript('field.modal-fields');

		// Script to proxy the select modal function to the modal-fields.js file.
		if ($allowSelect)
        {
			static $scriptSelect = null;

			if (is_null($scriptSelect))
            {
				$scriptSelect = array();
			}

			if (!isset($scriptSelect[$this->id]))
            {
				$wa->addInlineScript("
				window.jSelectReservation_" . $this->id . " = function (id, title, object) {
					window.processModalSelect('Reservation', '" . $this->id . "', id, title, '', object);
				}",
					[],
					['type' => 'reservation']
				);

				Text::script('JGLOBAL_ASSOCIATIONS_PROPAGATE_FAILED');

				$scriptSelect[$this->id] = true;
			}
		}

        // Setup variables for display.
        $linkReservations = 'index.php?option=com_dnbooking&amp;view=reservations&amp;layout=modal&amp;tmpl=component&amp;' . Session::getFormToken() . '=1';
        $linkReservation  = 'index.php?option=com_dnbooking&amp;view=reservation&amp;layout=modal&amp;tmpl=component&amp;' . Session::getFormToken() . '=1';
		$modalTitle   = Text::_('COM_DNBOOKING_SELECT_A_RESERVATION');

		if (isset($this->element['language']))
        {
			$linkReservations .= '&amp;forcedLanguage=' . $this->element['language'];
			$linkReservation   .= '&amp;forcedLanguage=' . $this->element['language'];
			$modalTitle .= ' &#8212; ' . $this->element['label'];
		}

		$urlSelect = $linkReservations . '&amp;function=jSelectReservation_' . $this->id;
		$urlEdit   = $linkReservation . '&amp;task=reservation.details&amp;id=\' + document.getElementById("' . $this->id . '_id").value + \'';
		$urlNew    = $linkReservation . '&amp;task=reservation.details';

		if ($value)
        {
			$db    = Factory::getContainer()->get('DatabaseDriver');
			$query = $db->getQuery(true)
				->select($db->quoteName('title'))
				->from($db->quoteName('#__dnbooking_reservations'))
				->where($db->quoteName('id') . ' = :id')
				->bind(':id', $value, ParameterType::INTEGER);
			$db->setQuery($query);

			try
            {
				$title = $db->loadResult();
			}
            catch (\RuntimeException $e)
            {
				Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			}
		}

		$title = empty($title) ? Text::_('COM_DNBOOKING_SELECT_A_RESERVATION') : htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The current reservation display field.
		$html  = '';

		if ($allowSelect || $allowNew || $allowEdit || $allowClear)
        {
			$html .= '<span class="input-group">';
		}

		$html .= '<input class="form-control" id="' . $this->id . '_name" type="text" value="' . $title . '" readonly size="35">';

		// Select reservation button
		if ($allowSelect)
		{
			$html .= '<button'
				. ' class="btn btn-primary' . ($value ? ' hidden' : '') . '"'
				. ' id="' . $this->id . '_select"'
				. ' data-bs-toggle="modal"'
				. ' type="button"'
				. ' data-bs-target="#ModalSelect' . $modalId . '">'
				. '<span class="icon-file" aria-hidden="true"></span> ' . Text::_('JSELECT')
				. '</button>';
		}

		// New reservation button
		if ($allowNew)
		{
			$html .= '<button'
				. ' class="btn btn-secondary' . ($value ? ' hidden' : '') . '"'
				. ' id="' . $this->id . '_new"'
				. ' data-bs-toggle="modal"'
				. ' type="button"'
				. ' data-bs-target="#ModalNew' . $modalId . '">'
				. '<span class="icon-plus" aria-hidden="true"></span> ' . Text::_('JACTION_CREATE')
				. '</button>';
		}

		// Edit reservation button
		if ($allowEdit)
		{
			$html .= '<button'
				. ' class="btn btn-primary' . ($value ? '' : ' hidden') . '"'
				. ' id="' . $this->id . '_edit"'
				. ' data-bs-toggle="modal"'
				. ' type="button"'
				. ' data-bs-target="#ModalEdit' . $modalId . '">'
				. '<span class="icon-pen-square" aria-hidden="true"></span> ' . Text::_('JACTION_EDIT')
				. '</button>';
		}

		// Clear reservation button
		if ($allowClear)
		{
			$html .= '<button'
				. ' class="btn btn-secondary' . ($value ? '' : ' hidden') . '"'
				. ' id="' . $this->id . '_clear"'
				. ' type="button"'
				. ' onclick="window.processModalParent(\'' . $this->id . '\'); return false;">'
				. '<span class="icon-times" aria-hidden="true"></span> ' . Text::_('JCLEAR')
				. '</button>';
		}


		// Propagate reservation button
		if ($allowPropagate && count($languages) > 2)
		{
			// Strip off language tag at the end
			$tagLength = (int) strlen($this->element['language']);
			$callbackFunctionStem = substr("jSelectReservation_" . $this->id, 0, -$tagLength);

			$html .= '<button'
			. ' class="btn btn-primary' . ($value ? '' : ' hidden') . '"'
			. ' type="button"'
			. ' id="' . $this->id . '_propagate"'
			. ' title="' . Text::_('JGLOBAL_ASSOCIATIONS_PROPAGATE_TIP') . '"'
			. ' onclick="Joomla.propagateAssociation(\'' . $this->id . '\', \'' . $callbackFunctionStem . '\');">'
			. '<span class="icon-sync" aria-hidden="true"></span> ' . Text::_('JGLOBAL_ASSOCIATIONS_PROPAGATE_BUTTON')
			. '</button>';
		}

		if ($allowSelect || $allowNew || $allowEdit || $allowClear)
		{
			$html .= '</span>';
		}

		// Select reservation modal
		if ($allowSelect)
		{
			$html .= HTMLHelper::_(
				'bootstrap.renderModal',
				'ModalSelect' . $modalId,
				array(
					'title'       => $modalTitle,
					'url'         => $urlSelect,
					'height'      => '400px',
					'width'       => '800px',
					'bodyHeight'  => 70,
					'modalWidth'  => 80,
					'footer'      => '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'
										. Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</button>',
				)
			);
		}

		// New reservation modal
		if ($allowNew)
		{
			$html .= HTMLHelper::_(
				'bootstrap.renderModal',
				'ModalNew' . $modalId,
				array(
					'title'       => Text::_('COM_DNBOOKING_NEW_RESERVATION'),
					'backdrop'    => 'static',
					'keyboard'    => false,
					'closeButton' => false,
					'url'         => $urlNew,
					'height'      => '400px',
					'width'       => '800px',
					'bodyHeight'  => 70,
					'modalWidth'  => 80,
					'footer'      => '<button type="button" class="btn btn-secondary"'
							. ' onclick="window.processModalEdit(this, \''
							. $this->id . '\', \'add\', \'reservation\', \'cancel\', \'reservation-form\', \'jform_id\', \'jform_name\'); return false;">'
							. Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</button>'
							. '<button type="button" class="btn btn-primary"'
							. ' onclick="window.processModalEdit(this, \''
							. $this->id . '\', \'add\', \'reservation\', \'save\', \'reservation-form\', \'jform_id\', \'jform_name\'); return false;">'
							. Text::_('JSAVE') . '</button>'
							. '<button type="button" class="btn btn-success"'
							. ' onclick="window.processModalEdit(this, \''
							. $this->id . '\', \'add\', \'reservation\', \'apply\', \'reservation-form\', \'jform_id\', \'jform_name\'); return false;">'
							. Text::_('JAPPLY') . '</button>',
				)
			);
		}

		// Edit reservation modal.
		if ($allowEdit)
		{
			$html .= HTMLHelper::_(
				'bootstrap.renderModal',
				'ModalEdit' . $modalId,
				array(
					'title'       => Text::_('COM_DNBOOKING_EDIT_RESERVATION'),
					'backdrop'    => 'static',
					'keyboard'    => false,
					'closeButton' => false,
					'url'         => $urlEdit,
					'height'      => '400px',
					'width'       => '800px',
					'bodyHeight'  => 70,
					'modalWidth'  => 80,
					'footer'      => '<button type="button" class="btn btn-secondary"'
							. ' onclick="window.processModalEdit(this, \'' . $this->id
							. '\', \'edit\', \'reservation\', \'cancel\', \'reservation-form\', \'jform_id\', \'jform_name\'); return false;">'
							. Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</button>'
							. '<button type="button" class="btn btn-primary"'
							. ' onclick="window.processModalEdit(this, \''
							. $this->id . '\', \'edit\', \'reservation\', \'save\', \'reservation-form\', \'jform_id\', \'jform_name\'); return false;">'
							. Text::_('JSAVE') . '</button>'
							. '<button type="button" class="btn btn-success"'
							. ' onclick="window.processModalEdit(this, \''
							. $this->id . '\', \'edit\', \'reservation\', \'apply\', \'reservation-form\', \'jform_id\', \'jform_name\'); return false;">'
							. Text::_('JAPPLY') . '</button>',
				)
			);
		}

		// Note: class='required' for client side validation.
		$class = $this->required ? ' class="required modal-value"' : '';

		$html .= '<input type="hidden" id="' . $this->id . '_id"' . $class . ' data-required="' . (int) $this->required . '" name="' . $this->name
			. '" data-text="' . htmlspecialchars(Text::_('COM_DNBOOKING_SELECT_A_RESERVATION', true), ENT_COMPAT, 'UTF-8') . '" value="' . $value . '">';

		return $html;
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   1.0.0
	 */
	protected function getLabel()
	{
		return str_replace($this->id, $this->id . '_name', parent::getLabel());
	}
}
