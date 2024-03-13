<?php
use Joomla\CMS\Language\Text;
?>
<div class="uk-container">
    <div class="uk-grid uk-child-width-1-2@m" uk-grid>
        <div>
            <div class="uk-margin">
                <label class="uk-form-label" for="firstname"><?= Text::_('COM_DNBOOKING_FIRSTNAME_LABEL'); ?></label>
                <div class="uk-form-controls uk-inline">
                    <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: user"></span>
                    <input class="uk-input" id="firstname" type="text" placeholder="<?= Text::_('COM_DNBOOKING_FIRSTNAME_LABEL'); ?>" required>
                </div>
            </div>
        </div>

        <div>
            <div class="uk-margin">
                <label class="uk-form-label" for="lastname"><?= Text::_('COM_DNBOOKING_LASTNAME_LABEL'); ?></label>
                <div class="uk-form-controls uk-inline">
                    <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: user"></span>
                    <input class="uk-input" id="lastname" type="text" placeholder="<?= Text::_('COM_DNBOOKING_LASTNAME_LABEL'); ?>" required>
                </div>
            </div>
        </div>

        <div>
            <div class="uk-margin">
                <label class="uk-form-label" for="email"><?= Text::_('COM_DNBOOKING_EMAIL_LABEL'); ?></label>
                <div class="uk-form-controls uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: mail"></span>
                    <input class="uk-input" id="email" type="email" placeholder="<?= Text::_('COM_DNBOOKING_EMAIL_LABEL'); ?>" required>
                </div>
            </div>
        </div>

        <div>
            <div class="uk-margin">
                <label class="uk-form-label" for="phone"><?= Text::_('COM_DNBOOKING_PHONE_LABEL'); ?></label>
                <div class="uk-form-controls uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: receiver"></span>
                    <input class="uk-input" id="phone" type="text" placeholder="<?= Text::_('COM_DNBOOKING_PHONE_LABEL'); ?>">
                </div>
            </div>
        </div>
    </div>
</div>
