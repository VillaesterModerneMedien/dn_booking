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

$data = $displayData['jform'];
$params = $displayData['params'];
$packageprice = $params['packagepriceregular'];
$packagepriceTotal = $packageprice * $data['visitors'];
?>
<div id="summary">
<p><h3>Hallo <?= $data['firstname'] . ' ' . $data['lastname']?>, Ihre Buchung im Überblick</h3></p>

    <p><?php echo $data['salutation'] . ' ' . $data['firstname'] . ' ' . $data['lastname']; ?><br/>
        <?php echo $data['address']; ?><br/>
        <?php echo $data['zip'] . ' ' . $data['city']; ?>
    </p>
    <p>
        <?php echo $data['email']; ?><br/>
        <?php echo $data['phone']; ?>
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
			 <td><?= $data['visitors'] . ' x </td><td>' . Text::_('COM_DNBOOKING_TICKET_TEXT') . ' <strong>' . date('d.m.Y', strtotime($data['date'])) . ' - ' . $data['time'] . '</strong><td> ' . number_format($packagepriceTotal, 2, ",", ".") . ' €</td>'?>
        </tr>
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
    <h4><?php echo JText::_('COM_DNBOOKING_COMMENTS_LABEL'); ?></h4>
    <div class="uk-card uk-card-default uk-card-body">
        <p>
			<?php echo $data['comments']; ?>
        </p>
    </div>


    <button type="submit" class="uk-button uk-button-primary"><?php echo JText::_('COM_DNBOOKING_SEND_RESERVATION'); ?></button>

</div>
