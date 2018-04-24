<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\UsersRequest;
use App\Repositories\UserRepository;
use App\Services\UsersService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $res = $userRepository->userList($request);
        return $this->sendResponse($res,'成员列表获取成功');
    }


    /**
     * 说明:获取添加成员等级和所有未归属的门店信息
     *
     * @param UsersService $service
     * @return array
     * @author 刘坤涛
     */
    public function create(UsersService $service)
    {
        return $res = $service->getInfo();
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
        $role = Auth::guard('api')->user()->can();
        if(empty($role)) {
            return $this->sendError('无添加成员权限');
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
        $res = $userRepository->updateUser($request, $user);
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

}
