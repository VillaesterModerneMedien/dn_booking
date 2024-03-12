<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Site\View\Categories;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\CategoriesView;

/**
 * Dnbooking categories view.
 *
 * @since  1.0.0
 */
class HtmlView extends CategoriesView
{
	/**
	 * Language key for default page heading
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $pageHeading = 'COM_DNBOOKING_DEFAULT_PAGE_TITLE';

	/**
	 * @var    string  The name of the extension for the category
	 * @since  3.2
	 */
	protected $extension = 'com_dnbooking';
}
