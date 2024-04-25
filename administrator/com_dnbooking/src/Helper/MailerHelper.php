<?php


use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

class MailerHelper
{

	function sendBappuMail($offerData, $formData, $anemometer, $vocoo)
	{
		$app = Factory::getApplication();
        $module = JModuleHelper::getModule('bappu_konfigurator');
        $moduleParams = new JRegistry();
        $params = $moduleParams->loadString($module->params);

        $currency = Text::_('BAPPU_STD_CURRENCY');

        $customerData = array();
        parse_str($formData, $customerData);

        $options = [];

		foreach ($offerData as $option)
        {
            $option = str_replace('-', '_', $option);

            $options[$option] = $params->get($option . '_price');
        }

		$customerList = '';
        $emailKunde = '';

        $workshopValueChoosen = 0;
		foreach ($customerData as $key => $customer)
        {

            if($key != 'workshopOption' && $key != 'discountValue'){
                $customerList .= '<li><strong>' . Text::_('FORM_LABEL_' . $key) . '</strong>: ' . $customer . '</li>';
            }
            if($key == 'workshopOption'){
                $workshopValueChoosen = (int) $customer;
            }
            $emailKunde = $customerData['email'];
        }


		$tbody = '<tbody>';

		$totalPrice = 0;

		foreach($options as $key => $price)
		{

			$tbody .=  '<tr>';

			if($key == 'workshop'){
			    if($workshopValueChoosen == 1 || $workshopValueChoosen == 2){
			        $price = $params->get('workshop_price');
                }
			    else{
			        $price = 0;
                }
            }

			if($key == 'bappaeck'){
			    if($anemometer == 1){
			        $price = (int) $params->get('bappaeck_price') + 22;
                }
                if($vocoo == 1){
                    $price = (int) $params->get('bappaeck_price') + 22;
                }
                if($vocoo == 1 && $anemometer == 1){
                    $price = (int) $params->get('bappaeck_price') + 44;
                }
            }
			$tbody .= '<td style="border: 1px solid #CCC;">' . Text::_($key . '_TITLE') . '</td><td style="border: 1px solid #CCC;">' . number_format($price, 2, ',', '.') . ' ' . $currency . '</td>';

			$tbody .= '</tr>';

			$totalPrice = $totalPrice + $price;
		}

            $tbody .=  '<tr>';

            $tbody .= '<td style="border: 1px solid #CCC;">' . Text::_('BAPPU_TOTAL_PRICE') . '</td><td style="border: 1px solid #CCC;">' . number_format($totalPrice, 2, ',', '.') . ' ' . $currency . '</td>';

            $tbody .= '</tr>';

			$tbody .= '</tbody>';


		$mailtext = '<html>';
		$mailtext .=    '<head>';
		$mailtext .=        '<title>' . Text::_('MAIL_HEADLINE') . '</title>';
		$mailtext .=        '<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">';
		$mailtext .=    '</head>';
		$mailtext .=    '<style>';
		//$mailtext .=        $style;
		$mailtext .=    '</style>';
		$mailtext .=    '<body style="font-family:\'Open Sans\', Arial, sans-serif; background: #F3F3F3; text-align:center">';
		$mailtext .=        '<table id="layout" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; width: 100%; margin: 20px;box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.38); background: #FFF;">';
        $mailtext .=            '<tr>';
        $mailtext .=            '    <td style="padding: 30px;">';
        $mailtext .=            '        <img src="' .  JURI::root() . 'images/system/logo-elk-retina.png" style="width: 150px;">';
        $mailtext .=            '    </td>';
        $mailtext .=            '    <td style="font-size: 12px;">';
        $mailtext .=            '<p><strong>ELK - Gesellschaft für Erstellung, Layout und Konzeption elektronischer Systeme mbH</strong></p>
                                            <ul class="uk-list" style="list-style: none; margin: 0; padding: 0">        
                                            <li class="el-item">
                                            Gladbacher Straße 232 - 47805 Krefeld
                                            </li>
                                            <li class="el-item">
                                            Tel. 0 2151 788 86-0 | Fax. 0 2151 788 86-02 | info@elk.de' . '
                                            </li>
                        
                                                </ul>';
        $mailtext .=            '    </td>';
        $mailtext .=            '</tr>';
		$mailtext .=            '<tr style="background: #2C4C9C; height: 75px; ">';
        $mailtext .=            '    <td colspan="2" style="vertical-align:middle; padding: 15px;">';
        $mailtext .=                '<h1 style="font-size: 20px; font-weight: 400;color: #FFF; text-transform: uppercase; display: block; width: 100%; text-align: center">' . Text::_('MAIL_HEADLINE') . '</h1>';
        $mailtext .=            '    </td>';
		$mailtext .=            '</tr>';
        $mailtext .=            '<tr>';
        $mailtext .=            '    <td colspan="2" style="padding: 30px 0;">';
        $mailtext .=                    '<ul style="list-style-type: none">';
        $mailtext .=                        $customerList;
        $mailtext .=                    '</ul>';
        $mailtext .=            '    </td>';
        $mailtext .=            '</tr>';
        $mailtext .=            '<tr>';
        $mailtext .=            '    <td colspan="2" style="padding: 30px; padding-bottom: 50px;">';
        $mailtext .=                    '<table border="0" style="margin: 0 auto;text-align:left; border-collapse: collapse;width: 90%"><thead><tr><th style="border: 1px solid #CCC;">Option</th><th style="border: 1px solid #CCC;" colspan="2">Preis</th></tr></thead>';
        $mailtext .=                    $tbody;
        $mailtext .=                    '</table>';
        if(!empty($customerData['workshopOption'])){
            switch ($customerData['workshopOption']){
                case 1:
                    $mailtext .= '<p><strong>Gewählter Workshop-Typ:</strong>' . Text::_('WORKSHOP_OPTION_1') . '</p>';
                    break;
                case 2:
                    $mailtext .= '<p><strong>Gewählter Workshop-Typ:</strong>' . Text::_('WORKSHOP_OPTION_2') . '</p>';
                    break;
                case 3:
                    $mailtext .= '<p><strong>Gewählter Workshop-Typ:</strong>' . Text::_('WORKSHOP_OPTION_3') . '</p>';
                    break;
                case 4:
                    $mailtext .= '<p><strong>Gewählter Workshop-Typ:</strong>' . Text::_('WORKSHOP_OPTION_4') . '</p>';
                    break;
            }
        }
        $mailtext .= '<p><strong>Alle Preise zzgl. MwSt.</strong></p>';

        if(!empty($customerData['discountValue'])){
            $mailtext .= '<p><strong>Der Kunde erhält einen Rabatt in Höhe von: </strong>' . $customerData['discountValue'] . '</p>';
        }
        $mailtext .=            '    </td>';
        $mailtext .=            '</tr>';
        $mailtext .=            '<tr style="background: #2C4C9C; font-size: 14px;">';
        $mailtext .=            '    <td colspan="2" style="padding: 30px; color: #FFF;">';
        $mailtext .=                        '
                                            <p>BAPPU ist ein Produkt der</p>
                                            <p><strong>ELK - Gesellschaft für Erstellung, Layout und Konzeption elektronischer Systeme mbH</strong></p>
                                            <ul class="uk-list" style="list-style: none; margin: 0; padding: 0">        
                                            <li class="el-item">
                                            Vertreten durch: Axel Stamm
                                            </li>                                            
                                            <li class="el-item">
                                            Registergericht: Amtsgericht Krefeld - Registernummer: HRB 5685
                                            </li>                                            
                                            <li class="el-item">
                                            Gladbacher Straße 232 - 47805 Krefeld
                                            </li>
                                            <li class="el-item">
                                            Tel. 0 2151 788 86-0 | Fax. 0 2151 788 86-02 | <a style="color: #FFF" href="mailto:info@elk.de">info@elk.de</a> | <a style="color: #FFF" href="https://www.elk.de">www.elk.de</a>
                                            </li>
                        
                                                </ul>';
        $mailtext .=            '    </td>';
        $mailtext .=            '</tr>';
		$mailtext .=        '</table>';

		$mailtext .= '	</body>';
		$mailtext .= '</html>';

		$empfaenger = "team@bappu.de";
		$absender   = "info@elk.de";
		$subject    = "Anfrage bappu.de Konfigurator";

		//$send = JFactory::getMailer()->isHtml(true)->sendMail($absender, 'BDK', $empfaenger, $subject, $mailtext);

		$mailer = Factory::getMailer();

		$mailer->setSender($absender);
		$mailer->addRecipient($empfaenger);
		$mailer->addRecipient($emailKunde);
		$mailer->addRecipient('info@whykiki.de');
		$mailer->isHtml( true);
		$mailer->CharSet  = 'UTF-8';
		$mailer->Encoding = 'base64';
		$mailer->setSubject($subject);
		$mailer->setBody($mailtext);
		$mailer->Send();

		return 'Mail sent';
	}
}
