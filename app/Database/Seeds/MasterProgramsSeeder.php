<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterProgramsSeeder extends Seeder
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
        
        $data = [
            [
                'id' => $generateUUID(),
                'title' => 'Master of Business Administration (MBA)',
                'description' => 'An intensive postgraduate program designed to develop advanced leadership, strategic thinking, and professional management skills for future business leaders.',
                'thumbnail' => null,
                'features' => json_encode([
                    'Strategic leadership development',
                    'Global business networking',
                    'Executive mentorship program',
                    'Capston project with corporate partners',
                    'Advanced financial management'
                ]),
                'facilities' => json_encode([
                    'Executive conference rooms',
                    'Bloomberg finance lab',
                    'Graduate study lounge',
                    'Strategic simulation room',
                    'Professional networking hub'
                ]),
                'extra_facilities' => json_encode([
                    'International study tours',
                    'Business roundtable membership',
                    'Executive coaching sessions',
                    'Lifetime alumni network access'
                ]),
                'registration_fee' => 750000,
                'tuition_fee' => 25000000,
                'discount' => 5.00,
                'category' => 'Business',
                'sub_category' => 'Postgraduate',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => $generateUUID(),
                'title' => 'MSc in Cybersecurity Engineering',
                'description' => 'Advanced technical program focused on infrastructure protection, advanced cryptology, and forensic analysis to combat global cyber threats.',
                'thumbnail' => null,
                'features' => json_encode([
                    'Advanced penetration testing',
                    'Cloud security architecture',
                    'Cryptographic engineering',
                    'Digital forensics investigation',
                    'Security policy and governance'
                ]),
                'facilities' => json_encode([
                    'Next-gen SOC environment',
                    'Hardware security lab',
                    'Isolated malware analysis sandbox',
                    'Cloud infrastructure testing rig',
                    'Forensic investigation suite'
                ]),
                'extra_facilities' => json_encode([
                    'Voucher for CISSP/CEH exams',
                    'Private vulnerability research lab',
                    'Access to international security summits',
                    'High-performance cluster computing'
                ]),
                'registration_fee' => 800000,
                'tuition_fee' => 22000000,
                'discount' => 0.00,
                'category' => 'Technology',
                'sub_category' => 'Postgraduate',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => $generateUUID(),
                'title' => 'Master of Applied Data Science',
                'description' => 'A comprehensive graduate program bridging the gap between database management, artificial intelligence, and strategic decision making.',
                'thumbnail' => null,
                'features' => json_encode([
                    'Deep learning and AI',
                    'Large-scale data engineering',
                    'Predictive analytics modeling',
                    'Natural language processing',
                    'Ethics in AI and data privacy'
                ]),
                'facilities' => json_encode([
                    'AI research laboratory',
                    'NVIDIA DGX compute nodes',
                    'Visualization wall',
                    'Collaboration sandbox',
                    'Big data processing farm'
                ]),
                'extra_facilities' => json_encode([
                    'AWS/Azure cloud credits',
                    'Publication support for research',
                    'Industry data internships',
                    'Access to supercomputing facilities'
                ]),
                'registration_fee' => 700000,
                'tuition_fee' => 20000000,
                'discount' => 10.00,
                'category' => 'Technology',
                'sub_category' => 'Postgraduate',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => $generateUUID(),
                'title' => 'Bachelor of Artificial Intelligence',
                'description' => 'Undergraduate program focused on the fundamentals of AI, robotic process automation, and cognitive computing.',
                'thumbnail' => null,
                'features' => json_encode([
                    'Foundations of machine learning',
                    'Robotics and automation',
                    'Computer vision',
                    'Software engineering for AI',
                    'Algorithmic problem solving'
                ]),
                'facilities' => json_encode([
                    'Robotics workshop',
                    'IoT innovation lab',
                    'AI software design studio',
                    'Smart classroom facility',
                    'Technical library'
                ]),
                'extra_facilities' => json_encode([
                    'Robotics competition funding',
                    'Open-source contribution mentoring',
                    'Tech club membership',
                    'Maker space access'
                ]),
                'registration_fee' => 500000,
                'tuition_fee' => 16000000,
                'discount' => 15.00,
                'category' => 'Technology',
                'sub_category' => 'Artificial Intelligence',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => $generateUUID(),
                'title' => 'Master of Laws (LL.M.) in Digital Law',
                'description' => 'Specialized law degree focusing on digital transformation, intellectual property in technology, and data protection regulations.',
                'thumbnail' => null,
                'features' => json_encode([
                    'Cyber law and regulations',
                    'IP in the digital age',
                    'International trade law',
                    'Data privacy compliance',
                    'Legal tech innovation'
                ]),
                'facilities' => json_encode([
                    'Moot Court room',
                    'Legal research database access',
                    'Case study library',
                    'Policy research unit',
                    'Consultation rooms'
                ]),
                'extra_facilities' => json_encode([
                    'Legal clinic participation',
                    'Policy internship and advisory',
                    'Digital law journal subscription',
                    'Networking with legal experts'
                ]),
                'registration_fee' => 600000,
                'tuition_fee' => 18000000,
                'discount' => 5.00,
                'category' => 'Law',
                'sub_category' => 'Postgraduate',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        
        // Use database builder to insert all records
        $db = \Config\Database::connect();
        $builder = $db->table('programs');
        
        $builder->insertBatch($data);
        
        echo count($data) . " additional master/postgraduate program records have been seeded successfully!\n";
    }
}
