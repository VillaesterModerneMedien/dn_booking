<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use DnbookingNamespace\Component\Dnbooking\Administrator\Helper\DnbookingHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

\defined('_JEXEC') or die;

$item = (array) $this->item;
$customer = $item['customer'];
$id = $item['id'];
$params = ComponentHelper::getParams('com_dnbooking');

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


$extraTotal = array_key_exists('extras_price_total', $item) ? $item['extras_price_total'] : 0;

$totalPrice = DnbookingHelper::calcPrice($item['additional_info'], $item['room'], $extraTotal, $item['holiday']);

?>
<div class="col-12">
    <div class="card">

    <div class="card-header">
            <h3>
                <?= Text::sprintf('COM_DNBOOKING_HEADLINE_DAYDASHBOARDS_RESERVATION', $id , date('H:i', strtotime($item['reservation_date']))); ?>
            </h3>
        </div>

        <div class="card-body">
            <h4><?= Text::_('COM_DNBOOKING_DAYDASHBOARDS_RESERVATION_DATE_LABEL') . date('d.m.Y - H:i', strtotime($item['reservation_date'])) ?></h4>

            <p><?= Text::_($customer['salutation']) . ' ' . $customer['firstname'] . ' ' . $customer['lastname']; ?><br/>
                <?= $customer['address']; ?><br/>
                <?= $customer['zip'] . ' ' . $customer['city']; ?>
            </p>
            <p>
                <?= $customer['email']; ?><br/>
                <?= $customer['phone']; ?>
            </p>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="uk-table-small"><?= Text::_('COM_DNBOOKING_AMOUNT_LABEL') ?></th>
                        <th class="uk-table-expand"><?= Text::_('COM_DNBOOKING_NAME_LABEL') ?></th>
                        <th class="uk-table-shrink alignRight"><?= Text::_('COM_DNBOOKING_TOTAL_LABEL') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $item['visitorsPackage'] ?> x </td>
                        <td><?= Text::_('COM_DNBOOKING_PACKAGE_TEXT') ?></td>
                        <td class="alignRight"><?= number_format($packagepriceTotal, 2, ",", ".") ?> €</td>
                    </tr>
                    <?php if($item['visitors'] > 0): ?>
                        <tr>
                            <td><?= $item['visitors'] ?> x </td>
                            <td><?= Text::_('COM_DNBOOKING_TICKET_TEXT') ?></td>
                            <td class="alignRight"><?= number_format($admissionpriceTotal, 2, ",", ".") ?> €</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot></tfoot>
            </table>
            <?php foreach ($item as $key => $value): ?>
                <?php if ($key == 'room'): ?>
                    <p><strong><?= Text::_('COM_DNBOOKING_ROOM_LABEL') ?>:</strong></p>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th class="uk-table-small"><?= Text::_('COM_DNBOOKING_AMOUNT_LABEL') ?></th>
                            <th class="uk-table-expand"><?= Text::_('COM_DNBOOKING_NAME_LABEL') ?></th>
                            <th class="uk-table-shrink alignRight"><?= Text::_('COM_DNBOOKING_TOTAL_LABEL') ?></th>
                        </tr>
                        </thead>
                        <tr>
                            <td>1 x</td>
                            <td><?= $value['title'] ?></td>
                            <td class="alignRight"><?= number_format((float) $value['priceregular'], 2, ",", ".") ?> €</td>
                        </tr>
                    </table>
                <?php elseif ($key == 'extras'): ?>
                    <p><strong><?= Text::_('COM_DNBOOKING_EXTRAS_LABEL') ?>:</strong></p>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th class="uk-table-small"><?= Text::_('COM_DNBOOKING_AMOUNT_LABEL') ?></th>
                            <th class="uk-table-expand"><?= Text::_('COM_DNBOOKING_NAME_LABEL') ?></th>
                            <th class="uk-table-shrink"><?= Text::_('COM_DNBOOKING_TOTAL_LABEL') ?></th>
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
            <p>
                <?= $item['customer_notes']; ?>
            </p>
        </div>
    </div>
</div>
