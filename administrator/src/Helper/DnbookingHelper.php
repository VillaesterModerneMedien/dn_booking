<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Administrator\Helper;

\defined('_JEXEC') or die;

use DnbookingNamespace\Component\Dnbooking\Administrator\Extension\DnbookingMailTemplate;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Mail\Exception\MailDisabledException;
use Joomla\CMS\Mail\Mail;
use Joomla\CMS\Mail\MailerFactoryInterface;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;
use JText;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use PHPMailer\PHPMailer\Exception as phpMailerException;

require_once JPATH_ADMINISTRATOR . '/components/com_dnbooking/vendor/autoload.php';


/**
 * Dnbooking component helper.
 *
 * @since  1.0.0
 */
class DnbookingHelper
{

    /**
     * Calculates the total price for a booking.
     *
     * This function takes the booking information, room details, extras, and a flag indicating whether it's a holiday or weekend,
     * and calculates the total cost of the booking.
     *
     * @param   array  $infos               Information about the booking. This can be an array or a JSON string. It should have 'visitorsPackage' and 'visitors' properties.
     * @param   array  $roomParams          An array containing the room details. It should have 'priceregular' and 'pricecustom' properties.
     * @param   float  $extrasTotal         Total extras cost
     * @param   int    $isHolidayOrWeekend  A flag indicating whether the booking is for a holiday or weekend.
     *
     * @return float The total cost of the booking.
     */
    public static function calcPrice($infos, $roomParams, $extrasTotal, $isHolidayOrWeekend)
    {
        if (!is_array($infos))
        {
            $infos = json_decode($infos, true);
        }

        $visitorsPackage = 0;
        $visitorsAdmission = 0;

        if($infos != [])
        {
            $visitorsPackage   = (int) $infos['visitorsPackage'];
            $visitorsAdmission = (int) $infos['visitors'];
        }

        $totalCosts = $extrasTotal;

        $params = ComponentHelper::getParams('com_dnbooking');

        if (!$isHolidayOrWeekend)
        {
            if($roomParams != [])
            {
                $totalCosts       += (float) $roomParams['priceregular'];
            }

            $packagePriceRegular = $params->get('packagepriceregular');
            $totalCosts          += $packagePriceRegular * $visitorsPackage;

            $admissionPriceRegular = $params->get('admissionpriceregular');
            $totalCosts            += $admissionPriceRegular * $visitorsAdmission;
        }
        else
        {
            if($roomParams != [])
            {
                $totalCosts       += (float) $roomParams['pricecustom'];
            }

            $packagePriceCustom = $params->get('packagepricecustom');
            $totalCosts         += $packagePriceCustom * $visitorsPackage;

            $admissionPriceCustom = $params->get('admissionpricecustom');
            $totalCosts           += $admissionPriceCustom * $visitorsAdmission;
        }

        return $totalCosts;
    }

    /**
     * Filters the reservations for today.
     *
     * This function takes an array of reservation objects and returns a new array that only contains the reservations for today.
     * Each reservation object should have a 'reservation_date' property that contains the date of the reservation in 'Y-m-d H:i:s' format.
     *
     * @param   array  $reservations  An array of reservation objects. Each object should have a 'reservation_date' property.
     *
     * @return array An array of reservation objects for today.
     */
    public static function filterReservationsToday($reservations)
    {
        // Get today's date in 'Y-m-d' format
        $app = Factory::getApplication();

        $currentDate = $app->getUserState('com_dnbooking.daydashboards.currentDate', date('Y-m-d'));
        if(is_array($currentDate)){
            $currentDate = $currentDate['currentDate'];
        }

        if($currentDate == '')
        {
            $today = date('Y-m-d');
        }
        else{
            $today = $currentDate;
        }

        $reservationsToday = [];

        foreach ($reservations as $reservation)
        {
            $extractedDate = substr($reservation->reservation_date, 0, 10);
            if ($extractedDate == $today)
            {
                $reservationsToday[] = $reservation;
            }
        }

        return $reservationsToday;
    }

