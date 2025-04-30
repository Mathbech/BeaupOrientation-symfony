<?php

namespace App\MessageHandler;

use App\Messages\NotificationMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class NotificationMessageHandler
{
    public function __invoke(NotificationMessage $message): void
    {
        // Traitement du message
        // Exemple : log, notif, ou juste un dump
        dump('Message reçu via Redis : ' . $message->content);
    }
}
