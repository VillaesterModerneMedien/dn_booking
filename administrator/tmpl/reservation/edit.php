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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\User\UserHelper;

$app   = Factory::getApplication();
$input = $app->input;

$wa = $this->document->getWebAssetManager();
$wa->useScript('joomla.dialog-autocreate');
$wa->useStyle('com_dnbooking.reservation.admin');
$reservation     = $this->item;
$customer = $this->customer;

$layout  = 'details';
$tmpl = $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';

$reservationId = $reservation->id;
if (empty($this->form->getValue('reservation_token')))
{
	$reservationToken = UserHelper::genRandomPassword(32);
	$this->form->setValue('reservation_token', null, $reservationToken);
}
?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tabs = document.querySelectorAll('#myTabs .nav-link');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabs.forEach(tab => {
            tab.addEventListener('click', function (e) {
                e.preventDefault();

                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const targetPane = document.querySelector(tab.getAttribute('data-bs-target'));
                tabPanes.forEach(pane => {
                    pane.classList.remove('show');
                    pane.classList.remove('active');
                });

                targetPane.classList.add('show');
                targetPane.classList.add('active');
            });
        });
    });

</script>

<div class="dnbooking dnbooking_reservation">
    <form action="<?php echo Route::_('index.php?option=com_dnbooking&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm">
        <div class="row">

            <div class="col-md-6">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-header"><h3><?= Text::_('COM_DNBOOKING_HEADLINE_NEW_RESERVATION'); ?></h3>
                    </div>
                    <div class="card-body">
                        <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
                            <div class="container-fluid">
                                <div class="collapse navbar-collapse" id="navbarNav">
                                    <ul class="navbar-nav nav-tabs" id="myTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="tab1-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab1" type="button" role="tab" aria-controls="tab1"
                                                    aria-selected="true"><?= Text::_('COM_DNBOOKING_HEADING_RESERVATION_VIEW_HEADLINE'); ?></button>
                                        </li>

                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab2-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab2" type="button" role="tab" aria-controls="tab2"
                                                    aria-selected="false">
												<?php if(!$reservationId): ?>
													<?= Text::_('COM_DNBOOKING_NEW_CUSTOMER'); ?>
												<?php else: ?>
													<?= Text::_('COM_DNBOOKING_HEADING_CUSTOMER_EDIT_HEADLINE'); ?>
												<?php endif; ?>
                                            </button>
                                        </li>

                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab3-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab3" type="button" role="tab" aria-controls="tab3"
                                                    aria-selected="false"><?= Text::_('COM_DNBOOKING_HEADING_EXTRAS_EDIT_HEADLINE'); ?></button>
                                        </li>

                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab4-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab4" type="button" role="tab" aria-controls="tab4"
                                                    aria-selected="false"><?= Text::_('COM_DNBOOKING_HEADING_ADDINFOS2_HEADLINE'); ?></button>
                                        </li>

                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab5-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab5" type="button" role="tab" aria-controls="tab5"
                                                    aria-selected="false"><?= Text::_('COM_DNBOOKING_HEADING_NOTES_HEADLINE'); ?></button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </nav>

                        <div class="tab-content" id="meineTabsContent">

							<?php echo LayoutHelper::render('reservation.reservation_tab', $this); ?>
							<?php echo LayoutHelper::render('reservation.customer_tab', $customer); ?>
							<?php echo LayoutHelper::render('reservation.extras_tab', $this); ?>
							<?php echo LayoutHelper::render('reservation.addinfos2_tab', $this); ?>
							<?php echo LayoutHelper::render('reservation.notes_tab', $this); ?>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div hidden>

			<?php echo $this->form->renderFieldset('mybasic'); ?>

        </div>
		<?php echo HTMLHelper::_('form.token'); ?>
        <input type="hidden" name="task" value="">
    </form>
</div>
