<?php

namespace App\Repositories;

use App\Models\Role;

class RolesRepository extends BaseRepository
{

    private $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function rolesList($request)
    {
        return $this->model->paginate(10);

    }

    public function addRoles($request)
    {
        \DB::beginTransaction();
        try {
            $role = $this->model->create([
                'name' => $request->name,
                'name_en' => $request->name_en,
                'name_cn' => $request->name_cn,
                'guard_name' => 'web'
            ]);
            if (empty($role)) {
                throw new \Exception('角色信息添加失败');
            }
            $role->givePermissionTo($request->permission);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('添加'.$request->name_cn.'角色失败'.$e->getMessage());
            return false;
        }
    }



}