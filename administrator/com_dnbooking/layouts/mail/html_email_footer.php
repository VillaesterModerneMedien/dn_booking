<?php

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;
$params = ComponentHelper::getParams('com_dnbooking');
?>

            <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td>
                        <h4><?php echo Text::_('COM_DNBOOKING_EMAIL_FOOTER_ADDRESS_LABEL'); ?></h4>
	                    <?php echo $params->get('vendor_address'); ?>
                    </td>
                    <td>
                        <h4><?php echo Text::_('COM_DNBOOKING_EMAIL_FOOTER_CONTACT_LABEL'); ?></h4>
                        Email: <?php echo $params->get('vendor_email'); ?><br>
                        Von: <?php echo $params->get('vendor_from'); ?><br>
                        Telefon: <?php echo $params->get('vendor_phone'); ?>
                    </td>
                    <td>
                        <h4><?php echo Text::_('COM_DNBOOKING_EMAIL_FOOTER_ACCOUNT_LABEL'); ?></h4>
                        <?php echo $params->get('vendor_accountdata'); ?>
                    </td>
                </tr>
        </table>
    </body>
</html>
