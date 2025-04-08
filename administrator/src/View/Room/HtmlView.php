<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Administrator\View\Room;
 
\defined('_JEXEC') or die;

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View to edit a Room.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The \JForm object
	 *
	 * @var  \JForm
	 */
	protected $form;


	/**
	 * The active item
	 *
	 * @var  object
	 */
	protected $item;

    /**
     * The model state
     *
     * @var  object
     */
    protected $state;
	
	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');
		
		$this->addToolbar();

		return parent::display($tpl);
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
		// disable Joomla main menue
		Factory::getApplication()->input->set('hidemainmenu', true);
		
		$user = Factory::getApplication()->getIdentity();
		$canDo = ContentHelper::getActions('com_dnbooking');
		
		$isNew = ($this->item->id == 0);
		
		if ($isNew)
		{
			ToolbarHelper::title(Text::_('COM_DNBOOKING_MANAGER_ROOM_NEW'), 'home com_dnbooking');
		}
		else
		{
			ToolbarHelper::title(Text::_('COM_DNBOOKING_MANAGER_ROOM_EDIT'), 'home com_dnbooking');
		}
		
		$toolbarButtons = [];

		// If a new room, can save the room.  Allow users with edit permissions to apply changes to prevent returning to grid.
		if ($isNew && $canDo->get('core.create'))
		{
			if ($canDo->get('core.edit'))
			{
				ToolbarHelper::apply('room.apply');
			}

			$toolbarButtons[] = ['save', 'room.save'];
		}

		// If not checked out, can save the room.
		if (!$isNew && $canDo->get('core.edit'))
		{
			ToolbarHelper::apply('room.apply');

			$toolbarButtons[] = ['save', 'room.save'];
		}

		// If the user can create new rooms, allow them to see Save & New
		if ($canDo->get('core.create'))
		{
			$toolbarButtons[] = ['save2new', 'room.save2new'];
		}

		// If an existing room, can save to a copy only if we have create rights.
		if (!$isNew && $canDo->get('core.create'))
		{
			$toolbarButtons[] = ['save2copy', 'room.save2copy'];
		}

		ToolbarHelper::saveGroup(
			$toolbarButtons,
			'btn-success'
		);

		if (empty($this->item->id))
		{
			ToolbarHelper::cancel('room.cancel');
		}
		else
		{
			ToolbarHelper::cancel('room.cancel', 'JTOOLBAR_CLOSE');
		}
		
		ToolbarHelper::divider();
        
        if (version_compare(JVERSION, 4.2, '>='))
		{
            // inline help button
            $inlinehelp  = (string) $this->form->getXml()->config->inlinehelp['button'] == 'show' ?: false;
            if ($inlinehelp)
            {
                ToolbarHelper::inlinehelp();
            }
        }
        
		
		ToolbarHelper::help('index', true);
		
	}
}
