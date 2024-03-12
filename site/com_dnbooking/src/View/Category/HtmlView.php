<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Site\View\Category;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\CategoryView;
use DnbookingNamespace\Component\Dnbooking\Site\Helper\RouteHelper;

/**
 * HTML View class for the Dnbooking component
 *
 * @since  1.0.0
 */
class HtmlView extends CategoryView
{
	/**
	 * @var    string  The name of the extension for the category
	 * @since  1.0.0
	 */
	protected $extension = 'com_dnbooking';

	/**
	 * @var    string  Default title to use for page title
	 * @since  1.0.0
	 */
	protected $defaultPageTitle = 'COM_DNBOOKING_DEFAULT_PAGE_TITLE';

	/**
	 * @var    string  The name of the view to link individual items to
	 * @since  1.0.0
	 */
	protected $viewName = 'reservation';

	/**
	 * Run the standard Joomla plugins
	 *
	 * @var    boolean
	 * 
	 * @since  1.0.0
	 */
	protected $runPlugins = true;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		parent::commonCategoryDisplay();

		// Flag indicates to not add limitstart=0 to URL
		$this->pagination->hideEmptyLimitstart = true;

		return parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return  void
	 * 
	 * @since  1.0.0
	 */
	protected function prepareDocument()
	{
		parent::prepareDocument();

		$menu = $this->menu;
		$id = (int) @$menu->query['id'];

		if ($menu && (!isset($menu->query['option']) || $menu->query['option'] != $this->extension || $menu->query['view'] == $this->viewName
			|| $id != $this->category->id))
		{
			$path = [['title' => $this->category->title, 'link' => '']];
			$category = $this->category->getParent();

			while ((!isset($menu->query['option']) || $menu->query['option'] !== 'com_dnbooking' || $menu->query['view'] === 'reservation'
				|| $id != $category->id) && $category->id > 1)
			{
				$path[] = ['title' => $category->title, 'link' => RouteHelper::getCategoryRoute($category->id, $category->language)];
				$category = $category->getParent();
			}

			$path = array_reverse($path);

			foreach ($path as $item)
			{
				$this->pathway->addItem($item['title'], $item['link']);
			}
		}

		parent::addFeed();
	}
}
