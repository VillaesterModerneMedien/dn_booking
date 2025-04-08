<?php

/**
 * @package         Joomla.Site
 * @subpackage      Layout
 *
 * @copyright   (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;


extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   Form   $tmpl            The Empty form for template
 * @var   array  $forms           Array of JForm instances for render the rows
 * @var   bool   $multiple        The multiple state for the form field
 * @var   int    $min             Count of minimum repeating in multiple mode
 * @var   int    $max             Count of maximum repeating in multiple mode
 * @var   string $name            Name of the input field.
 * @var   string $fieldname       The field name
 * @var   string $fieldId         The field ID
 * @var   string $control         The forms control
 * @var   string $label           The field label
 * @var   string $description     The field description
 * @var   string $class           Classes for the container
 * @var   array  $buttons         Array of the buttons that will be rendered
 * @var   bool   $groupByFieldset Whether group the subform fields by it`s fieldset
 */
if ($multiple)
{
	// Add script
	Factory::getApplication()
		->getDocument()
		->getWebAssetManager();
	//->useScript('webcomponent.field-subform');
}

$class = $class ? ' ' . $class : '';

$sublayout = empty($groupByFieldset) ? 'section' : 'section-byfieldsets';

$model  = Factory::getApplication()->bootComponent('com_dnbooking')->getMVCFactory()->createModel('Reservation', 'Site');
$extras = $model->getOrderFeatures('Extras');

$class         = $class ? ' ' . $class : '';
$columns       = 3;
$counter       = 0;
$subformValues = [];
$subformItems  = [];

foreach ($extras as $key => $extra)
{

	$subformValues['extras_ids' . $key] = [
		'extra_count' => 0,
		'extra_id'    => $extra->id,
	];

	$subformItems['extras_ids' . $key] = [
		'id'          => $extra->id,
		'title'       => $extra->title,
		'description' => $extra->description,
		'price'       => $extra->price,
		'image'       => $extra->image,
		'published'   => $extra->published,
	];

}
?>
<div class="subform-repeatable-wrapper subform-layout">
    <joomla-field-subform class="subform-repeatable<?php echo $class; ?>" name="<?php echo $name; ?>">
        <ul class="extraList uk-grid uk-child-width-1-1 uk-child-width-1-<?= $columns; ?>@s uk-child-width-1-<?= $columns; ?>@m uk-grid-match"
            uk-grid="">
			<?php
			foreach ($forms as $k => $form) :
				/** @var Form $form */
				$form->bind($subformValues[$fieldname . $k]);

				echo $this->sublayout($sublayout, ['subformItem' => $subformItems[$fieldname . $k], 'form' => $form, 'basegroup' => $fieldname, 'group' => $fieldname . $k, 'buttons' => $buttons]);
			endforeach;
			?>
        </ul>

        <template class="subform-repeatable-template-section hidden"><?php
		    echo trim($this->sublayout($sublayout, ['form' => $tmpl, 'basegroup' => $fieldname, 'group' => $fieldname . 'X', 'buttons' => $buttons]));
		    ?>
        </template>
    </joomla-field-subform>
</div>
