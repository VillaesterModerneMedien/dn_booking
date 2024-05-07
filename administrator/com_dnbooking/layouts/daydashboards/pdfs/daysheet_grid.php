<?php

    use Joomla\CMS\Component\ComponentHelper;
    use Joomla\CMS\Factory;
    use Joomla\CMS\HTML\HTMLHelper;
    use Joomla\CMS\Language\Text;
    use Joomla\Utilities\ArrayHelper;

    defined('_JEXEC') or die;

    $app = Factory::getApplication();
    $items = ArrayHelper::fromObject($displayData);
    $params = ComponentHelper::getParams('com_dnbooking');
    $reservationDate = HTMLHelper::_('date', $items[0]['reservation_date'], Text::_('DATE_FORMAT_LC4'));
    $holiday = $items[0]['holiday'];
    $packageprice = $params->get('packagepriceregular');
    $admissionprice = $params->get('admissionpriceregular');

    usort($items, function ($a, $b) {
        return strtotime($a['reservation_date']) <=> strtotime($b['reservation_date']);
    });
    if($holiday) {
        $packageprice = $params->get('packagepricecustom');
        $packageprice = $params->get('admissionpricecustom');
    }
?>
<div class="daysheetGrid">

    <div class="gridHeader">
        <h1 class="h1-headline-pdf"><?= Text::sprintf('COM_DNBOOKING_HEADLINE_DAYDASHBOARDS' ,$reservationDate ); ?></h1>
    </div>

    <div class="daysheetBody">
        <table class="reservationsGrid">
	        <?php foreach (array_chunk($items, 4) as $row) : ?>
                <tr>
                    <?php foreach ($row as $item) : ?>
                        <?php $reservationTime = HTMLHelper::_('date', $item['reservation_date'], 'H:i');?>

                        <?php $children = json_decode($item['additional_infos2'], true);?>
                        <?php $persons = json_decode($item['additional_info'], true);?>
                        <td width="25%">
                            <table class="singleReservation">
                                <thead>
                                    <tr>
                                        <th>
                                            <?= $reservationTime;?>
                                        </th>
                                        <th>
                                            <?= $item['firstname'] . ' ' . $item['lastname'];?>
                                        </th>
                                    </tr>
                                </thead>
                                <tr>
                                    <td colspan="2" class="border-bottom">
                                        <strong><?= Text::_('COM_DNBOOKING_ROOM_LABEL') ?>: </strong> <?= $item['room_title'];?>
                                    </td>
                                </tr>
                                <?php if($children != null):?>
                                <?php foreach($children['addinfos2_subform'] as $key => $value):?>
                                <?php $geburtsdatum = HTMLHelper::_('date', $value['kinddatum'], 'd.m.Y');?>
                                    <tr>
                                        <td colspan="2">
                                            <?=  $value['kindname'] . ', ' . $geburtsdatum . ', ' . $value['kindgeschlecht'];?>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                                <?php endif;?>
                                <tr>
                                    <td colspan="2" class="border-top">
                                        <?= Text::_('COM_DNBOOKING_TABLE_LABEL_PERSONS_COUNT') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
	                                    <?= $persons['visitorsPackage'] . ' x ';?>
                                    </td>
                                    <td>
                                        <?= Text::_('COM_DNBOOKING_TABLE_LABEL_PERSONS_WITH_PACKAGE') ?>
                                    </td>
                                </tr>
                                <?php if($persons['visitors'] > 0):?>
                                <tr>
                                    <td>
			                            <?= $persons['visitors'] . ' x ';?>
                                    </td>
                                    <td>
	                                    <?= Text::_('COM_DNBOOKING_TABLE_LABEL_PERSONS_WITHOUT_PACKAGE') ?>
                                    </td>
                                </tr>
                                <?php endif;?>
                                <tr>
                                    <td colspan="2" class="border-top">
                                        <?= Text::_('COM_DNBOOKING_EXTRAS_LABEL') ?>:
                                    </td>
                                </tr>
                                <?php foreach($item['extras'] as $extra):?>
                                    <tr>
                                        <td width="10px" class="checkbox">

                                        </td>
                                        <td>
                                            <?= $extra['amount'] . ' x ' . $extra['name'];?>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                                <?php if($item['customer_notes'] != null) :?>
                                <tr>
                                    <td colspan="2" class="border-top">
                                        <?= Text::_('COM_DNBOOKING_COMMENTS_LABEL') ?>
                                    </td>
                                <tr>
                                <tr>
                                    <td colspan="2">
		                                <?= $item['customer_notes'];?>
                                    </td>
                                </tr>
                                <?php endif;?>
                                <?php if($item['admin_notes'] != null) :?>
                                    <td colspan="2" class="border-top">
                                        <?= Text::_('COM_DNBOOKING_INTERNAL_COMMENTS_LABEL') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <?= $item['admin_notes'];?>
                                    </td>
                                </tr>
                                <?php endif;?>
                            </table>
                        </td>
                    <?php endforeach; ?>
                </tr>
	        <?php endforeach; ?>
        </table>
    </div>

</div>
