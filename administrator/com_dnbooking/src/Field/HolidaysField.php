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
use Joomla\CMS\Layout\FileLayout;

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
	 * Get the renderer
	 *
	 * @param   string  $layoutId  Id to load
	 *
	 * @return  FileLayout
	 *
	 * @since   3.5
	 */
	protected function getRenderer($layoutId = 'default')
	{
		$renderer = new FileLayout($layoutId);

		$renderer->setDebug($this->isDebugEnabled());

		$layoutPaths = $this->getLayoutPaths();
		array_unshift($layoutPaths, JPATH_ROOT . '/administrator/components/com_dnbooking/layouts');

		if ($layoutPaths) {
			$renderer->setIncludePaths($layoutPaths);
		}

		return $renderer;
	}

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

	// Funktion zum Sortieren des Arrays nach dem Startdatum
	public function compareStartDate($a, $b) {
		return strtotime($a['startDate']) - strtotime($b['startDate']);
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
	    (int) $year = date('Y');
		$region = $params->get('region');
	    $region = strtoupper($region);

		$holidays = [];

		if($params->get('selectHolidaysType')){
			if(in_array('publicHolidays', $params->get('selectHolidaysType'))){
				$publicHolidayList = json_decode($this->_getPublicHolidays($region, $year), true);
				foreach ($publicHolidayList as $holiday)
				{
					$holidays[] = [
						'start' => $holiday['startDate'],
						'end' => $holiday['endDate'],
						'name' => $holiday['name'][0]['text'],
					];
				}
			}
			if(in_array('holiday', $params->get('selectHolidaysType'))){
				$holidayList = json_decode($this->_getHoliday($region, $year), true);
				foreach ($holidayList as $holiday)
				{
					$holidays[] = [
						'start' => $holiday['startDate'],
						'end' => $holiday['endDate'],
						'name' => $holiday['name'][0]['text'],
					];
				}
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

	    usort($value, array($this,'compareStartDate'));

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

	protected function _getPublicHolidays($region, $year)
	{
		$curl = curl_init();
		$curlURL = 'https://openholidaysapi.org/PublicHolidays?countryIsoCode=DE&languageIsoCode=DE&validFrom='. $year .'-01-01&validTo='. $year+1 . '-12-31&subdivisionCode='. $region;
		curl_setopt_array($curl, array(

			CURLOPT_URL => $curlURL,
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

	protected function _getHoliday($region, $year)
	{
		$curl = curl_init();
		$curlURL = 'https://openholidaysapi.org/SchoolHolidays?countryIsoCode=DE&languageIsoCode=DE&validFrom=' . $year . '-01-01&validTo=' . $year+1 . '-12-31&subdivisionCode='. $region;

		curl_setopt_array($curl, array(
			CURLOPT_URL => $curlURL,
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
