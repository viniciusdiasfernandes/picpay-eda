<?php

namespace Transaction\Application\UseCases\DTO;

class MailerSendInput
{
    public function __construct(
        public string $email,
        public string $subject,
        public string $message
    )
    {
    }
}