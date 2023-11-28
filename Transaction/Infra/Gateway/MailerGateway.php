<?php

namespace Transaction\Infra\Gateway;

use Transaction\Application\UseCases\DTO\MailerSendInput;
use Transaction\Application\UseCases\DTO\MailerSendOutput;

interface MailerGateway
{
    public function send(MailerSendInput $input): MailerSendOutput;
}