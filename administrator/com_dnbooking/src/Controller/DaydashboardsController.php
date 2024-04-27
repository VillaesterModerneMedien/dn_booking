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
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\MVC\Controller\AdminController;
use Mpdf\Mpdf; 

require_once JPATH_ADMINISTRATOR . '/components/com_dnbooking/vendor/autoload.php';


/**
 * The Rooms list controller class.
 *
 * @since  1.0.0
 */
class DaydashboardsController extends AdminController
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_DNBOOKING_DAYDASHBOARDS';

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
	public function getModel($name = 'Daydashboards', $prefix = 'Administrator', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}


	public function printDaysheet()
	{
		$app = Factory::getApplication();
		$date = date('Y-m-d'); // Verwende das heutige Datum

		$model = $this->getModel();
		$items = $model->getItems($date);

		$layout = new FileLayout('daydashboards.pdfs.daysheet', JPATH_ADMINISTRATOR . '/components/com_dnbooking/layouts');
		$html = $layout->render(['items' => $items, 'date' => $date]);

		$mpdf = new Mpdf(['orientation' => 'L']);
		$mpdf->WriteHTML($html);
		$mpdf->Output('daysheet-' . $date . '.pdf', 'D');

		$app->close();
	}


}
