<?php
	use Joomla\CMS\Language\Text;
	$reservation = $displayData;
    $form = $displayData->get('form');
?>
<div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
    <div class="firstCard card mb-3">
        <div class="card-header">
            <!--  <h3><?= Text::_('COM_DNBOOKING_HEADING_RESERVATION_HEADLINE'); ?></h3> -->
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
					<?php echo $form->renderFieldset('reservationfieldset_data1'); ?>
					<?php echo $form->renderFieldset('reservationfieldset_data2'); ?>
					<?php echo $form->renderFieldset('customerfieldset'); ?>
                </div>
            </div>
        </div>
    </div>

</div>
