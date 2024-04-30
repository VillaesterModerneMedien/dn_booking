<?php

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;
$params = ComponentHelper::getParams('com_dnbooking');
$emailFooter = $params->get('emailfooter');

?>
<?= $emailFooter;?>

    </body>
</html>
