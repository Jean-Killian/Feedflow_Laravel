<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default organization
        $organization = Organization::create([
            'name' => 'Organization par défaut',
        ]);

        // Create default user
        $user = User::create([
            'last_name'     => 'Doe',
            'first_name'    => 'John',
            'email'         => 'test@feedflow.local',
            'password'      => bcrypt('password'),
            'email_notifications_enabled' => true,
        ]);

        // Create organization with user_id
        $organization = Organization::create([
            'name' => 'Organization par défaut',
            'user_id' => $user->id,  // ← Ajout du user_id
        ]);
        
        // Link user to organization
        \DB::table('organization_user')->insert([
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
