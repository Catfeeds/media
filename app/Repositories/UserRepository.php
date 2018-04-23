<?php
namespace App\Repositories;

use App\User;

class UserRepository extends BaseRepository
{
    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }


    public function addUser(
        $request
    )
    {
        \DB::beginTransaction();
        try {
            $user = $this->model->create([
                'tel' => $request->tel,
                'real_name' => $request->real_name,
                'nick_name' => $request->nick_name,
                'ascription_store' => $request->ascription_store,
                'level' => $request->level,
                'role' => $request->role,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'remark' => $request->remark,
            ]);

            if (!$user) {
                throw new \Exception('用户添加失败');
            }

            $user->assignRole($request->role);

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \DB::rollBack();
            return false;
        }

    }

}