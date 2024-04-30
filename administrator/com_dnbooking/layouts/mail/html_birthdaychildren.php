<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

$app = Factory::getApplication();
$birthdayChildren = $displayData;

if(!is_array($birthdayChildren['additional_infos2'])) {
    $birthdayChildren['additional_infos2'] = json_decode($birthdayChildren['additional_infos2'], true);
}
?>
<div id="orderTableBirthdayChildren">
    <?php
    foreach ($birthdayChildren['additional_infos2']['addinfos2_subform'] as $children) {
        foreach ($children as $key => $value) {
            $label = Text::_('COM_DNBOOKING_BIRTHDAY_CHILDREN_' . strtoupper($key));
            echo $label . $value . '<br>';
        }
    }
    ?>
</div>
