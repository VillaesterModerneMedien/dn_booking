<?php
defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;

$params = ComponentHelper::getParams('com_dnbooking');

?>

<table class="footer">
    <tr>
        <td>
            <h4><?php echo Text::_('COM_DNBOOKING_EMAIL_FOOTER_ADDRESS_LABEL'); ?></h4>
			<?php echo $params->get('vendor_address'); ?>
        </td>
        <td>
            <h4><?php echo Text::_('COM_DNBOOKING_EMAIL_FOOTER_CONTACT_LABEL'); ?></h4>
            <?php echo $params->get('vendor_email'); ?><br />
            <?php echo $params->get('vendor_from'); ?><br />
            <?php echo $params->get('vendor_phone'); ?>
        </td>
        <td>
            <h4><?php echo Text::_('COM_DNBOOKING_EMAIL_FOOTER_ACCOUNT_LABEL'); ?></h4>
			<?php echo $params->get('vendor_accountdata'); ?>
        </td>
    </tr>
</table>
