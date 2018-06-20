<?php
namespace App\Repositories;

use App\Handler\Common;
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
     * 说明：获取成员列表
     *
     * @param User $user
     * @param $request
     * @return mixed
     * @author jacklin
     */
    public function userList(User $user, $request)
    {
        $result = $this->model->where('level', '!=', 1);

        if ($user->level === 2) {
            // 获取该区域经理下的所有 门店
            $store = Storefront::where('area_manager_id', $user->id)->get()->pluck('id')->toArray();
            $result = User::whereIn('ascription_store', $store)->whereIn('level', [3,4,5]);
        }

        if($user->level === 3) {
            // 获取当前门店 下 除了自己的员工
            $result = User::where('ascription_store', $user->ascription_store)->where('id', '!=', $user->id)->whereIn('level',[4,5]);
        }

        if (!empty($request->shopId)) {
            $result = $result->where('ascription_store', $request->shopId);
        }

        if (!empty($request->name)) {
            $result = $result->where('real_name', $request->name)->orWhere('tel', $request->name);
        }

        return $result->paginate($request->per_page??10);
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
            if (empty($request->ascription_store)) {
                $ascription_store = null;
            } else {
                $ascription_store = $request->ascription_store;
            }

            if (empty($request->group_id)) {
                // 清空组长id
                $group_id = null;
            } else {
                $group_id = $request->group_id;
            }

            $user = $this->model->create([
                'tel' => $request->tel,
                'real_name' => $request->real_name,
                'ascription_store' => $ascription_store,
                'level' => $request->level,
                'password' => bcrypt($request->password),
                'remark' => $request->remark,
                'group_id' => $group_id    // 所属组
            ]);
            if (!$user) throw new \Exception('用户添加失败');

            if ($request->level == 3) {
                $storefront = Storefront::where('id', $request->ascription_store)->update(['user_id' => $user->id]);
                if (!$storefront) throw new \Exception('添加店长失败');
            }

            // 组长权限跟业务员一样
            if ($request->level == 5) {
                $request->level = 4;
            }

            $user->assignRole($request->level);
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
            $user->level = $request->level;
            $user->remark = $request->remark;

            if (empty($request->ascription_store)) {
                $user->ascription_store = null;
            } else {
                $user->ascription_store = $request->ascription_store;
            }

            if (empty($request->group_id)) {
                // 清空组长id
                $user->group_id = null;
            } else {
                $user->group_id = $request->group_id;
            }

            if (!$user->save()) throw new \Exception('用户修改失败');

            if($request->level == 3) {
                $storefront = Storefront::find($request->ascription_store);
                $storefront->user_id = $user->id;
                if (!($storefront->save())) throw new \Exception('店长修改失败');
            }

            $user->syncRoles($request->level);
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
     * @param $request
     * @return bool
     * @author 刘坤涛
     */
    public function changePassword(User $user, $request)
    {
        $user->password = bcrypt($request->password);
         if ($user->save()) {
             return true;
         }
         return false;
    }

    /**
     * 说明:修改电话
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

    /**
     * 说明: 获取所有区域经理
     *
     * @return mixed
     * @author 罗振
     */
    public function getAllAreaManager($where = [])
    {
        return $this->model->where($where)->where('level', 2)->get();
    }

}