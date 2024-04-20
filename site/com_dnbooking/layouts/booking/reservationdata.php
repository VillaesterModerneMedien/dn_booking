<?php

use Joomla\CMS\Language\Text;
$form = $displayData;
$fieldsets = $form->getFieldsets();
?>

<div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid="">
    <div class="uk-width-1-3@m uk-flex-bottom uk-grid-item-match">
	    <?php echo $form->renderField('reservation_date'); ?>
    </div>
    <div class="uk-width-1-3@m uk-flex-bottom uk-grid-item-match">
	    <?php echo $form->renderField('persons_count'); ?>
    </div>
    <div class="uk-width-1-3@m uk-flex-bottom uk-grid-item-match">
		leer
    </div>
    <div class="uk-width-1-3@m uk-flex-bottom uk-grid-item-match">
        <div class="uk-margin">
            <button id="checkStatus" class="uk-button uk-button-default uk-width-1-1">
                Verfügbarkeit prüfen
            </button>
        </div>
    </div>
</div>

<div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid="">
	<?php echo $form->renderField('additional_info'); ?>
</div>
