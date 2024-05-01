<?php

use DnbookingNamespace\Component\Dnbooking\Administrator\Helper\DnbookingHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

$app = Factory::getApplication();
$item = ArrayHelper::fromObject($displayData);
$customer = 0;
$room = [];

if(array_key_exists('customer', $item)) {
    $customer = $item['customer'];
}
if(array_key_exists('room', $item)) {
    $room = $item['room'];
}

$id = $item['id'];
$params = ComponentHelper::getParams('com_dnbooking');

if($id) {
    $createdHeadline = HTMLHelper::_('date', $item['reservation_date'], Text::_('DATE_FORMAT_LC5'));
}

/**
 * siehe Settings in der Konfiguration
 * visitors, visitorsPackage, birthdayChild
 */

if(!empty($item['additional_info'])) {
    foreach (json_decode($item['additional_info']) as $key => $value) {
        $item[$key] = $value;
    }
}
else{
    $item['additional_info'] = [];
}

$packageprice = $params->get('packagepriceregular');
$admissionprice = $params->get('admissionpriceregular');

if($item['holiday']) {
	$packageprice = $params->get('packagepricecustom');
	$admissionprice = $params->get('admissionpricecustom');
}
if(array_key_exists('visitorsPackage', $item))
{
    $packagepriceTotal = $packageprice * (int) $item['visitorsPackage'];
}
if(array_key_exists('visitors',$item))
{
	$admissionpriceTotal = $admissionprice * (int) $item['visitors'];
}
if (!array_key_exists('extras_price_total', $item)) {
	$item['extras_price_total'] = 0;
}
if (!array_key_exists('holiday', $item)) {
	$item['holiday'] = 0;
}

$totalPrice = DnbookingHelper::calcPrice($item['additional_info'], $room, $item['extras_price_total'], $item['holiday']);
?>
<div id="summary">

    <?php if($customer): ?>
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

    <?php else: ?>
    <p><h3><?= Text::sprintf('COM_DNBOOKING_HEADLINE_FILLALL_REQUIRED') ?></h3></p>
    <?php endif; ?>

    <?php if($customer && $room): ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th><?= Text::_('COM_DNBOOKING_AMOUNT_LABEL') ?></th>
                <th><?= Text::_('COM_DNBOOKING_NAME_LABEL') ?></th>
                <th class="alignRight"><?= Text::_('COM_DNBOOKING_TOTAL_LABEL') ?></th>
            </tr>
            </thead>
            <tr>
                <td><?= $item['visitorsPackage'] ?> x </td>
                <td><?= Text::_('COM_DNBOOKING_PACKAGE_TEXT') ?></td>
                <td class="alignRight"><?= number_format($packagepriceTotal, 2, ",", ".") ?> €</td>
            </tr>
            <?php if($item['visitors'] > 0): ?>
                <tr>
                <tr>
                    <td><?= $item['visitors'] ?> x </td>
                    <td><?= Text::_('COM_DNBOOKING_TICKET_TEXT') ?></td>
                    <td class="alignRight"><?= number_format($admissionpriceTotal, 2, ",", ".") ?> €</td>
                </tr>
            <?php endif; ?>
        </table>
        <?php foreach ($item as $key => $value): ?>
            <?php if ($key == 'room'): ?>
                <p><strong><?= Text::_('COM_DNBOOKING_ROOM_LABEL') ?>:</strong></p>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th><?= Text::_('COM_DNBOOKING_AMOUNT_LABEL') ?></th>
                        <th><?= Text::_('COM_DNBOOKING_NAME_LABEL') ?></th>
                        <th class="alignRight"><?= Text::_('COM_DNBOOKING_TOTAL_LABEL') ?></th>
                    </tr>
                    </thead>
                    <tr>
                        <td>1 x</td>
                        <td><?= $value['title'] ?></td>
                        <?php if($item['holiday']): ?>
                            <?php $roomPrice = $value['pricecustom']; ?>
                        <?php else: ?>
                            <?php $roomPrice = $value['priceregular']; ?>
                        <?php endif; ?>
                            <td class="alignRight"><?= number_format((float) $roomPrice, 2, ",", ".") ?> €</td>
                    </tr>
                </table>
            <?php elseif ($key == 'extras'): ?>
                <p><strong><?= Text::_('COM_DNBOOKING_EXTRAS_LABEL') ?>:</strong></p>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th><?= Text::_('COM_DNBOOKING_AMOUNT_LABEL') ?></th>
                        <th><?= Text::_('COM_DNBOOKING_NAME_LABEL') ?></th>
                        <th class="alignRight"><?= Text::_('COM_DNBOOKING_TOTAL_LABEL') ?></th>
                    </tr>
                    </thead>
                    <?php foreach ($value as $extra => $value): ?>
                        <tr>
                            <td><?= $value['amount'] ?> x </td>
                            <td><?= $value['name'] ?></td>
                            <td class="alignRight"><?= number_format((float) $value['price_total'], 2, ",", ".") ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>

        <?php endforeach; ?>

        <table class="table table-striped">
            <tbody>
            <tr>
                <td><strong><?= Text::_('COM_DNBOOKING_TOTAL_LABEL') ?></strong></td>
                <td class="alignRight"><?= number_format((float) $totalPrice, 2, ",", ".") ?> €</td>
            </tr>
            </tbody>

        </table>

        <h4><?= Text::_('COM_DNBOOKING_COMMENTS_LABEL'); ?></h4>
        <div class="uk-card uk-card-default uk-card-body">
            <p>
                <?= $item['customer_notes']; ?>
            </p>
        </div>

    <?php endif; ?>
</div>
