<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
// use App\Models\Employee;

// Employee::factory()->count(10)->create();

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('employees')->insert([
            [
                'employee_id'   => 'EMP001',
                'employee_name' => '山田 太郎',
                'gender'        => 'male',
                'birthday'      => '1990-05-15',
                'email'         => 'yamada.taro@example.com',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'employee_id'   => 'EMP002',
                'employee_name' => '佐藤 花子',
                'gender'        => 'female',
                'birthday'      => '1995-08-22',
                'email'         => 'sato.hanako@example.com',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ]);
    }
}
