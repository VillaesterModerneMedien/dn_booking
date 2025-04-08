<?php

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

$app = Factory::getApplication();

$data = $displayData;

if (empty($data['stornoText'])) {
	$data['stornoText'] = Text::_('COM_DNBOOKING_FIELD_SEND_MAIL_STORNOTEXT_DEFAULT');
}
?>

<div id="stornoText">
    <?= $data['stornoText'];?>
</div>
