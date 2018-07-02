<?php

namespace App\Repositories;

use App\Models\PermissionGroup;
use Illuminate\Database\Eloquent\Model;

class PermissionGroupsRepository extends Model
{
    public function permissionGroupsList($per_page)
    {
        return PermissionGroup::where(['stage' => 1])->paginate($per_page??10);
    }
    
    public function addPermissionGroups(
        $request
    )
    {
        return PermissionGroup::create([
            'group_name' => $request->group_name,
            'stage' => 1
        ]);
    }
}