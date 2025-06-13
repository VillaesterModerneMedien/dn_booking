x<?php
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
            <th><?php echo Text::_('COM_DNBOOKING_HEADING_BIRTHDAYCHILDREN'); ?></th>
            <th><?php echo Text::_('COM_DNBOOKING_HEADING_VISITORS'); ?></th>
            <th><?php echo Text::_('COM_DNBOOKING_HEADING_MEAL_TIME'); ?></th>
            <th><?php echo Text::_('COM_DNBOOKING_HEADING_CUSTOMER'); ?></th>
            <th><?php echo Text::_('COM_DNBOOKING_HEADING_BOOKING_NUMBER'); ?></th>
            <th><?php echo Text::_('COM_DNBOOKING_HEADING_STATE'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($items as $item):
            $date = HTMLHelper::_('date', $item->reservation_date, Text::_('DATE_FORMAT_LC4'));
            $time = HTMLHelper::_('date', $item->reservation_date, 'H:i');
            $reservationYear = HTMLHelper::_('date', $item->reservation_date, 'Y');
            $bookingId = $prefix . '-' . $reservationYear . '-' . $item->id;
            $children = json_decode($item->additional_infos2, true);
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
                <td>
		            <?php if(!empty($children)): ?>
			            <?php foreach ($children['addinfos2_subform'] as $child) : ?>
				            <?php foreach ($child as $key => $value) :
					            if (DateTime::createFromFormat('Y-m-d H:i:s', $value) !== false) {
						            echo date('d.m.Y', strtotime($value));
					            }
					            else {
						            echo $value . ' ';
					            }?>

				            <?php endforeach; ?>
                            <br />
			            <?php endforeach; ?>
		            <?php endif; ?>
                </td>
                <td>
		            <?php
		            $visitors = json_decode($item->additional_info, true);
		            echo Text::_('COM_DNBOOKING_TABLE_LABEL_PERSONS_WITH_PACKAGE') . ': ' . $visitors['visitorsPackage'];
		            echo "<br />";
		            echo Text::_('COM_DNBOOKING_TABLE_LABEL_BIRTHDAYCHILDREN') . ': ' . $visitors['birthdaychildren'];
		            echo "<br />";
		            echo Text::_('COM_DNBOOKING_TABLE_LABEL_PERSONS_WITHOUT_PACKAGE') . ': ' . $visitors['visitors'];
		            ?>
                </td>
                <td><?php echo $item->meal_time; ?></td>
                <td><?php echo $item->firstname . ' ' . $item->lastname; ?></td>
                <td><?php echo $bookingId; ?></td>
                <td>
                    <?php switch ($item->published)
                    {
                    case -2:
                    echo Text::_('COM_DNBOOKING_FIELD_RESERVATION_STATUS_TRASHED');
                    break;
                    case 0:
                    echo Text::_('COM_DNBOOKING_FIELD_RESERVATION_STATUS_UNPUBLISHED');
                    break;
                    case 1:
                    echo Text::_('COM_DNBOOKING_FIELD_RESERVATION_STATUS_PUBLISHED');
                    break;
                    case 2:
                    echo Text::_('COM_DNBOOKING_FIELD_RESERVATION_STATUS_ARCHIVED');
                    break;
                    case 3:
                    echo Text::_('COM_DNBOOKING_FIELD_RESERVATION_STATUS_DOWN_PAYMENT_LOCALE');
                    break;
                    case 4:
                    echo Text::_('COM_DNBOOKING_FIELD_RESERVATION_STATUS_DOWN_PAYMENT_MADE');
                    break;
                    default:
                    echo Text::_('COM_DNBOOKING_FIELD_RESERVATION_STATUS_UNPUBLISHED');
                    break;
                    }?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