	private static function _newMail($app, $recipient, $orderDataFlattened, $sendMailFormValues){
		/** @var Mail $mail */
		$mail = Factory::getContainer()->get(MailerFactoryInterface::class)->createMailer();
		$mail->setSender($orderDataFlattened['vendor_email'], $orderDataFlattened['vendor_from']);

		$mailer = new DnbookingMailTemplate('com_dnbooking.' . $sendMailFormValues['sendMailType'], 'de-DE', $mail);
		$mailer->addTemplateData($orderDataFlattened);

		if ($recipient === 'admin')
		{
			$mailer->addRecipient($orderDataFlattened['vendor_email'], $orderDataFlattened['vendor_from']);
		}
		else if ($recipient === 'user')
		{
			$mailer->addRecipient($orderDataFlattened['customer_email'], $orderDataFlattened['customer_firstname'] . ' ' . $orderDataFlattened['customer_lastname']);
		}

		try
		{
			$mailSent = $mailer->send();
		}
		catch (MailDisabledException|phpMailerException $e)
		{
			return $app->enqueueMessage($e->getMessage(), 'error');
		}

		$methodName = Text::_('COM_DNBOOKING_SENDMAIL_METHOD_' . strtoupper($mail->Mailer));

		if ($mailSent === true)
		{
			if ($mail->Mailer !== $app->get('mailer'))
			{
				$app->enqueueMessage(Text::sprintf('COM_CONFIG_SENDMAIL_SUCCESS_FALLBACK', $app->get('mailfrom'), $methodName), 'warning');
			}
			else
			{
				$app->enqueueMessage(Text::sprintf('COM_DNBOOKING_SENDMAIL_SUCCESS', $app->get('mailfrom'), $methodName), 'message');
			}

			return true;
		}
		else {
			$app->enqueueMessage(Text::sprintf('COM_DNBOOKING_SENDMAIL_ERROR', $recipient, $methodName), 'error');
			return false;
		}
	}

    public static function sendMail($item, $isFrontend = false)
    {
        $app = Factory::getApplication();
        $input = $app->getInput();
        $sendMailFormValues = $input->get('sendMails', [], 'ARRAY');
	    $params = ComponentHelper::getParams('com_dnbooking');


	    $savedItem = [];

        if ($isFrontend) {
            $reservationsModel = Factory::getApplication()->bootComponent('com_dnbooking')->getMVCFactory()->createModel('Reservations', 'Administrator');
            $items = $reservationsModel->getItems();
            $items = ArrayHelper::pivot($items, 'reservation_token');
            $itemId = $items[$item['reservation_token']]->id;

            $reservationModel = Factory::getApplication()->bootComponent('com_dnbooking')->getMVCFactory()->createModel('Reservation', 'Administrator');
            $savedItem = $reservationModel->getItem($itemId);
            $savedItem = ArrayHelper::fromObject($savedItem);
        }

        if(!is_array($item))
        {
            $item = ArrayHelper::fromObject($item);
        }

        $item = array_merge($savedItem, $item);

        $componentParams = ComponentHelper::getParams('com_dnbooking');

        $orderData                       = ArrayHelper::fromObject($item);
        $orderData['vendor_email']       = $componentParams['vendor_email'];
        $orderData['vendor_from']        = $componentParams['vendor_from'];
        $orderData['vendor_phone']       = $componentParams['vendor_phone'];
        $orderData['vendor_address']     = $componentParams['vendor_address'];
        $orderData['vendor_accountdata'] = $componentParams['vendor_accountdata'];

		$orderData['customText'] = $sendMailFormValues['sendMailCustomText'];
		$orderData['stornoText'] = $sendMailFormValues['sendMailStornoText'];



        $layout                              = new FileLayout('mail.html_ordertable_simple', JPATH_ROOT . '/administrator/components/com_dnbooking/layouts');
        $htmlOrderTableSimple                = $layout->render($orderData);
        $orderData['html_ordertable_simple'] = $htmlOrderTableSimple;

	    $layout                              = new FileLayout('mail.html_ordertable_price', JPATH_ROOT . '/administrator/components/com_dnbooking/layouts');
	    $htmlOrderTablePrice                 = $layout->render($orderData);
	    $orderData['html_ordertable_price']  = $htmlOrderTablePrice;

	    $layout                              = new FileLayout('mail.html_custommail', JPATH_ROOT . '/administrator/components/com_dnbooking/layouts');
	    $htmlCustomMail                      = $layout->render($orderData);
	    $orderData['html_custommail']        = $htmlCustomMail;

	    $layout                              = new FileLayout('mail.html_stornotext', JPATH_ROOT . '/administrator/components/com_dnbooking/layouts');
	    $htmlStornoText                      = $layout->render($orderData);
	    $orderData['html_stornotext']        = $htmlStornoText;

        $layout                              = new FileLayout('mail.html_birthdaychildren', JPATH_ROOT . '/administrator/components/com_dnbooking/layouts');
        $htmlBirthdayChildren                = $layout->render($orderData);
        $orderData['html_birthdaychildren']  = $htmlBirthdayChildren;

        $orderDataFlattened = ArrayHelper::flatten($orderData, '_');
		$orderDataFlattened['reservation_timesingle'] = HTMLHelper::_('date', $orderDataFlattened['reservation_date'], 'H:i');
		$orderDataFlattened['reservation_datesingle'] = HTMLHelper::_('date', $orderDataFlattened['reservation_date'], 'd. F Y');
	    $reservationYear = HTMLHelper::_('date', $orderDataFlattened['reservation_date'], 'Y');
	    $prefix =$params->get('prefix');
	    $id = $prefix . '-' . $reservationYear . '-' .$orderData['id'];

	    $orderDataFlattened['rID'] = $id;

	    if($isFrontend){
		    $sendMailFormValues['sendMailType'] = 'reservation_pending';
	    }

		if($sendMailFormValues['sendMailType'] === "0"){
			$sendMailFormValues['sendMailType'] = match ($orderDataFlattened['published']) {
				0 => 'reservation_cancelled',
				1 => 'reservation_pending',
				2 => 'reservation_closed',
				3 => 'reservation_downpayment',
				4 => 'reservation_downpayment',
				default => 'reservation_statuschange',
			};
		}

		$adminmails = match ($sendMailFormValues['sendMailType']) {
		    'reservation_pending' => true,
			default => false,
	    };
		$usermails = match ($sendMailFormValues['sendMailType']) {
			'reservation_pending',
			'reservation_downpayment',
			'reservation_reservation_cancelled',
			'reservation_cancelled',
			'reservation_closed',
			'customMail',
			'reservation_statuschange' => true,
			default => false,
		};

	    $error = [];

	    if ($adminmails && $componentParams['send_admin_email'])
	    {
			array_push($error, self::_newMail($app, 'admin', $orderDataFlattened, $sendMailFormValues));
	    }

		if ($usermails)
	    {
			array_push($error, self::_newMail($app, 'user', $orderDataFlattened, $sendMailFormValues));
	    }

		if (in_array($error, [false])){
			return false;
		}

		return true;
    }

