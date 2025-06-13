<?php
	use Joomla\CMS\Language\Text;
	$customer = $displayData;
?>
<div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">

	<div class="firstCard card mb-3">
		<div class="card-header card-header-reservation">
			<h3>
				<?= Text::_('COM_DNBOOKING_HEADING_CUSTOMER_HEADLINE'); ?>

			</h3>

            <?php if($customer->id): ?>
                <button type="button" class="btn btn-secondary btn-sm apply-sample-data" data-joomla-dialog='{"popupType": "iframe", "id":"test", "width":"80vw", "height": "80vh", "textHeader":"<?= Text::sprintf('COM_DNBOOKING_EDIT_CUSTOMER_SPRINTF', ' (ID: ' .$customer->id . ')' . $customer->firstname . ' ' . $customer->lastname); ?>", "src": "<?= 'index.php?option=com_dnbooking&view=customer&tmpl=component&layout=modal&task=customer.edit&id=' . (int) $customer->id ?>"}'>
                    <span class="icon-edit" aria-hidden="true"></span>
                    <?= Text::_('COM_DNBOOKING_LABEL_EDIT'); ?>
                </button>
            <?php else: ?>
                <button type="button" class="btn btn-secondary btn-sm apply-sample-data" data-joomla-dialog='{"popupType": "iframe", "id":"test", "width":"80vw", "height": "80vh", "textHeader":"<?= Text::_('COM_DNBOOKING_NEW_CUSTOMER'); ?>", "src": "<?= 'index.php?option=com_dnbooking&view=customer&tmpl=component&layout=modal&task=customer.add' ?>"}'>
                    <span class="icon-edit" aria-hidden="true"></span>
                    <?= Text::_('COM_DNBOOKING_NEW_CUSTOMER'); ?>
                </button>
            <?php endif; ?>

		</div>
		<div class="card-body">
			<p class="card-text"><strong><?= Text::_('COM_DNBOOKING_FIELD_ID_LABEL') ?>:</strong> <?= $customer->id; ?></p>
			<hr/>
			<p class="card-text"><strong><?= Text::_('COM_DNBOOKING_FIELD_FIRSTNAME_LABEL') ?>:</strong> <?= $customer->firstname; ?></p>
			<hr/>
			<p class="card-text"><strong><?= Text::_('COM_DNBOOKING_FIELD_LASTNAME_LABEL') ?>:</strong> <?= $customer->lastname; ?></p>
			<hr/>
			<p class="card-text"><strong><?= Text::_('COM_DNBOOKING_FIELD_EMAIL_LABEL') ?>:</strong> <?= $customer->email; ?></p>
			<hr/>
			<p class="card-text"><strong><?= Text::_('COM_DNBOOKING_FIELD_PHONE_LABEL') ?>:</strong> <?= $customer->phone; ?></p>
			<hr/>
			<p class="card-text"><strong><?= Text::_('COM_DNBOOKING_FIELD_ADDRESS_LABEL') ?>:</strong> <?= $customer->address; ?></p>
			<hr/>
			<p class="card-text"><strong><?= Text::_('COM_DNBOOKING_FIELD_CITY_LABEL') ?>:</strong> <?= $customer->city; ?></p>
			<hr/>
			<p class="card-text"><strong><?= Text::_('COM_DNBOOKING_FIELD_ZIP_LABEL') ?>:</strong> <?= $customer->zip; ?></p>
			<hr/>
			<p class="card-text"><strong><?= Text::_('COM_DNBOOKING_FIELD_COUNTRY_LABEL') ?>:</strong> <?= $customer->country; ?></p>
			<hr/>
		</div>
	</div>

</div>
