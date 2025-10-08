<?php

/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2019 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace DnbookingNamespace\Component\Dnbooking\Administrator\Extension;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Mail\Exception\MailDisabledException;
use Joomla\CMS\Mail\MailHelper;
use Joomla\CMS\Mail\MailTemplate;
use Joomla\Filesystem\Path;
use Joomla\Registry\Registry;
use PHPMailer\PHPMailer\Exception as phpmailerException;

\defined('_JEXEC') or die;

/**
 * Email Templating Class
 *
 * @since  4.0.0
 */
class DnbookingMailTemplate extends MailTemplate
{
	/**
	 * Render and send the mail
	 *
	 * @return  boolean  True on success
	 *
	 * @since   4.0.0
	 * @throws  \Exception
	 * @throws  MailDisabledException
	 * @throws  phpmailerException
	 */
	public function send()
	{
		$config = ComponentHelper::getParams('com_mails');

		$mail = self::getTemplate($this->template_id, $this->language);

		// If the Mail Template was not found in the db, we cannot send an email.
		if ($mail === null) {
			return false;
		}

		/** @var Registry $params */
		$params      = $mail->params;
		$app         = Factory::getApplication();
		$replyTo     = $app->get('replyto', '');
		$replyToName = $app->get('replytoname', '');

		if ((int) $config->get('alternative_mailconfig', 0) === 1 && (int) $params->get('alternative_mailconfig', 0) === 1) {
			if ($this->mailer->Mailer === 'smtp' || $params->get('mailer') === 'smtp') {
				$smtpauth   = ($params->get('smtpauth', $app->get('smtpauth')) == 0) ? null : 1;
				$smtpuser   = $params->get('smtpuser', $app->get('smtpuser'));
				$smtppass   = $params->get('smtppass', $app->get('smtppass'));
				$smtphost   = $params->get('smtphost', $app->get('smtphost'));
				$smtpsecure = $params->get('smtpsecure', $app->get('smtpsecure'));
				$smtpport   = $params->get('smtpport', $app->get('smtpport'));
				$this->mailer->useSmtp($smtpauth, $smtphost, $smtpuser, $smtppass, $smtpsecure, $smtpport);
			}

			if ($params->get('mailer') === 'sendmail') {
				$this->mailer->isSendmail();
			}

			$mailfrom = $params->get('mailfrom', $app->get('mailfrom'));
			$fromname = $params->get('fromname', $app->get('fromname'));

			if (MailHelper::isEmailAddress($mailfrom)) {
				$this->mailer->setFrom(MailHelper::cleanLine($mailfrom), MailHelper::cleanLine($fromname), false);
			}

			$replyTo     = $params->get('replyto', $replyTo);
			$replyToName = $params->get('replytoname', $replyToName);
		}

		$app->triggerEvent('onMailBeforeRendering', [$this->template_id, &$this]);

		$subject = $this->replaceTags(Text::_($mail->subject), $this->data);
		$this->mailer->setSubject($subject);

		//E-Mail Header Template
		$layout = new FileLayout('mail.html_email_header', JPATH_ROOT .'/administrator/components/com_dnbooking/layouts');
		$htmlEmailHeader = $layout->render();

		//E-Mail Footer Template
		$layout = new FileLayout('mail.html_email_footer', JPATH_ROOT .'/administrator/components/com_dnbooking/layouts');
		$htmlEmailFooter = $layout->render();



		$mailStyle = $config->get('mail_style', 'plaintext');
		$plainBody = $this->replaceTags(Text::_($mail->body), $this->data);
		$htmlBody  = $this->replaceTags($htmlEmailHeader . Text::_($mail->htmlbody) . $htmlEmailFooter, $this->data);

		if ($mailStyle === 'plaintext' || $mailStyle === 'both') {
			// If the Plain template is empty try to convert the HTML template to a Plain text
			if (!$plainBody) {
				$plainBody = strip_tags(str_replace(['<br>', '<br />', '<br/>'], "\n", $htmlBody));
			}

			$this->mailer->setBody($plainBody);

			// Set alt body, use $mailer->Body directly because it was filtered by $mailer->setBody()
			if ($mailStyle === 'both') {
				$this->mailer->AltBody = $this->mailer->Body;
			}
		}

		if ($mailStyle === 'html' || $mailStyle === 'both') {
			$this->mailer->isHtml(true);

			// If HTML body is empty try to convert the Plain template to html
			if (!$htmlBody) {
				$htmlBody = nl2br($plainBody, false);
			}

			$htmlBody = MailHelper::convertRelativeToAbsoluteUrls($htmlBody);

			$this->mailer->setBody($htmlBody);
		}

		if ($config->get('copy_mails') && $params->get('copyto')) {
			$this->mailer->addBcc($params->get('copyto'));
		}

		foreach ($this->recipients as $recipient) {
			switch ($recipient->type) {
				case 'cc':
					$this->mailer->addCc($recipient->mail, $recipient->name);
					break;
				case 'bcc':
					$this->mailer->addBcc($recipient->mail, $recipient->name);
					break;
				case 'to':
				default:
					$this->mailer->addAddress($recipient->mail, $recipient->name);
			}
		}

		if ($this->replyto) {
			$this->mailer->addReplyTo($this->replyto->mail, $this->replyto->name);
		} elseif ($replyTo) {
			$this->mailer->addReplyTo($replyTo, $replyToName);
		}

		if (trim($config->get('attachment_folder', ''))) {
			$folderPath = rtrim(Path::check(JPATH_ROOT . '/' . $config->get('attachment_folder')), \DIRECTORY_SEPARATOR);

			if ($folderPath && $folderPath !== Path::clean(JPATH_ROOT) && is_dir($folderPath)) {
				foreach ((array) json_decode($mail->attachments) as $attachment) {
					$filePath = Path::check($folderPath . '/' . $attachment->file);

					if (is_file($filePath)) {
						$this->mailer->addAttachment($filePath, $this->getAttachmentName($filePath, $attachment->name));
					}
				}
			}
		}

		foreach ($this->attachments as $attachment) {
			if (is_file($attachment->file)) {
				$this->mailer->addAttachment($attachment->file, $this->getAttachmentName($attachment->file, $attachment->name));
			} else {
				$this->mailer->addStringAttachment($attachment->file, $attachment->name);
			}
		}

		return $this->mailer->Send();
	}


}
