<?php
defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper;
extract($displayData);
?>


<?php foreach ($items as $item) : ?>
    <div class="column">
        <?php echo LayoutHelper::render('daydashboards.pdfs.daysheet_item', $item); ?>
    </div>
<?php $mpdf->AddPage(); ?>
<?php endforeach; ?>
