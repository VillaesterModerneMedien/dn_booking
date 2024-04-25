<?php

use DnbookingNamespace\Component\Dnbooking\Administrator\Helper\DnbookingHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

$app = Factory::getApplication();
$item = ArrayHelper::fromObject($displayData);
$customer = $item['customer'];
$id = $item['id'];
$params = ComponentHelper::getParams('com_dnbooking');
$bookingHelper = new DnbookingHelper();

if($id) {
    $createdHeadline = HTMLHelper::_('date', $item['reservation_date'], Text::_('DATE_FORMAT_LC5'));
}

/**
 * siehe Settings in der Konfiguration
 * visitors, visitorsPackage, birthdayChild
 */
foreach (json_decode($item['additional_info']) as $key => $value) {
	$item[$key] = $value;
}

$packageprice = $params->get('packagepriceregular');
if($item['holiday']) {
	$packageprice = $params->get('packagepricecustom');
}
$packagepriceTotal = $packageprice * (int) $item['visitorsPackage'];

$admissionprice = $params->get('admissionpriceregular');
if($item['holiday']) {
	$packageprice = $params->get('admissionpricecustom');
}
$admissionpriceTotal = $admissionprice * (int) $item['visitors'];

$totalPrice = $bookingHelper->calcPrice($item['additional_info'], $item['room'], $item['extras'], $item['holiday']);
?>
<div id="summary">

    <p><h3><?= Text::sprintf('COM_DNBOOKING_HEADLINE_RESERVATION', $id , $item['reservation_date'], $customer['firstname'] . ' ' . $customer['lastname']) ?></h3></p>
    <h4><?= Text::_('COM_DNBOOKING_RESERVATION_DATE_LABEL') . $item['reservation_date']; ?></h4>

    <p><?= Text::_($customer['salutation']) . ' ' . $customer['firstname'] . ' ' . $customer['lastname']; ?><br/>
		<?= $customer['address']; ?><br/>
		<?= $customer['zip'] . ' ' . $customer['city']; ?>
    </p>
    <p>
		<?= $customer['email']; ?><br/>
		<?= $customer['phone']; ?>
    </p>

    <table class="uk-table uk-table-justify uk-table-responsive uk-table-striped uk-table-small">
        <thead>
        <tr>
            <th class="uk-table-small"><?= Text::_('COM_DNBOOKING_AMOUNT_LABEL') ?></th>
            <th class="uk-table-expand"><?= Text::_('COM_DNBOOKING_NAME_LABEL') ?></th>
            <th class="uk-table-shrink"><?= Text::_('COM_DNBOOKING_TOTAL_LABEL') ?></th>
        </tr>
        </thead>
        <tr>
            <td><?= $item['visitorsPackage'] . ' x </td><td>' . Text::_('COM_DNBOOKING_PACKAGE_TEXT') . ' <strong>' . $item['reservation_date'] . '</strong><td> ' . number_format($packagepriceTotal, 2, ",", ".") . ' €</td>'?>
        </tr>
		<?php if($item['visitors'] > 0): ?>
            <tr>
            <tr>
                <td><?= $item['visitors'] . ' x </td><td>' . Text::_('COM_DNBOOKING_TICKET_TEXT') . ' <strong>' . $item['reservation_date'] . '</strong><td> ' . number_format($admissionpriceTotal, 2, ",", ".") . ' €</td>'?>
            </tr>
		<?php endif; ?>
    </table>
	<?php foreach ($item as $key => $value): ?>
		<?php if ($key == 'room'): ?>
            <p><strong><?= Text::_('COM_DNBOOKING_ROOM_LABEL') ?>:</strong></p>
            <table class="uk-table uk-table-justify uk-table-responsive uk-table-striped uk-table-small">
                <thead>
                <tr>
                    <th class="uk-table-small"><?= Text::_('COM_DNBOOKING_AMOUNT_LABEL') ?></th>
                    <th class="uk-table-expand"><?= Text::_('COM_DNBOOKING_NAME_LABEL') ?></th>
                    <th class="uk-table-shrink"><?= Text::_('COM_DNBOOKING_TOTAL_LABEL') ?></th>
                </tr>
                </thead>
                <tr>
					<?= '<td>1 x</td><td>' . $value['title'] . '<td> ' . number_format((float) $value['priceregular'], 2, ",", ".") . ' €</td>'?>
                </tr>
            </table>
		<?php elseif ($key == 'extras'): ?>
            <p><strong><?= Text::_('COM_DNBOOKING_EXTRAS_LABEL') ?>:</strong></p>
            <table class="uk-table uk-table-justify uk-table-responsive uk-table-striped uk-table-small">
                <thead>
                <tr>
                    <th class="uk-table-small"><?= Text::_('COM_DNBOOKING_AMOUNT_LABEL') ?></th>
                    <th class="uk-table-expand"><?= Text::_('COM_DNBOOKING_NAME_LABEL') ?></th>
                    <th class="uk-table-shrink"><?= Text::_('COM_DNBOOKING_TOTAL_LABEL') ?></th>
                </tr>
                </thead>
				<?php foreach ($value as $extra => $value): ?>
                    <tr>
						<?= '<td>aa' . $value['amount'] . ' x </td><td> ' . $value['name'] . ' </td><td> ' . number_format((float) $value['price_total'], 2, ",", ".") . ' €</td>'?>
                    </tr>
				<?php endforeach; ?>
            </table>
		<?php endif; ?>

	<?php endforeach; ?>
    <h4><?= Text::_('COM_DNBOOKING_COMMENTS_LABEL'); ?></h4>
    <div class="uk-card uk-card-default uk-card-body">
        <p>
			<?= $item['customer_notes']; ?>
        </p>
    </div>
</div>
