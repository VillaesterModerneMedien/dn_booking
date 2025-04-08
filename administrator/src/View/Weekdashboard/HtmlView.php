<?php
namespace DnbookingNamespace\Component\Dnbooking\Administrator\View\Weekdashboard;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('com_dnbooking.weekdashboard');


class HtmlView extends BaseHtmlView
{
	public $items;
	public $pagination;
	public $state;
	protected $context = 'com_dnbooking.weekdashboard';

	public function display($tpl = null)
	{
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');

		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		$toolbar = Toolbar::getInstance('toolbar');

		// Get the selected or current values
		$app          = Factory::getApplication();
		$selectedWeek = $app->getUserStateFromRequest($this->context . '.filter.calendarWeek', 'filter_calendarWeek', date('W'));
		$selectedYear = $app->getUserStateFromRequest($this->context . '.filter.year', 'filter_year', date('Y'));

		// Calculate week dates
		$dto = new \DateTime();
		$dto->setISODate($selectedYear, $selectedWeek);
		$weekStart = $dto->format('d.m.Y');
		$dto->modify('+6 days');
		$weekEnd = $dto->format('d.m.Y');

		ToolbarHelper::title(Text::sprintf('COM_DNBOOKING_HEADLINE_WEEKDASHBOARDS', " $selectedWeek ($weekStart - $weekEnd)"), 'calendar');
		// Print button
		$toolbar->appendButton('Custom', '<button class="btn btn-primary d-flex align-items-center" style="height: 38px;" onclick="Joomla.submitbutton(\'weekdashboard.printWeeksheet\')">
    <span class="icon-print" aria-hidden="true"></span>
    ' . Text::_('COM_DNBOOKING_PRINT_WEEKSHEET') . '
</button>', 'print-button');

// Calendar Week Input
		$toolbar->appendButton('Custom', '<div class="input-group mx-2" style="margin-top:5px; margin-right:0!important; height: 38px;">
    <span class="input-group-text h-100 d-flex align-items-center">KW</span>
    <input type="number" name="filter_calendarWeek" id="filter_calendarWeek" min="1" max="53" value="' . $selectedWeek . '" class="form-control h-100" style="width: 80px;">
</div>', 'week-input');

// Year Input
		$toolbar->appendButton('Custom', '<div class="input-group mx-2" style=" margin-top:5px;height: 38px;">
    <span class="input-group-text h-100 d-flex align-items-center">Jahr</span>
    <input type="number" name="filter_year" id="filter_year" value="' . $selectedYear . '" class="form-control h-100" style="width: 100px;">
</div>', 'year-input');

// Update Button
		$toolbar->appendButton('Custom', '<button onclick="updateWeekDashboard()" class="btn btn-primary d-flex align-items-center" style="height: 38px;">
    <span class="icon-refresh" aria-hidden="true"></span>
    Aktualisieren
</button>', 'update-button');
	}
}
