<?php
namespace App\Repositories;

use App\Models\Storefront;
use App\User;

class UserRepository extends BaseRepository
{
    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * 说明:获取成员列表
     *
     * @param $request
     * @return mixed
     * @author 刘坤涛
     */
    public function userList($request)
    {
        return $this->model->paginate(10);
    }

    /**
     * 说明:添加成员
     *
     * @param $request
     * @return bool
     * @author 刘坤涛
     */
    public function addUser($request)
    {
        \DB::beginTransaction();
        try {
            $user = $this->model->create([
                'tel' => $request->tel,
                'real_name' => $request->real_name,
                'nick_name' => $request->nick_name,
                'ascription_store' => $request->ascription_store,
                'level' => $request->level,
                'password' => bcrypt($request->password),
                'remark' => $request->remark,
            ]);
            if (!$user) {
                throw new \Exception('用户添加失败');
            }
            if($user->level != 4) {
                foreach($request->ascription_store as $v) {
                    $res = Storefront::where('id',$v)->update(['user_id'=>$user->id]);
                    if (empty($res)) {
                        throw new \Exception('区域经理/店长关联店面失败');
                    }
                }
            }
            $user->assignRole($request->role);
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::error($exception->getMessage());
            return false;
        }
    }

    /**
     * 说明:更新成员
     *
     * @param $user
     * @param $request
     * @return bool
     * @author 刘坤涛
     */
    public function updateUser($user, $request)
    {
        \DB::beginTransaction();
        try {
                $user->real_name = $request->real_name;
                $user->nick_name = $request->nick_name;
                $user->ascription_store = $request->ascription_store;
                $user->level = $request->level;
                $user->remark = $request->remark;
            if (!$user->save()) {
                throw new \Exception('用户修改失败');
            }
            if($user->level != 4) {
                foreach($request->ascription_store as $v) {
                    $res = Storefront::where('id',$v)->update(['user_id'=>$user->id]);
                    if (empty($res)) {
                        throw new \Exception('区域经理/店长关联店面失败');
                    }
                }
            }
            $user->assignRole($request->role);
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::error($exception->getMessage());
            return false;
        }
    }

    /**
     * 说明:修改密码
     *
     * @param $user
     * @param $request
     * @return bool
     * @author 刘坤涛
     */
    public function changePassword($user, $request)
    {
         $user->password= bcrypt($request->password);
         if($user->save()) {
             return true;
         }
         return false;
    }

    /**
     * 说明:修改手机号
     *
     * @param $user
     * @param $request
     * @return bool
     * @author 刘坤涛
     */
    public function changeTel($user, $request)
    {
        $user->tel = $request->tel;
        if ($user->save()) {
            return true;
        }
        return false;
    }

}