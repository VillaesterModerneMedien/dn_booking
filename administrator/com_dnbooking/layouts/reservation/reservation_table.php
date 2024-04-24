<?php

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$app = Factory::getApplication();
$item = $displayData;
$id = $item->id;
if($id) {
    $createdHeadline = HTMLHelper::_('date', $item->created, Text::_('DATE_FORMAT_LC5'));
}
?>
<?php if($id): ?>

<table>
    <tr colspan="2">
        <h2><?=  Text::sprintf('COM_DNBOOKING_TABLE_HEADING', $id, $createdHeadline); ?></h2>
        <td><?= $item->id; ?></td>
    </tr>

    <tr>
        <td><?= Text::_('COM_DNBOOKING_TABLE_LABEL_RESERVATION_PRICE'); ?></td>
        <td><?= $item->reservation_price; ?></td>
    </tr>
    <tr>
        <td><?= Text::_('COM_DNBOOKING_TABLE_LABEL_RESERVATION_STATUS'); ?></td>
        <td><?= $item->published; ?></td>
    </tr>
    <tr>
        <td><?= Text::_('COM_DNBOOKING_TABLE_LABEL_CUSTOMER_ID'); ?></td>
        <td><?= $item->customer_id; ?></td>
    </tr>
    <tr>
        <td><?= Text::_('COM_DNBOOKING_TABLE_LABEL_ROOM_ID'); ?></td>
        <td><?= $item->room_id; ?></td>
    </tr>
    <tr>
        <td><?= Text::_('COM_DNBOOKING_TABLE_LABEL_PERSONS_COUNT'); ?></td>
        <td><?= $item->persons_count; ?></td>
    </tr>
    <tr>
        <td><?= Text::_('COM_DNBOOKING_TABLE_LABEL_ADDITIONAL_INFO'); ?></td>
        <td><?= htmlspecialchars($item->additional_info); ?></td>
    </tr>
    <tr>
        <td><?= Text::_('COM_DNBOOKING_TABLE_LABEL_RESERVATION_DATE'); ?></td>
        <td><?php echo HTMLHelper::_('date', $item->reservation_date, Text::_('DATE_FORMAT_LC5')); ?></td>
    </tr>

    <tr>
        <td><?= Text::_('COM_DNBOOKING_TABLE_LABEL_CREATED'); ?></td>
        <td>
	        <?php echo HTMLHelper::_('date', $item->created, Text::_('DATE_FORMAT_LC5')); ?>
        </td>
    </tr>
    <tr>
        <td><?= Text::_('COM_DNBOOKING_TABLE_LABEL_MODIFIED'); ?></td>
        <td><?php echo HTMLHelper::_('date', $item->modified, Text::_('DATE_FORMAT_LC5')); ?></td>
    </tr>
    <tr>
        <td><?= Text::_('COM_DNBOOKING_TABLE_LABEL_EXTRAS_IDS'); ?></td>
        <td><?= htmlspecialchars($item->extras_ids); ?></td>
    </tr>
</table>
<?php else: ?>
    <h2><?=  Text::_('COM_DNBOOKING_TABLE_ADD_RESERVATION'); ?></h2>
<?php endif; ?>
