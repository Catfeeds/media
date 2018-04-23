<?php

namespace App\Http\Controllers\API;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends APIBaseController
{

    public function store(
        Request $request,
        UserRepository $userRepository
    )
    {

        if ($request->password != $request->password_confirmation) {
            return $this->sendError('密码与确认密码不一致,请重新输入');
        }

        $userRepository->addUser($request);



    }



}
