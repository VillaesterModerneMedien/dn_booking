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

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('com_dnbooking.script');
$wa->useStyle('com_dnbooking.booking');
// $wa->useAsset('script', 'jquery');

$dateformat = 'Y-m-d';
$params = ComponentHelper::getParams('com_dnbooking');
$priceRegular = $params->get('packagepriceregular');
$priceCustom = $params->get('packagepricecustom');

?>
<form class="uk-form-stacked" action="/index.php?option=com_dnbooking&task=booking.sendForm" name="bookingForm" id="bookingForm" method="POST">
    <section id="booking" class="booking uk-section uk-section-default">
        <div class="uk-grid uk-child-width-1-2@m" uk-grid>
            <div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="date"><?php echo Text::_('COM_DNBOOKING_DATE_LABEL'); ?></label>
                    <div class="uk-form-controls">
                        <input type="date" class="uk-input checkrooms" id="date" name="date">
                    </div>
                </div>
            </div>
            <div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="time"><?php echo Text::_('COM_DNBOOKING_TIME_LABEL'); ?></label>
                    <div class="uk-form-controls">
                        <input type="time" class="uk-input checkrooms" id="time" name="time">
                    </div>
                </div>
            </div>
        </div>
        <div class="uk-grid uk-child-width-1-2@m" uk-grid>
            <div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="visitors"><?php echo Text::_('COM_DNBOOKING_VISITORS_LABEL'); ?></label>
                    <div class="uk-form-controls">
                        <input type="number" class="uk-input checkrooms" id="visitors" name="visitors" value="0">
                    </div>
                </div>
            </div>
            <div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="birthdaychildren"><?php echo Text::_('COM_DNBOOKING_BIRTHDAYCHILDREN_LABEL'); ?></label>
                    <div class="uk-form-controls">
                        <input type="number" class="uk-input" id="birthdaychildren" name="birthdaychildren" value="0">
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section id="rooms" class="rooms uk-section uk-section-default">
		<?php echo LayoutHelper::render('booking.roomlist', $this->rooms); ?>
    </section>

    <section id="rooms" class="rooms uk-section uk-section-default">
		<?php echo LayoutHelper::render('booking.extraslist', $this->extras); ?>
    </section>

    <section id="customer" class="customer uk-section  uk-section-default">
	    <?php echo LayoutHelper::render('booking.customer'); ?>
    </section>

	<button type="button" id="checkBooking" class="btn btn-primary"><?php echo Text::_('COM_DNBOOKING_SEARCH'); ?></button>
</form>
