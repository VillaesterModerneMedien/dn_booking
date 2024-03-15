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
        <div class="form-group">
            <label for="date"><?php echo Text::_('COM_DNBOOKING_DATE_LABEL'); ?></label>
            <input type="date" class="form-control checkrooms" id="date" name="date">
        </div>
        <div class="form-group">
            <label for="visitors"><?php echo Text::_('COM_DNBOOKING_VISITORS_LABEL'); ?></label>
            <input type="number" class="form-control checkrooms" id="visitors" name="visitors" value="0">
        </div>
        <div class="form-group">
            <label for="birthdaychildren"><?php echo Text::_('COM_DNBOOKING_BIRTHDAYCHILDREN_LABEL'); ?></label>
            <input type="number" class="form-control" id="birthdaychildren" name="birthdaychildren" value="0">
        </div>
    </section>

    <section id="rooms" class="rooms uk-section uk-section-default">
		<?php echo LayoutHelper::render('booking.roomlist', $this->rooms); ?>
    </section>

    <section id="customer" class="customer uk-section  uk-section-default">
	    <?php echo LayoutHelper::render('booking.customer'); ?>
    </section>

	<button type="button" id="checkBooking" class="btn btn-primary"><?php echo Text::_('COM_DNBOOKING_SEARCH'); ?></button>
</form>
