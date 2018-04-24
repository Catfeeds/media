<?php

namespace App\Http\Controllers\API;

use App\Handler\Common;
use App\Http\Requests\API\UsersRequest;
use App\Repositories\UserRepository;
use App\Services\UsersService;
use App\User;
use Illuminate\Http\Request;

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
        if(empty(Common::user()->can('user_list'))) {
            return $this->sendError('无成员列表权限',403);
        }

        $res = $userRepository->userList($request);
        return $this->sendResponse($res,'成员列表获取成功');
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
            return $this->sendResponse($res,'添加成员成功');
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
        return $this->sendResponse($user,'成员修改之前原始数据');
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
        if(empty(Common::user()->can('update_user'))) {
            return $this->sendError('无修改成员权限','403');
        }

        $res = $userRepository->updateUser($user, $request);
        if($res) {
            return $this->sendResponse($res, '修改成员成功');
        }
        return $this->sendError('修改成员失败');
    }


    /**
     * 说明:修改密码
     *
     * @param UserRepository $userRepository
     * @param UsersRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @author 刘坤涛
     */
    public function updatePassword
    (
        UserRepository $userRepository,
        UsersRequest $request,
        User $user
    )
    {
        $res = $userRepository->changePassword($user, $request);
        if ($res) {
            return $this->sendResponse($res,'密码修改成功');
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
        $res = $userRepository->changeTel($user ,$request);
        if ($res) {
            return $this->sendResponse($res,'电话修改成功');
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
        if(empty(Common::user()->can('del_user'))) {
            return $this->sendError('无删除成员权限','403');
        }

        $res = $user->delete();
        return $this->sendResponse($res,'成员删除成功');
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
        return $usersService->getInfo($request);
    }
}
