<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Administrator\Field\AdditionalInfos;


\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Form\Field\SubformField;
use Joomla\CMS\Form\Form;

/**
 * Custom Field class to load opening hours from component's configuration
 *
 * @since  1.0.0
 */
class AdditionalInfosField extends SubformField
{

	/**
	 * The name of this Field type.
	 *
	 * @var string
	 *
	 * @since 4.0.0
	 */
	public $type = 'AdditionalInfos';

	/**
	 * Layout to render the form
	 * @var  string
	 */
	protected $layout = 'joomla.form.field.subform.repeatable';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.6
	 */
	protected function getInput()
	{
		// Prepare data for renderer
		$data    = $this->getLayoutData();
		$tmpl    = null;
		$control = $this->name;

		try {
			$tmpl  = $this->loadSubForm();
			$forms = $this->loadSubFormData($tmpl);
		} catch (\Exception $e) {
			return $e->getMessage();
		}

		$data['tmpl']            = $tmpl;
		$data['forms']           = $forms;
		$data['min']             = $this->min;
		$data['max']             = $this->max;
		$data['control']         = $control;
		$data['buttons']         = $this->buttons;
		$data['fieldname']       = $this->fieldname;
		$data['fieldId']         = $this->id;
		$data['groupByFieldset'] = $this->groupByFieldset;

		/**
		 * For each rendering process of a subform element, we want to have a
		 * separate unique subform id present to could distinguish the eventhandlers
		 * regarding adding/moving/removing rows from nested subforms from their parents.
		 */
		static $unique_subform_id  = 0;
		$data['unique_subform_id'] = ('sr-' . ($unique_subform_id++));

		// Prepare renderer
		$renderer = $this->getRenderer($this->layout);

		// Allow to define some Layout options as attribute of the element
		if ($this->element['component']) {
			$renderer->setComponent((string) $this->element['component']);
		}

		if ($this->element['client']) {
			$renderer->setClient((string) $this->element['client']);
		}

		// Render
		$html = $renderer->render($data);

		// Add hidden input on front of the subform inputs, in multiple mode
		// for allow to submit an empty value
		if ($this->multiple) {
			$html = '<input name="' . $this->name . '" type="hidden" value="">' . $html;
		}

		return $html;
	}


	/**
	 * Loads the form instance for the subform.
	 *
	 * @return  Form  The form instance.
	 *
	 * @throws  \InvalidArgumentException if no form provided.
	 * @throws  \RuntimeException if the form could not be loaded.
	 *
	 * @since   3.9.7
	 */
	public function loadSubForm()
	{
		$params = ComponentHelper::getParams('com_dnbooking');

		$formDataJSON = $params->get('additional_info_form');
		$formXML = '<form>';

		foreach ($formDataJSON as $field) {
			$formXML .= '<field name="' . $field->fieldName . '" type="' . $field->fieldType . '" label="' . $field->fieldLabel . '" />';
		}

		$formXML .= '</form>';

		$this->formsource = $formXML;

		$control = $this->name;

		if ($this->multiple) {
			$control .= '[' . $this->fieldname . 'X]';
		}

		// Prepare the form template
		$formname = 'subform.' . str_replace(['jform[', '[', ']'], ['', '.', ''], $this->name);
		$tmpl     = Form::getInstance($formname, $this->formsource, ['control' => $control]);

		return $tmpl;
	}

}
