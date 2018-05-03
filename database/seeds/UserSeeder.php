<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Models\Storefront;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $temp = User::create([
            'real_name' => '总经理',
            'ascription_store' => '',
            'level' => '1',
            'tel' => '12345678901',
            'remark' => '这是总经理',
            'password' => bcrypt(123456),
        ]);
        $temp->assignRole(1);

        $temp1 = User::create([
            'real_name' => '区域经理1',
            'ascription_store' => '',
            'level' => '2',
            'tel' => '12345678902',
            'remark' => '这是区域经理1',
            'password' => bcrypt(123456),
        ]);
        $temp1->assignRole(2);

        $temp2 = User::create([
            'real_name' => '区域经理2',
            'ascription_store' => '',
            'level' => '2',
            'tel' => '12345678903',
            'remark' => '这是区域经理2',
            'password' => bcrypt(123456),
        ]);
        $temp2->assignRole(2);

        // 创建门店
        $storefrontTemp = Storefront::create([
            'storefront_name' => '光谷1店',
            'address' => '光谷',
            'fixed_tel' => '98765432101',
            'area_manager_id' => $temp1->id
        ]);

        $storefrontTemp1 = Storefront::create([
            'storefront_name' => '光谷2店',
            'address' => '光谷',
            'fixed_tel' => '98765432102',
            'area_manager_id' => $temp2->id
        ]);

        // 创建店长
        $temp3 = User::create([
            'real_name' => '店长1',
            'ascription_store' => $storefrontTemp->id,
            'level' => '3',
            'tel' => '12345678904',
            'remark' => '这是店长1',
            'password' => bcrypt(123456),
        ]);
        $temp3->assignRole(3);

        $temp4 = User::create([
            'real_name' => '店长2',
            'ascription_store' => $storefrontTemp1->id,
            'level' => '3',
            'tel' => '12345678905',
            'remark' => '这是店长2',
            'password' => bcrypt(123456),
        ]);
        $temp4->assignRole(3);

        // 绑定门店
        Storefront::where('id', $storefrontTemp->id)->update([
            'user_id' => $temp3->id
        ]);

        Storefront::where('id', $storefrontTemp1->id)->update([
            'user_id' => $temp4->id
        ]);

        // 创建业务员
        $temp5 = User::create([
            'real_name' => '业务员1',
            'ascription_store' => $storefrontTemp->id,
            'level' => '4',
            'tel' => '12345678906',
            'remark' => '这是业务员1',
            'password' => bcrypt(123456),
        ]);
        $temp5->assignRole(4);

        $temp6 = User::create([
            'real_name' => '业务员2',
            'ascription_store' => $storefrontTemp1->id,
            'level' => '4',
            'tel' => '12345678907',
            'remark' => '这是业务员2',
            'password' => bcrypt(123456),
        ]);
        $temp6->assignRole(4);
    }
}
