<?php
defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('com_dnbooking.weekdashboard');

$params = ComponentHelper::getParams('com_dnbooking');
$prefix = $params->get('prefix');


?>
<form action="<?php echo Route::_('index.php?option=com_dnbooking&view=weekdashboard'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
				<?php if (empty($this->items)) : ?>
                    <div class="alert alert-info">
                        <span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
						<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                    </div>
				<?php else : ?>
                    <table class="table w-100" id="weekdashboardList">
                        <thead>
                        <tr>
                            <th scope="col" class="text-nowrap"><?php echo Text::_('COM_DNBOOKING_HEADING_DATE'); ?></th>
                            <th scope="col" class="text-nowrap"><?php echo Text::_('COM_DNBOOKING_HEADING_TIME'); ?></th>
                            <th scope="col" class="text-nowrap"><?php echo Text::_('COM_DNBOOKING_HEADING_ROOM'); ?></th>
                            <th scope="col" class="w-30"><?php echo Text::_('COM_DNBOOKING_HEADING_EXTRAS'); ?></th>
                            <th scope="col" class="w-30"><?php echo Text::_('COM_DNBOOKING_HEADING_BIRTHDAYCHILDREN'); ?></th>
                            <th scope="col" class="w-30"><?php echo Text::_('COM_DNBOOKING_HEADING_VISITORS'); ?></th>
                            <th scope="col" class="w-50"><?php echo Text::_('COM_DNBOOKING_HEADING_MEAL_TIME'); ?></th>
                            <th scope="col" class="w-20"><?php echo Text::_('COM_DNBOOKING_HEADING_CUSTOMER'); ?></th>
                            <th scope="col" class="text-nowrap"><?php echo Text::_('COM_DNBOOKING_HEADING_BOOKING_NUMBER'); ?></th>
                            <th scope="col" class="text-nowrap"><?php echo Text::_('COM_DNBOOKING_HEADING_STATE'); ?></th>

                        </tr>
                        </thead>
                        <tbody>
						<?php foreach ($this->items as $i => $item) :
							$date = HTMLHelper::_('date', $item->reservation_date, Text::_('DATE_FORMAT_LC4'));
							$time = HTMLHelper::_('date', $item->reservation_date, 'H:i');
                            $bookingId = $prefix . '-' . date('Y', strtotime($item->reservation_date)) . '-' . $item->id;
							$children = json_decode($item->additional_infos2, true);
                            ?>
                            <tr>
                                <td><?php echo $date; ?></td>
                                <td><?php echo $time; ?></td>
                                <td><?php echo $item->room_title; ?></td>
                                <td>
									<?php if (!empty($item->extras)) : ?>
										<?php foreach ($item->extras as $extra) : ?>
											<?php echo $extra['amount'] . 'x ' . $extra['name'] . '<br>'; ?>
										<?php endforeach; ?>
									<?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!empty($children)): ?>
                                        <?php foreach ($children['addinfos2_subform'] as $child) : ?>
                                            <?php foreach ($child as $key => $value) :
                                                if (DateTime::createFromFormat('Y-m-d H:i:s', $value) !== false) {
                                                    echo date('d.m.Y', strtotime($value));
                                                }
                                                else {
                                                    echo $value . ' ';
                                                }?>

                                            <?php endforeach; ?>
                                            <br />
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $visitors = json_decode($item->additional_info, true);
                                    echo Text::_('COM_DNBOOKING_TABLE_LABEL_PERSONS_WITH_PACKAGE') . ': ' . $visitors['visitorsPackage'];
                                    echo "<br />";
                                    echo Text::_('COM_DNBOOKING_TABLE_LABEL_BIRTHDAYCHILDREN') . ': ' . $visitors['birthdaychildren'];
                                    echo "<br />";
                                    echo Text::_('COM_DNBOOKING_TABLE_LABEL_PERSONS_WITHOUT_PACKAGE') . ': ' . $visitors['visitors'];
                                    ?>
                                </td>
                                <td><?php echo $item->meal_time; ?></td>
                                <td><?php echo $item->firstname . ' ' . $item->lastname; ?></td>
                                <td><?php echo $bookingId; ?></td>
                                <td>
		                            <?php switch ($item->published)
		                            {
			                            case -2:
				                            echo Text::_('COM_DNBOOKING_FIELD_RESERVATION_STATUS_TRASHED');
				                            break;
			                            case 0:
				                            echo Text::_('COM_DNBOOKING_FIELD_RESERVATION_STATUS_UNPUBLISHED');
				                            break;
			                            case 1:
				                            echo Text::_('COM_DNBOOKING_FIELD_RESERVATION_STATUS_PUBLISHED');
				                            break;
			                            case 2:
				                            echo Text::_('COM_DNBOOKING_FIELD_RESERVATION_STATUS_ARCHIVED');
				                            break;
			                            case 3:
				                            echo Text::_('COM_DNBOOKING_FIELD_RESERVATION_STATUS_DOWN_PAYMENT_LOCALE');
				                            break;
			                            case 4:
				                            echo Text::_('COM_DNBOOKING_FIELD_RESERVATION_STATUS_DOWN_PAYMENT_MADE');
				                            break;
			                            default:
				                            echo Text::_('COM_DNBOOKING_FIELD_RESERVATION_STATUS_UNPUBLISHED');
				                            break;
		                            }?>
                                </td>

                            </tr>
						<?php endforeach; ?>
                        </tbody>
                    </table>
				<?php endif; ?>
                <input type="hidden" name="task" value="">
				<?php echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>
