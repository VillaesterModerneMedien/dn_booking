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


/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_dnbooking.daydashboards');

$user      = Factory::getApplication()->getIdentity();
$userId    = $user->get('id');

$itemsToday = DnbookingHelper::filterReservationsToday($this->items);

?>

<form action="<?php echo JRoute::_('index.php?option=com_dnbooking&view=daydashboards'); ?>" method="post" name="adminForm" id="adminForm">

    <div class="card-columns daydashboardsContainer">
        <?php foreach (array_chunk($itemsToday, 3) as $items) : ?>
            <?php foreach ($items as $item) : ?>
                <?php $this->item = $item; ?>
                <?php echo $this->loadTemplate('item'); ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>

    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
    
</form>
