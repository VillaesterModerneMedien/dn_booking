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
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;
use DnbookingNamespace\Component\Dnbooking\Site\Helper\RouteHelper;

HTMLHelper::_('behavior.core');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<div class="com-dnbooking-list__items">
	<?php if (empty($this->items)) : ?>
		<p class="com-dnbooking-items__message"> <?php echo Text::_('COM_DNBOOKING_NO_RESERVATIONS'); ?>	 </p>
	<?php else : ?>

        <?php foreach ($this->items as $i => $item) : ?>
            <p>
                <a href="<?php echo Route::_(RouteHelper::getReservationRoute($item->slug, $item->catid)); ?>">
                    <?php echo $item->title; ?>
                </a>
            </p>
        <?php endforeach; ?>

	<?php endif; ?>
</div>
