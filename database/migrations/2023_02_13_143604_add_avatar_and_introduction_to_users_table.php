<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAvatarAndIntroductionToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable();
            //新建头像在oss中的相对地址，供nova后台显示
            //本地前端显示头像需要
            $table->string('address')->default('http://avatar86177.oss-cn-hangzhou.aliyuncs.com/')->nullable();
            $table->string('introduction')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table->dropColumn('address');
            $table->dropColumn('introduction');
        });
    }
}
