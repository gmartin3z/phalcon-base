<?php

namespace App\Libraries\Mailer;

use Phalcon\Mvc\User\Component;
use Phalcon\Mvc\View;
use Swift_Mailer as SwiftMail;
use Swift_Message as Message;
use Swift_SmtpTransport as Smtp;

class Mailer extends Component
{
    public function getTemplate($name, $params)
    {
        return $this->view->getRender('emails', $name, $params, function ($view) {
            $view->setRenderLevel(View::LEVEL_LAYOUT);
        });

        return $view->getContent();
    }

    public function send($to, $subject, $name, $params)
    {
        $mail_settings = $this->config->mail;
        $template      = $this->getTemplate($name, $params);

        if ($mail_settings->useMail == true) {
            $prepare_message = new Message();
            $prepare_message->setSubject($subject)
                ->setTo($to)
                ->setFrom([$mail_settings->fromEmail => $mail_settings->fromName])
                ->setBody($template, 'text/html');

            $transport = new Smtp($mail_settings->smtp->server, $mail_settings->smtp->port, $mail_settings->smtp->security);
            $transport
                ->setUsername($mail_settings->smtp->username)
                ->setPassword($mail_settings->smtp->password);

            $message = new SwiftMail($transport);
            $message->send($prepare_message);

            return $message;
        } else {
            $logger = $this->logger;
            $logger->begin();
            $logger->info(PHP_EOL . $template);
            $logger->commit();
        }
    }
}
