<?php

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

$app = Factory::getApplication();
$params = ComponentHelper::getParams('com_dnbooking');

$item = $displayData;
$reservationDate = HTMLHelper::_('date', $item['reservation_date'], Text::_('DATE_FORMAT_LC4'));
$reservationYear = HTMLHelper::_('date', $item->reservation_date, 'Y');
$reservationTime = HTMLHelper::_('date', $item['reservation_date'], 'H:i');
$prefix =$params->get('prefix');
$id = $prefix . '-' . $reservationYear . '-' .$item['id'];

$additionalInfos2FieldKeys = $params->get('additional_info_form2');


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
    #orderTableSimple tr td:last-child{
        width:100%;
    }
</style>
<div id="orderTableSimple">
	<table>
        <tr>
            <td><?= Text::_('COM_DNBOOKING_MAIL_BOOKINGNUMBER') ?></td>
            <td><?= $id; ?> </td>

        </tr>
        <tr>
            <td>
	            <?= Text::_('COM_DNBOOKING_MAIL_BOOKINGDATE') ?>
            </td>
            <td>
                <?= $reservationDate ?>
            </td>

        </tr>
        <tr>
            <td>
				<?= Text::_('COM_DNBOOKING_MAIL_ARRIVALTIME') ?>
            </td>
            <td>
				<?= $reservationTime ?>
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
        </tr>
        <tr>
            <td>
                <?= Text::_('COM_DNBOOKING_MAIL_BIRTHDAYCHILDREN') ?>
            </td>
            <td>
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
	            <?= Text::_('COM_DNBOOKING_MAIL_ROOM') ?>
            </td>
            <td>
                <?= $item['room']['title'] ?>
            </td>
        </tr>
        <tr>
            <td>
	            <?= Text::_('COM_DNBOOKING_MAIL_EXTRAS') ?>
            </td>
            <td>
                <?php if(array_key_exists('extras', $item) && !empty($item['extras'])) :?>
                    <?php foreach ($item['extras'] as $extra => $value): ?>
                        <?= $value['amount'] . ' x ' . $value['name'] ?><br/>
                    <?php endforeach; ?>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>
	            <?= Text::_('COM_DNBOOKING_MAIL_NOTICE') ?>
            </td>
            <td>
                <?= $item['customer_notes'] ?>
            </td>
        </tr>


	</table>

</div>
