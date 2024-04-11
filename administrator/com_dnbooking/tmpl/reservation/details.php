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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

$app = Factory::getApplication();
$input = $app->input;

$wa = $this->document->getWebAssetManager();
$wa->useScript('com_dnbooking.calendar');
$wa->useStyle('com_dnbooking.reservation');
$item = $this->item;
?>

<div class="row">
    <div class="col-lg-4">
        <div class="bs-component">
            <div class="card text-white bg-primary mb-3" style="max-width: 20rem;">
                <div class="card-header">Header</div>
                <div class="card-body">
                    <h4 class="card-title">Primary card title</h4>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <div class="card text-white bg-secondary mb-3" style="max-width: 20rem;">
                <div class="card-header">Header</div>
                <div class="card-body">
                    <h4 class="card-title">Secondary card title</h4>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <div class="card text-white bg-success mb-3" style="max-width: 20rem;">
                <div class="card-header">Header</div>
                <div class="card-body">
                    <h4 class="card-title">Success card title</h4>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <div class="card text-white bg-danger mb-3" style="max-width: 20rem;">
                <div class="card-header">Header</div>
                <div class="card-body">
                    <h4 class="card-title">Danger card title</h4>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <div class="card text-white bg-warning mb-3" style="max-width: 20rem;">
                <div class="card-header">Header</div>
                <div class="card-body">
                    <h4 class="card-title">Warning card title</h4>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <div class="card text-white bg-info mb-3" style="max-width: 20rem;">
                <div class="card-header">Header</div>
                <div class="card-body">
                    <h4 class="card-title">Info card title</h4>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <div class="card bg-light mb-3" style="max-width: 20rem;">
                <div class="card-header">Header</div>
                <div class="card-body">
                    <h4 class="card-title">Light card title</h4>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <div class="card text-white bg-dark mb-3" style="max-width: 20rem;">
                <div class="card-header">Header</div>
                <div class="card-body">
                    <h4 class="card-title">Dark card title</h4>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <button class="source-button btn btn-primary btn-xs" type="button" tabindex="0"><i class="bi bi-code"></i></button></div>
    </div>
    <div class="col-lg-4">
        <div class="bs-component">
            <div class="card border-primary mb-3" style="max-width: 20rem;">
                <div class="card-header">Header</div>
                <div class="card-body">
                    <h4 class="card-title">Primary card title</h4>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <div class="card border-secondary mb-3" style="max-width: 20rem;">
                <div class="card-header">Header</div>
                <div class="card-body">
                    <h4 class="card-title">Secondary card title</h4>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <div class="card border-success mb-3" style="max-width: 20rem;">
                <div class="card-header">Header</div>
                <div class="card-body">
                    <h4 class="card-title">Success card title</h4>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <div class="card border-danger mb-3" style="max-width: 20rem;">
                <div class="card-header">Header</div>
                <div class="card-body">
                    <h4 class="card-title">Danger card title</h4>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <div class="card border-warning mb-3" style="max-width: 20rem;">
                <div class="card-header">Header</div>
                <div class="card-body">
                    <h4 class="card-title">Warning card title</h4>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <div class="card border-info mb-3" style="max-width: 20rem;">
                <div class="card-header">Header</div>
                <div class="card-body">
                    <h4 class="card-title">Info card title</h4>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <div class="card border-light mb-3" style="max-width: 20rem;">
                <div class="card-header">Header</div>
                <div class="card-body">
                    <h4 class="card-title">Light card title</h4>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <div class="card border-dark mb-3" style="max-width: 20rem;">
                <div class="card-header">Header</div>
                <div class="card-body">
                    <h4 class="card-title">Dark card title</h4>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <button class="source-button btn btn-primary btn-xs" type="button" tabindex="0"><i class="bi bi-code"></i></button></div>
    </div>

    <div class="col-lg-4">
        <div class="bs-component">
            <div class="card mb-3">
                <h3 class="card-header">Card header</h3>
                <div class="card-body">
                    <h5 class="card-title">Special title treatment</h5>
                    <h6 class="card-subtitle text-muted">Support card subtitle</h6>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="d-block user-select-none" width="100%" height="200" aria-label="Placeholder: Image cap" focusable="false" role="img" preserveAspectRatio="xMidYMid slice" viewBox="0 0 318 180" style="font-size:1.125rem;text-anchor:middle">
                    <rect width="100%" height="100%" fill="#868e96"></rect>
                    <text x="50%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text>
                </svg>
                <div class="card-body">
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Cras justo odio</li>
                    <li class="list-group-item">Dapibus ac facilisis in</li>
                    <li class="list-group-item">Vestibulum at eros</li>
                </ul>
                <div class="card-body">
                    <a href="#" class="card-link">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                </div>
                <div class="card-footer text-muted">
                    2 days ago
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Card title</h4>
                    <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="card-link">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                </div>
            </div>
            <button class="source-button btn btn-primary btn-xs" type="button" tabindex="0"><i class="bi bi-code"></i></button></div>
    </div>
</div>


