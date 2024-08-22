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
use Joomla\CMS\Application\CMSWebApplicationInterface;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Mail\MailerFactoryAwareInterface;
use Joomla\CMS\Mail\MailerFactoryAwareTrait;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;

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
	public function downpayment()
	{
		$this->publish();
	}
	public function downpayment_locale()
	{
		$this->publish();
	}
	/**
	 * Method to publish a list of items
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function publish()
	{
		// Check for request forgeries
		$this->checkToken();

		// Get items to publish from the request.
		$cid   = (array) $this->input->get('cid', [], 'int');
		$data  = ['publish' => 1, 'unpublish' => 0, 'archive' => 2, 'trash' => -2, 'report' => -3, "downpayment_locale" => 3, "downpayment" => 4];
		$task  = $this->getTask();
		$value = ArrayHelper::getValue($data, $task, 0, 'int');

		// Remove zero values resulting from input filter
		$cid = array_filter($cid);

		if (empty($cid)) {
			$this->getLogger()->warning(Text::_($this->text_prefix . '_NO_ITEM_SELECTED'), ['category' => 'jerror']);
		} else {
			// Get the model.
			$model = $this->getModel();

			// Publish the items.
			try {
				$model->publish($cid, $value);
				$errors = $model->getErrors();
				$ntext  = null;

				if ($value === 1) {
					if ($errors) {
						$this->app->enqueueMessage(
							Text::plural($this->text_prefix . '_N_ITEMS_FAILED_PUBLISHING', \count($cid)),
							CMSWebApplicationInterface::MSG_ERROR
						);
					} else {
						$ntext = $this->text_prefix . '_N_ITEMS_PUBLISHED';
					}
				} elseif ($value === 0) {
					$ntext = $this->text_prefix . '_N_ITEMS_UNPUBLISHED';
				} elseif ($value === 2) {
					$ntext = $this->text_prefix . '_N_ITEMS_ARCHIVED';
				} elseif ($value === 3) {
					$ntext = $this->text_prefix . '_N_ITEMS_DOWNPAYMENT_LOCALE';
				} elseif ($value === 4) {
					$ntext = $this->text_prefix . '_N_ITEMS_DOWNPAYMENT';
				} else {
					$ntext = $this->text_prefix . '_N_ITEMS_TRASHED';
				}

				if (\count($cid)) {
					$this->setMessage(Text::plural($ntext, \count($cid)));
				}

				if($value !== -2){
					$this->sendMails();
				}

			} catch (\Exception $e) {
				$this->setMessage($e->getMessage(), 'error');
			}
		}



		$this->setRedirect(
			Route::_(
				'index.php?option=' . $this->option . '&view=' . $this->view_list
				. $this->getRedirectToListAppend(),
				false
			)
		);
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

			foreach ($ids as $id)
			{
				$helper->sendMail($model->getItem($id));
			}
		}

		$this->setRedirect('index.php?option=com_dnbooking&view=reservations');
	}

}
