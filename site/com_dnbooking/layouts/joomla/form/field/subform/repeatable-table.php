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
if ($multiple) {
    // Add script
    Factory::getApplication()
        ->getDocument()
        ->getWebAssetManager()
        ->useScript('webcomponent.field-subform');
}

$class = $class ? ' ' . $class : '';

// Build heading
$table_head = '';

$table_head .= '<div class="uk-grid" >';
foreach ($tmpl->getGroup('') as $field) {
	$table_head .= '<div class="uk-width-1-3" >' . strip_tags($field->label);

	if ($field->description) {
		$table_head .= '<span class="icon-info-circle" aria-hidden="true" tabindex="0"></span><div role="tooltip" id="tip-' . $field->id . '">' . Text::_($field->description) . '</div>';
	}

	$table_head .= '</div>';
}
$table_head .= '</div>';

$sublayout = 'section';

// Label will not be shown for sections layout, so reset the margin left
Factory::getApplication()
	->getDocument()
	->addStyleDeclaration('.subform-table-sublayout-section .controls { margin-left: 0px }');


?>
<div class="TABLE subform-repeatable-wrapper subform-table-layout subform-table-sublayout-<?php echo $sublayout; ?>">
    <joomla-field-subform class="subform-repeatable<?php echo $class; ?>" name="<?php echo $name; ?>"
        button-add=".group-add" button-remove=".group-remove" button-move="<?php echo empty($buttons['move']) ? '' : '.group-move' ?>"
        repeatable-element=".subform-repeatable-group"
        rows-container="tbody.subform-repeatable-container" minimum="<?php echo $min; ?>" maximum="<?php echo $max; ?>">

        <?php echo $table_head; ?>

        <div class="uk-grid">
            <?php
                foreach ($forms as $k => $form) :
                    echo $this->sublayout($sublayout, ['form' => $form, 'basegroup' => $fieldname, 'group' => $fieldname . $k, 'buttons' => $buttons]);
                endforeach;
            ?>
        </div>

    </joomla-field-subform>
</div>
