<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::factory()->create([
            'name' => 'Admin iCommerce',
            'email' => 'admin@icommerce-gabon.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        // Client test
        User::factory()->create([
            'name' => 'Client Test',
            'email' => 'client@test.com',
            'role' => 'client',
            'password' => bcrypt('password'),
        ]);

        // Settings
        $settings = [
            ['key' => 'default_profit_margin_percent', 'value' => '15', 'type' => 'float', 'group' => 'pricing'],
            ['key' => 'default_service_fee_percent', 'value' => '10', 'type' => 'float', 'group' => 'pricing'],
            ['key' => 'default_currency', 'value' => 'XAF', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_name', 'value' => 'iCommerce Gabon', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_address', 'value' => 'Libreville, Gabon', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_phone', 'value' => '+241 00 00 00 00', 'type' => 'string', 'group' => 'general'],
            ['key' => 'admin_email', 'value' => 'admin@icommerce-gabon.com', 'type' => 'string', 'group' => 'notifications'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
