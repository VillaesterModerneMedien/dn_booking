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
use Joomla\CMS\Session\Session;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('multiselect');

$user      = Factory::getApplication()->getIdentity();
$userId    = $user->get('id');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';

if ($saveOrder && !empty($this->items)) {
	$saveOrderingUrl = 'index.php?option=com_dnbooking&task=rooms.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
	HTMLHelper::_('draggablelist.draggable');
}

?>
<form action="<?php echo Route::_('index.php?option=com_dnbooking&view=rooms'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
	            <?php
	            // Search tools bar
	            echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));
	            ?>
                <?php if (empty($this->items)) : ?>
                    <div class="alert alert-info">
						<span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
						<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
					</div>
                <?php else : ?>

                    <table class="table" id="roomsList">
                        <caption class="visually-hidden">
                            <?php echo Text::_('COM_DNBOOKING_TABLE_CAPTION'); ?>,
                            <span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
                            <span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
                        </caption>
                        <thead>
                            <tr>
                                <td class="w-1 text-center">
                                    <?php echo HTMLHelper::_('grid.checkall'); ?>
                                </td>
                                <th scope="col" class="w-1 text-center d-none d-md-table-cell">
		                            <?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-sort'); ?>
                                </th>
                                <th scope="col">
                                    <?php echo HTMLHelper::_('searchtools.sort', Text::_('COM_DNBOOKING_HEADING_ROOM_TITLE'), 'a.title', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-1 text-center">
                                    <?php echo HTMLHelper::_('searchtools.sort', Text::_('JSTATUS'), 'a.published', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-5 d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody<?php if ($saveOrder) :
	                        ?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" data-nested="true"<?php
                        endif; ?>>
                        <?php foreach ($this->items as $i => $item) :
                            $canCreate  = $user->authorise('core.create',     'com_dnbooking.room.' . $item->id);
							$canEdit    = $user->authorise('core.edit',       'com_dnbooking.room.' . $item->id);
                            $canEditOwn = $user->authorise('core.edit.own',   'com_dnbooking.room.' . $item->id);
							$canChange  = $user->authorise('core.edit.state', 'com_dnbooking.room.' . $item->id);


                        ?>
                            <tr class="row<?php echo $i % 2; ?>"  data-draggable-group="raum">
                                <td class="center">
                                    <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                                </td>
                                <td class="text-center d-none d-md-table-cell">
		                            <?php
		                            $iconClass = '';
		                            if (!$canChange) {
			                            $iconClass = ' inactive';
		                            } elseif (!$saveOrder) {
			                            $iconClass = ' inactive" title="' . Text::_('JORDERINGDISABLED');
		                            }
		                            ?>
                                    <span class="sortable-handler<?php echo $iconClass ?>">
                                        <span class="icon-ellipsis-v" aria-hidden="true"></span>
                                    </span>
		                            <?php if ($canChange && $saveOrder) : ?>
                                        <input type="text" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order hidden">
		                            <?php endif; ?>
                                </td>
                                <th scope="row" class="has-context">
                                    <div>
                                        <?php if ($canEdit) : ?>
                                        <a class="hasTooltip" href="<?php echo Route::_('index.php?option=com_dnbooking&task=room.edit&id=' . (int) $item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->title); ?>">
                                            <?php echo $item->title; ?>
                                        </a>
                                        <?php else : ?>
                                            <?php echo $this->escape($item->title); ?>
                                        <?php endif; ?>

                                        <?php if (!empty($item->note)) : ?>
                                            <div class="small">
                                                <?php echo Text::sprintf('JGLOBAL_LIST_NOTE', $this->escape($item->note)); ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="small">
                                            <?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
                                        </div>

                                    </div>
                                </th>
                                <td>
                                    <?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'rooms.', $canChange, 'cb'); ?>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <?php echo $item->id; ?>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                    <?php // load the pagination.
					echo $this->pagination->getListFooter();
                    ?>

                <?php endif; ?>
                <?php echo HTMLHelper::_('form.token'); ?>
                <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
                <input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
			</div>
		</div>
	</div>
</form>
