<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;

abstract class UserBasedController
{
    public function getUser(): User
    {
        if (!auth('sanctum')->check()) {
            throw new \RuntimeException('User not authenticated.');
        }

        return auth('sanctum')->user();
    }
}
