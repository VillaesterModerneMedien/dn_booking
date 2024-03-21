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
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */

//TODO Parameter aus Komponente -> Tage geschlossen usw
$testParams = [
    'test1' => 'test1',
    'test2' => 'test2',
    'test3' => 'test3'
];

//TODO Translations auch hier übergeben
$translations = [
    'test1' => 'test1',
    'test2' => 'test2',
    'test3' => 'test3'
];

$wa = $this->document->getWebAssetManager();
$wa->useScript('com_dnbooking.calendar');
$wa->addInlineScript('const testParams = ' .  json_encode($testParams), ['position' => 'before'], [], ['com_dnbooking.calendar']);
$wa->addInlineScript('const calendarTranslations = ' .  json_encode($translations), ['position' => 'before'], [], ['com_dnbooking.calendar']);
$wa->useStyle('com_dnbooking.admin-calendar');

$user      = Factory::getApplication()->getIdentity();
$userId    = $user->get('id');

?>
<form action="<?php echo Route::_('index.php?option=com_dnbooking&view=openinghours'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
                <div class="jumbotron">
                    <h1 class="text-center"><a id="left" href="#"><i class="fa fa-chevron-left"> </i></a><span>&nbsp;</span><span id="month"> </span><span>&nbsp;</span><span id="year"> </span><span>&nbsp;</span><a id="right" href="#"><i class="fa fa-chevron-right"> </i></a></h1>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table tableCalendar"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo HTMLHelper::_('form.token'); ?>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
</form>
