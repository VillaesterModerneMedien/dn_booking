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
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Versioning\VersionableControllerTrait;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Event\Model;

/**
 * Controller for a single Reservation
 *
 * @since  1.0.0
 */
class ReservationController extends FormController
{
	use VersionableControllerTrait;

	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_DNBOOKING_RESERVATION';


	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   1.6
	 */
	public function save($key = null, $urlVar = null)
	{
		// Check for request forgeries.
		$this->checkToken();

		$model   = $this->getModel();
		$table   = $model->getTable();
		$data    = $this->input->get('jform', [], 'array');
		$checkin = $table->hasField('checked_out');
		$context = "$this->option.edit.$this->context";
		$task    = $this->getTask();

		// Determine the name of the primary key for the data.
		if (empty($key)) {
			$key = $table->getKeyName();
		}

		// To avoid data collisions the urlVar may be different from the primary key.
		if (empty($urlVar)) {
			$urlVar = $key;
		}

		$recordId = $this->input->getInt($urlVar);

		// Populate the row id from the session.
		$data[$key] = $recordId;

		// The save2copy task needs to be handled slightly differently.
		if ($task === 'save2copy') {
			// Check-in the original row.
			if ($checkin && $model->checkin($data[$key]) === false) {
				// Check-in failed. Go back to the item and display a notice.
				$this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()), 'error');

				$this->setRedirect(
					Route::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend($recordId, $urlVar),
						false
					)
				);

				return false;
			}

			// Reset the ID, the multilingual associations and then treat the request as for Apply.
			$data[$key]           = 0;
			$data['associations'] = [];
			$task                 = 'apply';
		}

		// Validate the posted data.
		// Sometimes the form needs some posted data, such as for plugins and modules.
		$form = $model->getForm($data, false);

		if (!$form) {
			$this->app->enqueueMessage($model->getError(), CMSWebApplicationInterface::MSG_ERROR);

			return false;
		}

		// Send an object which can be modified through the plugin event
		$objData = (object) $data;
		$this->getDispatcher()->dispatch(
			'onContentNormaliseRequestData',
			new Model\NormaliseRequestDataEvent('onContentNormaliseRequestData', [
				'context' => $this->option . '.' . $this->context,
				'data'    => $objData,
				'subject' => $form,
			])
		);
		$data = (array) $objData;

		// Test whether the data is valid.
		$validData = $model->validate($form, $data);

		// Check for validation errors.
		if ($validData === false) {
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = \count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof \Exception) {
					$this->app->enqueueMessage($errors[$i]->getMessage(), CMSWebApplicationInterface::MSG_WARNING);
				} else {
					$this->app->enqueueMessage($errors[$i], CMSWebApplicationInterface::MSG_WARNING);
				}
			}

			/**
			 * We need the filtered value of calendar fields because the UTC normalisation is
			 * done in the filter and on output. This would apply the Timezone offset on
			 * reload. We set the calendar values we save to the processed date.
			 */
			$filteredData = $form->filter($data);

			foreach ($form->getFieldset() as $field) {
				if ($field->type === 'Calendar') {
					$fieldName = $field->fieldname;

					if (isset($filteredData[$fieldName])) {
						$data[$fieldName] = $filteredData[$fieldName];
					}
				}
			}

			// Save the data in the session.
			$this->app->setUserState($context . '.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar),
					false
				)
			);

			return false;
		}

		if (!isset($validData['tags'])) {
			$validData['tags'] = [];
		}

		// Attempt to save the data.
		if (!$model->save($validData)) {
			// Save the data in the session.
			$this->app->setUserState($context . '.data', $validData);

			// Redirect back to the edit screen.
			$this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()), 'error');

			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar),
					false
				)
			);

			return false;
		}

		// Save succeeded, so check-in the record.
		if ($checkin && $model->checkin($validData[$key]) === false) {
			// Save the data in the session.
			$this->app->setUserState($context . '.data', $validData);

			// Check-in failed, so go back to the record and display a notice.
			$this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()), 'error');

			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar),
					false
				)
			);

			return false;
		}

		$langKey = $this->text_prefix . ($recordId === 0 && $this->app->isClient('site') ? '_SUBMIT' : '') . '_SAVE_SUCCESS';
		$prefix  = $this->app->getLanguage()->hasKey($langKey) ? $this->text_prefix : 'JLIB_APPLICATION';

		$this->setMessage(Text::_($prefix . ($recordId === 0 && $this->app->isClient('site') ? '_SUBMIT' : '') . '_SAVE_SUCCESS'));

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task) {
			case 'apply':
				// Set the record data in the session.
				$recordId = $model->getState($model->getName() . '.id');
				$this->holdEditId($context, $recordId);
				$this->app->setUserState($context . '.data', null);
				$model->checkout($recordId);

				// Redirect back to the edit screen.
				$this->setRedirect(
					Route::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend($recordId, $urlVar),
						false
					)
				);
				break;

			case 'save2new':
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$this->app->setUserState($context . '.data', null);

				// Redirect back to the edit screen.
				$this->setRedirect(
					Route::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend(null, $urlVar),
						false
					)
				);
				break;

			default:
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$this->app->setUserState($context . '.data', null);

				$url = 'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend();

				// Check if there is a return value
				$return = $this->input->get('return', null, 'base64');

				if (!\is_null($return) && Uri::isInternal(base64_decode($return))) {
					$url = base64_decode($return);
				}

				// Redirect to the list screen.
				$this->setRedirect(Route::_($url, false));
				break;
		}

		// Invoke the postSave method to allow for the child class to access the model.
		$this->postSaveHook($model, $validData);

		return true;
	}

}
