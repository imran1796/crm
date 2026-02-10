<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches= [
            ['name'=>'Dhaka Office','short_name'=>'DHK','code'=>'2592,5235','location'=>'Dhaka'],
            ['name'=>'Chittagong Office','short_name'=>'CTG','code'=>'2591','location'=>'Chittagong'],
            ['name'=>'All','short_name'=>'ALL','code'=>'2591,2592,5235','location'=>'All']
        ];

        foreach ($branches as $key => $value) {

            Branch::create($value);

        }
    }
}
