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
 * @var   array   $buttons          Array of the buttons that will be rendered
 * @var   bool    $groupByFieldset  Whether group the subform fields by it`s fieldset
 */
$form = $forms[0];


// Sprachvariablen definieren
$singleCheckList = Text::_('COM_DNBOOKING_SINGLE_CHECK_LIST');
$optionsCheckList = Text::_('COM_DNBOOKING_OPTIONS_CHECK_LIST');

// Sprachvariablen in einem JavaScript-Objekt verfügbar machen
$document = Factory::getDocument();
$document->addScriptDeclaration("
    var JoomlaLang = {
        'COM_DNBOOKING_SINGLE_CHECK_LIST': '{$singleCheckList}',
        'COM_DNBOOKING_OPTIONS_CHECK_LIST': '{$optionsCheckList}'
    };
");

?>
<div class="subform-wrapper">
<?php foreach ($form->getGroup('') as $field) : ?>
    <?php echo $field->renderField(); ?>
<?php endforeach; ?>
</div>
