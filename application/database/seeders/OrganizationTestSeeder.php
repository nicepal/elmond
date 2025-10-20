<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class OrganizationTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the test organization
        $organization = Organization::where('email', 'test@company.com')->first();
        
        if (!$organization) {
            echo "Test organization not found. Please create it first.\n";
            return;
        }
        
        // Create test users
        $users = [
            [
                'firstname' => 'Alice',
                'lastname' => 'Johnson',
                'username' => 'alice.johnson',
                'email' => 'alice@example.com',
                'country_code' => '1',
                'mobile' => '5551234567',
                'password' => Hash::make('password123'),
                'status' => 1,
                'ev' => 1,
                'sv' => 1,
                'ts' => 0,
            ],
            [
                'firstname' => 'Bob',
                'lastname' => 'Smith',
                'username' => 'bob.smith',
                'email' => 'bob@example.com',
                'country_code' => '1',
                'mobile' => '5551234568',
                'password' => Hash::make('password123'),
                'status' => 1,
                'ev' => 1,
                'sv' => 1,
                'ts' => 0,
            ],
            [
                'firstname' => 'Carol',
                'lastname' => 'Davis',
                'username' => 'carol.davis',
                'email' => 'carol@example.com',
                'country_code' => '1',
                'mobile' => '5551234569',
                'password' => Hash::make('password123'),
                'status' => 1,
                'ev' => 1,
                'sv' => 1,
                'ts' => 0,
            ]
        ];
        
        foreach ($users as $userData) {
            // Check if user already exists
            $existingUser = User::where('email', $userData['email'])->first();
            
            if (!$existingUser) {
                // Create user using DB insert to bypass fillable restrictions
                $userId = DB::table('users')->insertGetId(array_merge($userData, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
                
                $user = User::find($userId);
                echo "Created user: {$user->fullname} ({$user->email})\n";
            } else {
                $user = $existingUser;
                echo "User already exists: {$user->fullname} ({$user->email})\n";
            }
            
            // Attach user to organization if not already attached
            if (!$organization->users()->where('user_id', $user->id)->exists()) {
                $organization->users()->attach($user->id, [
                    'employee_id' => 'EMP' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                    'department' => ['IT', 'HR', 'Sales'][array_rand(['IT', 'HR', 'Sales'])],
                    'position' => ['Developer', 'Manager', 'Analyst'][array_rand(['Developer', 'Manager', 'Analyst'])],
                    'joined_at' => now()->subDays(rand(1, 365)),
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                echo "Attached {$user->fullname} to organization\n";
            } else {
                echo "{$user->fullname} already attached to organization\n";
            }
        }
        
        echo "\nTest data seeding completed!\n";
        echo "Organization: {$organization->company_name} ({$organization->email})\n";
        echo "Password: password123\n";
        echo "Total employees: " . $organization->users()->count() . "\n";
    }
}
