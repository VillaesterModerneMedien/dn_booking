<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace DnbookingNamespace\Component\Dnbooking\Administrator\View\Openinghours;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 *  * View class for a list of openinghours.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * An array of items
	 *
	 * @var  array
	 */
	protected $items;

	/**
	 * The pagination object
	 *
	 * @var  \Joomla\CMS\Pagination\Pagination
	 */
	protected $pagination;

	/**
	 * The model state
	 *
	 * @var  \Joomla\CMS\Object\CMSObject
	 */
	protected $state;

	/**
	 * Form object for search filters
	 *
	 * @var  \Joomla\CMS\Form\Form
	 */
	public $filterForm;

	/**
	 * The active search filters
	 *
	 * @var  array
	 */
	public $activeFilters;

	/**
	 * Is this view an Empty State
	 *
	 * @var   boolean
	 *
	 * @since 1.0.0
	 */
	private $isEmptyState = false;
	
	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
    public function display($tpl = null): void
    {
        //$this->items         = $this->get('Items');
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$user = Factory::getApplication()->getIdentity();
        $canDo = ContentHelper::getActions('com_dnbooking', 'category', $this->state->get('filter.category_id'));
        
		ToolbarHelper::title(Text::_('COM_DNBOOKING_HEADLINE_OPENINGHOURS'), 'list com_dnbooking');
        
		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');
        if ($canDo->get('core.create') || \count($user->getAuthorisedCategories('com_dnbooking', 'core.create')) > 0)
		{
			$toolbar->addNew('openinghour.add');
		}
        
        if (!$this->isEmptyState && $canDo->get('core.edit.state'))
		{
            $dropdown = $toolbar->dropdownButton('status-group')
				->text('JTOOLBAR_CHANGE_STATUS')
				->toggleSplit(false)
				->icon('icon-ellipsis-h')
				->buttonClass('btn btn-action')
				->listCheck(true);

			$childBar = $dropdown->getChildToolbar();

			$childBar->publish('openinghours.publish')->listCheck(true);

			$childBar->unpublish('openinghours.unpublish')->listCheck(true);
            
            $childBar->archive('openinghours.archive')->listCheck(true);
            
            if ($this->state->get('filter.published') != -2)
			{
				$childBar->trash('openinghours.trash')->listCheck(true);
			}
        }
        
        if (!$this->isEmptyState && $this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			$toolbar->delete('openinghours.delete')
				->text('JTOOLBAR_EMPTY_TRASH')
				->message('JGLOBAL_CONFIRM_DELETE')
				->listCheck(true);
		}
		
		if ($user->authorise('core.admin', 'com_dnbooking') || $user->authorise('core.options', 'com_dnbooking'))
		{
			$toolbar->preferences('com_dnbooking');
		}
		
		ToolbarHelper::help('index', true);
		
	}
}