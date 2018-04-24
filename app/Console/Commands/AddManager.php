<?php

namespace App\Console\Commands;

use App\Services\UsersService;
use Illuminate\Console\Command;

/**
 * 生成总经理命令
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
    protected static $usersService = null;

    /**
     * AddManager constructor.
     * @param UsersService $usersService
     */
    public function __construct(UsersService $usersService)
    {
        parent::__construct();
        self::$usersService = $usersService;
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

    /**
     * 说明: 添加总经理
     *
     * @author 罗振
     */
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

            // 验证
            $validator = \Validator::make($data, [
                'real_name' => 'required|max:32',
                'tel' => 'required|max:16',
                'password' => 'required|min:6|max:12'
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $this->table(['错误'], (array) $errors->toArray());
                self::create();
                return ;
            }
            $result = self::$usersService->addManager($data);
            if (!empty($result)) {
                $this->info('添加成功');
            } else {
                $this->error('添加失败');
                self::create();
            }

        } else {
            $this->info('取消注册');
        }
    }
}
