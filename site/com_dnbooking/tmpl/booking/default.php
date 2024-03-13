<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('com_dnbooking.script');
// $wa->useAsset('script', 'jquery');

?>

<form>
	<div class="form-group">
		<label for="date"><?php echo Text::_('COM_DNBOOKING_DATE'); ?></label>
		<input type="date" class="form-control" id="date" name="date">
	</div>
    <div id="rooms">
        leer
    </div>
	<button type="submit" class="btn btn-primary"><?php echo Text::_('COM_DNBOOKING_SEARCH'); ?></button>
</form>
