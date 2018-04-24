<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

/**
 * 注册超级管理员命令
 *
 * Class CreateAdministrator
 * @package App\Console\Commands
 * @author scort
 */
class AddManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addManager {--u} {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建总经理 eg: addManager --u root';

    // 管理员业务辅助
    protected static $adminService = null;

    /**
     * CreateAdministrator constructor.
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
        // 创建总经理
        self::create();
    }


    public function create()
    {
        $password = $this->secret('请输入密码：');

        if (!($this->argument('user') == env('ROOT_USERNAME') && $password == env('ROOT_PASSWORD'))){
            $this->error('用户名或密码有误！');
            return ;
        }
        $this->info('登录成功！');
        $realName = $this->ask('请输入真实姓名：');
        $tel = $this->ask('请输入手机号：');
        $password = $this->ask('请输入密码：');
        $this->info('密码：'.$password);
        if ($this->confirm('你确认注册吗?')) {
            //确定
            $this->info('正在注册...');
            $data = [
                'real_name' => $realName,
                'tel' => $tel,
                'password' => $password,
            ];



        } else {
            $this->info('取消注册');
        }

    }
}
