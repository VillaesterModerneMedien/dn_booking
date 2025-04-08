<?php

    use Joomla\CMS\Component\ComponentHelper;
    use Joomla\CMS\Factory;
    use Joomla\CMS\HTML\HTMLHelper;
    use Joomla\CMS\Language\Text;
    use Joomla\Utilities\ArrayHelper;

    defined('_JEXEC') or die;

    $app = Factory::getApplication();

    $items = ArrayHelper::fromObject($displayData);
    $filteredItems = array_filter($items, function($item) {
        return $item['published'] == 4 || $item['published'] == 3;
    });
    $items = $filteredItems;
    $itemCount = 0;
    $gridItems = [];
    $params = ComponentHelper::getParams('com_dnbooking');

    $reservationDate = HTMLHelper::_('date', $items[0]['reservation_date'], Text::_('DATE_FORMAT_LC4'));
    $holiday = $items[0]['holiday'];
    $packageprice = $params->get('packagepriceregular');
    $admissionprice = $params->get('admissionpriceregular');

    usort($items, function ($a, $b) {
        return $a['room']['ordering'] <=> $b['room']['ordering'];
    });

    foreach ($items as $reservation){
        while($itemCount < $reservation['room']['ordering']){
            $gridItems[] = 'empty';
            $itemCount++;
        }
        $gridItems[] = $reservation;
	    $itemCount++;
    }
    $rows = array_chunk($gridItems, 7);
    $rowsHeight = 277 / count($rows);
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
        <table class="reservationsGrid" height="100%">
	        <?php foreach ( $rows as $row) : ?>
                <tr height="<?=$rowsHeight;?>mm">
                    <?php foreach ($row as $item) : ?>
	                    <?php $count++; ?>
                        <?php if($item === "empty"):?>
                            <td width="14.28%" height="<?=$rowsHeight;?>mm" class="emptyCell" style="min-height:<?=$rowsHeight;?>vh"><table height="<?=$rowsHeight;?>mm" width="100%"><tr></tr><td>&nbsp;</td></table></td>
                        <?php else:?>
                            <?php if ($item['published'] !=4 && $item['published'] !=3):?>
		                        <?php continue;?>
                            <?php endif;?>
                        <?php $reservationTime = HTMLHelper::_('date', $item['reservation_date'], 'H:i');?>
                        <?php $children = json_decode($item['additional_infos2'], true);?>
                        <?php $persons = json_decode($item['additional_info'], true);?>
                        <td width="14.28%" height="<?=$rowsHeight;?>mm">
                            <table class="singleReservation" width="100%" height="<?=$rowsHeight;?>mm">
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
                                <tr>
                                    <td colspan="2" class="border-top">
			                            <?= Text::_('COM_DNBOOKING_FIELD_MEAL_TIME_LABEL') ?>:<br> <?= $item['meal_time'];?>
                                    </td>
                                </tr>
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
                                <tr>
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
                        <?php endif;?>
                    <?php endforeach; ?>
                    <?php for ($i = $count; $i < 7; $i++) : ?>
                        <td width="14.28%" class="quarterCell emptyCell"></td>
	                <?php endfor; ?>
                </tr>
	        <?php endforeach; ?>
        </table>
    </div>
</div>
