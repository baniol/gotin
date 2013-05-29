<?php

/**
 * Send mail library
 *
 * @license     http://www.opensource.org/licenses/mit MIT License
 * @link        http://bundles.laravel.com/bundle/gotin
 * @filesource
 * @author      Marcin Baniowski
 * @package     Gotin\Lbraries
 */

class Mailer {

	/**
	 * Sends an email
	 *
	 * @param string  $email
	 * @param string  $title
	 * @param string $content
	 * @return boolean
	 */
	public static function send($email, $title, $content) {

		Bundle::start('swiftmailer');

		$transporter = Swift_SmtpTransport::newInstance('smtp.googlemail.com', Config::get('gotin::gotin.smtp_port'), 'ssl')
		->setUsername(Config::get('gotin::gotin.smtp_user'))
		->setPassword(Config::get('gotin::gotin.smtp_password'));

		$mailer = Swift_Mailer::newInstance($transporter);

		$message = Swift_Message::newInstance($title)
		->setFrom(array(Config::get('gotin::gotin.from_email') => Config::get('gotin::gotin.from_title')))
		->setTo(array($email => $email))
		->setBody($content, 'text/html');

		return $mailer->send($message);
	}


}
