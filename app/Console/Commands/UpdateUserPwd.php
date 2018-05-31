<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class UpdateUserPwd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateUserPwd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '修改用户密码';

    /**
     * AddManager constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 修改用户密码
        self::updateUserPwd();
    }

    /**
     * 说明: 修改用户密码
     *
     * @author 罗振
     */
    public function updateUserPwd()
    {
        $users = User::get();

        foreach ($users as $user) {
            $user->password = bcrypt(123456);
            if (!$user->save()) {
                \Log::error($user->real_name.'的密码跟新失败');
            }
        }


    }
}
