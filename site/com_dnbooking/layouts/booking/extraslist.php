<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

    use Joomla\CMS\Factory;
    use Joomla\CMS\Language\Text;
use function YOOtheme\app;
use YOOtheme\Config;
use YOOtheme\View;

use Joomla\Input\Input;

list($config, $view, $input) = app(Config::class, View::class, Input::class);

$extras = $displayData;
$columns=3;
$counter=0;
Factory::getApplication()
?>
<ul class="uk-list uk-list-divider">
    <?php foreach($extras as $extra):
	    if ($extra->published == -2) continue;?>
        <li class="extra extraListItem" data-extra-id="<?= $extra["id"] ?>">
            <div class="uk-grid-small uk-flex-middle" uk-grid>
                <div class="uk-width-auto">
                    <img class="uk-border-circle" width="40" height="40" src="<?= $extra["image"] ?>">
                </div>
                <div class="uk-width-expand">
                    <h3 class="uk-margin-remove-bottom"><?= $extra["title"] ?></h3>
                    <p class="uk-text-meta uk-margin-remove-top">
                        <?= $extra["description"] ?>
                    </p>
                    <p><?= Text::_('COM_DNBOOKING_EXTRAS_PRICE'); ?>: <?= number_format($extra["price"], 2, ",", '.' )?> €</p>
                </div>
                <div class="uk-width-auto">
                    <input class="uk-checkbox" type="checkbox" id="extra-<?= $extra["id"] ?>" style="display: none;">
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
