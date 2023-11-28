<?php

namespace Account\Domain;

enum AccountType: string
{
    case Common = "common";
    case Merchant = "merchant";
}