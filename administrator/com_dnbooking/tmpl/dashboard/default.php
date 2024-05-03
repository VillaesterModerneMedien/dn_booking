<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use DnbookingNamespace\Component\Dnbooking\Administrator\Helper\DnbookingHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;


/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_dnbooking.reservation');
$app = Factory::getApplication();
$user = $app->getIdentity();

?>


<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <div class="card text-white bg-primary">

                <div class="card-header">
                    <h4><?= Text::_('COM_DNBOOKING_DASHBOARD_INFOS_HEADLINE') ?></h4>
                </div>

                <div class="card-body">
                    <p>
	                    <?= Text::_('COM_DNBOOKING_DASHBOARD_INFOS_TEXT1') ?>
                    <hr />
                    <h4><?= Text::_('COM_DNBOOKING_DASHBOARD_SUPPORT_HEADLINE') ?></h4>
                    </p>                 <p>
	                    <?= Text::_('COM_DNBOOKING_DASHBOARD_SUPPORT_TEXT') ?>
                    </p>

                </div>

            </div>
        </div>

        <div class="col-6">
            <div class="card text-white bg-primary">

                <div class="card-header">
                    <h4><?= Text::_('COM_DNBOOKING_DASHBOARD_INFOS_HEADLINE') ?></h4>
                </div>

                <div class="card-body">
                    <h3><?php echo Text::_('COM_DNBOOKING_DASHBOARD_STATS_HEADLINE');?></h3>
                    <table class="table table-condensed table-hover">
                        <thead>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?php echo Text::_('COM_DNBOOKING_DASHBOARD_RESERVATIONS_TOTAL_HEADLINE'); ?></td>
                            <td><?php echo $this->statistic->reservations;?></td>
                        </tr>
                        <tr>
                            <td><?php echo Text::_('COM_DNBOOKING_DASHBOARD_CUSTOMERS_TOTAL_HEADLINE'); ?></td>
                            <td><?php echo $this->statistic->customers;?></td>
                        </tr>
                        </tbody>
                    </table>

                </div>

            </div>
        </div>

</div>
