<?php
	use Joomla\CMS\Language\Text;
	$reservation = $displayData;
    $reservationItem = $reservation->get('item');
    $form = $reservation->get('form');

?>
<div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
    <div class="firstCard card mb-3">
        <div class="card-header">
            <!--  <h3><?= Text::_('COM_DNBOOKING_HEADING_RESERVATION_HEADLINE'); ?></h3> -->
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
	                <?php if($reservationItem->additional_info == 'null'): ?>
                        <div class="alert alert-dismissible alert-warning">
			                <?= Text::_('COM_DNBOOKING_NO_EXTRAS'); ?>
                        </div>

	                <?php endif; ?>
					<?php echo $form->renderFieldset('reservationfieldset_extras'); ?>
                </div>
            </div>
        </div>
    </div>

</div>
