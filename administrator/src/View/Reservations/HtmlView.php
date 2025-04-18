<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace DnbookingNamespace\Component\Dnbooking\Administrator\View\Reservations;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 *  * View class for a list of reservations.
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
        $this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

        if (empty($this->items) && $this->isEmptyState = $this->get('IsEmptyState'))
        {
			$this->setLayout('emptystate');
		}

        // We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			if(count($this->items) > 0)
			$this->addToolbar();
        }

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
        $canDo = ContentHelper::getActions('com_dnbooking');
		$authorized = $user->authorise('reservation.edit', 'com_dnbooking');

		ToolbarHelper::title(Text::_('COM_DNBOOKING_HEADLINE_RESERVATIONS'), 'list com_dnbooking');

		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

        if ($canDo->get('core.create') || \count($user->getAuthorisedCategories('com_dnbooking', 'core.create')) > 0)
		{
			$toolbar->addNew('reservation.add');
		}

        if (!$this->isEmptyState && ($canDo->get('core.edit') || $canDo->get('reservation.edit')))
		{
            $dropdown = $toolbar->dropdownButton('status-group')
				->text('JTOOLBAR_CHANGE_STATUS')
				->toggleSplit(false)
				->icon('icon-ellipsis-h')
				->buttonClass('btn btn-action')
				->listCheck(true);

			$childBar = $dropdown->getChildToolbar();

			$childBar->publish('reservations.publish')
				->text('COM_DNBOOKING_FIELD_RESERVATION_STATUS_PUBLISHED')
	            ->icon('fa-solid fa-arrows-rotate')
				->listCheck(true);

            $childBar->publish('reservations.downpayment')
	            ->text('COM_DNBOOKING_FIELD_RESERVATION_STATUS_DOWN_PAYMENT_MADE')
	            ->icon('fa-solid fa-euro-sign')
	            ->listCheck(true);

			$childBar->publish('reservations.downpayment_locale')
	            ->text('COM_DNBOOKING_FIELD_RESERVATION_STATUS_DOWN_PAYMENT_LOCALE')
	            ->icon('fa-solid fa-house')
	            ->listCheck(true);

			$childBar->archive('reservations.archive')
				->text('COM_DNBOOKING_FIELD_RESERVATION_STATUS_ARCHIVED')
				->icon('icon-check')
				->listCheck(true);

			$childBar->unpublish('reservations.unpublish')
				->text('COM_DNBOOKING_FIELD_RESERVATION_STATUS_UNPUBLISHED')
				->listCheck(true);

            if ($this->state->get('filter.published') != -2)
			{
				$childBar->trash('reservations.trash')->listCheck(true);
			}
        }
        if (!$this->isEmptyState && $this->state->get('filter.published') == -2 && ($canDo->get('core.delete') || $canDo->get('reservation.delete')))
		{
			$toolbar->delete('reservations.delete')
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
