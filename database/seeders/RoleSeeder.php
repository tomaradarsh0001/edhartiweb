<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        $roles = [
            'super-admin',
            'admin',
            'user',
            'CDV',
            'CDN',
            'applicant',
            'section-officer',
            'supritendent',
            'assistent-section-officer',
            'deputy-lndo',
            'lndo',
            'engineer-officer',
            'AE',
            'JE',
            'AO',
            'audit-cell',
            'vegillence',
            'it-cell',
        ];
        foreach($roles as $role){
            Role::create([
                'name' => $role
            ]);
        }
        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
