<?php
defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper;

$items = $displayData['items'];
$date = $displayData['date'];
?>

<?php foreach (array_chunk($items, 3) as $itemsChunk) : ?>
    <div class="row">
        <?php foreach ($itemsChunk as $item) : ?>
            <div class="col">
                <?php echo LayoutHelper::render('daydashboards.pdfs.daysheet_item', ['item' => $item]); ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>