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
<div class="uk-container">
    <div class="uk-grid tm-grid-expand uk-child-width-1-1 uk-grid-margin">
        <div class="uk-width-1-1">
            <div class="uk-margin">
                <ul class="extraList uk-grid uk-child-width-1-1 uk-child-width-1-<?=$columns;?>@s uk-child-width-1-<?=$columns;?>@m uk-grid-match" uk-grid="">
					<?php foreach($extras as $extra):
						if ($extra['published'] == -2) continue;
                        $image = $view->el('image', [

                        'class' => [
                        'el-image',
                        ],
                        'src' => $extra['image'],
                        'alt' => 'Foto von ' . $extra["title"],
                        'width' => null,
                        'height' => null,
                        'uk-img' => true,
                        //'uk-cover' => true,
                        'thumbnail' => true,
                        ]);?>
                        <li class="extraListItem" data-extra-id="<?= $extra["id"] ?>">
                            <div class="el-item uk-panel uk-margin-remove-first-child">
                                <div class="uk-grid-small uk-flex-middle" uk-grid>
                                    <div class="uk-width-1-1 uk-text-center">
                                       <?= $image;?>
                                    </div>
                                    <div class="uk-width-1-1 ">
                                        <h3 class="el-title uk-margin-remove-bottom"><?= $extra["title"] ?></h3>
                                        <p class="el-meta uk-text-meta uk-margin-remove-top"><?= $extra["description"] ?></p>
                                        <p class="el-content"><?= Text::_('COM_DNBOOKING_PRICE_LABEL'); ?>: <?= number_format($extra["price"], 2, ",", '.') ?> €</p>
                                        <input type="number" class="uk-input uk-form-width-small" value="0" min="0" name="extra-<?= $extra["id"] ?>" placeholder="Anzahl">
                                    </div>
                                </div>
                            </div>
                        </li>
					<?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
