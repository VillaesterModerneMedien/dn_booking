<?php

/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   Form    $form       The form instance for render the section
 * @var   string  $basegroup  The base group name
 * @var   string  $group      Current group name
 * @var   array   $buttons    Array of the buttons that will be rendered
 */

?>

<div class="subform-repeatable-group uk-child-width-1-3" uk-grid data-base-name="<?php echo $basegroup; ?>" data-group="<?php echo $group; ?>">
<?php foreach ($form->getGroup('') as $field) : ?>
    <?php echo $field->renderField(); ?>
<?php endforeach; ?>
</div>
