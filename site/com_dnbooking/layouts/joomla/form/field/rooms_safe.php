<?php

/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
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
 * @var   string   $label           Label of the field.
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
 * @var   array    $dataAttributes  Miscellaneous data attribute for eg, data-*
 */

list($config, $view, $input) = app(Config::class, View::class, Input::class);
$factory = Factory::getApplication()->bootComponent('com_dnbooking')->getMVCFactory();
$roomModel = $factory->createModel('Booking', 'Site');
$rooms = $roomModel->getRooms();

$columns=3;
$counter=0;

$html = [];
$attr = '';

// Initialize the field attributes.
$attr .= !empty($class) ? ' class="form-select ' . $class . '"' : ' class="form-select"';
$attr .= !empty($size) ? ' size="' . $size . '"' : '';
$attr .= $multiple ? ' multiple' : '';
$attr .= $required ? ' required' : '';
$attr .= $autofocus ? ' autofocus' : '';
$attr .= $onchange ? ' onchange="' . $onchange . '"' : '';
$attr .= !empty($description) ? ' aria-describedby="' . ($id ?: $name) . '-desc"' : '';
$attr .= $dataAttribute;

// To avoid user's confusion, readonly="readonly" should imply disabled="disabled".
if ($readonly || $disabled) {
    $attr .= ' disabled="disabled"';
}

// Create a read-only list (no name) with hidden input(s) to store the value(s).
if ($readonly) {
    $html[] = HTMLHelper::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $value, $id);

    // E.g. form field type tag sends $this->value as array
    if ($multiple && is_array($value)) {
        if (!count($value)) {
            $value[] = '';
        }

        foreach ($value as $val) {
            $html[] = '<input type="hidden" name="' . $name . '" value="' . htmlspecialchars($val, ENT_COMPAT, 'UTF-8') . '">';
        }
    } else {
        $html[] = '<input type="hidden" name="' . $name . '" value="' . htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '">';
    }
} else // Create a regular list passing the arguments in an array.
{
    $listoptions = [];
    $listoptions['option.key'] = 'value';
    $listoptions['option.text'] = 'text';
    $listoptions['list.select'] = $value;
    $listoptions['id'] = $id;
    $listoptions['list.translate'] = false;
    $listoptions['option.attr'] = 'optionattr';
    $listoptions['list.attr'] = trim($attr);
    $html[] = HTMLHelper::_('select.genericlist', $options, $name, $listoptions);
}

echo implode($html);
?>



<ul class="roomList uk-grid uk-child-width-1-1 uk-child-width-1-<?=$columns;?>@s uk-child-width-1-<?=$columns;?>@m uk-grid-match" uk-grid="">

	<?php
	foreach ($rooms as $room):
		if ($room->published == -2) continue;
		$counter++;?>
		<li data-room-id="<?= $room->id ?>" class="room <?php if($counter % $columns == 0): echo 'uk-first-column';endif;?>">
			<input type="radio" name="room" value="<?= $room->id ?>" id="room<?= $room->id ?>" hidden/>
			<div class="el-item uk-panel uk-margin-remove-first-child">
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
						//'uk-cover' => true,
						'thumbnail' => true,
					]);

					echo $image();
				}
				?>
				<h3 class="el-title uk-margin-top uk-margin-remove-bottom">
					<?= $room->title ?>
				</h3>
				<div class="el-meta uk-text-meta uk-margin-top">
					<?= sprintf(Text::_('COM_DNBOOKING_ROOMSGRID_PERSONS'), $room->personsmin, $room->personsmax);?>
				</div>
				<div class="el-content uk-panel uk-margin-top">
					<p><?= Text::_('COM_DNBOOKING_ROOMSGRID_PRICEREGULAR') . $room->priceregular . ' €'?></p>
					<p><?= Text::_('COM_DNBOOKING_ROOMSGRID_PRICECUSTOM') . $room->pricecustom . ' €'?></p>
				</div>
			</div>
		</li>
	<?php
	endforeach;
	?>

</ul>
