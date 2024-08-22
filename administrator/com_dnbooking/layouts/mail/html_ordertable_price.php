<?php

use DnbookingNamespace\Component\Dnbooking\Administrator\Helper\DnbookingHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

$app = Factory::getApplication();
$params = ComponentHelper::getParams('com_dnbooking');

$item = $displayData;
$reservationDate = HTMLHelper::_('date', $item['reservation_date'], Text::_('DATE_FORMAT_LC4'));
$reservationTime = HTMLHelper::_('date', $item['reservation_date'], 'H:i');
$additionalInfos2FieldKeys = $params->get('additional_info_form2');

$packageprice = $params->get('packagepriceregular');
$admissionprice = $params->get('admissionpriceregular');

$roomprice = $item['room']['priceregular'];

if(!is_array($item['additional_info'])) {
	$item['additional_info'] = json_decode($item['additional_info'], true);
}

/**
 * siehe Settings in der Konfiguration
 * visitors, visitorsPackage, birthdayChild
 */
foreach ($item['additional_info'] as $key => $value) {
	$item[$key] = $value;
}


if($item['holiday']) {
	$packageprice = $params->get('packagepricecustom');
	$admissionprice = $params->get('admissionpricecustom');
    $roomprice = $item['room']['pricecustom'];
}
$packagepriceTotal = $packageprice * (int) $item['visitorsPackage'];
$admissionpriceTotal = $admissionprice * (int) $item['visitors'];

$downpayment = $item['published'] === 4 ? $params->get('downpayment') : -1;

?>
<style>
    #orderTableSimple table {
        border-collapse: collapse;
        width:100%;
    }
    #orderTableSimple tr td{
        border-bottom: 1pt solid #1f5098;
        vertical-align: top;
        padding:5px;
    }
    #orderTableSimple tr td:first-child {
        min-width:200px;
    }
    #orderTableSimple tr td:nth-child(2) {
        width:100%;
    }
    #orderTableSimple tr td:last-child{
        min-width:100px;
    }
</style>
<div id="orderTableSimple">
    a
    <table>
        <tr>
            <td><?= Text::_('COM_DNBOOKING_MAIL_BOOKINGNUMBER') ?></td>
            <td colspan="2"><?= $item['id'] ?> </td>
        </tr>
        <tr>
            <td>
				<?= Text::_('COM_DNBOOKING_MAIL_BOOKINGDATE') ?>
            </td>
            <td colspan="2">
				<?= $reservationDate ?>
            </td>
        </tr>
        <tr>
            <td>
				<?= Text::_('COM_DNBOOKING_MAIL_ARRIVALTIME') ?>
            </td>
            <td colspan="2">
				<?= $reservationTime ?>
            </td>
        </tr>
        <tr>
            <td>
			    <?= Text::_('COM_DNBOOKING_MAIL_BIRTHDAYCHILDREN') ?>
            </td>
            <td colspan="2">
			    <?php
			    $additionalInfos2FieldKeys = $params->get('additional_info_form2');
			    $fieldCount = count((array)$additionalInfos2FieldKeys);
			    if(is_array($item['additional_infos2'])){
				    $children = $item['additional_infos2'];
			    }
			    else
			    {
                    $children = json_decode($item['additional_infos2']);
				    $children = ArrayHelper::fromObject($children);
			    }
			    foreach($children as $child){
				    foreach ($child as $key => $value) {
					    $currentField = 1;
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
					    echo "<br/>";
				    }
			    } ?>
            </td>
        </tr>
        <tr>
            <td>
				<?= Text::_('COM_DNBOOKING_MAIL_PERSONSCOUNT') ?>
            </td>
            <td>
				<?= Text::_('COM_DNBOOKING_MAIL_PERSONSCOUNT_PACKAGE')?>: <?= $item['visitorsPackage'] ?><br/>
				<?= Text::_('COM_DNBOOKING_MAIL_PERSONSCOUNT_TICKET')?>: <?= $item['visitors'] ?>
            </td>
            <td>
                <?= $packageprice * $item['visitorsPackage']?> €<br/>
                <?= $admissionprice * $item['visitors']?> €
            </td>
        </tr>
        <tr>
            <td>
				<?= Text::_('COM_DNBOOKING_MAIL_ROOM') ?>
            </td>
            <td>
				<?= $item['room']['title'] ?>
            </td>
            <td>
		        <?= $roomprice ?> €
            </td>
        </tr>
        <?php if(array_key_exists('extras', $item) && !empty($item['extras'])) :?>
        <tr>
            <td>
				<?= Text::_('COM_DNBOOKING_MAIL_EXTRAS') ?>
            </td>
            <td >

					<?php foreach ($item['extras'] as $extra => $value): ?>
						<?= $value['amount'] . ' x ' . $value['name'] ?><br/>
					<?php endforeach; ?>

            </td>
            <td style="vertical-align:bottom!important;">
	            <?= $item['extras_price_total'] ?> €
            </td>
        </tr>
        <?php endif; ?>
        <?php if($downpayment >= 0):
            $item['reservation_price'] -= $downpayment;
            ?>
        <tr>
            <td>
            </td>
            <td style="text-align:right;">
                <strong>
				    <?= Text::_('COM_DNBOOKING_MAIL_DOWNPAYMENT') ?>
                </strong>
            </td>
            <td>
                <strong>
				    - <?= $downpayment ?> €
                </strong>
            </td>
        </tr>
        <?php endif;?>
	    <?php if($item['published'] === 3):?>
            <tr>
                <td>
                </td>
                <td style="text-align:right;">
                    <strong>
					    <?= Text::sprintf('COM_DNBOOKING_MAIL_DOWNPAYMENT_LOCALE', $params->get('downpayment')) ?>
                    </strong>
                </td>
                <td>
                </td>
            </tr>
	    <?php endif;?>
        <tr>
            <td>
            </td>
            <td style="text-align:right;">
			    <strong>
                    <?= Text::_('COM_DNBOOKING_MAIL_TOTALCOSTS') ?>
                </strong>
            </td>
            <td>
                <strong>
			        <?= $item['reservation_price'] ?> €
                </strong>
            </td>
        </tr>
        <tr>
            <td>
				<?= Text::_('COM_DNBOOKING_MAIL_NOTICE') ?>
            </td>
            <td colspan="2">
				<?= $item['customer_notes'] ?>
            </td>
        </tr>
    </table>
</div>
