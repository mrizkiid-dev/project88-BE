<?php

namespace App\Enums;

enum StatusOrderEnum: string
{
    case PENDING = "pending";
    case PAID = "paid";
    case SEND = "send";
    case NEED_CONFIRMATION = "need_confirmation";
    case DONE = "done";
    case CANCEL = "cancel";
}