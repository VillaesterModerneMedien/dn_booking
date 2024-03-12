<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('com_dnbooking.script');
// $wa->useAsset('script', 'jquery');
?>
<div class="com-dnbooking-featured list-featured<?php echo $this->pageclass_sfx; ?>">
<?php if ($this->params->get('show_page_heading') != 0) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
<?php endif; ?>

<?php echo $this->loadTemplate('items'); ?>

<?php if ($this->pagination->pagesTotal > 1) : ?>
	<div class="com-dnbooking-featured__pagination w-100">
        <p class="counter float-right pt-3 pr-2">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>

        <?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php endif; ?>
</div>
