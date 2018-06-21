<?php

namespace App\Http\Controllers\API;

use App\Handler\Common;
use App\Http\Requests\API\UsersRequest;
use App\Models\OwnerViewRecord;
use App\Models\Storefront;
use App\Repositories\UserRepository;
use App\Services\UsersService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends APIBaseController
{
    /**
     * 说明:获取成员列表
     *
     * @param UserRepository $userRepository
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function index
    (
        UserRepository $userRepository,
        Request $request
    )
    {
        $user = Common::user();
        if (empty($user->can('user_list'))) {
            return $this->sendError('无成员列表权限', 403);
        }

        $res = $userRepository->userList($user, $request);
        return $this->sendResponse($res, '成员列表获取成功');
    }

    /**
     * 说明: 用户信息
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function show($id)
    {
        if (empty($user = Common::user())) {
            return $this->sendError('登录账户异常', 401);
        }

        $result = $user->toArray();
        $result['access'] = $user->getAllPermissions()->pluck('name')->toArray()??[];

        // 查看业主信息 必须跟进逻辑
        $ownerViewRecord = OwnerViewRecord::where([
            'user_id' => $user->id,
            'status' => 1
        ])->first();
        if (!empty($ownerViewRecord)) {
            $result['ownerViewRecord'] = [
                'type' => $ownerViewRecord->model_type,
                'house_id' => $ownerViewRecord->house_id,
                'status' => true
            ];
        } else {
            $result['ownerViewRecord'] = [
                'status' => false
            ];
        }

        // 用户的称呼逻辑
        switch ($user->level) {
            case 1:
                $result['user_info'] = '市场总监:' . $user->real_name;
                break;
            case 2:
                $result['user_info'] = '区域经理:' . $user->real_name;
                break;
            case 3:
                $result['user_info'] = '（' . $user->storefront->storefront_name . ')' . '商圈经理:' . $user->real_name;
                break;
            case 4:
                $result['user_info'] = '（' . $user->storefront->storefront_name . ')' . '业务经理:' . $user->real_name;
                break;
            case 5:
                $result['user_info'] = '（' . $user->storefront->storefront_name . ')' . '门店经理:' . $user->real_name;
                break;
        }


        return $this->sendResponse($result, '成功');
    }

    /**
     * 说明:添加成员
     *
     * @param UsersRequest $request
     * @param UserRepository $userRepository
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function store(
        UsersRequest $request,
        UserRepository $userRepository
    )
    {
        if(empty(Common::user()->can('add_user'))) {
            return $this->sendError('无添加成员权限','403');
        }

        if ($request->password != $request->password_confirmation) {
            return $this->sendError('密码与确认密码不一致,请重新输入');
        }
        $res = $userRepository->addUser($request);
        if ($res) {
            return $this->sendResponse($res, '添加成员成功');
        }
        return $this->sendError('添加成员失败');
    }

    /**
     * 说明:获取成员修改之前原始数据
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function edit(User $user)
    {
        if ($user->level == 3) {
            // 获取原始门店数据
            $storefront = Storefront::where('user_id', $user->id)->first();
            $data['value'] = $storefront->id;
            $data['label'] = $storefront->storefront_name;
            $user->storefrontInfo = $data;
        }

        return $this->sendResponse($user, '成员修改之前原始数据');
    }

    /**
     * 说明:修改成员信息
     *
     * @param User $user
     * @param UserRepository $userRepository
     * @param UsersRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function update
    (
        User $user,
        UserRepository $userRepository,
        UsersRequest $request
    )
    {
        if (empty(Common::user()->can('update_user'))) return $this->sendError('无修改成员权限', '403');

        $res = $userRepository->updateUser($user, $request);
        if ($res) return $this->sendResponse($res, '修改成员成功');
        return $this->sendError('修改成员失败');
    }


    /**
     * 说明:修改密码
     *
     * @param UserRepository $userRepository
     * @param UsersRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function updatePassword
    (
        UserRepository $userRepository,
        UsersRequest $request
    )
    {
        $user = Common::user();
        if (!Hash::check($request->old_password, $user->password)) {
            return $this->sendError('旧密码输入错误');
        }
        $res = $userRepository->changePassword($user, $request);
        if ($res) {
            return $this->sendResponse($res, '密码修改成功');
        }
        return $this->sendError('密码修改失败');
    }

    /**
     * 说明:修改电话
     *
     * @param UserRepository $userRepository
     * @param UsersRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function updateTel
    (
        UserRepository $userRepository,
        UsersRequest $request,
        User $user
    )
    {
        // 检测手机号是否重复
        if (!empty($request->tel) && $request->tel != $user->tel && in_array($request->tel, User::pluck('tel')->toArray())) {
            return $this->sendError($request->tel . '已存在，请勿重复添加');
        }

        $res = $userRepository->changeTel($user, $request);
        if ($res) {
            return $this->sendResponse($res, '电话修改成功');
        }
        return $this->sendError('修改电话失败');
    }

    /**
     * 说明:删除成员
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @author 刘坤涛
     */
    public function destroy(User $user)
    {
        if (empty(Common::user()->can('del_user'))) {
            return $this->sendError('无删除成员权限', '403');
        }

        $res = $user->delete();
        return $this->sendResponse($res, '成员删除成功');
    }

    /**
     * 说明: 获取门店信息
     *
     * @param Request $request
     * @param UsersService $usersService
     * @return mixed
     * @author 罗振
     */
    public function getStorefrontsInfo(
        Request $request,
        UsersService $usersService
    )
    {

        $result = $usersService->getInfo($request)->map(function ($v) {
            return [
                'label' => $v->storefront_name,
                'value' => $v->id
            ];
        });
        return $this->sendResponse($result, '获取门店信息成功');
    }

    /**
     * 说明: 获取下级信息
     *
     * @param UsersService $usersService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function getSubordinateUser(
        UsersService $usersService
    )
    {
        $res = $usersService->getSubordinateUser();
        return $this->sendResponse($res, '获取下级信息成功');
    }

    /**
     * 说明: 获取门店及门店经理信息
     *
     * @param UsersService $usersService
     * @return \Illuminate\Http\JsonResponse
     * @author 罗振
     */
    public function getGroupAndStorefronts(
        UsersService $usersService
    )
    {
        $res = $usersService->getGroupAndStorefronts();
        return $this->sendResponse($res, '获取门店下所有门店经理');
    }

}
