<?php

namespace App\Messages;

class NotificationMessage
{
    public function __construct(
        public readonly string $content
    ) {}
}
