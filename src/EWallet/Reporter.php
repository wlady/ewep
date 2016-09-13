<?php
/**
 * Created by PhpStorm.
 * User: wlady2001
 * Date: 19.08.16
 * Time: 21:42
 */

namespace EWallet;


class Reporter
{
    use BaseContainer;

    const SEND_COMPAIN_TO = 'wlady2001@gmail.com';
    const SEND_ALARM_TO = 'wlady2001@gmail.com';

    protected $transport;
    protected $mailer;

    public function init()
    {
        $this->transport = \Swift_SendmailTransport::newInstance();
        $this->mailer = \Swift_Mailer::newInstance($this->transport);
    }

    public function complain($user, $params)
    {
        $subject = 'Complain';
        $from = $user->email;
        $reply = $user->email;
        $to = self::SEND_COMPAIN_TO;
        $body = $this->container->view->fetch('emails/complain.tpl', ['params' => $params]);
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setReplyTo($reply)
            ->addPart($body, 'text/html');
        $this->mailer->send($message);
    }

    public function alarm($user, $params)
    {
        $subject = 'Alarm';
        $from = $user->email;
        $reply = $user->email;
        $to = self::SEND_ALARM_TO;
        $body = $this->container->view->fetch('emails/alarm.tpl', ['params' => $params]);
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setReplyTo($reply)
            ->addPart($body, 'text/html');
        $this->mailer->send($message);
    }
}
