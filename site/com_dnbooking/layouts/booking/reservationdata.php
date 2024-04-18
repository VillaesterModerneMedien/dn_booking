<?php

use Joomla\CMS\Language\Text;
$form = $displayData->get('form');
?>
<div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid="">
    <div class="uk-width-1-3@m uk-first-column uk-flex-bottom uk-grid-item-match">
        <div class="uk-margin">
            <div>
                <label class="uk-form-label" for="date"><?php echo Text::_('COM_DNBOOKING_DATE_LABEL'); ?></label>
                <div class="uk-form-controls">
	                <?php echo $this->form->renderField('reservation_date'); ?>
                    <!-- >input type="date" class="uk-input" id="date" name="date"> -->
                </div>
            </div>
        </div>
    </div>
    <div class="uk-width-1-3@m uk-flex-bottom uk-grid-item-match">
        <div class="uk-margin">
            <div>
                <label class="uk-form-label" for="time"><?php echo Text::_('COM_DNBOOKING_TIME_LABEL'); ?></label>
                <div class="uk-form-controls">
                    <input type="time" class="uk-input" id="time" name="time">
                </div>
            </div>
        </div>
    </div>
    <div class="uk-width-1-3@m uk-flex-bottom uk-grid-item-match">
        <div class="uk-margin">
            <button id="checkStatus" class="uk-button uk-button-default uk-width-1-1">
                Verfügbarkeit prüfen
            </button>
        </div>
    </div>
</div>
<div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid>
    <div class="uk-width-1-3@m">
        <div class="uk-margin">
            <div>
                <label class="uk-form-label"
                       for="visitorsPackage"><?php echo Text::_('COM_DNBOOKING_VISITORSPACKAGE_LABEL'); ?></label>
                <div class="uk-form-controls">
                    <input type="number" class="uk-input" id="visitorsPackage" name="visitorsPackage" value="0">
                </div>
            </div>
        </div>
    </div>
    <div class="uk-width-1-3@m uk-first-column">
        <div class="uk-margin">
            <div>
                <label class="uk-form-label"
                       for="visitors"><?php echo Text::_('COM_DNBOOKING_VISITORS_LABEL'); ?></label>
                <div class="uk-form-controls">
                    <input type="number" class="uk-input checkrooms" id="visitors" name="visitors" value="0">
                </div>
            </div>
        </div>
    </div>

    <div class="uk-width-1-3@m">
        <div class="uk-margin">
            <div>
                <label class="uk-form-label"
                       for="birthdaychildren"><?php echo Text::_('COM_DNBOOKING_BIRTHDAYCHILDREN_LABEL'); ?></label>
                <div class="uk-form-controls">
                    <input type="number" class="uk-input" id="birthdaychildren" name="birthdaychildren" value="0" min="0" max="3">
                </div>
            </div>
        </div>
    </div>
</div>
