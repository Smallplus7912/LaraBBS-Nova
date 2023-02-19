<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NovaSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('nova_settings')->insert([
            ['key' => 'admin_name', 'value' => 'LaraBBS'],
            ['key' => 'admin_email', 'value' => 'liujialun@lf-network.com'],
            ['key' => 'seo_description', 'value' => 'LaraBBS 社区爱好者'],
            ['key' => 'seo_keyword', 'value' => '社区，论坛，开发者社区'],
        ]);
    }
}
