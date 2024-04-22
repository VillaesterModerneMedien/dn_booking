<?php

use Joomla\CMS\Language\Text;

?>

<div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid="" id="childExample" hidden>
    <div class="uk-width-1-3@m">
        <div class="uk-margin">
            <div>
                <label class="uk-form-label"
                       for="childname"><?php echo Text::_('COM_DNBOOKING_CHILDNAME_LABEL'); ?></label>
                <div class="uk-form-controls">
                    <input type="text" class="uk-input" id="childname" name="childname">
                </div>
            </div>
        </div>
    </div>
    <div class="uk-width-1-3@m uk-first-column">
        <div class="uk-margin">
            <div>
                <label class="uk-form-label"
                       for="childdate"><?php echo Text::_('COM_DNBOOKING_CHILDDATE_LABEL'); ?></label>
                <div class="uk-form-controls">
                    <input type="date" class="uk-input" id="childdate" name="childdate" value="0">
                </div>
            </div>
        </div>
    </div>

    <div class="uk-width-1-3@m">
        <div class="uk-margin">
            <div>
                <label class="uk-form-label" for="childgender"><?= Text::_('COM_DNBOOKING_CHILDGENDER_LABEL'); ?></label>
                <div class="uk-form-controls">
                    <select class="uk-select" id="childgender" name="childgender">
                        <option value="Männlich">Männlich</option>
                        <option value="Weiblich">Weiblich</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
