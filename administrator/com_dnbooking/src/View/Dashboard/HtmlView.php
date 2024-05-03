<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace DnbookingNamespace\Component\Dnbooking\Administrator\View\Dashboard;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Utilities\ArrayHelper;

/**
 *  * View class for a list of rooms.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{

	/**
	 * An array of items
	 *
	 * @var  array
	 */
	protected $items;

	/**
	 * The model state
	 *
	 * @var  \Joomla\CMS\Object\CMSObject
	 */
	protected $state;


	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function display($tpl = null): void
	{
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Displays a toolbar for a specific page.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	private function addToolbar()
	{

		$app = Factory::getApplication();
		$input = $app->input;

		$input = $app->input;
		$date = $app->getUserState('com_dnbooking.daydashboards.currentDate', date('Y-m-d'));

		if(is_array($date))
		{
			$date = $date['currentDate'];
		}

		if($date == '')
		{
			//$today = date('Y-m-d');
			$date = date('d.m.Y');
		}

		$date = date('d.m.Y', strtotime($date));

		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');
		$headline = Text::sprintf('COM_DNBOOKING_HEADLINE_DAYDASHBOARDS', $date);
		ToolbarHelper::title($headline, 'calendar');

		// Füge den "Print Daysheet" Button hinzu
		ToolbarHelper::custom(
			'daydashboards.printDaysheet',
			'print',
			'print',
			'COM_DNBOOKING_PRINT_DAYSHEET',
			false
		);

		$dropdown = $toolbar->dropdownButton('status-group')
			->text('JTOOLBAR_CHANGE_STATUS')
			->toggleSplit(false)
			->icon('icon-ellipsis-h')
			->buttonClass('btn btn-action')
			->listCheck(true);

		$childBar = $dropdown->getChildToolbar();

		$childBar->popupButton('chooseDay', 'COM_DNBOOKING_CHOOSEDAY_LABEL')
			->selector('chooseDayModal')
			->icon('icon-mail')
			->listCheck(true);

	}

}
