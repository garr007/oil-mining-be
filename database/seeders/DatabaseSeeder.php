<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Division;
use App\Models\Employee;
use App\Models\EmployeeStatus;
use App\Models\Position;
use App\Models\User;
use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Testing\Fakes\Fake;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $arrDivisions = [
            Division::MINING,
            Division::HRD,
            Division::RND,
            Division::SALES,
            Division::FINANCE,
            Division::LEGAL,
            Division::IT,
        ];
        $arrPositions = [
            Position::ROLE_MANAGER,
            'Other'
        ];

        // create 1 admin
        $admin = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => 'password',
            'is_admin' => true,
        ]);

        // employee statuses
        $permanentStatus = EmployeeStatus::factory()->create([
            'name' => EmployeeStatus::PERMANENT,
        ]);
        $contractStatus = EmployeeStatus::factory()->create([
            'name' => EmployeeStatus::CONTRACT,
        ]);

        // divisions
        $divisions = array();
        foreach ($arrDivisions as $a) {
            array_push(
                $divisions,
                Division::factory()->create([
                    'name' => $a,
                ])
            );
        }

        // positions
        foreach ($divisions as $division) {
            foreach ($arrPositions as $a) {
                Position::factory()
                    ->for($division, 'division')
                    ->create(['name' => $a,]);
            }
        }


        // creating employees/manager
        foreach ($divisions as $division) {

            // creating 1 manager for each divisions
            $userManager = User::factory()->create([
                'first_name' => 'Dummy Manager',
                'last_name' => $division->name,
            ]);

            $managerPosition = Position::where([
                ['division_id', $division->id],
                ['name', Position::ROLE_MANAGER],
            ])->first();

            Employee::factory()
                ->for($userManager, 'user')
                ->for($managerPosition, 'position')
                ->for($permanentStatus, 'status')
                ->create([
                    'is_active' => true,
                ]);
            //==========================

            // creating other employees (10 for each division)
            // with random is_active

            $otherPosition = Position::where([
                ['division_id', $division->id],
                ['name', 'Other'],
            ])->first();

            $userEmployees = User::factory()->count(10)->create();
            foreach ($userEmployees as $userEmployee) {
                Employee::factory()
                    ->for($userEmployee, 'user')
                    ->for($otherPosition, 'position')
                    ->for(rand(0, 1) == 1 ? $permanentStatus : $contractStatus, 'status')
                    ->create();
            }
        } // end of creating employee/manager

    } // end of run()
}
