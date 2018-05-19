<?php

namespace App\Console\Commands;

use App\Handler\Common;
use Illuminate\Console\Command;

class GetWbHouseData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getWbHouseData {--u} {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '爬去58房源数据 eg: getWbHouseData --u root';

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
        self::getWbHouseData();
    }

    public function getWbHouseData()
    {
        $password = $this->secret('请输入密码：');

        if (!($this->argument('user') == env('ROOT_USERNAME') && $password == env('ROOT_PASSWORD'))){
            $this->error('用户名或密码有误！');
            return ;
        }
        $this->info('登录成功！');

        $page = 1;
        for($i = 1; $i <= $page; $i++) {
            dump($i);
            $data = Common::getCurl('https://www.haozu.com/bj/cooperation/wuba/?callback=jQuery11240025335480915851694_'.time().'&page_size=20&city_id=158&district_id=158&page_no='.$i.'&_='.time());
            $search ='/\{.+\}/';
            preg_match($search,$data,$r);
            dump(json_decode($r[0])->data);
            // 房源数据
            $houses = json_decode($r[0])->data;

            // 判断接口是否异常
            if (empty($data) && empty($houses) && $i == 1) {
                $this->error('接口异常');
            }

            foreach ($houses as $v) {

                if (!empty($v->thumb_image)) {

                    $res = file_get_contents($v->thumb_image);



                } else {
                    continue;
                }


            }


        }



    }
}
