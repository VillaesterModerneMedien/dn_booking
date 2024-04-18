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
<form class="uk-form-stacked"
         action="/index.php?option=com_dnbooking&task=booking.sendForm"
         name="bookingForm"
         id="bookingForm"
         method="POST">
    <section id="bookingTimes" class="booking uk-section uk-section-default" data-step="1">
        <h2><?php echo JText::_('COM_DNBOOKING_RESERVATIONDATA'); ?></h2>
	    <?php echo LayoutHelper::render('booking.reservationdata'); ?>
        <div id="childrenContainer" class="uk-grid tm-grid-expand uk-grid-margin" uk-grid>
	    <?php echo LayoutHelper::render('booking.childrencontainer'); ?>
        </div>
    </section>
    <section id="rooms" class="rooms uk-section uk-section-default" data-step="2">
        <h2><?php echo JText::_('COM_DNBOOKING_SELECT_ROOM'); ?></h2>
		<?php echo LayoutHelper::render('booking.roomlist', $this->rooms); ?>
    </section>
    <section id="extras" class="rooms uk-section uk-section-default" data-step="3">
        <h2><?php echo JText::_('COM_DNBOOKING_ADD_EXTRAS'); ?></h2>
		<?php echo LayoutHelper::render('booking.extraslist', $this->extras); ?>
    </section>
    <section id="customer" class="customer uk-section uk-section-default" data-step="4">
        <h2><?php echo JText::_('COM_DNBOOKING_ENTER_DETAILS'); ?></h2>
		<?php echo LayoutHelper::render('booking.customer'); ?>
        <button type="button" id="checkBooking" class="uk-button uk-button-default"><?php echo JText::_('COM_DNBOOKING_CHECKBOOKING_LABEL'); ?></button>
    </section>
    <section data-step="2" class="stickyBottom uk-section uk-section-default">
        <div class="uk-grid uk-grid-small uk-child-width-1-3@m " uk-grid>
            <div>
                <button class="uk-button uk-button-primary" dnprev>
                    zurück
                </button>
            </div>
            <div>

            </div>
            <div>
                <button class="uk-button uk-button-primary" dnnext>
                    Weiter
                </button>
            </div>
        </div>
    </section>

</form>
