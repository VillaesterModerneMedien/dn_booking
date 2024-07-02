<?php

use Joomla\CMS\Component\ComponentHelper;

defined('_JEXEC') or die;
$params = ComponentHelper::getParams('com_dnbooking');
$emailHeader = $params->get('emailheader');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
	  xmlns:v="urn:schemas-microsoft-com:vml"
	  xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0 " />
	<meta name="format-detection" content="telephone=no"/>
	<style type="text/css">
		body { -webkit-text-size-adjust: 100% !important; -ms-text-size-adjust: 100% !important; -webkit-font-smoothing: antialiased !important; }
		table { border-collapse: collapse; mso-table-lspace: 0px; mso-table-rspace: 0px; }
		td, a, span { border-collapse: collapse; mso-line-height-rule: exactly; }
        .footerTable td {padding-left:10px; padding-right:10px;}
        .headerTable td{padding:0;}
        .emailBody{padding:10px;background-color:#edfbff;}
	</style>
	<!--[if gte mso 9]><xml>
		<o:OfficeDocumentSettings>
			<o:AllowPNG/>
			<o:PixelsPerInch>96</o:PixelsPerInch>
		</o:OfficeDocumentSettings>
	</xml><![endif]-->
</head>
<body>
<?= $emailHeader; ?>
<div class="emailBody">


