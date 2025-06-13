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

use JFactory;
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

	protected $app;

	protected $input;


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

	public function checkMonth(){
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		$app = Factory::getApplication();
		$input = $app->input;
		$month = $input->get('date', '', 'INT');
		$year = $input->get('year', '', 'INT');

		$model = $this->getModel();

		if($month < 10)
		{
			$month = '0'.$month;
		}

		echo json_encode($model->checkMonth($year, $month));
		JFactory::getApplication()->close();
	}

	public function edit()
	{
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));
		$app = Factory::getApplication();
		$input = $app->input;

		$model = $this->getModel();
        $model->updateDay($input->post->getArray());

		$this->setRedirect('index.php?option=com_dnbooking&view=openinghours');
	}

	public function add()
	{
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));
		$app = Factory::getApplication();
		$input = $app->input;

		$model = $this->getModel();
		$model->addDay($input->post->getArray());

		$this->setRedirect('index.php?option=com_dnbooking&view=openinghours');
	}

}
