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
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\SubformField;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;

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
		$app = Factory::getApplication();

		$formXML = '';

		if($this->fieldname == 'additional_info'){
			$formDataJSON = $params->get('additional_info_form');
			$formXML = '<form>';
		}else{
			$formDataJSON = $params->get('additional_info_form2');
			$formXML .= '<form>';
			if ($app->isClient('site')) {
				$formXML .= '<field name="addinfos2_subform" type="subform" multiple="true" min="1" max="10" layout="joomla.form.field.subform.repeatable-children" hiddenLabel="true" >';
			} else if ($app->isClient('administrator')) {
				$formXML .= '<field name="addinfos2_subform" type="subform" multiple="true" min="1" max="10" layout="joomla.form.field.subform.repeatable-table" hiddenLabel="true" >';
			}
			$formXML .= '<form>';
		}

		$counter = 0;
		foreach ($formDataJSON as $field) {

			$counter++;
			$firstClass = '';
			if($counter == 1)
			{
				$firstClass = 'uk-first-column';
			}

			switch($field->fieldType) {
				case 'list':
					$options = explode(',', $field->fieldOptions);
					$formXML .= '<field name="' . $field->fieldName . '" type="' . $field->fieldType . '" label="' . $field->fieldLabel . '"  class="' . $firstClass . '">';
					$formXML .= '<option value="">' . Text::_('COM_DNBOOKING_LABEL_CHOOSE') . '</option>';
					foreach ($options as $option) {
						list($value, $text) = explode(':', trim($option));
						$formXML .= '<option value="' . $value . '">' . $text . '</option>';
					}
					$formXML .= '</field>';
					break;
				case 'calendar':
					$formXML .= '<field name="' . $field->fieldName . '" type="' . $field->fieldType . '" label="' . $field->fieldLabel . '" 
	showtime="false" todaybutton="false" filltable="false" translateformat="true" default="NOW" filterformat="%d.%m.%Y" format="%d.%m.%Y"   class="' . $firstClass . '" />';

					break;
				case 'number':
					$formXML .= '<field name="' . $field->fieldName . '" type="' . $field->fieldType . '" label="' . $field->fieldLabel . '"   class="' . $firstClass . '"  default="0" min="0"/>';
					break;
				default:
					$formXML .= '<field name="' . $field->fieldName . '" type="' . $field->fieldType . '" label="' . $field->fieldLabel . '"   class="' . $firstClass . '" />';
			}

		}

		if($this->fieldname == 'additional_info'){
			$formXML .= '</form>';
		}else{
			$formXML .= '</form>';
			$formXML .= '</field>';
			$formXML .= '</form>';
		}


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