	public static function checkPrice($date){
		$params = ComponentHelper::getParams('com_dnbooking');
		$date = HTMLHelper::date($date, 'Y-m-d');

		$db = Factory::getContainer()->get('DatabaseDriver');
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->quoteName('#__dnbooking_openinghours'))
			->where($db->quoteName('day') . ' = ' . $db->quote($date));

		// Führe die Abfrage für die Tabelle #__dnbooking_openinghours aus
		$db->setQuery($query);
		$openingHours = $db->loadAssocList();
		$customOpeningHour = is_array($openingHours) ? $openingHours[0] : false ;
		$regularOpeningHourClass = $params->get('regular_opening_hours');
		$weeklyOpeningHour = $params->get('weekly_opening_hours');
		$weekdayNumber = !empty($date) ? DnbookingHelper::getWeekdayNumber($date) : -1;
		$hoursArray = [];
		$regularOpeningHour = [];

		foreach ($weeklyOpeningHour as $key => $value){
			$hoursArray[$key] = $value;
		}

		foreach ($regularOpeningHourClass as $key => $value){
			foreach ($value as $valuename => $valuecontent){
				$regularOpeningHour[$key][$valuename] = $valuecontent;
			}
		}

		$weeklyOpeningHourKeys = array_keys($hoursArray);

		if ($customOpeningHour) {

			$openingCount = count($regularOpeningHour);
			$a = $customOpeningHour['opening_time'];
			$b = $regularOpeningHour['regular_opening_hours' . $a];
			$isHigherPrice = $b['higherPrice'];

		}

		$a =  $hoursArray[$weeklyOpeningHourKeys[$weekdayNumber]];
		if ($a && !$customOpeningHour) {
			$b = json_decode($a, true);
			$c = array_keys($b);
			$openingTime = $b[$c[0]];
			$d = $regularOpeningHour[$openingTime];
			$isHigherPrice = $d['higherPrice'];
		}

