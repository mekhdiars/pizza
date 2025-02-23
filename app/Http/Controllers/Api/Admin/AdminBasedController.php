<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Admin;

abstract class AdminBasedController
{
    public function getAdmin(): Admin
    {
        if (!auth('sanctum')->check()) {
            throw new \RuntimeException('Admin not authenticated.');
        }

        return auth('sanctum')->user();
    }
}
