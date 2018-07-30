<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHouseHasCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_has_companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('house_id')->nullable()->comment('房源id');
            $table->string('company_name',32)->nullable()->comment('企业名称');
            $table->string('company_tel',16)->nullable()->comment('企业联系方式');
            $table->string('charge_name',32)->nullable()->comment('负责人姓名');
            $table->string('charge_tel',16)->nullable()->comment('负责人联系方式');
            $table->string('landlord_name',32)->nullable()->comment('房东姓名');
            $table->string('landlord_tel',16)->nullable()->comment('房东联系方式');
            $table->timestamps();
            $table->softDeletes();
        });
        \DB::statement("alter table `house_has_companies` comment'房源公司关联表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('house_has_companies');
    }
}
