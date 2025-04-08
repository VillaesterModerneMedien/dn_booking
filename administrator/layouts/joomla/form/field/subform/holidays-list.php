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

$test = json_encode($forms);
$class = $class ? ' ' . $class : '';

// Build heading
$table_head = '';

if (!empty($groupByFieldset)) {
	foreach ($tmpl->getFieldsets() as $k => $fieldset) {
		$table_head .= '<th scope="col">' . Text::_($fieldset->label);

		if ($fieldset->description) {
			$table_head .= '<span class="icon-info-circle" aria-hidden="true" tabindex="0"></span><div role="tooltip" id="tip-th-' . $fieldId . '-' . $k . '">' . Text::_($fieldset->description) . '</div>';
		}

		$table_head .= '</th>';
	}

	$sublayout = 'section-byfieldsets';
} else {
	foreach ($tmpl->getGroup('') as $field) {
		$table_head .= '<th scope="col" style="width:45%">' . strip_tags($field->label);

		if ($field->description) {
			$table_head .= '<span class="icon-info-circle" aria-hidden="true" tabindex="0"></span><div role="tooltip" id="tip-' . $field->id . '">' . Text::_($field->description) . '</div>';
		}

		$table_head .= '</th>';
	}

	$sublayout = 'section';

	// Label will not be shown for sections layout, so reset the margin left
	Factory::getApplication()
		->getDocument()
		->addStyleDeclaration('.subform-table-sublayout-section .controls { margin-left: 0px }');
}


?>

<script>
    function getData(region){
        const checkFieldset = document.getElementById('jform_selectHolidaysType');
        const checkBoxes = checkFieldset.querySelectorAll('input[type="checkbox"]:checked');
        const checkChoices = [];

        console.log(region);
        checkBoxes.forEach(checkbox => {
            checkChoices.push(checkbox.value);
        });
        const checkString = checkChoices.join(',');

        const container = document.querySelector('#subfieldList_jform_holidays .subform-repeatable-container');
        // Spinner-Code mit Text, transparentem Hintergrund und voller Breite
        const spinnerHtml = `
            <div style="color: #FFF; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100%; width: 100%; background-color: transparent; position: absolute;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Laden...</span>
                </div>
                <p>Feiertaqe und Ferien werden geladen...</p>
            </div>`;

        var url = 'index.php?option=com_dnbooking&task=config.generateHolidayTableHTML';
        // Die Daten, die an den Server gesendet werden
        var data = {
            region: region,
            checkLiveStatus: checkString
        };
        // Die Optionen für die Fetch-Anfrage
        var options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest' // Wichtig für Joomla, um zu erkennen, dass es sich um eine AJAX-Anfrage handelt
            },
            body: new URLSearchParams(data).toString()
        };

        container.innerHTML = spinnerHtml; // Spinner und Text anzeigen

        // Senden der Fetch-Anfrage
        fetch(url, options)
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html; // Antwortinhalt einfügen und Spinner ausblenden
            })
            .catch(error => {
                container.innerHTML = '<div>Error loading data</div>'; // Fehlerbehandlung
                console.error('Error:', error);
            });
    }
    document.addEventListener('DOMContentLoaded', function() {

        const region = document.getElementById('jform_region');
        const holidayCheckbox = document.getElementById('jform_selectHolidaysType');

        holidayCheckbox.addEventListener('change', function() {
            getData(region.value);
        });

        region.addEventListener('change', function() {
            getData(this.value);
        });
    });
</script>


<div class="subform-repeatable-wrapper subform-table-layout subform-table-sublayout-<?php echo $sublayout; ?>">
    <joomla-field-subform class="subform-repeatable<?php echo $class; ?>" name="<?php echo $name; ?>">
        <div class="table-responsive">
            <table class="table" id="subfieldList_<?php echo $fieldId; ?>">
                <caption class="visually-hidden">
					<?php echo Text::_('JGLOBAL_REPEATABLE_FIELDS_TABLE_CAPTION'); ?>
                </caption>
                <thead>
                <tr>
					<?php echo $table_head; ?>
                </tr>
                </thead>
                <tbody class="subform-repeatable-container">
				<?php
				foreach ($forms as $k => $form) :
					echo $this->sublayout($sublayout, ['form' => $form, 'basegroup' => $fieldname, 'group' => $fieldname . $k, 'buttons' => $buttons]);
				endforeach;
				?>
                </tbody>
            </table>
        </div>
		<?php if ($multiple) : ?>
            <template class="subform-repeatable-template-section hidden">
				<?php echo trim($this->sublayout($sublayout, ['form' => $tmpl, 'basegroup' => $fieldname, 'group' => $fieldname . 'X', 'buttons' => $buttons])); ?>
            </template>
		<?php endif; ?>
        <div style="height: 100px;"></div>
    </joomla-field-subform>
</div>
