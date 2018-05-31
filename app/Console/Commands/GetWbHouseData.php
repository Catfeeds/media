<?php

namespace App\Console\Commands;

use App\Handler\Common;
use App\Models\Area;
use App\Models\Building;
use App\Models\WbHouse;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

        $page = 100;
        $count = 1;
        for($i = 1; $i <= $page; $i++) {
            $data = Common::getCurl('https://www.haozu.com/bj/cooperation/wuba/?callback=jQuery11240025335480915851694_'.time().'&page_size=20&city_id=158&district_id=158&page_no='.$i.'&_='.time());
            $search ='/\{.+\}/';
            preg_match($search,$data,$r);
            // 房源数据
            $houses = json_decode($r[0])->data;

            // 判断接口是否异常
            if (empty($data) && empty($houses) && $i == 1) {
                $this->error('接口异常');
            }

            $tempData = array();
            foreach ($houses as $v) {
                // 去除工位房源
                if ($v->prict_unit_type === '元/工位/周') {
                    continue;
                }

                // 拼接数据
                $tempData['title'] = $v->title;
                $tempData['unit_price'] = $v->price_unit;
                $tempData['total_price'] = $v->total_price;
                $tempData['wb_house_id'] = $v->house_id;
                $tempData['house_description'] = $v->brief;
                $tempData['constru_acreage'] = $v->area;

                $area = DB::table('areas')->where('name', 'like', '%'.$v->district_name.'%')->first();
                if (empty($area)) {
                    // 新建区域
                    $addArea = Area::create([
                        'name' => $v->district_name,
                        'city_id' => 1
                    ]);

                    $tempData['area_id'] = $addArea->id;
                } else {
                    $tempData['area_id'] = $area->id;
                }

                $building = DB::table('buildings')->where('name', 'like', '%'.$v->building_name.'%')->first();
                if (empty($building)) {
                    // 新建楼盘
                    $addBuilding = Building::create([
                        'name' => $v->building_name,
                        'area_id' => $tempData['area_id'],
                        'address' => $v->address,
                        'type' => 2,
                    ]);

                    $tempData['building_id'] = $addBuilding->id;
                } else {
                    $tempData['building_id'] = $building->id;
                }

                // 处理图片
                $imgName = time().rand(10,9999999999);
                if (!empty($v->thumb_image)) {
                    // 处理图片
                    $img = substr($v->thumb_image,2);
                    if ($img === 'web-cdn.haozu.com/static/image/picture/noimg_150120.jpg') {
                        $tempData['indoor_img'][0] = '';
                        continue;
                    }
                    $img = explode('@', 'https://'.$img);

                    // 图片类型
                    $imgType= strtolower(strrchr($img[0],"."));

                    // 下载图片
                    Storage::put('public/' . $imgName . $imgType, file_get_contents($img[0]));
                    $keyNew = 'houseImg/' . $imgName . $imgType;
                    $res = Common::QiniuUpload(storage_path() . '/app/public/' . $imgName . $imgType, $keyNew);
                    dump($res);

                    // 上传成功删除
                    if ($res['status'] == false) {
                        \Log::info($imgName . $imgType. '上传失败');
                    } else {
                        // 图片数据
                        $tempData['indoor_img'][0] = $keyNew;
                        unlink(storage_path() . '/app/public/' . $imgName . $imgType);
                    }
                }

                // 去除数据
                unset($tempData['area_id']);
                unset($tempData['building_id']);

                // 存房源数据
                $wbHouse = WbHouse::create($tempData);
                if (empty($wbHouse)) {
                    \Log::info('爬取失败'.$count++.'条数据');
                }

            }
        }
    }
}
