<?php

namespace Transaction\Application\UseCases\DTO;

class MailerSendOutput
{
    public function __construct(
        public bool $success
    )
    {
    }
}