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
$wa->useScript('table.columns')
	->useScript('multiselect');

$user   = Factory::getApplication()->getIdentity();
$userId = $user->get('id');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

?>
<form action="<?php echo Route::_('index.php?option=com_dnbooking&view=reservations'); ?>" method="post"
      name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
				<?php
				// Search tools bar
				echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));
				?>
				<?php if (empty($this->items)) : ?>
                    <div class="alert alert-info">
                        <span class="icon-info-circle" aria-hidden="true"></span><span
                                class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
						<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                    </div>
				<?php else : ?>

                    <table class="table" id="reservationsList">
                        <caption class="visually-hidden">
							<?php echo Text::_('COM_DNBOOKING_TABLE_CAPTION'); ?>,
                            <span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
                            <span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
                        </caption>
                        <thead>
                        <tr>
                            <th class="w-1 text-center">
								<?php echo HTMLHelper::_('grid.checkall'); ?>
                            </th>
                            <th scope="col">
								<?php echo Text::_('COM_DNBOOKING_HEADING_RESERVATION_TITLE'); ?>
                            </th>
                            <th scope="col">
								<?php echo Text::_('COM_DNBOOKING_HEADING_RESERVATION_DATE'); ?>
                            </th>
                            <th scope="col">
								<?php echo Text::_('COM_DNBOOKING_HEADING_RESERVATION_PRICE'); ?>
                            </th>
                            <th scope="col">
								<?php echo Text::_('COM_DNBOOKING_HEADING_ROOM_ID'); ?>
                            </th>
                            <th scope="col" class="w-1 text-center">
								<?php echo HTMLHelper::_('searchtools.sort', Text::_('JSTATUS'), 'a.published', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" class="w-5 d-none d-md-table-cell">
								<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
						<?php foreach ($this->items as $i => $item) :

							$id = $item->id;
							$reservationDate = HTMLHelper::_('date', $item->reservation_date, Text::_('DATE_FORMAT_LC5'));
							$customer = $item->firstname . ' ' . $item->lastname;
							$admin_notes = $item->admin_notes;
							$short_admin_notes = null;

							$orderStatus = $item->published;

							switch ($item->published)
							{
								case -2:
									$orderStatus = 'COM_DNBOOKING_FIELD_RESERVATION_STATUS_TRASHED';
									$statusIcon  = 'icon-trash';
									$btnClass    = 'btn-danger';
									break;
                                case 0:
									$orderStatus = 'COM_DNBOOKING_FIELD_RESERVATION_STATUS_UNPUBLISHED';
									$statusIcon  = 'icon-cancel';
									$btnClass    = 'btn-danger';
									break;
								case 1:
									$orderStatus = 'COM_DNBOOKING_FIELD_RESERVATION_STATUS_PUBLISHED';
									$statusIcon  = 'fa-solid fa-arrows-rotate ';
									$btnClass    = 'btn-default';
									break;
								case 2:
									$orderStatus = 'COM_DNBOOKING_FIELD_RESERVATION_STATUS_ARCHIVED';
									$statusIcon  = 'icon-check';
									$btnClass    = 'btn-success';
									break;
								case 4:
									$orderStatus = 'COM_DNBOOKING_FIELD_RESERVATION_STATUS_DOWN_PAYMENT_MADE';
									$statusIcon  = 'fa-solid fa-euro-sign';
									$btnClass    = 'btn-primary';
									break;
								default:
									$orderStatus = 'COM_DNBOOKING_FIELD_RESERVATION_STATUS_UNPUBLISHED';
									$statusIcon  = 'icon-cancel';
									$btnClass    = 'btn-primary';
									break;
							}

							if (!empty($admin_notes))
							{
								$admin_notes_without_html = strip_tags($admin_notes);
								$short_admin_notes        = substr($admin_notes_without_html, 0, 150);
							}
							$headline = Text::sprintf('COM_DNBOOKING_HEADLINE_RESERVATION_LISTING', $id, $customer);

							$canCreate  = $user->authorise('core.create', 'com_dnbooking.reservation.' . $item->id);
							$canEdit    = $user->authorise('core.edit', 'com_dnbooking.reservation.' . $item->id);
							$canEditOwn = $user->authorise('core.edit.own', 'com_dnbooking.reservation.' . $item->id);
							$canChange  = $user->authorise('core.edit.state', 'com_dnbooking.reservation.' . $item->id);

							?>
                            <tr class="row<?php echo $i % 2; ?>">
                                <td class="center">
									<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                                </td>
                                <td scope="row" class="has-context w-20">
                                    <div>
										<?php if ($canEdit) : ?>
                                            <a class="hasTooltip"
                                               href="<?php echo Route::_('index.php?option=com_dnbooking&view=reservation&layout=details&id=' . (int) $item->id); ?>"
                                               title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($headline); ?>">
												<?php echo $headline; ?>
                                            </a>
										<?php else : ?>
											<?php echo $this->escape($headline); ?>
										<?php endif; ?>

										<?php if (!empty($short_admin_notes)) : ?>
                                            <div class="small">
												<?php echo Text::_('COM_DNBOOKING_RESERVATION_INTERNAL_NOTICE') . ' ' . $this->escape($short_admin_notes); ?>
                                            </div>
										<?php endif; ?>
                                    </div>
                                </td>
                                <td class="w-10">
									<?= $reservationDate; ?>
                                </td>
                                <td class="w-10">
									<?= number_format((float) $item->reservation_price, 2, ',', '.') . ' €'; ?>
                                </td>
                                <td class="w-10">
									<?= $item->room_title . '<br> (ID: ' . $item->room_id . ')' ?>
                                </td>
                                <td class="w-10 text-center">
                                    <button class="btn <?= $btnClass ?>" type="button" disabled>
                                        <span class="<?= $statusIcon ?>" aria-hidden="true"></span>
										<?= Text::_($orderStatus) ?>
                                    </button>
                                </td>
                                <td class="d-none d-md-table-cell">
		                            <?php echo $item->id; ?>
                                </td>
                            </tr>
						<?php endforeach; ?>
                        </tbody>
                    </table>
					<?php // load the pagination.
					echo $this->pagination->getListFooter();
					?>

				<?php endif; ?>
				<?php echo HTMLHelper::_('form.token'); ?>
                <input type="hidden" name="filter_order" value=" <?php echo $this->sortColumn; ?>"/>
                <input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>"/>
                <input type="hidden" name="task" value=""/>
                <input type="hidden" name="boxchecked" value="0"/>


                <?php echo HTMLHelper::_(
                    'bootstrap.renderModal',
                    'sendMailModal',
                    [
                        'title'  => Text::_('COM_DNBOOKING_MAILS_HEADLINE'),
                        'footer' => $this->loadTemplate('batch_footer'),
                    ],
                    $this->loadTemplate('batch_body')
                ); ?>

            </div>
        </div>
    </div>
</form>
