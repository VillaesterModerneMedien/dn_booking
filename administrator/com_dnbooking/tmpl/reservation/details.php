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

    $app   = Factory::getApplication();
    $input = $app->input;

    $wa = $this->document->getWebAssetManager();
    $wa->useScript('joomla.dialog-autocreate');
    $wa->useStyle('com_dnbooking.reservation');
    $item     = $this->item;
    $customer = $this->customer;
?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tabs = document.querySelectorAll('#myTabs .nav-link');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabs.forEach(tab => {
            tab.addEventListener('click', function (e) {
                e.preventDefault();

                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const targetPane = document.querySelector(tab.getAttribute('data-bs-target'));
                tabPanes.forEach(pane => {
                    pane.classList.remove('show');
                    pane.classList.remove('active');
                });

                targetPane.classList.add('show');
                targetPane.classList.add('active');
            });
        });
    });
</script>

<div class="dnbooking dnbooking_reservation">
    <form action="<?php echo Route::_('index.php?option=com_dnbooking&view=reservations'); ?>" method="post"
          name="adminForm" id="reservation" enctype="multipart/form-data" class="form-validate">

        <div class="row">

            <div class="col-md-6">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-header"><h3><?= Text::_('COM_DNBOOKING_HEADING_RESERVATION_EDIT_HEADLINE'); ?></h3>
                    </div>
                    <div class="card-body">
                        <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
                            <div class="container-fluid">
                                <div class="collapse navbar-collapse" id="navbarNav">
                                    <ul class="navbar-nav nav-tabs" id="myTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="tab1-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab1" type="button" role="tab" aria-controls="tab1"
                                                    aria-selected="true"><?= Text::_('COM_DNBOOKING_HEADING_RESERVATION_VIEW_HEADLINE'); ?></button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab2-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab2" type="button" role="tab" aria-controls="tab2"
                                                    aria-selected="false"><?= Text::_('COM_DNBOOKING_HEADING_RESERVATION_EDIT_HEADLINE'); ?></button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </nav>

                        <div class="tab-content" id="meineTabsContent">
                            <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">

                                <div class="firstCard card text-white bg-primary border-secondary mb-3">
                                    <div class="card-header card-header-reservation">
                                        <h3>
                                            <?= Text::_('COM_DNBOOKING_HEADING_CUSTOMER_HEADLINE'); ?>
                                        </h3>

                                        <button type="button" class="btn btn-secondary btn-sm apply-sample-data" data-joomla-dialog='{"popupType": "iframe", "width":"80vw", "height": "80vh", "src": "<?= 'index.php?option=com_dnbooking&view=customer&tmpl=component&layout=modal&task=customer.edit&id=' . (int) $customer->id ?>"}'>
                                            <span class="icon-edit" aria-hidden="true"></span>
                                            <?= Text::_('COM_DNBOOKING_LABEL_EDIT'); ?>
                                        </button>

                                    </div>
                                    <div class="card-body">
                                        <p class="card-text"><strong>Titel:</strong> <?= $customer->title; ?></p>
                                        <hr/>
                                        <p class="card-text"><strong>Vorname:</strong> <?= $customer->firstname; ?></p>
                                        <hr/>
                                        <p class="card-text"><strong>Nachname:</strong> <?= $customer->lastname; ?></p>
                                        <hr/>
                                        <p class="card-text"><strong>E-Mail:</strong> <?= $customer->email; ?></p>
                                        <hr/>
                                        <p class="card-text"><strong>Telefon:</strong> <?= $customer->phone; ?></p>
                                        <hr/>
                                        <p class="card-text"><strong>Adresse:</strong> <?= $customer->address; ?></p>
                                        <hr/>
                                        <p class="card-text"><strong>Stadt:</strong> <?= $customer->city; ?></p>
                                        <hr/>
                                        <p class="card-text"><strong>Postleitzahl:</strong> <?= $customer->zip; ?></p>
                                        <hr/>
                                        <p class="card-text"><strong>Land:</strong> <?= $customer->country; ?></p>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                                <div class="firstCard card text-white bg-secondary mb-3">
                                    <div class="card-header">
                                        <h3><?= Text::_('COM_DNBOOKING_HEADING_CUSTOMER_HEADLINE'); ?></h3></div>
                                    <div class="card-body">
                                        <!-- <h4 class="card-title">Secondary card title</h4> -->

                                        <div class="row">
                                            <div class="col-lg-9">
                                                <?php echo $this->form->renderFieldset('reservationfieldset'); ?>
                                            </div>
                                            <div class="col-lg-3">
                                                <?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
                                            </div>

                                            <div class="input-group mb-6">
                                                <input type="text" class="form-control"
                                                       placeholder="<?= Text::_('COM_DNBOOKING_HEADING_CUSTOMER_TITLE'); ?>"
                                                       aria-label="<?= Text::_('COM_DNBOOKING_HEADING_CUSTOMER_TITLE'); ?>"
                                                       aria-describedby="button-addon2">
                                                <button class="btn btn-primary" type="button" id="button-addon2">
                                                    Button
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-header">
                        <h3><?= Text::_('COM_DNBOOKING_HEADING_RESERVATION_SUMMARY_HEADLINE'); ?></h3>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">Secondary card title</h4>

                        - Kundenfeld als readonly Textfeld mit Icon, dann Task
                        - Raum ebenfalls


                        <div class="container">
                            <h2><?= Text::_('COM_DNBOOKING_TABLE_HEADING'); ?></h2>
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th scope="row"><?= Text::_('COM_DNBOOKING_TABLE_LABEL_BOOKINGNUMBER'); ?></th>
                                    <td>9995</td>
                                </tr>
                                <tr>
                                    <th scope="row"><?= Text::_('COM_DNBOOKING_TABLE_LABEL_BIRTHDAYCHILD'); ?></th>
                                    <td>Audrey</td>
                                </tr>
                                <tr>
                                    <th scope="row"><?= Text::_('COM_DNBOOKING_TABLE_LABEL_BIRTHDAYCHILD'); ?></th>
                                    <td>Mary Anne</td>
                                </tr>
                                <tr>
                                    <th scope="row"><?= Text::_('COM_DNBOOKING_TABLE_LABEL_DATE'); ?></th>
                                    <td>11.09.2021</td>
                                </tr>
                                <tr>
                                    <th scope="row"><?= Text::_('COM_DNBOOKING_TABLE_LABEL_ARRIVALTIME'); ?></th>
                                    <td>12:00 Uhr</td>
                                </tr>
                                <tr>
                                    <th scope="row"><?= Text::_('COM_DNBOOKING_TABLE_LABEL_PACKAGE'); ?></th>
                                    <td>Häppi Motto</td>
                                </tr>
                                <tr>
                                    <th scope="row"><?= Text::_('COM_DNBOOKING_TABLE_LABEL_ROOM'); ?></th>
                                    <td>Zauberküche</td>
                                </tr>
                                <!-- Personenzahl und Preise -->
                                <tr>
                                    <th scope="row" class="dividerRow"
                                        colspan="2"><?= Text::_('COM_DNBOOKING_TABLE_LABEL_NUMBEROFPEOPLE'); ?></th>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td><?= text::_('com_dnbooking_table_label_2birthdaychildren'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?= text::_('com_dnbooking_table_label_4children'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?= text::_('com_dnbooking_table_label_0children'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?= text::_('com_dnbooking_table_label_2adults'); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>56,00 EUR</td>
                                            </tr>
                                            <tr>
                                                <td>0,00 EUR</td>
                                            </tr>
                                            <tr>
                                                <td>12,00 EUR</td>
                                            </tr>
                                            <tr>
                                                <td>20,00 EUR</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!-- Kuchen und Extras -->
                                <tr>
                                <tr>
                                    <th scope="row" class="dividerRow"
                                        colspan="2"><?= Text::_('COM_DNBOOKING_TABLE_LABEL_CAKEANDEXTRAS'); ?></th>
                                </tr>
                                <tr>
                                    <td><?= Text::_('COM_DNBOOKING_TABLE_LABEL_OWNCAKE'); ?></td>
                                    <td>9,50 EUR</td>
                                </tr>
                                <!-- Gesamtsumme -->
                                <tr>
                                    <th scope="row" class="dividerRow"
                                        colspan="2"><?= Text::_('COM_DNBOOKING_TABLE_LABEL_GESAMMTSUMME'); ?></th>
                                </tr>
                                <tr>
                                    <td><?= Text::_('COM_DNBOOKING_TABLE_LABEL_TOTAL'); ?></td>
                                    <td>233,50 EUR</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>

        </div>

        <input type="hidden" name="task" value=""/>
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>
</div>
