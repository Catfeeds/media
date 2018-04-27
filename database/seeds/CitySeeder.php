<?php

use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 管理员子账户管理
        \App\Models\City::create([
            'id' => 1,
            'name' => '武汉'
        ]);
    }
}
