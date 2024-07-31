<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use DnbookingNamespace\Component\Dnbooking\Administrator\Helper\DnbookingHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;
use function YOOtheme\app;
use YOOtheme\Config;
use YOOtheme\View;

use Joomla\Input\Input;

list($config, $view, $input) = app(Config::class, View::class, Input::class);

$data = $displayData;

/**
 * siehe Settings in der Konfiguration
 * visitors, visitorsPackage, birthdayChild
 */
foreach ($data['additional_info'] as $key => $value) {
    $data[$key] = $value;
}

$params = $displayData['params'];
$packageprice = $params['packagepriceregular'];
if($data['isHolidayOrWeekend']) {
    $packageprice = $params['packagepricecustom'];
}
$packagepriceTotal = $packageprice * (int) $data['visitorsPackage'];

$admissionprice = $params['admissionpriceregular'];
if($data['isHolidayOrWeekend']) {
	$packageprice = $params['admissionpricecustom'];
}
$admissionpriceTotal = $admissionprice * (int) $data['visitors'];
$room = ArrayHelper::fromObject($data['room_id']);

$roomprice = $data['isHolidayOrWeekend'] ? $room['pricecustom'] : $room['priceregular'];

$total = DnbookingHelper::calcPrice($data['additional_info'], $room, $data['extras_price_total'], $data['isHolidayOrWeekend']);
$reservationDate = HTMLHelper::_('date', $data['reservation_date'], 'd. F Y - H:i');

$params = ComponentHelper::getParams('com_dnbooking');
$additionalInfos2FieldKeys = $params->get('additional_info_form2');

$tableHead = " 
    <thead>
        <tr>
            <th class='uk-table-shrink amount'>". Text::_('COM_DNBOOKING_AMOUNT_LABEL') . "</th>
            <th class='uk-table-expand description'>".Text::_('COM_DNBOOKING_NAME_LABEL') . " </th>
            <th class='uk-table-small price'>".Text::_('COM_DNBOOKING_TOTAL_LABEL') . " </th>
        </tr>
    </thead>";
?>
<div id="summary">

<p><h3><?= Text::sprintf('COM_DNBOKING_BOOKING_SUMMARY_HEADLINE', $data['firstname'] . ' ' . $data['lastname']) ?></h3></p>
    <h4>
       <?= Text::_('COM_DNBOOKING_DATE_LABEL'); ?><strong><?= $reservationDate?></strong>
    </h4>
    <h4><?= Text::_('COM_DNBOOKING_ADRESS_LABEL'); ?></h4>
    <p><?= Text::_($data['salutation']) . ' ' . $data['firstname'] . ' ' . $data['lastname']; ?><br/>
        <?= isset($data['adress']) ? $data['address'] . '<br/>' : ''; ?>
        <?= isset($data['zip']) ? $data['zip'] . ' ' : '';?><?=  isset($data['city']) ? $data['city'] : ''; ?>
    </p>
    <p>
        <?= isset($data['email']) ? $data['email'] : ''; ?><br/>
        <?= isset($data['phone']) ? $data['phone'] : ''; ?>
    </p>

    <p><strong><?= Text::_('COM_DNBOOKING_PACKAGE_LABEL') ?></strong></p>
    <table class="uk-table  uk-table-responsive uk-table-striped uk-table-small">
        <?= $tableHead ?>
        <tr>
            <td><?= $data['visitorsPackage']; ?> x </td>
            <td>
                <?= Text::_('COM_DNBOOKING_PACKAGE_TEXT'); ?><br/>
                <p class="uk-text-meta uk-margin-left uk-margin-remove-top">
                    <strong><?= Text::_('COM_DNBOOKING_BIRTHDAYCHILDREN_LABEL'); ?></strong><br/>
	                <?php foreach($data['additional_infos2']['addinfos2_subform'] as $key => $value): ?>
		                <?php
		                $fieldCount = count((array)$additionalInfos2FieldKeys);
		                $currentField = 1;
		                ?>
		                <?php foreach ($additionalInfos2FieldKeys as $fieldKey): ?>
			                <?php if (isset($value[$fieldKey->fieldName])): ?>
				                <?= $value[$fieldKey->fieldName]; ?><?php if ($currentField < $fieldCount): ?>, <?php endif; ?>
			                <?php endif; ?>
			                <?php $currentField++; ?>
		                <?php endforeach; ?>
                        <br/>
	                <?php endforeach; ?>
                </p>
            </td>
            <td>
                <?= number_format($packagepriceTotal, 2, ",", "."); ?> €
            </td>

        </tr>
        <?php if($data['visitors'] > 0): ?>
        <tr>
			 <td><?= $data['visitors'] . ' x </td><td>' . Text::_('COM_DNBOOKING_TICKET_TEXT') . ' <td> ' . number_format($admissionpriceTotal, 2, ",", ".") . ' €</td>'?>
        </tr>
        <?php endif; ?>
    </table>

    <p><strong><?= Text::_('COM_DNBOOKING_ROOM_LABEL') ?></strong></p>
    <table class="uk-table  uk-table-responsive uk-table-striped uk-table-small">
	    <?= $tableHead ?>
        <tr>
			<?= '<td>1 x</td><td>' . $room['title'] . '<td> ' . number_format((float) $roomprice, 2, ",", ".") . ' €</td>'?>
        </tr>
    </table>

    <?php if(array_key_exists('extras', $data) && !empty($data['extras'])) :?>
    <p><strong><?= Text::_('COM_DNBOOKING_EXTRAS_LABEL') ?></strong></p>
    <table class="uk-table  uk-table-responsive uk-table-striped uk-table-small">
	    <?= $tableHead ?>
		<?php foreach ($data['extras'] as $extra => $value): ?>
            <tr>
				<?= '<td>' . $value['amount'] . ' x </td><td> ' . $value['name'] . ' </td><td> ' . number_format($value['price_total'], 2, ",", ".") . ' €</td>'?>
            </tr>
		<?php endforeach; ?>
    </table>
    <?php endif; ?>

    <table class="uk-table  uk-table-responsive uk-table-striped uk-table-small">
        <tr>
            <td class="uk-table-small amount"><h4><?= Text::_('COM_DNBOOKING_TOTAL_LABEL'); ?></h4></td>
            <td class="uk-table-expand description"></td>
            <td class="uk-table-shrink price"><?= number_format($total, 2, ",", ".")?> €</td>
        </tr>
    </table>

    <?php if(array_key_exists('customer_notes', $data) && !empty($data['customer_notes'])) :?>
    <h4><?= Text::_('COM_DNBOOKING_COMMENTS_LABEL'); ?></h4>
    <div class="uk-card uk-card-default uk-card-body">
        <p>
			<?= $data['customer_notes']; ?>
        </p>
    </div>
    <?php endif; ?>
</div>
