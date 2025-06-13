<?php

namespace App\MessageHandler;

use App\Message\SendEmailNotification;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;

#[AsMessageHandler]
final class SendEmailNotificationHandler {

    public function __invoke(SendEmailNotification $message): void {
        $this->mailer->send(
                (new Email())
                        ->from('mansourilamine19@gmail.com')
                        ->to('mansourilamine19@gmail.com')
                        ->subject('Important action made')
                        ->html('<h1>Important action</h1><p>Made by ' . '</p>')
        );
    }

    public function __construct(
            private ContainerBagInterface $params,
            private MailerInterface $mailer,
    ) {
        
    }
}
