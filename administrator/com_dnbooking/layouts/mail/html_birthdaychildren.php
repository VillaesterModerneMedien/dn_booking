<?php

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

$app = Factory::getApplication();
$params = ComponentHelper::getParams('com_dnbooking');
$additionalInfos2FieldKeys = $params->get('additional_info_form2');

$birthdayChildren = $displayData;

if(!is_array($birthdayChildren['additional_infos2'])) {
    $birthdayChildren['additional_infos2'] = json_decode($birthdayChildren['additional_infos2'], true);
}
?>
<div id="orderTableBirthdayChildren">
    <?php
    foreach ($birthdayChildren['additional_infos2']['addinfos2_subform'] as $key => $value) {

	        $fieldCount = count((array)$additionalInfos2FieldKeys);

	        $currentField = 1;

            $label = Text::_('COM_DNBOOKING_BIRTHDAY_CHILDREN_' . strtoupper($key));

           foreach ($additionalInfos2FieldKeys as $fieldKey)
           {
               if (isset($value[$fieldKey->fieldName])):
                   echo $value[$fieldKey->fieldName];
                   if ($currentField < $fieldCount):
                       echo "," ;
                   endif;
               endif;
               $currentField++;
           }
    }
    ?>
</div>