<div class="dnbooking dnbooking_reservation">
    <form action="<?php echo Route::_('index.php?option=com_dnbooking&view=reservations'); ?>" method="post" name="adminForm" id="reservation" enctype="multipart/form-data" class="form-validate">
        <div class="row-fluid">
            <div class="well">
                <h1><a href="<?php echo Route::_('index.php?option=com_dnbooking&task=company.edit&id=' . (int) $item->id); ?>"><span class="icon-edit"></span> <?= $item->title; ?></a></h1>
                <p><strong><?= Text::sprintf('com_dnbooking_ORDER_CREATION'); ?></strong> <?= date("d.m.Y | H:i", strtotime($item->created)); ?> ||
                    <?php
                        if($item->created_by != 0)
                        {
                            echo Text::sprintf('com_dnbooking_ORDER_BACKEND');
                        }
                        else
                        {
                            echo Text::sprintf('com_dnbooking_ORDER_FORM');
                        }
                    ?>
                </p>
                <h3><?= Text::sprintf('com_dnbooking_ORDER_CONTACT_DATA'); ?></h3>
                <div class="row-fluid">
                    <div class="span3">
                        <ul class="unstyled">
                            <li><label><strong><?= Text::sprintf('com_dnbooking_ORDER_CONTACT'); ?>:</strong> </label><?= $item->contact; ?></li>
                            <li><label><strong><?= Text::sprintf('com_dnbooking_ORDER_STREET'); ?>:</strong> </label><?= $item->street; ?></li>
                        </ul>
                    </div>
                    <div class="span3">
                        <ul class="unstyled">
                            <li><label><strong><?= Text::sprintf('com_dnbooking_ORDER_PHONE'); ?>:</strong> </label><?= $item->phone; ?></li>
                            <li><label><strong><?= Text::sprintf('com_dnbooking_ORDER_ZIP_CITY'); ?>:</strong> </label><?= $item->zipcode . ' ' . $item->city; ?></li>
                        </ul>
                    </div>
                    <div class="span3">
                        <ul class="unstyled">
                            <li><label><strong><?= Text::sprintf('com_dnbooking_ORDER_EMAIL'); ?>:</strong> </label><a href="mailto:<?= $item->mail; ?>"><span class="icon-mail"></span> <?= $item->mail; ?></a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>

        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">
                <?php $total = (int) 0; ?>
                <?php $registrationCount = $this->registrationCount; ?>
                <?php foreach($this->registrations as $title => $registrations): ?>

                    <table class="table table-striped table-bordered table-hover">
                        <h3><?= $title; ?></h3>
                        <thead>
                        <tr>
                            <th></th>
                            <th><?= Text::sprintf('com_dnbooking_ORDERS_FIRSTNAME'); ?></th>
                            <th><?= Text::sprintf('com_dnbooking_ORDERS_LASTNAME'); ?></th>
                            <th><?= Text::sprintf('com_dnbooking_ORDERS_PRICE'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $subtotal = (int) 0; ?>

                        <?php foreach($registrations as $registration): ?>

                            <tr>
                                <td width="12%"><a href="<?php echo Route::_('index.php?option=com_dnbooking&task=registration.edit&id=' . (int) $registration['id']); ?>"><span class="icon-edit"></span> Editieren</a></td>
                                <td><?= $registration['firstname']; ?></td>
                                <td><?= $registration['lastname']; ?></td>
                                <td style="text-align: right"><?= $registration['preis']; ?> €</td>
                            </tr>
                            <?php $subtotal = $subtotal + $registration['preis']; ?>

                        <?php endforeach; ?>

                        <tr class="success">
                            <td colspan="3">
                                <strong><?= Text::sprintf('com_dnbooking_ORDERS_SUBTOTAL'); ?></strong>
                            </td>
                            <td width="10%" style="text-align: right">
                                <?= $subtotal; ?> €
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <?php $total = $total + $subtotal; ?>
                <?php endforeach; ?>
                <table class="table">
                    <tbody>
                    <tr class="warning">
                        <td width="90%">
                            <strong><?= Text::sprintf('com_dnbooking_ORDERS_TOTAL'); ?></strong>
                        </td>
                        <td style="text-align: right">
                            <?= $total; ?> €
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <div class="span6">
                <div class="well">
                    <h2 style="font-size: 250%; margin-bottom: 30px;"><?= Text::sprintf('com_dnbooking_ORDERS_SUMMARY'); ?></h2>
                    <ul class="unstyled">
                        <li style="font-size: 150%; margin-bottom: 10px;"><strong><?= Text::sprintf('com_dnbooking_PRICE_TOTAL'); ?>: </strong>&nbsp;<?= $total; ?> €</li>
                        <li style="font-size: 150%;"><strong><?= Text::sprintf('com_dnbooking_REGISTRATION_COUNT'); ?>: </strong>&nbsp;<?= $registrationCount; ?></li>
                    </ul>
                </div>
            </div>
        </div>

        <input type="hidden" name="task" value="" />
        <?php echo HTMLHelper::_('form.token'); ?>

    </form>
</div>
