<?php

namespace App\Repositories;

use App\Models\MediaPermissionGroup;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class PermissionsRepository extends Model
{
    public function mediaPermissionsList()
    {
        return Permission::where(['guard_name' => 'web'])->paginate(10);
    }

    public function addPermissions(
        $request
    )
    {
        return Permission::create([
            'name' => $request->name,
            'label' => $request->label,
            'group_id' => $request->group_id,
            'guard_name' => 'web'
        ]);
    }

    public function updatePermissions(
        $request,
        Permission $permission
    )
    {
        $permission->name = $request->name;
        $permission->label = $request->label;
        $permission->group_id = $request->group_id;

        if (!$permission->save()) return false;
        return true;
    }

    public function permissionsGroup()
    {
        return MediaPermissionGroup::where(['stage' => 1])->get();
    }
}