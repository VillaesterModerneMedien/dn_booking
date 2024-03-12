<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('com_dnbooking.script');
// $wa->useAsset('script', 'jquery');
?>
<div class="com-dnbooking-category">
	<?php
		$this->subtemplatename = 'items';
		echo LayoutHelper::render('joomla.content.category_default', $this);
	?>
</div>
