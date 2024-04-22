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

$fields = $form->getGroup('');
$fieldsCount = count($fields);
$class = 'uk-width-1-' . $fieldsCount . '@m uk-flex-bottom uk-grid-item-match';
?>

<?php foreach ($form->getGroup('') as $field) : ?>
    <div class="<?= $class; ?>">
        <?php echo $field->renderField(); ?>
    </div>
<?php endforeach; ?>
