<?php
	use Joomla\CMS\Language\Text;
	$reservation = $displayData;
    $form = $displayData->get('form');
?>
<div class="tab-pane" id="tab5" role="tabpanel" aria-labelledby="tab5-tab">
    <div class="firstCard card mb-3">
        <div class="card-header">
            <h3><?= Text::_('COM_DNBOOKING_HEADING_NOTES_HEADLINE'); ?></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
					<?php echo $form->renderFieldset('reservationfieldset_notes'); ?>
                </div>
            </div>
        </div>
    </div>

</div>
