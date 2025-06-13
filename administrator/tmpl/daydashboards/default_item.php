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
use Joomla\Utilities\ArrayHelper;

\defined('_JEXEC') or die;

$item = (array) $this->item;
$customer = $item['customer'];
$id = $item['id'];
$params = ComponentHelper::getParams('com_dnbooking');
$reservationYear = HTMLHelper::_('date', $item['reservation_date'], 'Y');
$prefix =$params->get('prefix');
$id = $prefix . '-' . $reservationYear . '-' .$item['id'];
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
$discountValue = $item['discount'];
$discountPrice = $totalPrice - $discountValue;

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
                <?= $customer['zip'] . ' ' . $customer['city']; ?><br/>
	            <?= $customer['country']; ?><br/>
            </p>
            <p>
                <?= $customer['email']; ?><br/>
                <?= $customer['phone']; ?>
            </p>

            <p><strong><?= Text::_('COM_DNBOOKING_BIRTHDAYCHILDREN_LABEL') ?>:</strong></p>
            <table class="table table-striped">
		        <?php
		        $additionalInfos2FieldKeys = $params->get('additional_info_form2');
		        $fieldCount = count((array)$additionalInfos2FieldKeys);

		        $children = json_decode($item['additional_infos2']);
		        $children = ArrayHelper::fromObject($children);
		        foreach($children as $child){
			        foreach ($child as $key => $value) {

				        $currentField = 1;
				        echo "<tr>";
				        echo "<td>";
				        foreach ($additionalInfos2FieldKeys as $fieldKey)
				        {

					        if (isset($value[$fieldKey->fieldName])){
						        if (DateTime::createFromFormat('Y-m-d H:i:s', $value[$fieldKey->fieldName]) !== false) {
							        echo date('d.m.Y', strtotime($value[$fieldKey->fieldName]));
						        }
						        else{
							        echo  $value[$fieldKey->fieldName];
						        }
						        if ($currentField < $fieldCount){
							        echo ", ";
						        }
					        }

					        $currentField++;
				        }
				        echo "</td>";
				        echo "</tr>";
			        }
		        } ?>
            </table>

            <p><strong><?= Text::_('COM_DNBOOKING_PACKAGE_LABEL') ?></strong></p>
            <table class="table table-striped orderTable">
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
                    <table class="table table-striped orderTable">
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
                    <table class="table table-striped orderTable">
                        <thead>
                        <tr>
                            <th class="uk-table-small"><?= Text::_('COM_DNBOOKING_AMOUNT_LABEL') ?></th>
                            <th class="uk-table-expand"><?= Text::_('COM_DNBOOKING_NAME_LABEL') ?></th>
                            <th class="uk-table-shrink alignRight"><?= Text::_('COM_DNBOOKING_TOTAL_LABEL') ?></th>
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
                <?php if($item['discount'] > 0): ?>
                    <tr>
                        <td><strong><?= Text::_('COM_DNBOOKING_FIELD_DISCOUNT_LABEL') ?></strong></td>
                        <td class="alignRight"> <?= number_format((float) $discountValue, 2, ",", ".") ?> €</td>
                    </tr>
                    <tr>
                        <td><strong><?= Text::_('COM_DNBOOKING_DISCOUNTEDTOTAL_LABEL') ?></strong></td>
                        <td class="alignRight"><?= number_format((float) $discountPrice, 2, ",", ".") ?> €</td>
                    </tr>
                <?php endif; ?>
                </tbody>

            </table>


            <h4><?= Text::_('COM_DNBOOKING_COMMENTS_LABEL'); ?></h4>
            <p>
                <?= $item['customer_notes']; ?>
            </p>
            <h4><?= Text::_('COM_DNBOOKING_INTERNAL_COMMENTS_LABEL'); ?></h4>
            <p>
		        <?= $item['admin_notes']; ?>
            </p>
                <h4><?= Text::_('COM_DNBOOKING_HEADING_STATE'); ?></h4>
                <div class="uk-card uk-card-default uk-card-body">
                    <p>
				        <?php switch ($item['published'])
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
                    </p>
                </div>
        </div>
    </div>
</div>
