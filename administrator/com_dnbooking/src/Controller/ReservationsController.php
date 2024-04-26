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

use DnbookingNamespace\Component\Dnbooking\Administrator\Helper\DnbookingHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Mail\Exception\MailDisabledException;
use Joomla\CMS\Mail\MailerFactoryAwareInterface;
use Joomla\CMS\Mail\MailerFactoryAwareTrait;
use Joomla\CMS\Mail\MailTemplate;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;
use PHPMailer\PHPMailer\Exception as phpMailerException;

/**
 * The Reservations list controller class.
 *
 * @since  1.0.0
 */
class ReservationsController extends AdminController implements MailerFactoryAwareInterface
{

	use MailerFactoryAwareTrait;

	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_DNBOOKING_RESERVATIONS';

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The name of the model.
	 * @param   string  $prefix  The prefix for the PHP class name.
	 * @param   array   $config  Array of configuration parameters.
	 *
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel
	 *
	 * @since   1.0.0
	 */
	public function getModel($name = 'Reservation', $prefix = 'Administrator', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Method to delete a record.
	 *
	 * @return  void
	 */
	public function delete()
	{
		// Check for request forgeries.
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		$ids    = $this->input->get('cid', array(), 'array');

		if (empty($ids))
		{
			JError::raiseWarning(500, Text::_('COM_DNBOOKING_NO_ITEM_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			foreach ($ids as $id)
			{
				$model->delete($id);
			}
		}

		$this->setRedirect('index.php?option=com_dnbooking&view=reservations');
	}



	public function sendMails(){
		// Check for request forgeries.
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		$ids    = $this->input->get('cid', array(), 'array');

		$helper = new DnbookingHelper();

		if (empty($ids))
		{
			JError::raiseWarning(500, Text::_('COM_DNBOOKING_NO_ITEM_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			$items = [];
			foreach ($ids as $id)
			{
				$helper->sendMail($model->getItem($id));
			}
		}

		$this->setRedirect('index.php?option=com_dnbooking&view=reservations');
	}


	/*
	protected function sendMail($item){
		// Set the new values to test with the current settings
		$app      = Factory::getApplication();
		$user     = $app->getIdentity();
		$input    = $app->getInput();
		$sendMailFormValues = $input->get('sendMails', [], 'ARRAY');
		$componentParams = ComponentHelper::getParams('com_dnbooking');

		$orderData = ArrayHelper::fromObject($item);
		$orderData['vendor_email'] = $componentParams['vendor_email'];
		$orderData['vendor_from'] = $componentParams['vendor_from'];
		$orderData['vendor_phone'] = $componentParams['vendor_phone'];
		$orderData['vendor_address'] = $componentParams['vendor_address'];
		$orderData['vendor_accountdata'] = $componentParams['vendor_accountdata'];

		$orderDataFlattened = ArrayHelper::flatten($orderData, '_');

		$mail = $this->getMailerFactory()->createMailer();
		$mail->setSender($orderData['vendor_email'], $orderData['vendor_from']);

		$mailer = new MailTemplate('com_dnbooking.' . $sendMailFormValues['sendMailType'], 'de-DE', $mail);
		$mailer->addTemplateData($orderDataFlattened);

		//$mailer->addRecipient($vendorEmail, $vendorName);
		$mailer->addRecipient($orderData['customer_email'], $orderData['customer_firstname'] . ' ' . $orderData['customer_lastname']);

		try {
			$mailSent = $mailer->send();
		} catch (MailDisabledException | phpMailerException $e) {
			$app->enqueueMessage($e->getMessage(), 'error');

			return false;
		}

		if ($mailSent === true) {
			$methodName = Text::_('COM_DNBOOKING_SENDMAIL_METHOD_' . strtoupper($mail->Mailer));

			// If JMail send the mail using PHP Mail as fallback.
			if ($mail->Mailer !== $app->get('mailer')) {
				$app->enqueueMessage(Text::sprintf('COM_CONFIG_SENDMAIL_SUCCESS_FALLBACK', $app->get('mailfrom'), $methodName), 'warning');
			} else {
				$app->enqueueMessage(Text::sprintf('COM_DNBOOKING_SENDMAIL_SUCCESS', $app->get('mailfrom'), $methodName), 'message');
			}

			return true;
		}
	}

	*/
}
