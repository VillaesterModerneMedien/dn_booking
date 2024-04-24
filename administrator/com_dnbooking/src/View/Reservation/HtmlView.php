<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Administrator\View\Reservation;

\defined('_JEXEC') or die;


use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View to edit a Reservation.
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
        $this->customer = $this->get('Customer');



		$this->addToolbar($this->item);

		return parent::display($tpl);
	}

    /**
     * Displays a toolbar for a specific page.
     *
     * @return  void
     *
     * @since   1.0.0
     */
    private function addToolbar($item)
    {
        $app = Factory::getApplication();
        $app->input->set('hidemainmenu', \true);
        $id = $item->id;
		$headline = Text::_('COM_DNBOOKING_HEADLINE_NEW_RESERVATION');

		if(!empty($item->id)){
		    $customer = $this->customer->firstname . ' ' . $this->customer->lastname;
	        $created = \date("d.m.Y | H:i", \strtotime($item->created));
            $headline = Text::sprintf('COM_DNBOOKING_HEADLINE_RESERVATION', $id, $created, $customer) ;
		}

        ToolbarHelper::title($headline);

        ToolbarHelper::apply('reservation.apply');
        ToolbarHelper::save('reservation.save');
        ToolbarHelper::cancel('reservation.cancel');
    }
}
