<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientLogoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Populates the clients table with 47 client logos extracted from
     * the "Our Clients" archive, grouped by business category.
     */
    public function run(): void
    {
        $clients = [
            // ── Government & Regulators ──
            ['name' => 'Tanzania Posts Corporation (Posta Tanzania)', 'logo_path' => 'Clients-01.png', 'website' => 'https://www.posta.co.tz', 'category' => 'government', 'sort_order' => 10],
            ['name' => 'Tanzania Revenue Authority (TRA)',            'logo_path' => 'Clients-02.png', 'website' => 'https://www.tra.go.tz', 'category' => 'government', 'sort_order' => 11],
            ['name' => 'TANESCO',                                      'logo_path' => 'Clients-03.png', 'website' => 'https://www.tanesco.co.tz', 'category' => 'government', 'sort_order' => 12],
            ['name' => 'National Housing Corporation',                 'logo_path' => 'Clients-04.png', 'website' => 'https://www.nhc.co.tz', 'category' => 'government', 'sort_order' => 13],
            ['name' => 'National Social Security Fund (NSSF)',          'logo_path' => 'Clients-06.png', 'website' => 'https://www.nssf.or.tz', 'category' => 'government', 'sort_order' => 14],
            ['name' => 'TANROADS',                                     'logo_path' => 'Clients-07.png', 'website' => 'https://www.tanroads.go.tz', 'category' => 'government', 'sort_order' => 15],
            ['name' => 'Arusha International Conference Centre',        'logo_path' => 'Clients-12.png', 'website' => 'https://www.aicc.co.tz', 'category' => 'government', 'sort_order' => 16],
            ['name' => 'National Identification Authority (NIDA)',      'logo_path' => 'Clients-14.png', 'website' => 'https://www.nida.go.tz', 'category' => 'government', 'sort_order' => 17],
            ['name' => 'Judicial Service Commission',                   'logo_path' => 'Clients-15.png', 'website' => 'https://www.jsc.go.tz', 'category' => 'government', 'sort_order' => 18],
            ['name' => 'Tanzania Coffee Board',                        'logo_path' => 'Clients-17.png', 'website' => 'https://www.coffeeboard.or.tz', 'category' => 'government', 'sort_order' => 19],
            ['name' => 'TanTrade',                                     'logo_path' => 'Clients-21.png', 'website' => 'https://www.tantrade.go.tz', 'category' => 'government', 'sort_order' => 20],
            ['name' => 'TAZARA',                                       'logo_path' => 'Clients-22.png', 'website' => 'https://www.tazarasite.com', 'category' => 'government', 'sort_order' => 21],
            ['name' => 'Tanzania Building Agency',                      'logo_path' => 'Clients-26.png', 'website' => 'https://www.tba.go.tz', 'category' => 'government', 'sort_order' => 22],
            ['name' => 'Tanzania Veterinary Laboratory Agency',         'logo_path' => 'Clients-27.png', 'website' => 'https://www.tvla.go.tz', 'category' => 'government', 'sort_order' => 23],

            // ── Banking & Finance ──
            ['name' => 'Bank of Tanzania',                              'logo_path' => 'Clients-10.png', 'website' => 'https://www.bot.go.tz', 'category' => 'banking', 'sort_order' => 30],
            ['name' => 'Tanzania Agricultural Development Bank (TADB)',  'logo_path' => 'Clients-32.png', 'website' => 'https://www.tadb.co.tz', 'category' => 'banking', 'sort_order' => 31],
            ['name' => 'UniCredit Microfinance',                        'logo_path' => 'Clients-39.png', 'website' => 'https://www.unicredit.co.tz', 'category' => 'banking', 'sort_order' => 32],

            // ── Telecom & Technology ──
            ['name' => 'TTCL Corporation',                              'logo_path' => 'Clients-05.png', 'website' => 'https://www.ttcl.co.tz', 'category' => 'telecom', 'sort_order' => 40],
            ['name' => 'Zantel',                                        'logo_path' => 'Clients-09.png', 'website' => 'https://www.zantel.co.tz', 'category' => 'telecom', 'sort_order' => 41],

            // ── Education ──
            ['name' => 'Mzumbe University',                             'logo_path' => 'Clients-08.png', 'website' => 'https://www.mzumbe.ac.tz', 'category' => 'education', 'sort_order' => 50],
            ['name' => 'University of Dodoma',                          'logo_path' => 'Clients-11.png', 'website' => 'https://www.udom.ac.tz', 'category' => 'education', 'sort_order' => 51],
            ['name' => 'VETA',                                          'logo_path' => 'Clients-23.png', 'website' => 'https://www.veta.go.tz', 'category' => 'education', 'sort_order' => 52],
            ['name' => 'Ardhi University',                               'logo_path' => 'Clients-25.png', 'website' => 'https://www.aru.ac.tz', 'category' => 'education', 'sort_order' => 53],
            ['name' => 'National College of Tourism',                    'logo_path' => 'Clients-34.png', 'website' => 'https://www.nct.ac.tz', 'category' => 'education', 'sort_order' => 54],
            ['name' => 'Moshi Co-operative University',                  'logo_path' => 'Clients-35.png', 'website' => 'https://www.mocu.ac.tz', 'category' => 'education', 'sort_order' => 55],
            ['name' => 'Shekinah Elementary School',                     'logo_path' => 'Clients-36.png', 'website' => null, 'category' => 'education', 'sort_order' => 56],
            ['name' => 'Livestock Training Agency',                      'logo_path' => 'Clients-37.png', 'website' => 'https://www.lita.go.tz', 'category' => 'education', 'sort_order' => 57],

            // ── Aviation & Transport ──
            ['name' => 'Tanzania Airports Authority',                    'logo_path' => 'Clients-19.png', 'website' => 'https://www.taa.go.tz', 'category' => 'aviation', 'sort_order' => 60],

            // ── NGOs & Development ──
            ['name' => 'HelpAge International',                         'logo_path' => 'Clients-30.png', 'website' => 'https://www.helpage.org', 'category' => 'ngo', 'sort_order' => 70],

            // ── Healthcare ──
            ['name' => 'Horizon Pharmacy',                              'logo_path' => 'Clients-40.png', 'website' => 'https://www.horizonpharmacy.co.tz', 'category' => 'healthcare', 'sort_order' => 80],
            ['name' => 'Vet Care Services Ltd',                         'logo_path' => 'Clients-45.png', 'website' => 'https://www.vetcare.co.tz', 'category' => 'healthcare', 'sort_order' => 81],

            // ── Hospitality & Tourism ──
            ['name' => 'Seascape Hotel',                                 'logo_path' => 'Clients-41.png', 'website' => 'https://www.seascapehotel.co.tz', 'category' => 'hospitality', 'sort_order' => 90],

            // ── Manufacturing & Industry ──
            ['name' => 'Sign Industries Ltd',                            'logo_path' => 'Clients-33.png', 'website' => 'https://www.signindustries.co.tz', 'category' => 'manufacturing', 'sort_order' => 100],
            ['name' => 'KAPS Batteries Ltd',                            'logo_path' => 'Clients-38.png', 'website' => 'https://www.kaps.co.tz', 'category' => 'manufacturing', 'sort_order' => 101],

            // ── Corporate & Professional Services ──
            ['name' => 'UNICC',                                         'logo_path' => 'Clients-16.png', 'website' => 'https://www.unicc.org', 'category' => 'corporate', 'sort_order' => 110],
            ['name' => 'Clouds Media Group',                             'logo_path' => 'Clients-18.png', 'website' => 'https://www.cloudsfm.com', 'category' => 'corporate', 'sort_order' => 111],
            ['name' => 'Camel Oil Tanzania',                             'logo_path' => 'Clients-20.png', 'website' => 'https://www.cameloil.co.tz', 'category' => 'corporate', 'sort_order' => 112],
            ['name' => 'Oilcom',                                        'logo_path' => 'Clients-24.png', 'website' => 'https://www.oilcom.co.tz', 'category' => 'corporate', 'sort_order' => 113],
            ['name' => 'ETG Logistics',                                  'logo_path' => 'Clients-28.png', 'website' => 'https://www.etgworld.com', 'category' => 'corporate', 'sort_order' => 114],
            ['name' => 'Imports International Ltd',                      'logo_path' => 'Clients-31.png', 'website' => 'https://www.iil.co.tz', 'category' => 'corporate', 'sort_order' => 115],
            ['name' => 'Viridium',                                      'logo_path' => 'Clients-43.png', 'website' => 'https://www.viridiumgroup.com', 'category' => 'corporate', 'sort_order' => 116],
            ['name' => 'V.J. Mistry & Company Ltd',                     'logo_path' => 'Clients-44.png', 'website' => 'https://www.vjmistry.com', 'category' => 'corporate', 'sort_order' => 117],
            ['name' => 'Regent Properties',                             'logo_path' => 'Clients-46.png', 'website' => 'https://www.regent.co.tz', 'category' => 'corporate', 'sort_order' => 118],
            ['name' => 'B.H. Ladwa Ltd',                                 'logo_path' => 'Clients-47.png', 'website' => 'https://www.bhladwa.com', 'category' => 'corporate', 'sort_order' => 119],
            ['name' => 'Primetime Promotions',                           'logo_path' => 'Clients-49.png', 'website' => 'https://www.primetime.co.tz', 'category' => 'corporate', 'sort_order' => 120],
            ['name' => 'DDW Cars',                                      'logo_path' => 'Clients-50.png', 'website' => 'https://www.ddwcars.co.tz', 'category' => 'corporate', 'sort_order' => 121],
        ];

        // Idempotent: match on name so re-running (on every container boot) neither
        // duplicates these clients nor wipes any added later via the admin panel.
        foreach ($clients as $client) {
            Client::updateOrCreate(
                ['name' => $client['name']],
                array_merge($client, ['is_active' => true])
            );
        }

        $this->command->info(count($clients) . ' clients seeded across ' . collect($clients)->pluck('category')->unique()->count() . ' categories.');
    }
}
