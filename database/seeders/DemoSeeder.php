<?php

namespace Database\Seeders;

use App\Models\ContentPage;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment() !== 'demo') {
            throw new RuntimeException('Demo fixtures are strictly limited to APP_ENV=demo.');
        }
        $this->call(DatabaseSeeder::class);
        $password = (string) env('FLYHIGH_DEMO_PASSWORD', 'DemoPass123!');
        if (strlen($password) < 10) throw new RuntimeException('Demo password is too short.');
        foreach ([
            ['Athena', 'athena@flyhigh.test', User::ATHENA],
            ['Hephaestus', 'hephaestus@flyhigh.test', User::HEPHAESTUS],
            ['Argus', 'argus@flyhigh.test', User::ARGUS],
            ['Demo Traveler', 'traveler@flyhigh.test', 'member'],
        ] as [$name,$email,$role]) {
            User::updateOrCreate(['email' => $email], [
                'name' => $name, 'password' => $password, 'role' => $role,
                'email_verified_at' => now(),
            ]);
        }
        PromoCode::firstOrCreate(['code'=>'DEMO10'], [
            'type'=>'percentage','value'=>10,'max_uses'=>250,'is_active'=>true,
        ]);
        ContentPage::firstOrCreate(['slug'=>'about-demo'],[
            'title'=>'About the FlyHigh demo',
            'body'=>'This is a standalone simulation. Flights, fares, payment confirmation, and e-tickets are fictional. Do not enter real payment or passport details.',
            'is_published'=>true,
        ]);
    }
}
