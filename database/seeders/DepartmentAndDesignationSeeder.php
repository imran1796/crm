<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Designation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentAndDesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments= [

            ['department_name' => 'Import Department'],
            ['department_name' => 'Feeder Department'],
            ['department_name' => 'Export Department'],
            ['department_name' => 'DO Department'],
            ['department_name' => 'Equipment Department'],
            ['department_name' => 'Accounts Department'],
            ['department_name' => 'Admin Department'],
            ['department_name' => 'IT Department'],
            ['department_name' => 'Sales Department'],



        ];
        $designations= [

            ['designation' => 'Junior Executive','value'=>10],
            ['designation' => 'Executive','value'=>20],
            ['designation' => 'Senior Executive','value'=>30],
            ['designation' => 'Assistant Manager','value'=>40],
            ['designation' => 'Deputy Manager','value'=>50],
            ['designation' => 'Manager','value'=>60],
            ['designation' => 'Senior Manager','value'=>70],
            ['designation' => 'Deputy General Manager','value'=>80],
            ['designation' => 'Assistant General Manager','value'=>90],
            ['designation' => 'General Manager','value'=>100]



        ];



        foreach ($designations as $key => $value) {

            Designation::create($value);

        }



        foreach ($departments as $key => $value) {

            Department::create($value);

        }
    }
}
