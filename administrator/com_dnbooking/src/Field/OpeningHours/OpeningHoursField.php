<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Administrator\Field\OpeningHours;


\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use Joomla\Database\ParameterType;



/**
 * Custom Field class to load opening hours from component's configuration
 *
 * @since  1.0.0
 */
class OpeningHoursField extends ListField
{
	/**
	 * Name of the layout being used to render the field
	 *
	 * @var    string
	 * @since  4.0.0
	 */
	protected $layout = 'joomla.form.field.list';

	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	protected $type = 'OpeningHours';

	protected function getOptions()
	{
		// Hole die Standardoptionen
		$options = parent::getOptions();

		// Datenbankverbindung herstellen
		$db = \Joomla\CMS\Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('params'))
			->from($db->quoteName('#__extensions'))
			->where($db->quoteName('element') . ' = ' . $db->quote('com_dnbooking'))
			->where($db->quoteName('type') . ' = ' . $db->quote('component'));
		$db->setQuery($query);
		$result = $db->loadResult();
		$params = json_decode($result, true);

		// Überprüfe, ob Öffnungszeiten definiert sind, und füge sie den Optionen hinzu
		if (!empty($params['regular_opening_hours']) && is_array($params['regular_opening_hours'])) {
			foreach ($params['regular_opening_hours'] as $name => $hours) {
				$text = $hours['starttime'] . ' - ' . $hours['endtime'];
				$regularParams['opening_times'] = $name;
				$regularParams['openingtimes_color'] = $hours['openinghour_color'];
				$options[] = \Joomla\CMS\HTML\HTMLHelper::_('select.option', json_encode($regularParams), $text);
			}
		}

		$closedParams['opening_times'] = "regular_opening_hoursclosed";
		$closedParams['openingtimes_color'] = $params['closed_color'];
		$options[] = \Joomla\CMS\HTML\HTMLHelper::_('select.option', json_encode($closedParams), Text::_('COM_DNBOOKING_CONFIG_OPENINGHOURS_CLOSED_LABEL'));
		return $options;
	}

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.7.0
	 */
	protected function getInput()
	{
		$data = $this->getLayoutData();

		$data['options'] = (array) $this->getOptions();

		return $this->getRenderer($this->layout)->render($data);
	}

}
