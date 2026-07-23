<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class InsuranceServiceSeeder extends Seeder
{
    /**
     * Adds a starter Insurance service so the Insurance division shows a card
     * on the public site. Safe to run more than once (updateOrCreate on slug).
     */
    public function run(): void
    {
        Service::updateOrCreate(
            ['slug' => 'insurance-placement'],
            [
                'title'       => 'Insurance Placement',
                'category'    => 'insurance',
                'description' => 'Group life, WCF, motor fleet, property and specialised covers arranged and managed on your behalf — the right protection at the most competitive premiums.',
                'icon'        => 'umbrella',
                'sort_order'  => 1,
                'is_active'   => true,
            ]
        );
    }
}
