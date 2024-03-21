<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('com_dnbooking.calendar');

$user      = Factory::getApplication()->getIdentity();
$userId    = $user->get('id');

?>
<form action="<?php echo Route::_('index.php?option=com_dnbooking&view=openinghours'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="calendar-container" class="calendar-container">
                <div id="selectDate">
                    <div>
                        <input type="number" name="year" id="yearSelect" class="form-control" step="1" min="<?php echo date("Y"); ?>" value="<?php echo date("Y"); ?>"/>
                    </div>
                    <div >
                        <select name="month" id="monthSelect" class="form-control">
                            <option value="0"><?php echo Text::_('COM_DNBOOKING_CALENDAR_SELECT_MONTH')?></option>
                            <option value="1"><?php echo Text::_('COM_DNBOOKING_CALENDAR_JANUARY')?></option>
                            <option value="2"><?php echo Text::_('COM_DNBOOKING_CALENDAR_FEBRUARY')?></option>
                            <option value="3"><?php echo Text::_('COM_DNBOOKING_CALENDAR_MARCH')?></option>
                            <option value="4"><?php echo Text::_('COM_DNBOOKING_CALENDAR_APRIL')?></option>
                            <option value="5"><?php echo Text::_('COM_DNBOOKING_CALENDAR_MAY')?></option>
                            <option value="6"><?php echo Text::_('COM_DNBOOKING_CALENDAR_JUNE')?></option>
                            <option value="7"><?php echo Text::_('COM_DNBOOKING_CALENDAR_JULY')?></option>
                            <option value="8"><?php echo Text::_('COM_DNBOOKING_CALENDAR_AUGUST')?></option>
                            <option value="9"><?php echo Text::_('COM_DNBOOKING_CALENDAR_SEPTEMBER')?></option>
                            <option value="10"><?php echo Text::_('COM_DNBOOKING_CALENDAR_OCTOBER')?></option>
                            <option value="11"><?php echo Text::_('COM_DNBOOKING_CALENDAR_NOVEMBER')?></option>
                            <option value="12"><?php echo Text::_('COM_DNBOOKING_CALENDAR_DECEMBER')?></option>
                        </select>
                    </div>
                </div>
                <div id="calendar">

                </div>

                <?php echo HTMLHelper::_('form.token'); ?>
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
			</div>
		</div>
	</div>
</form>
