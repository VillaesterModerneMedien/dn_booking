<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dnbooking
 *
 * @copyright   Copyright (C) 2024 Mario Hewera. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Administrator\View\Customer;

\defined('_JEXEC') or die;

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View to edit a Customer.
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
	 * The actions the user is authorised to perform
	 *
	 * @var  \Joomla\Registry\Registry
	 */
	protected $canDo;


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
     * @param string $tpl The name of the template file to parse; automatically searches through the template
     *                        paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');
	    $this->canDo = ContentHelper::getActions('com_dnbooking', 'customer', $this->item->id);

        if ($this->getLayout() === 'modalreturn') {
            parent::display($tpl);

            return;
        }

        // Check for errors.
        if (\count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        // If we are forcing a language in modal (used for associations).
        if ($this->getLayout() === 'modal' && $forcedLanguage = Factory::getApplication()->getInput()->get('forcedLanguage', '', 'cmd')) {
            // Set the language field to the forcedLanguage and disable changing it.
            $this->form->setValue('language', null, $forcedLanguage);
            $this->form->setFieldAttribute('language', 'readonly', 'true');

            // Only allow to select categories with All language or with the forced language.
            $this->form->setFieldAttribute('catid', 'language', '*,' . $forcedLanguage);

            // Only allow to select tags with All language or with the forced language.
            $this->form->setFieldAttribute('tags', 'language', '*,' . $forcedLanguage);
        }

        if ($this->getLayout() !== 'modal') {
            $this->addToolbar();
        } else {
            $this->addModalToolbar();
        }

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

        if ($isNew) {
            ToolbarHelper::title(Text::_('COM_DNBOOKING_MANAGER_CUSTOMER_NEW'), 'home com_dnbooking');
        } else {
            ToolbarHelper::title(Text::_('COM_DNBOOKING_MANAGER_CUSTOMER_EDIT'), 'home com_dnbooking');
        }

        $toolbarButtons = [];

        // If a new customer, can save the customer.  Allow users with edit permissions to apply changes to prevent returning to grid.
        if ($isNew && $canDo->get('core.create')) {
            if ($canDo->get('core.edit')) {
                ToolbarHelper::apply('customer.apply');
            }

            $toolbarButtons[] = ['save', 'customer.save'];
        }

        // If not checked out, can save the customer.
        if (!$isNew && $canDo->get('core.edit')) {
            ToolbarHelper::apply('customer.apply');

            $toolbarButtons[] = ['save', 'customer.save'];
        }

        // If the user can create new customers, allow them to see Save & New
        if ($canDo->get('core.create')) {
            $toolbarButtons[] = ['save2new', 'customer.save2new'];
        }

        // If an existing customer, can save to a copy only if we have create rights.
        if (!$isNew && $canDo->get('core.create')) {
            $toolbarButtons[] = ['save2copy', 'customer.save2copy'];
        }

        ToolbarHelper::saveGroup(
            $toolbarButtons,
            'btn-success'
        );

        if (empty($this->item->id)) {
            ToolbarHelper::cancel('customer.cancel');
        } else {
            ToolbarHelper::cancel('customer.cancel', 'JTOOLBAR_CLOSE');
        }

        ToolbarHelper::divider();

        if (version_compare(JVERSION, 4.2, '>=')) {
            // inline help button
            $inlinehelp = (string)$this->form->getXml()->config->inlinehelp['button'] == 'show' ?: false;
            if ($inlinehelp) {
                ToolbarHelper::inlinehelp();
            }
        }


        ToolbarHelper::help('index', true);

    }

    /**
     * Add the modal toolbar.
     *
     * @return  void
     *
     * @throws  \Exception
     * @since   5.0.0
     *
     */
    protected function addModalToolbar()
    {
        $user = $this->getCurrentUser();
        $userId = $user->id;
        $customer = $this->item;
        $isNew = ($this->item->id == 0);
        $canDo = $this->canDo;

        $toolbar = Toolbar::getInstance();
        //$toolbar    = Factory::getContainer()->get(ToolbarFactoryInterface::class)->createToolbar('toolbar');

        $headline = Text::sprintf('COM_DNBOOKING_HEADLINE_CUSTOMER_MODAL', $customer->firstname . ' ' . $customer->lastname . ' (ID:' . $customer->id . ')');

	    ToolbarHelper::title(
		    $headline,
		    'pencil-alt article-add'
	    );

	    $canCreate = $isNew && (\count($user->getAuthorisedCategories('com_dnbooking', 'customer.create')) > 0);
	    $canEdit   = $canDo->get('customer.edit') || $this->item->created_by == $userId;

	    // For new records, check the create permission.
	    if ($canCreate || $canEdit) {
		    $toolbar->save('customer.save');
	    }

	    $toolbar->cancel('customer.cancel');
    }
}
