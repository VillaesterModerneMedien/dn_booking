<?php
	use Joomla\CMS\Language\Text;
	$reservation = $displayData;
    $reservationItem = $reservation->get('item');
    $form = $reservation->get('form');
?>
<div class="tab-pane addInfos2Tab" id="tab4" role="tabpanel" aria-labelledby="tab4-tab">
    <div class="firstCard card text-white bg-secondary mb-3">
        <div class="card-header">
            <h3><?= Text::_('COM_DNBOOKING_HEADING_ADDINFOS2_HEADLINE'); ?></h3>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-12">
	                <?php if($reservationItem->additional_infos2 == 'null'): ?>
                        <div class="alert alert-dismissible alert-warning">
	                        <?= Text::_('COM_DNBOOKING_NO_ADDINFOS2'); ?>
                        </div>

	                <?php endif; ?>

					<?php echo $form->renderFieldset('reservationfieldset_data3'); ?>
                </div>

            </div>

        </div>

    </div>

</div>
