<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run()
    {
        // Helper function to generate UUID
        $generateUUID = function() {
            $data = random_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        };
        
        $categories = ['Chinese', 'Japanese', 'Korean', 'German', 'English'];
        $subCategories = [
            'Regular' => ['fee' => 2500000, 'desc' => 'Standard classroom learning with fixed schedule.'],
            'Private' => ['fee' => 5000000, 'desc' => 'One-on-one intensive sessions with flexible timing.'],
            'Package' => ['fee' => 7500000, 'desc' => 'Comprehensive multi-level bundle with extra materials.']
        ];

        $data = [];
        $timestamp = date('Y-m-d H:i:s');

        $levels = ['Level 1', 'Level 2', 'Level 3', 'Level 4'];

        foreach ($categories as $cat) {
            foreach ($subCategories as $sub => $info) {
                foreach ($levels as $index => $level) {
                    // Increase fee by 250k for each level above Level 1
                    $fee = $info['fee'] + ($index * 250000);
                    $data[] = [
                        'id' => $generateUUID(),
                        'title' => "$cat $sub Course - $level",
                        'description' => "Advance your $cat skills with our specialized $sub program ($level). " . $info['desc'] . " Focused on intensive " . strtolower($level) . " mastery.",
                        'features' => json_encode([
                            'Certified Instructors',
                            'Modern Curriculum',
                            'Interactive Learning',
                            'Progress Tracking',
                            'Completion Certificate'
                        ]),
                        'facilities' => json_encode([
                            'Air Conditioned Classroom',
                            'Multimedia Projector',
                            'Language Lab',
                            'Digital Library'
                        ]),
                        'extra_facilities' => json_encode([
                            'Student Lounge Access',
                            'High Speed Wi-Fi',
                            'Free Coffee/Tea',
                            'Consultation Service'
                        ]),
                        'registration_fee' => 500000,
                        'tuition_fee' => $fee,
                        'discount' => ($sub === 'Package' ? 15.00 : 0.00),
                        'category' => $cat,
                        'sub_category' => $sub,
                        'status' => 'active',
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ];
                }
            }
        }
        
        $db = \Config\Database::connect();
        $builder = $db->table('programs');
        
        // Truncate before seeding to ensure fresh start as requested
        $db->query('SET FOREIGN_KEY_CHECKS=0;');
        $builder->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS=1;');
        
        $builder->insertBatch($data);
        
        echo count($data) . " language programs have been seeded successfully!\n";
    }
}
