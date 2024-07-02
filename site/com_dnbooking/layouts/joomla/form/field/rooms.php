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
use Joomla\CMS\Language\Text;
use Joomla\Input\Input;
use YOOtheme\Config;
use YOOtheme\View;
use function YOOtheme\app;

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   string   $autocomplete    Autocomplete attribute for the field.
 * @var   boolean  $autofocus       Is autofocus enabled?
 * @var   string   $class           Classes for the input.
 * @var   string   $description     Description of the field.
 * @var   boolean  $disabled        Is this field disabled?
 * @var   string   $group           Group the field belongs to. <fields> section in form XML.
 * @var   boolean  $hidden          Is this field hidden in the form?
 * @var   string   $hint            Placeholder for the field.
 * @var   string   $id              DOM id of the field.
 * @var   string   $labelclass      Classes to apply to the label.
 * @var   boolean  $multiple        Does this field support multiple values?
 * @var   string   $name            Name of the input field.
 * @var   string   $onchange        Onchange attribute for the field.
 * @var   string   $onclick         Onclick attribute for the field.
 * @var   string   $pattern         Pattern (Reg Ex) of value of the form field.
 * @var   boolean  $readonly        Is this field read only?
 * @var   boolean  $repeat          Allows extensions to duplicate elements.
 * @var   boolean  $required        Is this field required?
 * @var   integer  $size            Size attribute of the input.
 * @var   boolean  $spellcheck      Spellcheck state for the form field.
 * @var   string   $validate        Validation rules to apply.
 * @var   string   $value           Value attribute of the field.
 * @var   array    $options         Options available for this field.
 * @var   string   $dataAttribute   Miscellaneous data attributes preprocessed for HTML output
 * @var   array    $dataAttributes  Miscellaneous data attribute for eg, data-*.
 */

/**
 * The format of the input tag to be filled in using sprintf.
 *     %1 - id
 *     %2 - name
 *     %3 - value
 *     %4 = any other attributes
 */
$format = '<input hidden type="radio" id="%1$s" name="%2$s" value="%3$s" %4$s>';
$alt    = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $name);
$input = Factory::getApplication()->input;


list($config, $view, $input) = app(Config::class, View::class, Input::class);
$counter=0;

?>
<fieldset id="<?php echo $id; ?>" class="<?php echo trim($class . ' radio'); ?>"
    <?php echo $disabled ? 'disabled' : ''; ?>
    <?php echo $required ? 'required' : ''; ?>
    <?php echo $autofocus ? 'autofocus' : ''; ?>
    <?php echo $dataAttribute; ?>>

    <?php if (!empty($options)) : ?>

    <?php
        if (isset($options[0]->text) && $options[0]->text === "COM_DNBOOKING_FIELD_SELECT_ROOM") {
            array_shift($options);
        }
    ?>

    <ul class="roomList uk-grid uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-3@m uk-grid-match" uk-grid="">
        <?php foreach ($options as $i => $room) : ?>
            <?php
                // Initialize some option attributes.
                $checked     = ((string) $room->value === $value) ? 'checked="checked"' : '';

                $oid        = $id . $i;
                $ovalue     = htmlspecialchars($room->value, ENT_COMPAT, 'UTF-8');
                $attributes = array_filter([$checked]);
            ?>
            <li data-room-id="<?= $room->id ?>" class="room ">
                <label for="<?php echo $oid; ?>">

                <?php if ($required) : ?>
                    <?php $attributes[] = 'required'; ?>
                <?php endif; ?>
                <div class="radio el-item uk-panel uk-margin-remove-first-child">

	                <?php
	                $images = json_decode($room->images, true);
	                if(!empty($images))
	                {
		                $firstImage = '/' . $images['images0']['image'];
		                $image = $view->el('image', [

			                'class' => [
				                'el-image',
			                ],
			                'src' => $firstImage,
			                'alt' => 'Foto von ' . $room->title,
			                'width' => null,
			                'height' => null,
			                'uk-img' => true,
			                'thumbnail' => true,
		                ]);

		                echo $image();
	                }
	                ?>
                    <h3 class="el-title uk-margin-top uk-margin-remove-bottom">
                            <?php echo Text::alt($room->title, $alt); ?>
                    </h3>
                    <div class="el-meta uk-text-meta ">
		                <?= sprintf(Text::_('COM_DNBOOKING_ROOMSGRID_PERSONS'), $room->personsmin, $room->personsmax);?>
                    </div>
                    <div class="el-content uk-panel uk-margin-top">
                        <p class="priceregular"><?= Text::_('COM_DNBOOKING_ROOMSGRID_PRICEREGULAR') . $room->priceregular . ' €'?></p>
                        <p class="pricecustom"><?= Text::_('COM_DNBOOKING_ROOMSGRID_PRICECUSTOM') . $room->pricecustom . ' €'?></p>
                        <?php if($room->description != ''):?>
                            <p class ="description"><?= $room->description; ?></p>
                        <?php endif;?>
                    </div>

                    <?php echo sprintf($format, $oid, $name, $ovalue, implode(' ', $attributes)); ?>
                </div>
                </label>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</fieldset>
