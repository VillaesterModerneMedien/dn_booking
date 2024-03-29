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

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Input\Input;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Router\Route;

/**
 * The Openinghours list controller class.
 *
 * @since  1.0.0
 */
class OpeninghoursController extends AdminController
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_DNBOOKING_OPENINGHOURS';

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
	public function getModel($name = 'Openinghours', $prefix = 'Administrator', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	public function edit()
	{

        $app = Factory::getApplication();
        $input = $app->input;

		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

        $dayID =  $input->get('dayID', 0, 'INT');

		if (empty($dayID))
		{
			JError::raiseWarning(500, Text::_('COM_DNBOOKING_NO_DAY_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();
            $model->updateDay($input->post->getArray());
		}

		$this->setRedirect('index.php?option=com_dnbooking&view=openinghours');
	}


	public function add()
	{

        $app = Factory::getApplication();
        $input = $app->input;

		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

        // Get the model.
        $model = $this->getModel();
        $model->addDay($input->post->getArray());


		$this->setRedirect('index.php?option=com_dnbooking&view=openinghours');
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

		$this->setRedirect('index.php?option=com_dnbooking&view=openinghours');
	}
}
