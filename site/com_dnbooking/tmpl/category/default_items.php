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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use DnbookingNamespace\Component\Dnbooking\Site\Helper\DnbookingHelper;
use DnbookingNamespace\Component\Dnbooking\Site\Helper\Route as DnbookingHelperRoute;
use DnbookingNamespace\Component\Dnbooking\Site\Helper\RouteHelper;

HTMLHelper::_('behavior.core');
$canDo   = DnbookingHelper::getActions('com_dnbooking', 'category', $this->category->id);
$canEdit = $canDo->get('core.edit');
$userId  = Factory::getApplication()->getIdentity()->id;
?>
<div class="com-dnbooking-category__items">
	<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
		<?php if ($this->params->get('filter_field') || $this->params->get('show_pagination_limit')) : ?>
		<fieldset class="com-dnbooking-category__filters filters btn-toolbar">
			<?php if ($this->params->get('filter_field')) : ?>
				<div class="com-dnbooking-category__filter btn-group">
					<label class="filter-search-lbl sr-only" for="filter-search">
						<span class="badge badge-warning">
							<?php echo Text::_('JUNPUBLISHED'); ?>
						</span>
						<?php echo Text::_('COM_DNBOOKING_FILTER_LABEL') . '&#160;'; ?>
					</label>
					<input
						type="text"
						name="filter-search"
						id="filter-search"
						value="<?php echo $this->escape($this->state->get('list.filter')); ?>"
						class="inputbox" onchange="document.adminForm.submit();"
						title="<?php echo Text::_('COM_DNBOOKING_FILTER_SEARCH_DESC'); ?>"
						placeholder="<?php echo Text::_('COM_DNBOOKING_FILTER_SEARCH_DESC'); ?>"
					>
				</div>
			<?php endif; ?>

			<?php if ($this->params->get('show_pagination_limit')) : ?>
				<div class="com-dnbooking-category__pagination btn-group float-right">
					<label for="limit" class="sr-only">
						<?php echo Text::_('JGLOBAL_DISPLAY_NUM'); ?>
					</label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			<?php endif; ?>
		</fieldset>
		<?php endif; ?>
		
		<?php if (empty($this->items)) : ?>
			<p>
				<?php echo Text::_('COM_DNBOOKING_NO_RESERVATIONS'); ?>
			</p>
		<?php else : ?>
			<ul class="com-dnbooking-category__list category row-striped">
				<?php foreach ($this->items as $i => $item) : ?>
                    <?php if ($this->items[$i]->published == 0) : ?>
                        <li class="row system-unpublished cat-list-row<?php echo $i % 2; ?>">
                    <?php else : ?>
                        <li class="row cat-list-row<?php echo $i % 2; ?>" >
                    <?php endif; ?>
                            <a href="<?php echo Route::_(RouteHelper::getReservationRoute($item->slug, $item->catid)); ?>">
								<?php echo $item->title; ?>
                            </a>
                        </li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

			<?php if ($canDo->get('core.create')) : ?>
				<?php echo HTMLHelper::_('reservationicon.create', $this->category, $this->category->params); ?>
			<?php endif; ?>

			<?php if ($this->params->get('show_pagination', 2)) : ?>
			<div class="com-dnbooking-category__counter w-100">
				<?php if ($this->params->def('show_pagination_results', 1)) : ?>
					<p class="counter float-right pt-3 pr-2">
						<?php echo $this->pagination->getPagesCounter(); ?>
					</p>
				<?php endif; ?>

				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
			<?php endif; ?>
			<div>
				<input type="hidden" name="filter_order" value="<?php echo $this->escape($this->state->get('list.ordering')); ?>">
				<input type="hidden" name="filter_order_Dir" value="<?php echo $this->escape($this->state->get('list.direction')); ?>">
			</div>
	</form>
</div>
