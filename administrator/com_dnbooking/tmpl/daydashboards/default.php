<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use DnbookingNamespace\Component\Dnbooking\Administrator\Helper\DnbookingHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;


/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_dnbooking.daydashboards');

$itemsToday = DnbookingHelper::filterReservationsToday($this->items);

?>

<form action="<?php echo Route::_('index.php?option=com_dnbooking&view=daydashboards'); ?>" method="post" name="adminForm" id="adminForm">

    <div class="card-columns daydashboardsContainer">
        <?php foreach (array_chunk($itemsToday, 3) as $items) : ?>
            <?php foreach ($items as $item) : ?>
                <?php $this->item = $item; ?>
                <?php echo $this->loadTemplate('item'); ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>

    <input type="hidden" name="task" value="" />
    <?php echo HTMLHelper::_('form.token'); ?>

</form>
