<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

$app = Factory::getApplication();
$item = ArrayHelper::fromObject($displayData);

/**
 * siehe Settings in der Konfiguration
 * visitors, visitorsPackage, birthdayChild
 */
foreach ($item['additional_info'] as $key => $value) {
	$item[$key] = $value;
}

?>
<div id="orderTableSimple">
	<table class="uk-table uk-table-justify uk-table-responsive uk-table-striped uk-table-small">
		<thead>
            <tr>
                <th class="uk-table-small"><?= Text::_('COM_DNBOOKING_AMOUNT_LABEL') ?></th>
                <th class="uk-table-expand"><?= Text::_('COM_DNBOOKING_NAME_LABEL') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $item['visitorsPackage'] ?> x </td>
                <td><?= Text::_('COM_DNBOOKING_PACKAGE_TEXT') ?>
                    <strong><?= $item['reservation_date'] ?></strong>
                </td>
            </tr>
		<?php if($item['visitors'] > 0): ?>
			<tr>
				<td><?= $item['visitors'] ?> x </td>
                <td><?= Text::_('COM_DNBOOKING_TICKET_TEXT') ?>
                    <strong><?= $item['reservation_date'] ?></strong>
                <td>
			</tr>
		<?php endif; ?>
        </tbody>
        <tfoot></tfoot>
	</table>
	<?php foreach ($item as $key => $value): ?>
		<?php if ($key == 'room'): ?>
			<p><strong><?= Text::_('COM_DNBOOKING_ROOM_LABEL') ?>:</strong></p>
			<table class="uk-table uk-table-justify uk-table-responsive uk-table-striped uk-table-small">
				<thead>
                    <tr>
                        <th class="uk-table-small"><?= Text::_('COM_DNBOOKING_AMOUNT_LABEL') ?></th>
                        <th class="uk-table-expand"><?= Text::_('COM_DNBOOKING_NAME_LABEL') ?></th>
                    </tr>
				</thead>
                <tbody>
                    <tr>
                        <td>1 x</td>
                        <td><?= $value['title'] ?><td>
                    </tr>
                </tbody>
                <tfoot></tfoot>
			</table>
		<?php elseif ($key == 'extras'): ?>
			<p><strong><?= Text::_('COM_DNBOOKING_EXTRAS_LABEL') ?>:</strong></p>
			<table class="uk-table uk-table-justify uk-table-responsive uk-table-striped uk-table-small">
				<thead>
                    <tr>
                        <th class="uk-table-small"><?= Text::_('COM_DNBOOKING_AMOUNT_LABEL') ?></th>
                        <th class="uk-table-expand"><?= Text::_('COM_DNBOOKING_NAME_LABEL') ?></th>
                    </tr>
				</thead>
                <tbody>
                    <?php foreach ($value as $extra): ?>
                        <tr>
                            <td><?= $extra['amount'] ?> x </td>
                            <td><?= $extra['name'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot></tfoot>
			</table>
		<?php endif; ?>
	<?php endforeach; ?>

    <?php if($item['customer_notes'] != ''): ?>
        <h4><?= Text::_('COM_DNBOOKING_COMMENTS_LABEL'); ?></h4>
        <div class="uk-card uk-card-default uk-card-body">
            <p>
                <?= $item['customer_notes']; ?>
            </p>
        </div>
    <?php endif; ?>
</div>
