<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PREPARING = 'preparing';
    case DELIVERING = 'delivering';
    case RECEIVED = 'received';
}
