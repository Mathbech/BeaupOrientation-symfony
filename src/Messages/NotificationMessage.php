<?php

namespace App\Message;

class NotificationMessage
{
    public function __construct(
        public readonly string $content
    ) {}
}
