<?php

namespace App\Http\Controllers\Lists;

use App\Http\Controllers\Controller;
use App\Support\Enums\Role;
use \App\Http\Resources\Role as RoleResource;

class RoleController extends Controller
{
    public function __invoke()
    {
        $this->authorize('list-roles');

        return RoleResource::collection(Role::cases());
    }
}
