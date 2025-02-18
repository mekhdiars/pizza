<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Preparing = 'preparing';
    case Delivering = 'delivering';
    case Received = 'received';
}
