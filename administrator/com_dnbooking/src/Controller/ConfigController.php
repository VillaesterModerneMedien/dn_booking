<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace DnbookingNamespace\Component\Dnbooking\Administrator\Controller;

\defined('_JEXEC') or die;


use Joomla\CMS\Application\CMSWebApplicationInterface;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Versioning\VersionableControllerTrait;
use Joomla\CMS\Event\Model;


/**
 * Controller for a single Customer
 *
 * @since  1.0.0
 */
class ConfigController extends FormController
{
	use VersionableControllerTrait;

	function generateHolidayTableHTML() {

		$app = Factory::getApplication();
		// $value = $this->value ? (array) $this->value : [];
		$params = ComponentHelper::getParams('com_dnbooking');
		(int) $year = date('Y');
		$region = $app->input->get('region');
		$region = strtoupper($region);
		$checkLiveStatus = $app->input->get('checkLiveStatus', '', 'STRING');
		$checkedOptions = explode(',', $checkLiveStatus);
		$holidays = [];


		//Ferien = holidays
		//Feiertage = publicHolidays

		if($params->get('selectHolidaysType') || $checkLiveStatus)
		{
			$publicHolidaysCheck =  $checkedOptions ? in_array('publicHolidays', $checkedOptions) : false;
			$publicHolidaysDB = $params->get('selectHolidaysType') ? in_array('publicHolidays', $params->get('selectHolidaysType')) : false;
			if (($publicHolidaysDB && $publicHolidaysCheck) || (!$publicHolidaysDB && $publicHolidaysCheck))
			{
				$publicHolidayList = json_decode($this->_getPublicHolidays($region, $year), true);
				foreach ($publicHolidayList as $holiday)
				{
					$holidays[] = [
						'start' => $holiday['startDate'],
						'end'   => $holiday['endDate'],
						'name'  => $holiday['name'][0]['text'],
					];
				}
			}
			$holidayCheck = $checkedOptions ? in_array('holiday', $checkedOptions) : false;
			$holidayDB = $params->get('selectHolidaysType') ? in_array('holiday', $params->get('selectHolidaysType')) : false;
			if (($holidayDB && $holidayCheck) || (!$holidayDB && $holidayCheck))
			{
				$holidayList = json_decode($this->_getHoliday($region, $year), true);
				foreach ($holidayList as $holiday)
				{
					$holidays[] = [
						'start' => $holiday['startDate'],
						'end'   => $holiday['endDate'],
						'name'  => $holiday['name'][0]['text'],
					];
				}
			}
		}
		
		$holidaysComplete = [];
		foreach ($holidays as $holiday)
		{
			$holidaysComplete[] = [
				'startDate' => $holiday['start'],
				'endDate' => $holiday['end'],
				'title' => $holiday['name'],
			];
		}

		usort($holidaysComplete, array($this,'_compareStartDate'));

		$html = '<tbody class="subform-repeatable-container">';

		foreach ($holidaysComplete as $index => $holiday) {
			$group = 'holidays' . $index;
			$html .= '<tr class="subform-repeatable-group" data-base-name="holidays" data-group="' . $group . '">';

			$holiday['startDate'] = date('d.m.Y', strtotime($holiday['startDate']));
			$holiday['endDate'] = date('d.m.Y', strtotime($holiday['endDate']));

			// Startdatum
			$html .= '<td data-column="Startdatum&nbsp;*">';
			$html .= '<div class="control-group"><div class="visually-hidden">';
			$html .= '<label id="jform_holidays__' . $group . '__startDate-lbl" for="jform_holidays__' . $group . '__startDate" class="required">Startdatum<span class="star" aria-hidden="true">&nbsp;*</span></label>';
			$html .= '</div><div class="controls"><div class="field-calendar">';
			$html .= '<input type="text" id="jform_holidays__' . $group . '__startDate" name="jform[holidays][' . $group . '][startDate]" value="' . $holiday['startDate'] . '" class="form-control required" readonly="readonly" required="" data-alt-value="' . $holiday['startDate'] . '" autocomplete="off">';
			$html .= '<button type="button" class="hidden btn btn-primary" id="jform_holidays__' . $group . '__startDate_btn" title="Kalender öffnen" data-inputfield="jform_holidays__' . $group . '__startDate" data-button="jform_holidays__' . $group . '__startDate_btn" data-date-format="%d.%m.%Y" data-firstday="1" data-weekend="0,6" data-today-btn="1" data-week-numbers="1" data-show-time="0" data-show-others="1" data-time24="24" data-only-months-nav="0"><span class="icon-calendar" aria-hidden="true"></span><span class="visually-hidden">Kalender öffnen</span></button>';
			$html .= '</div></div></div></td>';

			// Enddatum
			$html .= '<td data-column="Enddatum&nbsp;*">';
			$html .= '<div class="control-group"><div class="visually-hidden">';
			$html .= '<label id="jform_holidays__' . $group . '__endDate-lbl" for="jform_holidays__' . $group . '__endDate" class="required">Enddatum<span class="star" aria-hidden="true">&nbsp;*</span></label>';
			$html .= '</div><div class="controls"><div class="field-calendar">';
			$html .= '<input type="text" id="jform_holidays__' . $group . '__endDate" name="jform[holidays][' . $group . '][endDate]" value="' . $holiday['endDate'] . '" class="form-control required" readonly="readonly" required="" data-alt-value="' . $holiday['endDate'] . '" autocomplete="off">';
			$html .= '<button type="button" class="hidden btn btn-primary" id="jform_holidays__' . $group . '__endDate_btn" title="Kalender öffnen" data-inputfield="jform_holidays__' . $group . '__endDate" data-button="jform_holidays__' . $group . '__endDate_btn" data-date-format="%d.%m.%Y" data-firstday="1" data-weekend="0,6" data-today-btn="1" data-week-numbers="1" data-show-time="0" data-show-others="1" data-time24="24" data-only-months-nav="0"><span class="icon-calendar" aria-hidden="true"></span><span class="visually-hidden">Kalender öffnen</span></button>';
			$html .= '</div></div></div></td>';

			// Titel
			$html .= '<td data-column="Titel">';
			$html .= '<div class="control-group"><div class="visually-hidden">';
			$html .= '<label id="jform_holidays__' . $group . '__title-lbl" for="jform_holidays__' . $group . '__title">Titel</label>';
			$html .= '</div><div class="controls">';
			$html .= '<input type="text" name="jform[holidays][' . $group . '][title]" id="jform_holidays__' . $group . '__title" value="' . $holiday['title'] . '" class="form-control" readonly>';
			$html .= '</div></div></td>';

			$html .= '</tr>';
		}

		$html .= '</tbody>';
		echo $html;

		$app->close();
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


	// Funktion zum Sortieren des Arrays nach dem Startdatum
	protected function _compareStartDate($a, $b) {
		return strtotime($a['startDate']) - strtotime($b['startDate']);
	}


}
