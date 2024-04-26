<?php

use Joomla\CMS\Language\Text;
$form = $displayData;
$fieldsets = $form->getFieldsets();
?>
<div class="uk-grid uk-grid-margin uk-padding-small" uk-grid="">
    <div class="uk-width-1-1">
        <div class="uk-alert uk-alert-success">
            <div class="el-content uk-panel uk-text-small ">
				<?php echo Text::_('COM_DNBOOKING_NOTICE_PACKAGE'); ?>
            </div>
        </div>
    </div>
</div>
<div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid="">
	<?php echo $form->renderField('additional_info'); ?>
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
