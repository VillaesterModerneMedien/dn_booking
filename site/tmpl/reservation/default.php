<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\User\UserHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('com_dnbooking.script');
$wa->useStyle('com_dnbooking.booking');
$componentParams = ComponentHelper::getParams('com_dnbooking');
$frontendParams ['minDate'] = $componentParams->get('minDate');
$frontendParams ['maxDate'] = $componentParams->get('maxDate');

$translations = [
	'timeclosed' => Text::_('COM_DNBOOKING_TIME_CLOSED'),
	'dayclosed' => Text::_('COM_DNBOOKING_DAY_CLOSED'),
    'enterdate' => Text::_('COM_DNBOOKING_ENTER_DATE'),
    'opened' => Text::_('COM_DNBOOKING_OPENED'),
    'till' => Text::_('COM_DNBOOKING_TILL'),
    'btn_ok' => Text::_('COM_DNBOOKING_BTN_OK'),
    'btn_cancel' => Text::_('COM_DNBOOKING_BTN_CANCEL'),
    'btn_accept' => Text::_('COM_DNBOOKING_BTN_ACCEPT'),
];

Factory::getApplication()->getDocument()->addScriptOptions('com_dnbooking.translations', $translations);
Factory::getApplication()->getDocument()->addScriptOptions('com_dnbooking.frontendparams', $frontendParams);

$dateformat = 'Y-m-d';
$params = ComponentHelper::getParams('com_dnbooking');
$priceRegular = $params->get('packagepriceregular');
$priceCustom = $params->get('packagepricecustom');
$reservationToken = UserHelper::genRandomPassword(32);
$this->form->setValue('reservation_token', null, $reservationToken);
?>

<form class="uk-form-stacked"
         action="/index.php?option=com_dnbooking&task=reservation.sendForm"
         name="reservationForm"
         id="reservationForm"
         method="POST">

    <section id="bookingTimes" class="booking uk-section uk-section-default" data-step="1">
        <h2><?php echo Text::_('COM_DNBOOKING_RESERVATIONDATA'); ?></h2>
	    <?php echo LayoutHelper::render('reservation.moduletop', $this->form); ?>
	    <?php echo LayoutHelper::render('reservation.reservationdata', $this->form); ?>
    </section>

    <section id="rooms" class="rooms uk-section uk-section-default" data-step="2">
        <h2><?php echo Text::_('COM_DNBOOKING_SELECT_ROOM'); ?></h2>
		<?php echo LayoutHelper::render('reservation.roomlist', $this->form); ?>
    </section>

    <section id="extras" class="rooms uk-section uk-section-default" data-step="3">
        <h2><?php echo JText::_('COM_DNBOOKING_ADD_EXTRAS'); ?></h2>
		<?php echo LayoutHelper::render('reservation.extraslist',$this->form); ?>
    </section>

    <section id="customer" class="customer uk-section uk-section-default" data-step="4">
        <h2><?php echo JText::_('COM_DNBOOKING_ENTER_DETAILS'); ?></h2>
		<?php echo LayoutHelper::render('reservation.customer',  $this->form); ?>
    </section>

    <section id="sendButton" class="customer uk-section uk-section-default" data-step="5">
        <button type="button" id="checkBooking" class="uk-button uk-button-default"><?php echo JText::_('COM_DNBOOKING_CHECKBOOKING_LABEL'); ?></button>
    </section>

    <section class="stickyBottom uk-section uk-section-default" id="bookingNav">
        <div class="uk-grid uk-grid-small uk-child-width-1-3@m " uk-grid>
            <div>
            </div>
            <div>

            </div>
            <div>
                <button class="uk-button uk-button-primary"  id="dnnext" dnnext>
                    Weiter
                </button>
            </div>
        </div>
    </section>

    <?= $this->form->renderFieldset('hiddenFields'); ?>
    <input type="hidden" name="option" value="com_dnbooking" />
	<?php echo HTMLHelper::_('form.token'); ?>

</form>