		return (int) $isHigherPrice;
	}
    public static function checkHolidays($date)
    {
        $date = date('Y-m-d', strtotime($date));
        $weekdayNumber = !empty($date) ? self::getWeekdayNumber($date) : -1;
        if($weekdayNumber == 5 || $weekdayNumber == 6){
            return 1;
        }

        $params = ComponentHelper::getParams('com_dnbooking');

        foreach ($params->get('holidays') as $holiday) {
            $startDate = date('Y-m-d', strtotime($holiday->startDate));
            $endDate = date('Y-m-d', strtotime($holiday->endDate));

            if ($date >= $startDate && $date <= $endDate) {
                return 1;
            }
        }
        return 0;
    }


    /**
     * Method to get the weekday number of a date.
     *
     * @param $date
     *
     * @return int
     *
     * @since version
     */
    public static function getWeekdayNumber($date)
    {
        $weekdayNumber = (date('w', strtotime($date)) + 6) % 7;
        return $weekdayNumber;
    }

    public static function printDaysheet($items, $model, $orientation = 'P')
    {

        $app = Factory::getApplication();
        $input = $app->input;
        $currentDate = $input->getString('currentDate', date('Y-m-d'));

        if($currentDate == '')
        {
            $today = date('Y-m-d');
        }
        else{
            $today = $currentDate;
        }

        $date = date('Y-m-d'); // Verwende das heutige Datum

        $footerHTMLLayout = new FileLayout('daydashboards.pdfs.daysheet_footer', JPATH_ADMINISTRATOR . '/components/com_dnbooking/layouts');
        $footerHTML = $footerHTMLLayout->render();

        $stylesheet = file_get_contents(JPATH_SITE . '/media/com_dnbooking/css/printpdf.css');
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        try {
            ob_clean();

            $config = array(
                'format' => 'A3',
                'mode' => 'utf-8',
                'orientation' => $orientation,
                'margin_top' => 0,
                'margin_bottom' => 0,
                'setAutoTopMargin' => 'pad',
                'fontDir' => array_merge($fontDirs, [
                    JPATH_SITE . '/media/com_dnbooking/fonts',
                ]),
                'fontdata' => $fontData + [
                        'chewy' => [
                            'R' => 'chewy.ttf',
                        ]
                    ],
            );

			if($model ==='reservation'){
				$config['format'] = 'A4';
				$config['orientation'] = 'P';
			}

            $mpdf = new Mpdf($config);
            $mpdf->WriteHTML($stylesheet,HTMLParserMode::HEADER_CSS);

            $itemsCount = count($items);
            if($model === 'daydashboards'){
				$htmlLayout = new FileLayout('daydashboards.pdfs.daysheet_grid', JPATH_ADMINISTRATOR . '/components/com_dnbooking/layouts');
				$html = $htmlLayout->render($items);
	            $mpdf->WriteHTML($html);

            }
            else{
                $htmlLayout = new FileLayout('daydashboards.pdfs.daysheet', JPATH_ADMINISTRATOR . '/components/com_dnbooking/layouts');
	            for ($x = 0; $x < $itemsCount; $x++)
	            {
		            $html = $htmlLayout->render($items[$x]);
		            $mpdf->WriteHTML($html);
		            $mpdf->SetHTMLFooter($footerHTML);
		            if ($x < ($itemsCount -1))
		            {
			            $mpdf->AddPage();
		            }
	            }
            }

            $mpdf->Output('daysheet-' . $date . '.pdf', 'D');
        } catch (MpdfException $e) {
            echo $e->getMessage();
        }
    }
	public static function printWeeksheet($items)
	{

		$app = Factory::getApplication();
		$input = $app->input;

		$date = date('Y-m-d'); // Verwende das heutige Datum

		$stylesheet = file_get_contents(JPATH_SITE . '/media/com_dnbooking/css/printpdf.css');
		$defaultConfig = (new ConfigVariables())->getDefaults();
		$fontDirs = $defaultConfig['fontDir'];

		$defaultFontConfig = (new FontVariables())->getDefaults();
		$fontData = $defaultFontConfig['fontdata'];

		try {
			ob_clean();

			$config = array(
				'format' => 'A4',
				'mode' => 'utf-8',
				'orientation' => 'L',
				'margin_top' => 0,
				'margin_bottom' => 0,
				'setAutoTopMargin' => 'pad',
				'fontDir' => array_merge($fontDirs, [
					JPATH_SITE . '/media/com_dnbooking/fonts',
				]),
				'fontdata' => $fontData + [
						'chewy' => [
							'R' => 'chewy.ttf',
						]
					],
			);

			$mpdf = new Mpdf($config);
			$mpdf->WriteHTML($stylesheet,HTMLParserMode::HEADER_CSS);

			$htmlLayout = new FileLayout('weekdashboard.pdfs.weeksheet', JPATH_ADMINISTRATOR . '/components/com_dnbooking/layouts');
			//$html = $htmlLayout->render($items);
			$html = $htmlLayout->render(['items' => $items]);

			$mpdf->WriteHTML($html);

			$mpdf->Output('weeksheet-' . $date . '.pdf', 'D');
		} catch (MpdfException $e) {
			echo $e->getMessage();
		}
	}
	public static function filterReservationsWeek($reservations)
	{
		$app = Factory::getApplication();
		$selectedWeek = $app->getUserState('com_dnbooking.weekdashboard.filter.calendarWeek', date('W'));
		$selectedYear = $app->getUserState('com_dnbooking.weekdashboard.filter.year', date('Y'));

		$dto = new \DateTime();
		$dto->setISODate($selectedYear, $selectedWeek);
		$weekStart = $dto->format('Y-m-d');
		$dto->modify('+6 days');
		$weekEnd = $dto->format('Y-m-d');

		$reservationsWeek = [];

		foreach ($reservations as $reservation) {
			$reservationDate = substr($reservation->reservation_date, 0, 10);
			if ($reservationDate >= $weekStart && $reservationDate <= $weekEnd) {
				$reservationsWeek[] = $reservation;
			}
		}

		return $reservationsWeek;
	}

}
