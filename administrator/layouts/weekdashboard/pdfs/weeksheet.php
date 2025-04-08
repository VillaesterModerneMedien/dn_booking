<?php
defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

$items = ArrayHelper::fromObject($displayData['items']);
$items = json_decode(json_encode($items));

$params = ComponentHelper::getParams('com_dnbooking');
$prefix = $params->get('prefix');

// Get selected week and year from user state
$app = Factory::getApplication();
$selectedWeek = $app->getUserState('com_dnbooking.weekdashboard.filter.calendarWeek', date('W'));
$selectedYear = $app->getUserState('com_dnbooking.weekdashboard.filter.year', date('Y'));

// Calculate week dates
$dto = new \DateTime();
$dto->setISODate($selectedYear, $selectedWeek);
$weekStart = $dto->format('d.m.Y');
$dto->modify('+6 days');
$weekEnd = $dto->format('d.m.Y');
?>

<div class="daysheetGrid">
        <div class="gridHeader">
            <h1 class="h1-headline-pdf"><?php echo Text::sprintf('COM_DNBOOKING_HEADLINE_WEEKDASHBOARDS', "$selectedWeek"); ?></h1>
            <span>
                <?= $weekStart . " - " . $weekEnd; ?>
            </span>
        </div>

    <table class="table weeksheetTable">
        <thead>
        <tr>
            <th><?php echo Text::_('COM_DNBOOKING_HEADING_DATE'); ?></th>
            <th><?php echo Text::_('COM_DNBOOKING_HEADING_TIME'); ?></th>
            <th><?php echo Text::_('COM_DNBOOKING_HEADING_ROOM'); ?></th>
            <th><?php echo Text::_('COM_DNBOOKING_HEADING_EXTRAS'); ?></th>
            <th><?php echo Text::_('COM_DNBOOKING_HEADING_MEAL_TIME'); ?></th>
            <th><?php echo Text::_('COM_DNBOOKING_HEADING_CUSTOMER'); ?></th>
            <th><?php echo Text::_('COM_DNBOOKING_HEADING_BOOKING_NUMBER'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($items as $item):
        $date = HTMLHelper::_('date', $item->reservation_date, Text::_('DATE_FORMAT_LC4'));
        $time = HTMLHelper::_('date', $item->reservation_date, 'H:i');
        $reservationYear = HTMLHelper::_('date', $item->reservation_date, 'Y');
        $bookingId = $prefix . '-' . $reservationYear . '-' . $item->id;
        ?>
        <tr>
            <td><?php echo $date; ?></td>
            <td><?php echo $time; ?></td>
            <td><?php echo $item->room_title; ?></td>
            <td>
		        <?php if (!empty($item->extras)): ?>
			        <?php foreach ($item->extras as $extra): ?>
				        <?php echo $extra->amount . 'x ' . $extra->name . '<br>'; ?>
			        <?php endforeach; ?>
		        <?php endif; ?>
            </td>
            <td><?php echo $item->meal_time; ?></td>
            <td><?php echo $item->firstname . ' ' . $item->lastname; ?></td>
            <td><?php echo $bookingId; ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
