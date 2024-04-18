<?php
use Joomla\CMS\Language\Text;
?><div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid>
    <div class="uk-width-1-3@m uk-first-column uk-flex-bottom uk-grid-item-match">
        <div class="uk-margin">
            <label class="uk-form-label" for="salutation"><?= Text::_('COM_DNBOOKING_SALUTATION_LABEL'); ?></label>
            <div class="uk-form-controls">
                <select class="uk-select" id="salutation" name="salutation">
                    <option value="Herr">Herr</option>
                    <option value="Frau">Frau</option>
                </select>
            </div>
        </div>
    </div>
    <div class="uk-width-1-3@m uk-flex-bottom uk-grid-item-match">
        <div class="uk-margin">
            <label class="uk-form-label" for="firstname"><?= Text::_('COM_DNBOOKING_FIRSTNAME_LABEL'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="firstname" type="text" name="firstname">
            </div>
        </div>
    </div>
    <div class="uk-width-1-3@m uk-flex-bottom uk-grid-item-match">
        <div class="uk-margin">
            <label class="uk-form-label" for="lastname"><?= Text::_('COM_DNBOOKING_LASTNAME_LABEL'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="lastname" type="text" name="lastname">
            </div>
        </div>
    </div>
</div>

<div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid>
    <div class="uk-width-1-3@m uk-first-column uk-flex-bottom uk-grid-item-match">
        <div class="uk-margin">
            <label class="uk-form-label" for="street"><?= Text::_('COM_DNBOOKING_STREET_LABEL'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="street" type="text" name="street">
            </div>
        </div>
    </div>
    <div class="uk-width-1-3@m uk-flex-bottom uk-grid-item-match">
        <div class="uk-margin">
            <label class="uk-form-label" for="city"><?= Text::_('COM_DNBOOKING_CITY_LABEL'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="city" type="text" name="city">
            </div>
        </div>
    </div>
    <div class="uk-width-1-3@m uk-first-column uk-flex-bottom uk-grid-item-match">
        <div class="uk-margin">
            <label class="uk-form-label" for="zipcode"><?= Text::_('COM_DNBOOKING_ZIPCODE_LABEL'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="zipcode" type="text" name="zipcode">
            </div>
        </div>
    </div>
</div>

<div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid>

    <div class="uk-width-1-2@m uk-flex-bottom uk-grid-item-match">
        <div class="uk-margin">
            <label class="uk-form-label" for="email"><?= Text::_('COM_DNBOOKING_EMAIL_LABEL'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="email" type="email" name="email">
            </div>
        </div>
    </div>
    <div class="uk-width-1-2@m uk-first-column uk-flex-bottom uk-grid-item-match">
        <div class="uk-margin">
            <label class="uk-form-label" for="phone"><?= Text::_('COM_DNBOOKING_PHONE_LABEL'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="phone" type="text" name="phone">
            </div>
        </div>
    </div>
</div>

<div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid>

    <div class="uk-width-1-1@m uk-flex-bottom uk-grid-item-match">
        <div class="uk-margin">
            <label class="uk-form-label" for="comments"><?= Text::_('COM_DNBOOKING_COMMENTS_LABEL'); ?></label>
            <div class="uk-form-controls">
                <textarea class="uk-textarea" id="comments" rows="5" name="comments"></textarea>
            </div>
        </div>
    </div>
</div>



