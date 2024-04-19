<?php
use Joomla\CMS\Language\Text;
$form = $displayData;
?>

<div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid>
    <div class="uk-width-1-3@m uk-flex-bottom uk-grid-item-match">
		<?php echo $form->renderField('salutation'); ?>
    </div>
    <div class="uk-width-1-3@m uk-flex-bottom uk-grid-item-match">
		<?php echo $form->renderField('firstname'); ?>
    </div>
    <div class="uk-width-1-3@m uk-flex-bottom uk-grid-item-match">
		<?php echo $form->renderField('lastname'); ?>
    </div>
</div>

<div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid>
    <div class="uk-width-1-3@m uk-flex-bottom uk-grid-item-match">
		<?php echo $form->renderField('address'); ?>
    </div>
    <div class="uk-width-1-3@m uk-flex-bottom uk-grid-item-match">
		<?php echo $form->renderField('city'); ?>
    </div>
    <div class="uk-width-1-3@m uk-flex-bottom uk-grid-item-match">
		<?php echo $form->renderField('zip'); ?>
    </div>
</div>

<div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid>
    <div class="uk-width-1-2@m uk-flex-bottom uk-grid-item-match">
		<?php echo $form->renderField('email'); ?>
    </div>
    <div class="uk-width-1-2@m uk-flex-bottom uk-grid-item-match">
		<?php echo $form->renderField('phone'); ?>
    </div>
</div>

<div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid>

    <div class="uk-width-1-1@m uk-flex-bottom uk-grid-item-match">
	    <?php echo $form->renderField('customer_notes'); ?>
    </div>
</div>
