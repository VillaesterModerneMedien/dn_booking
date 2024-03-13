<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;


use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Language\Text;

$displayData = [
	'textPrefix' => 'COM_DNBOOKING',
	'formURL' => 'index.php?option=com_dnbooking',
	'helpURL' => 'https://YOUR-HELP-URL.COM',
	'icon' => 'icon-file',
];

$user = Factory::getApplication()->getIdentity();

if ($user->authorise('core.create', 'com_dnbooking') || count($user->getAuthorisedCategories('com_dnbooking', 'core.create')) > 0)
{
	$displayData['createURL'] = 'index.php?option=com_dnbooking&task=room.add';
}

echo LayoutHelper::render('joomla.content.emptystate', $displayData);