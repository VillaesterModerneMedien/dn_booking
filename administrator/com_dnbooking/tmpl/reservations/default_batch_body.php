<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   (C) 2015 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormFactoryInterface;

/** @var \Joomla\CMS\Form\Form $form */
$form = Factory::getContainer()
	->get(FormFactoryInterface::class)
	->createForm('sendMails', ['control' => 'sendMails']);

$form->loadFile('send_mails');

?>

<div class="p-3">
	<?php echo $form->renderFieldset('send_mails'); ?>
</div>
