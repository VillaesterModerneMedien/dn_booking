<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   (C) 2015 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormFactoryInterface;

/** @var \Joomla\CMS\Form\Form $form */
$form = Factory::getContainer()
	->get(FormFactoryInterface::class)
	->createForm('chooseDay', ['control' => 'chooseDay']);

$form->loadFile('choose_daysheet');

?>

<div class="p-3">
	<?php echo $form->renderFieldset('choose_daysheet'); ?>
</div>
