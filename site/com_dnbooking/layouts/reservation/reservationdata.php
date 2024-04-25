<?php

use Joomla\CMS\Language\Text;
$form = $displayData;
$fieldsets = $form->getFieldsets();
?>
<div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid="">
	<?php echo $form->renderField('additional_info'); ?>
</div>
<div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid="">
    <div class="uk-width-1-2@m uk-flex-bottom uk-grid-item-match">
	    <?php echo $form->renderField('reservation_date'); ?>
    </div>
    <div class="uk-width-1-2@m uk-flex-bottom uk-grid-item-match">
        <div class="uk-margin">
            <button id="checkStatus" class="uk-button uk-button-default uk-width-1-1">
               <?= Text::_('COM_DNBOOKING_CHECK_AVAILABILITY_LABEL') ?>
            </button>
        </div>
    </div>
</div>
