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
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Mail\Exception\MailDisabledException;
use Joomla\CMS\Mail\Mail;
use Joomla\CMS\Mail\MailerFactoryInterface;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;
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

		$visitorsPackage   = (int) $infos['visitorsPackage'];
		$visitorsAdmission = (int) $infos['visitors'];

		$totalCosts = $extrasTotal;

		$params = ComponentHelper::getParams('com_dnbooking');

		if (!$isHolidayOrWeekend)
		{
			$totalCosts       += (float) $roomParams['priceregular'];

			$packagePriceRegular = $params->get('packagepriceregular');
			$totalCosts          += $packagePriceRegular * $visitorsPackage;

			$admissionPriceRegular = $params->get('admissionpriceregular');
			$totalCosts            += $admissionPriceRegular * $visitorsAdmission;
		}
		else
		{
			$totalCosts      += (float) $roomParams['pricecustom'];

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
		$today = date('Y-m-d');

		// Initialize an empty array to hold the reservations for today
		$reservationsToday = [];

		$test = ArrayHelper::pivot($reservations, 'id');

		// Loop through each reservation object in the array
		foreach ($reservations as $reservation)
		{
			// Extract the date from the 'reservation_date' and compare it with today's date
			if (substr($reservation->reservation_date, 0, 10) == $today)
			{
				// If the reservation date is today, add it to the list
				$reservationsToday[] = $reservation;
			}
		}

		// Return the list of reservations for today
		return $reservationsToday;
	}


	public static function sendMail($item, $isFrontend = false)
	{
		// Get the application instance
		$app = Factory::getApplication();

		// Get the input object
		$input = $app->getInput();

		// Get the values from the sendMails form
		$sendMailFormValues = $input->get('sendMails', [], 'ARRAY');

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

		$item = array_merge($savedItem, $item);

		// Get the component parameters
		$componentParams = ComponentHelper::getParams('com_dnbooking');

		// Convert the order object to an array and add the vendor's information
		$orderData                       = ArrayHelper::fromObject($item);
		$orderData['vendor_email']       = $componentParams['vendor_email'];
		$orderData['vendor_from']        = $componentParams['vendor_from'];
		$orderData['vendor_phone']       = $componentParams['vendor_phone'];
		$orderData['vendor_address']     = $componentParams['vendor_address'];
		$orderData['vendor_accountdata'] = $componentParams['vendor_accountdata'];


		$layout                              = new FileLayout('mail.html_ordertable_simple', JPATH_ROOT . '/administrator/components/com_dnbooking/layouts');
		$htmlOrderTableSimple                = $layout->render($orderData);
		$orderData['html_ordertable_simple'] = $htmlOrderTableSimple;

		// Flatten the order data array
		$orderDataFlattened = ArrayHelper::flatten($orderData, '_');

		/** @var Mail $mail */
		$mail = Factory::getContainer()->get(MailerFactoryInterface::class)->createMailer();

		// Set the sender of the email
		$mail->setSender($orderDataFlattened['vendor_email'], $orderDataFlattened['vendor_from']);

		if($isFrontend)
		{
			$sendMailFormValues['sendMailType'] = 'reservation_pending';
		}

		// Create a new mail template
		$mailer = new DnbookingMailTemplate('com_dnbooking.' . $sendMailFormValues['sendMailType'], 'de-DE', $mail);

		// Add the order data to the mail template
		$mailer->addTemplateData($orderDataFlattened);
		//$mailTemplate = MailTemplate::getTemplate('com_dnbooking.' . $sendMailFormValues['sendMailType'], 'de-DE');


		// Add the customer as the recipient of the email
		$mailer->addRecipient($orderDataFlattened['customer_email'], $orderDataFlattened['customer_firstname'] . ' ' . $orderDataFlattened['customer_lastname']);

		try
		{
			// Try to send the email
			$mailSent = $mailer->send();
		}
		catch (MailDisabledException|phpMailerException $e)
		{
			// If an error occurs while sending the email, enqueue a message and return false
			$app->enqueueMessage($e->getMessage(), 'error');

			return false;
		}

		// If the email was sent successfully, enqueue a success message
		if ($mailSent === true)
		{
			$methodName = Text::_('COM_DNBOOKING_SENDMAIL_METHOD_' . strtoupper($mail->Mailer));

			// If JMail send the mail using PHP Mail as fallback.
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
			// Extract the start and end dates of the holiday
			$startDate = date('Y-m-d', strtotime($holiday->startDate));
			$endDate = date('Y-m-d', strtotime($holiday->endDate));

			// Check if the date is within the start and end dates of the holiday
			if ($date >= $startDate && $date <= $endDate) {
				// If it is, return true
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

	public static function printDaysheet($items, $orientation = 'P')
	{
		$date = date('Y-m-d'); // Verwende das heutige Datum

		//$htmlLayout = new FileLayout('daydashboards.pdfs.daysheet', JPATH_ADMINISTRATOR . '/components/com_dnbooking/layouts');
		//$html = $htmlLayout->render($items);


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
				'format' => 'A4',
				'mode' => 'utf-8',
				'orientation' => $orientation,
				'margin_top' => 5,
				'margin_bottom' => 35,
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
			$htmlLayout = new FileLayout('daydashboards.pdfs.daysheet_item', JPATH_ADMINISTRATOR . '/components/com_dnbooking/layouts');
			$itemsCount = count($items);
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
			$mpdf->Output('daysheet-' . $date . '.pdf', 'D');
		} catch (MpdfException $e) {
			echo $e->getMessage();
		}
	}

}
