<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;
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

?>
<div id="summary">

<p><h3><?= Text::sprintf('COM_DNBOKING_BOOKING_SUMMARY_HEADLINE', $data['firstname'] . ' ' . $data['lastname']) ?></h3></p>

    <p><?= Text::_($data['salutation']) . ' ' . $data['firstname'] . ' ' . $data['lastname']; ?><br/>
        <?= $data['address']; ?><br/>
        <?= $data['zip'] . ' ' . $data['city']; ?>
    </p>
    <p>
        <?= $data['email']; ?><br/>
        <?= $data['phone']; ?>
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
			 <td><?= $data['visitorsPackage'] . ' x </td><td>' . Text::_('COM_DNBOOKING_PACKAGE_TEXT') . ' <strong>' . $data['reservation_date'] . '</strong><td> ' . number_format($packagepriceTotal, 2, ",", ".") . ' €</td>'?>
        </tr>
        <?php if($data['visitors'] > 0): ?>
            <tr>
        <tr>
			 <td><?= $data['visitors'] . ' x </td><td>' . Text::_('COM_DNBOOKING_TICKET_TEXT') . ' <strong>' . $data['reservation_date'] . '</strong><td> ' . number_format($admissionpriceTotal, 2, ",", ".") . ' €</td>'?>
        </tr>
        <?php endif; ?>
    </table>
<?php foreach ($data as $key => $value): ?>
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
				<?= '<td>1 x</td><td>' . $value['title'] . '<td> ' . number_format($value['priceregular'], 2, ",", ".") . ' €</td>'?>
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
                <?= '<td>' . $value['amount'] . ' x </td><td> ' . $value['name'] . ' </td><td> ' . number_format($value['price_total'], 2, ",", ".") . ' €</td>'?>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

<?php endforeach; ?>
    <h4><?= Text::_('COM_DNBOOKING_COMMENTS_LABEL'); ?></h4>
    <div class="uk-card uk-card-default uk-card-body">
        <p>
			<?= $data['customer_notes']; ?>
        </p>
    </div>
</div>
