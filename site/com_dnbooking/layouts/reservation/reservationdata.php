<?php

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

$form = $displayData;
$fieldsets = $form->getFieldsets();
?>

<div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid="">
	<?php echo $form->renderField('additional_info'); ?>
    </div>

<div id="childrenContainer" class="uk-grid uk-grid-margin" uk-grid>
    <div class="uk-width-1-1">
        <h4 class="uk-padding-small uk-margin-remove-bottom uk-padding-remove-bottom">Geburtstagskinder</h4>
	    <?php echo LayoutHelper::render('reservation.childrencontainer', $form); ?>
    </div>

</div>
<div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid="">
    <div class="uk-width-1-2@m uk-flex-bottom uk-grid-item-match">
	    <?php echo $form->renderField('reservation_date'); ?>
    </div>

    <div class="uk-width-1-2@m uk-flex-bottom uk-grid-item-match">
        <div>
            <div class="uk-padding-small">
                <button id="checkStatus" class="uk-button uk-button-default uk-width-1-1">
                   <?= Text::_('COM_DNBOOKING_CHECK_AVAILABILITY_LABEL') ?>
                </button>
            </div>
        </div>
    </div>
</div>
