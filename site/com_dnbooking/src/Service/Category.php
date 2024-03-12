<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Site\Service;

\defined('_JEXEC') or die;

use Joomla\CMS\Categories\Categories;

/**
 * Dnbooking Component Category Tree
 *
 * @since  1.0.0
 */
class Category extends Categories
{
	/**
	 * Class constructor
	 *
	 * @param   array  $options  Array of options
	 *
	 * @since   1.0.0
	 */
	public function __construct($options = [])
	{
		$options['table']      = '#__dnbooking_reservations';
		$options['extension']  = 'com_dnbooking';
		$options['statefield'] = 'published';

		parent::__construct($options);
	}
}
