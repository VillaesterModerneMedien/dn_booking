<?php
	use Joomla\CMS\Language\Text;
	//$reservation = $displayData;
    $form = $displayData->get('form');
?>
<div class="tab-pane addInfos2Tab" id="tab4" role="tabpanel" aria-labelledby="tab4-tab">
    <div class="firstCard card text-white bg-secondary mb-3">
        <div class="card-header">
            <h3><?= Text::_('COM_DNBOOKING_HEADING_ADDINFOS2_HEADLINE'); ?></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
					<?php echo $form->renderFieldset('reservationfieldset_data3'); ?>
                </div>
            </div>
        </div>
    </div>

</div>
