<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            ClientLogoSeeder::class,
        ]);

        // Services
        $services = [
            [
                'title'       => 'Uniformed Security Guards',
                'category'    => 'security',
                'slug'        => 'uniformed-security-guards',
                'description' => 'Professional licensed security guards deployed in smart uniforms to protect your premises, assets, and personnel. Our guards undergo rigorous vetting, background checks, and continuous training to maintain the highest standards of physical security. We provide both static and mobile patrol solutions tailored to your risk profile.',
                'sort_order'  => 1,
                'is_active'   => true,
            ],
            [
                'title'       => 'Talent Acquisition',
                'category'    => 'hr',
                'slug'        => 'talent-acquisition',
                'description' => 'End-to-end recruitment for your organisation, from job profiling and advertising through shortlisting, competency-based interviewing, and onboarding support. Our HR specialists leverage an extensive candidate database and industry networks to connect you with top talent quickly and cost-effectively.',
                'sort_order'  => 2,
                'is_active'   => true,
            ],
            [
                'title'       => 'Commercial Cleaning',
                'category'    => 'cleaning',
                'slug'        => 'commercial-cleaning',
                'description' => 'High-standard commercial office cleaning services delivered by trained and uniformed cleaning teams. We use eco-friendly, hospital-grade cleaning products and modern equipment to maintain pristine environments in offices, warehouses, retail outlets, and hospitality spaces. Flexible scheduling—daily, weekly, or bespoke contracts available.',
                'sort_order'  => 3,
                'is_active'   => true,
            ],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(['slug' => $service['slug']], $service);
        }

        // Testimonials
        $testimonials = [
            [
                'client_name' => 'James Mwangi',
                'company'     => 'Serena Hotels',
                'quote'       => 'Kikosi Kazi has provided us with exceptional security personnel. Their guards are professional, punctual, and well-trained.',
                'sort_order'  => 1,
                'is_active'   => true,
            ],
            [
                'client_name' => 'Fatuma Hassan',
                'company'     => 'NMB Bank',
                'quote'       => 'Their HR consultancy service helped us fill 20 critical positions in record time. Highly recommended.',
                'sort_order'  => 2,
                'is_active'   => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::firstOrCreate(
                ['client_name' => $testimonial['client_name'], 'company' => $testimonial['company']],
                $testimonial
            );
        }

        // Jobs
        $jobs = [
            [
                'title'       => 'Security Guard',
                'department'  => 'Security Operations',
                'location'    => 'Dar es Salaam',
                'vacancies'   => 50,
                'status'      => 'active',
                'deadline'    => Carbon::now()->addMonths(3),
                'created_by'  => 1,
                'description' => 'We are seeking reliable and physically fit Security Guards to join our growing security operations team in Dar es Salaam. You will be responsible for maintaining the safety and security of client premises, deterring criminal activity, monitoring access control systems, and responding promptly to incidents. Successful candidates will be deployed to a variety of client sites including commercial buildings, hotels, banks, and industrial facilities. Kikosi Kazi provides full uniform, equipment, and ongoing professional development.',
                'requirements' => "- Minimum Form Four (CSEE) certificate\n- Valid security guard licence issued by the Tanzania Police Force\n- Minimum height: male 5'6\" / female 5'4\"\n- Certificate of Good Conduct (Police Clearance)\n- Age between 20 and 40 years\n- Ability to work shifts including nights, weekends, and public holidays\n- Good physical fitness and health\n- Prior security or military experience is an added advantage\n- Strong communication and observation skills\n- Ability to write clear incident reports",
            ],
            [
                'title'       => 'HR Officer',
                'department'  => 'Human Resources',
                'location'    => 'Arusha',
                'vacancies'   => 5,
                'status'      => 'active',
                'deadline'    => Carbon::now()->addMonths(3),
                'created_by'  => 1,
                'description' => 'Kikosi Kazi is looking for proactive and detail-oriented HR Officers to support our Human Resources department in Arusha. The HR Officer will assist in end-to-end recruitment cycles, manage employee records, coordinate training programmes, support payroll processing, and ensure compliance with Tanzanian labour laws. You will work closely with department heads to understand staffing needs and help build a motivated, high-performing workforce.',
                'requirements' => "- Bachelor's degree in Human Resource Management, Business Administration, or a related field\n- Minimum 2 years of experience in an HR role\n- Strong knowledge of the Employment and Labour Relations Act (Tanzania)\n- Proficiency in HR information systems (HRIS) and Microsoft Office Suite\n- Excellent interpersonal, communication, and negotiation skills\n- Ability to handle sensitive and confidential information with discretion\n- Experience in recruitment, onboarding, and performance management\n- Professional HR certification (e.g., CIPD, SHRM) is an added advantage\n- Fluency in Swahili and English (written and spoken)",
            ],
            [
                'title'       => 'Commercial Cleaner',
                'department'  => 'Cleaning Services',
                'location'    => 'Dar es Salaam',
                'vacancies'   => 30,
                'status'      => 'active',
                'deadline'    => Carbon::now()->addMonths(2),
                'created_by'  => 1,
                'description' => 'We are recruiting hardworking and dependable Commercial Cleaners to deliver high-standard cleaning services at client premises across Dar es Salaam. Duties include general office cleaning, sanitising restrooms, vacuuming carpets, mopping floors, cleaning windows, and waste disposal. Our cleaners operate in teams and are supplied with all necessary equipment, protective gear, and eco-friendly cleaning products. Flexible shift patterns are available to meet client requirements.',
                'requirements' => "- Minimum Primary School (Standard 7) education\n- Minimum 1 year of experience in commercial or domestic cleaning\n- Knowledge of safe handling of cleaning chemicals and equipment\n- Ability to follow cleaning schedules and checklists\n- Attention to detail and pride in delivering spotless results\n- Good time management and reliability\n- Ability to work both independently and as part of a team\n- Willingness to work early morning, evening, or weekend shifts\n- Certificate of Good Conduct is required\n- Basic knowledge of health and safety practices",
            ],
        ];

        foreach ($jobs as $job) {
            Job::firstOrCreate(
                ['title' => $job['title'], 'department' => $job['department'], 'location' => $job['location']],
                $job
            );
        }
    }
}
