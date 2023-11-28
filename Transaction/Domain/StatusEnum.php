<?php

namespace Transaction\Domain;

enum StatusEnum: string
{
    case Started = "started";
    case InProgress = "in_progress";
    case Cancelled = "cancelled";
    case Completed = "completed";
}
