<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$params = ComponentHelper::getParams('com_dnbooking');

$wa = $this->document->getWebAssetManager();
$wa->useScript('com_dnbooking.calendar');
$wa->useStyle('com_dnbooking.admin-calendar');


$user      = Factory::getApplication()->getIdentity();
$userId    = $user->get('id');

$zeiten = [
];

$translations = [
    'monday' => Text::_('COM_DNBOOKING_CALENDAR_MONDAY'),
    'tuesday' => Text::_('COM_DNBOOKING_CALENDAR_TUESDAY'),
    'wednesday' => Text::_('COM_DNBOOKING_CALENDAR_WEDNESDAY'),
    'thursday' => Text::_('COM_DNBOOKING_CALENDAR_THURSDAY'),
    'friday' => Text::_('COM_DNBOOKING_CALENDAR_FRIDAY'),
    'saturday' => Text::_('COM_DNBOOKING_CALENDAR_SATURDAY'),
    'sunday' => Text::_('COM_DNBOOKING_CALENDAR_SUNDAY'),
    'january' => Text::_('COM_DNBOOKING_CALENDAR_JANUARY'),
    'february' => Text::_('COM_DNBOOKING_CALENDAR_FEBRUARY'),
    'march' => Text::_('COM_DNBOOKING_CALENDAR_MARCH'),
    'april' => Text::_('COM_DNBOOKING_CALENDAR_APRIL'),
    'may' => Text::_('COM_DNBOOKING_CALENDAR_MAY'),
    'june' => Text::_('COM_DNBOOKING_CALENDAR_JUNE'),
    'july' => Text::_('COM_DNBOOKING_CALENDAR_JULY'),
    'august' => Text::_('COM_DNBOOKING_CALENDAR_AUGUST'),
    'september' => Text::_('COM_DNBOOKING_CALENDAR_SEPTEMBER'),
    'october' => Text::_('COM_DNBOOKING_CALENDAR_OCTOBER'),
    'november' => Text::_('COM_DNBOOKING_CALENDAR_NOVEMBER'),
    'december' => Text::_('COM_DNBOOKING_CALENDAR_DECEMBER'),
    'add' => Text::_('COM_DNBOOKING_CALENDAR_TASK_ADD'),
    'edit' => Text::_('COM_DNBOOKING_CALENDAR_TASK_EDIT'),
    'delete' => Text::_('COM_DNBOOKING_CALENDAR_TASK_DELETE'),
    'success' => Text::_('COM_DNBOOKING_CALENDAR_TASK_SUCCESS'),
    'failed' => Text::_('COM_DNBOOKING_CALENDAR_TASK_FAILED')
];

foreach ($params['weekly_opening_hours'] as $day => $value) {
    $zeiten[$day] = $value;
}

$settings = [
   'zeiten' => $zeiten,
   'texte'  => $translations
];

Factory::getDocument()->addScriptOptions('com_dnbooking', $settings);
?>
<form action="<?php echo Route::_('index.php?option=com_dnbooking&view=openinghours'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
                <div class="jumbotron">
                    <h1 class="text-center"><a id="left" href="#"><i class="fa fa-chevron-left"> </i></a><span>&nbsp;</span><span id="month"> </span><span>&nbsp;</span><span id="year"> </span><span>&nbsp;</span><a id="right" href="#"><i class="fa fa-chevron-right"> </i></a></h1>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table tableCalendar"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo HTMLHelper::_('form.token'); ?>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
</form>
