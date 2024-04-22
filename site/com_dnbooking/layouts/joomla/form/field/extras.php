<?php

/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
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
 * @var   Form    $tmpl             The Empty form for template
 * @var   array   $forms            Array of JForm instances for render the rows
 * @var   bool    $multiple         The multiple state for the form field
 * @var   int     $min              Count of minimum repeating in multiple mode
 * @var   int     $max              Count of maximum repeating in multiple mode
 * @var   string  $name             Name of the input field.
 * @var   string  $fieldname        The field name
 * @var   string  $fieldId          The field ID
 * @var   string  $control          The forms control
 * @var   string  $label            The field label
 * @var   string  $description      The field description
 * @var   string  $class            Classes for the container
 * @var   array   $buttons          Array of the buttons that will be rendered
 * @var   bool    $groupByFieldset  Whether group the subform fields by it`s fieldset
 */

list($config, $view, $input) = app(Config::class, View::class, Input::class);
$factory = Factory::getApplication()->bootComponent('com_dnbooking')->getMVCFactory();
$extrasModel = $factory->createModel('Booking', 'Site');
$extras = $extrasModel->getExtras();
$class = $class ? ' ' . $class : '';
$columns=3;
$counter=0;
?>

<ul class="extraList uk-grid uk-child-width-1-1 uk-child-width-1-<?=$columns;?>@s uk-child-width-1-<?=$columns;?>@m uk-grid-match" uk-grid="">
	<?php foreach($extras as $extra):
		if ($extra['published'] == -2) continue;
		$image = $view->el('image', [

			'class' => [
				'el-image',
			],
			'src' => $extra['image'],
			'alt' => 'Foto von ' . $extra['title'],
			'width' => null,
			'height' => null,
			'uk-img' => true,
			//'uk-cover' => true,
			'thumbnail' => true,
		]);?>
        <li class="extraListItem" data-extra-id="<?= $extra['id'] ?>">
            <div class="el-item uk-panel uk-margin-remove-first-child">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                    <div class="uk-width-1-1 uk-text-center">
						<?= $image;?>
                    </div>
                    <div class="uk-width-1-1 ">
                        <h3 class="el-title uk-margin-remove-bottom"><?= $extra['title'] ?></h3>

                        <p class="el-meta uk-text-meta uk-margin-remove-top"><?= $extra['description'] ?></p>
                        <p class="el-content"><?= Text::_('COM_DNBOOKING_PRICE_LABEL'); ?>: <?= number_format($extra['price'], 2, ",", '.') ?> €</p>
                        <input type="number" class="uk-input uk-form-width-small" value="0" min="0" name="extra-<?= $extra['id'] ?>" placeholder="Anzahl">
                    </div>
                </div>
            </div>
        </li>
	<?php endforeach; ?>
</ul>
<textarea id="<?= $fieldId; ?>" name="<?= $fieldId; ?>" hidden>
</textarea>
