<?php

/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Administrator\Field;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Form\Field\SubformField;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormField;
use Joomla\Filesystem\Path;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * The Field to load the form inside current form
 *
 * @Example with all attributes:
 *  <field name="field-name" type="holidays"
 *      formsource="path/to/form.xml" min="1" max="3" multiple="true" buttons="add,remove,move"
 *      layout="joomla.form.field.holidays.repeatable-table" groupByFieldset="false" component="com_example" client="site"
 *      label="Field Label" description="Field Description" />
 *
 * @since  3.6
 */
class HolidaysField extends SubformField
{
    /**
     * The form field type.
     * @var    string
     */
    protected $type = 'Holidays';

    /**
     * Form source
     * @var string
     */
    protected $formsource;

    /**
     * Minimum items in repeat mode
     * @var integer
     */
    protected $min = 0;

    /**
     * Maximum items in repeat mode
     * @var integer
     */
    protected $max = 1000;

    /**
     * Whether group holidays fields by it`s fieldset
     * @var boolean
     */
    protected $groupByFieldset = false;

    /**
     * Which buttons to show in multiple mode
     * @var boolean[] $buttons
     */
    protected $buttons = ['add' => true, 'remove' => true, 'move' => true];

    /**
     * Loads the form instance for the holidays.
     *
     * @return  Form  The form instance.
     *
     * @throws  \InvalidArgumentException if no form provided.
     * @throws  \RuntimeException if the form could not be loaded.
     *
     * @since   3.9.7
     */
    public function loadSubform()
    {
        $control = $this->name;

        if ($this->multiple) {
            $control .= '[' . $this->fieldname . 'X]';
        }

        // Prepare the form template
        $formname = 'subform.' . str_replace(['jform[', '[', ']'], ['', '.', ''], $this->name);
        $tmpl     = Form::getInstance($formname, $this->formsource, ['control' => $control]);

        return $tmpl;
    }

    /**
     * Binds given data to the holidays and its elements.
     *
     * @param   Form  $subform  Form instance of the holidays.
     *
     * @return  Form[]  Array of Form instances for the rows.
     *
     * @since   3.9.7
     */
    protected function loadSubformData(Form $subform)
    {
       // $value = $this->value ? (array) $this->value : [];
	    $params = ComponentHelper::getParams('com_dnbooking');
		$year = date('Y');
		$region = $params->get('region');
	    $years = $year . ',' . ($year + 1);
	    $yearsHolidays = explode(',', $years);

		$holidays = [];

		$holidaysCelebration = json_decode($this->_getHolidaysCelebration(strtolower($region), $years), true);
		foreach ($holidaysCelebration['feiertage'] as $holiday)
		{
			$holidays[] = [
				'start' => $holiday['date'],
				'end' => $holiday['date'],
				'name' => $holiday['fname'],
			];
		}

		foreach ($yearsHolidays as $year)
		{
			$region = strtoupper($region);

			$yearDataHoliday = json_decode($this->_getHolidays($region, $year), true);
			foreach ($yearDataHoliday as $holiday)
			{
				$holidays[] = [
					'start' => $holiday['start'],
					'end' => $holiday['end'],
					'name' => $holiday['name'],
				];
			}

		}

		$value = [];
		foreach ($holidays as $holiday)
		{
			$value[] = [
				'startDate' => $holiday['start'],
				'endDate' => $holiday['end'],
				'title' => $holiday['name'],
			];
		}

        // Simple form, just bind the data and return one row.
        if (!$this->multiple) {
	        $subform->bind($value);

            return [$subform];
        }

        // Multiple rows possible: Construct array and bind values to their respective forms.
        $forms = [];
        $value = array_values($value);

        // Show as many rows as we have values, but at least min and at most max.
        $c = max($this->min, min(\count($value), $this->max));

        for ($i = 0; $i < $c; $i++) {
            $control  = $this->name . '[' . $this->fieldname . $i . ']';
            $itemForm = Form::getInstance($subform->getName() . $i, $this->formsource, ['control' => $control]);

            if (!empty($value[$i])) {
                $itemForm->bind($value[$i]);
            }

            $forms[] = $itemForm;
        }

        return $forms;
    }

	protected function _getHolidays($region, $year)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://ferien-api.de/api/v1/holidays/' . $region . '/' . $year,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

	protected function _getHolidaysCelebration($region, $years)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://get.api-feiertage.de?years=' . $years . '&states=' . $region ,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

}
