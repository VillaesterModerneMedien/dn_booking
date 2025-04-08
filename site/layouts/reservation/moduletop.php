<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

$form = $displayData;
$fieldsets = $form->getFieldsets();

$app = Factory::getApplication();
$input = $app->input;
$document = Factory::getApplication()->getDocument();

$renderer = $document->loadRenderer('modules');
$options = array('style' => 'section');
$moduleIndividuell = $renderer->render("booking-top", $options, null);

?>
<?= $moduleIndividuell; ?>

