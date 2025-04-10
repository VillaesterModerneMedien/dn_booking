<?php

/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\Input\Input;
use YOOtheme\Config;
use YOOtheme\View;
use function YOOtheme\app;

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   Form    $form       The form instance for render the section
 * @var   string  $basegroup  The base group name
 * @var   string  $group      Current group name
 * @var   array   $buttons    Array of the buttons that will be rendered
 */
list($config, $view, $input) = app(Config::class, View::class, Input::class);
$columns = 3;
$counter = $form->getGroup('');

$image = $view->el('image', [
	'class'     => [
		'el-image',
	],
	'src'       => $subformItem['image'],
	'alt'       => 'Foto von ' . $subformItem['title'],
	'width'     => null,
	'height'    => null,
	'uk-img'    => true,
	'thumbnail' => true,
]);

?>
<?php if($subformItem['published'] == '1') : ?>
<li data-extra-id="<?= $subformItem['id'] ?>" data-extra-type="<?= $subformItem['type'] ?>" class="extraListItem subform-repeatable-group" data-base-name="<?php echo $basegroup; ?>" data-group="<?php echo $group; ?>">
    <div class="el-item uk-panel uk-margin-remove-first-child">
        <div class="uk-grid-small uk-flex-middle" uk-grid>
            <div class="uk-width-1-1 uk-text-center">
				<?= $image; ?>
            </div>
            <div class="uk-width-1-1 ">
                <h3 class="el-title uk-margin-remove-bottom"><?= $subformItem['title'] ?></h3>

                <p class="el-meta uk-text-meta uk-margin-remove-top"><?= $subformItem['description'] ?></p>
                <p class="el-content"><?= Text::_('COM_DNBOOKING_PRICE_LABEL'); ?>
                    : <?= number_format($subformItem['price'], 2, ",", '.') ?> €</p>

	            <?php foreach ($form->getGroup('') as $field) : ?>
                    <?php
                        $options = [
                            'hiddenLabel' => false,
                        ];
                    ?>
                    <?php echo $field->renderField($options); ?>
	            <?php endforeach; ?>
            </div>
        </div>
    </div>
</li>
<?php endif;?>