<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LanguageProgramSeeder extends Seeder
{
    public function run()
    {
        $programs = $this->getLanguagePrograms();
        
        foreach ($programs as $program) {
            $this->db->table('programs')->insert($program);
        }
        
        echo "Inserted " . count($programs) . " language programs.\n";
    }
    
    private function generateUUID(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    
    private function getLanguagePrograms(): array
    {
        $programs = [];
        $timestamp = date('Y-m-d H:i:s');
        
        // CHINESE LANGUAGE PROGRAMS
        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'Chinese Regular Course - Beginner',
            'description' => 'Learn Mandarin Chinese from scratch with our comprehensive beginner course. Master Pinyin, basic characters, and everyday conversations. Perfect for those starting their Chinese language journey.',
            'features' => json_encode([
                'HSK 1-2 preparation',
                'Pinyin mastery',
                '300+ essential characters',
                'Daily conversation practice',
                'Cultural insights',
                'Interactive learning materials'
            ]),
            'facilities' => json_encode([
                'Air-conditioned classroom',
                'Audio-visual equipment',
                'Chinese learning materials',
                'Practice workbooks',
                'Online resources access'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Library access',
                'Study lounge',
                'Certificate upon completion'
            ]),
            'registration_fee' => 500000.00,
            'tuition_fee' => 2500000.00,
            'discount' => 10.00,
            'category' => 'Chinese',
            'sub_category' => 'Regular',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
        
        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'Chinese Regular Course - Intermediate',
            'description' => 'Advance your Mandarin skills with intermediate grammar, expanded vocabulary, and complex sentence structures. Prepare for HSK 3-4 exams while improving fluency.',
            'features' => json_encode([
                'HSK 3-4 preparation',
                '1000+ vocabulary words',
                'Complex grammar patterns',
                'Business Chinese basics',
                'Reading comprehension',
                'Writing practice'
            ]),
            'facilities' => json_encode([
                'Air-conditioned classroom',
                'Multimedia learning tools',
                'Chinese newspapers & magazines',
                'Practice materials',
                'Online platform access'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Library access',
                'Language exchange sessions',
                'HSK exam preparation materials'
            ]),
            'registration_fee' => 500000.00,
            'tuition_fee' => 3000000.00,
            'discount' => 10.00,
            'category' => 'Chinese',
            'sub_category' => 'Regular',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
        
        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'Chinese Package Course - Intensive',
            'description' => 'Accelerated Chinese learning package covering beginner to intermediate levels in 6 months. Includes HSK exam preparation, cultural workshops, and conversation practice.',
            'features' => json_encode([
                'HSK 1-4 comprehensive preparation',
                'Intensive daily classes',
                '2000+ vocabulary mastery',
                'Cultural immersion activities',
                'Mock HSK exams',
                'Personal progress tracking'
            ]),
            'facilities' => json_encode([
                'Premium classroom facilities',
                'Advanced multimedia equipment',
                'Complete learning materials set',
                'Digital learning platform',
                'Practice lab access'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Library & resource center',
                'Cultural workshop sessions',
                'HSK exam voucher included',
                'Certificate of completion'
            ]),
            'registration_fee' => 750000.00,
            'tuition_fee' => 8500000.00,
            'discount' => 15.00,
            'category' => 'Chinese',
            'sub_category' => 'Package',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];

        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'Chinese Private Course - One-on-One',
            'description' => 'Personalized Chinese learning experience with dedicated instructor. Flexible schedule, customized curriculum, and focused attention on your specific goals and learning pace.',
            'features' => json_encode([
                'Customized learning plan',
                'Flexible scheduling',
                'One-on-one instruction',
                'Personalized materials',
                'Progress at your own pace',
                'Specific goal-oriented learning'
            ]),
            'facilities' => json_encode([
                'Private study room',
                'Personal learning materials',
                'Digital resources',
                'Recording equipment for practice',
                'Comfortable learning environment'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Flexible class hours',
                'Online/offline options',
                'Progress reports',
                'Lifetime material access'
            ]),
            'registration_fee' => 300000.00,
            'tuition_fee' => 5000000.00,
            'discount' => 5.00,
            'category' => 'Chinese',
            'sub_category' => 'Private',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
        
        // JAPANESE LANGUAGE PROGRAMS
        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'Japanese Regular Course - Beginner',
            'description' => 'Start your Japanese language journey with Hiragana, Katakana, and basic Kanji. Learn essential grammar and everyday expressions for practical communication.',
            'features' => json_encode([
                'JLPT N5-N4 preparation',
                'Hiragana & Katakana mastery',
                'Basic Kanji (100+ characters)',
                'Daily conversation skills',
                'Japanese culture introduction',
                'Listening & speaking practice'
            ]),
            'facilities' => json_encode([
                'Air-conditioned classroom',
                'Audio-visual equipment',
                'Japanese learning materials',
                'Textbooks & workbooks',
                'Online learning platform'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Library access',
                'Japanese media resources',
                'Certificate upon completion'
            ]),
            'registration_fee' => 500000.00,
            'tuition_fee' => 2800000.00,
            'discount' => 10.00,
            'category' => 'Japanese',
            'sub_category' => 'Regular',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];

        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'Japanese Regular Course - Intermediate',
            'description' => 'Enhance your Japanese proficiency with advanced grammar, expanded Kanji knowledge, and business Japanese basics. Prepare for JLPT N3-N2 certification.',
            'features' => json_encode([
                'JLPT N3-N2 preparation',
                '500+ Kanji characters',
                'Advanced grammar patterns',
                'Business Japanese introduction',
                'Reading comprehension',
                'Keigo (honorific language)'
            ]),
            'facilities' => json_encode([
                'Air-conditioned classroom',
                'Multimedia learning tools',
                'Japanese books & magazines',
                'Practice materials',
                'Digital learning resources'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Library access',
                'Language exchange program',
                'JLPT preparation materials'
            ]),
            'registration_fee' => 500000.00,
            'tuition_fee' => 3200000.00,
            'discount' => 10.00,
            'category' => 'Japanese',
            'sub_category' => 'Regular',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
        
        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'Japanese Package Course - Complete',
            'description' => 'Comprehensive Japanese learning package from beginner to advanced. Includes JLPT preparation, cultural workshops, and immersive learning experiences.',
            'features' => json_encode([
                'JLPT N5-N2 full preparation',
                'Complete Hiragana, Katakana, Kanji',
                '1000+ Kanji mastery',
                'Business Japanese skills',
                'Cultural immersion activities',
                'Mock JLPT exams'
            ]),
            'facilities' => json_encode([
                'Premium classroom facilities',
                'Advanced multimedia equipment',
                'Complete textbook series',
                'Digital learning platform',
                'Practice lab access'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Library & resource center',
                'Japanese cultural workshops',
                'JLPT exam voucher',
                'Certificate of completion'
            ]),
            'registration_fee' => 750000.00,
            'tuition_fee' => 9000000.00,
            'discount' => 15.00,
            'category' => 'Japanese',
            'sub_category' => 'Package',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];

        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'Japanese Private Course - Personalized',
            'description' => 'Tailored Japanese instruction with flexible scheduling and customized curriculum. Perfect for busy professionals or those with specific learning goals.',
            'features' => json_encode([
                'Customized learning plan',
                'Flexible scheduling',
                'One-on-one instruction',
                'Personalized materials',
                'Goal-oriented approach',
                'Business or casual focus'
            ]),
            'facilities' => json_encode([
                'Private study room',
                'Personal learning materials',
                'Digital resources',
                'Recording equipment',
                'Comfortable environment'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Flexible class hours',
                'Online/offline options',
                'Progress tracking',
                'Lifetime material access'
            ]),
            'registration_fee' => 300000.00,
            'tuition_fee' => 5500000.00,
            'discount' => 5.00,
            'category' => 'Japanese',
            'sub_category' => 'Private',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
        
        // KOREAN LANGUAGE PROGRAMS
        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'Korean Regular Course - Beginner',
            'description' => 'Master Hangul and basic Korean grammar with our beginner course. Learn essential vocabulary and expressions for everyday situations and K-culture appreciation.',
            'features' => json_encode([
                'TOPIK I preparation',
                'Hangul mastery',
                'Basic grammar patterns',
                'Daily conversation practice',
                'K-culture insights',
                'Pronunciation training'
            ]),
            'facilities' => json_encode([
                'Air-conditioned classroom',
                'Audio-visual equipment',
                'Korean learning materials',
                'Textbooks & workbooks',
                'Online resources'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Library access',
                'K-drama & K-pop resources',
                'Certificate upon completion'
            ]),
            'registration_fee' => 500000.00,
            'tuition_fee' => 2600000.00,
            'discount' => 10.00,
            'category' => 'Korean',
            'sub_category' => 'Regular',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];

        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'Korean Regular Course - Intermediate',
            'description' => 'Advance your Korean skills with complex grammar, expanded vocabulary, and formal/informal speech patterns. Prepare for TOPIK II certification.',
            'features' => json_encode([
                'TOPIK II preparation',
                'Advanced grammar structures',
                'Formal & informal speech',
                'Business Korean basics',
                'Reading & writing skills',
                'Cultural nuances'
            ]),
            'facilities' => json_encode([
                'Air-conditioned classroom',
                'Multimedia learning tools',
                'Korean books & magazines',
                'Practice materials',
                'Digital platform access'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Library access',
                'Language exchange sessions',
                'TOPIK preparation materials'
            ]),
            'registration_fee' => 500000.00,
            'tuition_fee' => 3000000.00,
            'discount' => 10.00,
            'category' => 'Korean',
            'sub_category' => 'Regular',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
        
        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'Korean Package Course - K-Culture Intensive',
            'description' => 'Immersive Korean language and culture package. Learn language through K-drama, K-pop, and Korean culture while preparing for TOPIK certification.',
            'features' => json_encode([
                'TOPIK I-II preparation',
                'K-culture immersion',
                'K-drama based learning',
                'K-pop lyric analysis',
                'Cultural workshops',
                'Comprehensive language skills'
            ]),
            'facilities' => json_encode([
                'Premium classroom facilities',
                'Advanced multimedia equipment',
                'Complete learning materials',
                'Digital learning platform',
                'K-culture resource center'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Library & media center',
                'K-culture workshops',
                'TOPIK exam voucher',
                'Certificate of completion'
            ]),
            'registration_fee' => 750000.00,
            'tuition_fee' => 8000000.00,
            'discount' => 15.00,
            'category' => 'Korean',
            'sub_category' => 'Package',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];

        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'Korean Private Course - Customized',
            'description' => 'Personalized Korean learning with flexible schedule and customized curriculum. Focus on your specific interests whether business, travel, or K-culture.',
            'features' => json_encode([
                'Customized learning plan',
                'Flexible scheduling',
                'One-on-one instruction',
                'Personalized materials',
                'Your choice of focus',
                'Rapid progress'
            ]),
            'facilities' => json_encode([
                'Private study room',
                'Personal learning materials',
                'Digital resources',
                'Recording equipment',
                'Comfortable environment'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Flexible class hours',
                'Online/offline options',
                'Progress reports',
                'Lifetime material access'
            ]),
            'registration_fee' => 300000.00,
            'tuition_fee' => 4800000.00,
            'discount' => 5.00,
            'category' => 'Korean',
            'sub_category' => 'Private',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
        
        // GERMAN LANGUAGE PROGRAMS
        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'German Regular Course - Beginner (A1-A2)',
            'description' => 'Begin your German language journey with fundamental grammar, vocabulary, and pronunciation. Prepare for Goethe-Zertifikat A1-A2 certification.',
            'features' => json_encode([
                'Goethe-Zertifikat A1-A2 prep',
                'German alphabet & pronunciation',
                'Basic grammar structures',
                'Everyday vocabulary',
                'Simple conversations',
                'German culture introduction'
            ]),
            'facilities' => json_encode([
                'Air-conditioned classroom',
                'Audio-visual equipment',
                'German learning materials',
                'Textbooks & workbooks',
                'Online learning platform'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Library access',
                'German media resources',
                'Certificate upon completion'
            ]),
            'registration_fee' => 500000.00,
            'tuition_fee' => 3000000.00,
            'discount' => 10.00,
            'category' => 'German',
            'sub_category' => 'Regular',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];

        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'German Regular Course - Intermediate (B1-B2)',
            'description' => 'Advance your German proficiency with complex grammar, expanded vocabulary, and professional communication skills. Prepare for Goethe-Zertifikat B1-B2.',
            'features' => json_encode([
                'Goethe-Zertifikat B1-B2 prep',
                'Advanced grammar patterns',
                'Professional vocabulary',
                'Business German basics',
                'Reading & writing skills',
                'Cultural understanding'
            ]),
            'facilities' => json_encode([
                'Air-conditioned classroom',
                'Multimedia learning tools',
                'German books & magazines',
                'Practice materials',
                'Digital resources'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Library access',
                'Language exchange program',
                'Goethe exam preparation'
            ]),
            'registration_fee' => 500000.00,
            'tuition_fee' => 3500000.00,
            'discount' => 10.00,
            'category' => 'German',
            'sub_category' => 'Regular',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
        
        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'German Package Course - Complete (A1-C1)',
            'description' => 'Comprehensive German learning from beginner to advanced. Includes all Goethe-Zertifikat levels, business German, and cultural immersion.',
            'features' => json_encode([
                'Goethe-Zertifikat A1-C1 prep',
                'Complete grammar mastery',
                'Business German proficiency',
                'Academic German skills',
                'Cultural workshops',
                'Mock certification exams'
            ]),
            'facilities' => json_encode([
                'Premium classroom facilities',
                'Advanced multimedia equipment',
                'Complete textbook series',
                'Digital learning platform',
                'Practice lab access'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Library & resource center',
                'German cultural events',
                'Goethe exam voucher',
                'Certificate of completion'
            ]),
            'registration_fee' => 750000.00,
            'tuition_fee' => 10000000.00,
            'discount' => 15.00,
            'category' => 'German',
            'sub_category' => 'Package',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];

        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'German Private Course - Professional',
            'description' => 'Personalized German instruction for professionals. Focus on business German, technical vocabulary, or exam preparation with flexible scheduling.',
            'features' => json_encode([
                'Customized learning plan',
                'Flexible scheduling',
                'One-on-one instruction',
                'Business German focus',
                'Technical vocabulary',
                'Exam preparation'
            ]),
            'facilities' => json_encode([
                'Private study room',
                'Professional learning materials',
                'Digital resources',
                'Recording equipment',
                'Executive environment'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Flexible class hours',
                'Online/offline options',
                'Progress tracking',
                'Lifetime material access'
            ]),
            'registration_fee' => 300000.00,
            'tuition_fee' => 6000000.00,
            'discount' => 5.00,
            'category' => 'German',
            'sub_category' => 'Private',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
        
        // ENGLISH LANGUAGE PROGRAMS
        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'English Regular Course - General English',
            'description' => 'Improve your English skills with comprehensive grammar, vocabulary, and communication practice. Suitable for all levels from beginner to intermediate.',
            'features' => json_encode([
                'Grammar fundamentals',
                'Vocabulary building',
                'Speaking & listening practice',
                'Reading comprehension',
                'Writing skills',
                'Pronunciation training'
            ]),
            'facilities' => json_encode([
                'Air-conditioned classroom',
                'Audio-visual equipment',
                'English learning materials',
                'Textbooks & workbooks',
                'Online learning platform'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Library access',
                'English media resources',
                'Certificate upon completion'
            ]),
            'registration_fee' => 500000.00,
            'tuition_fee' => 2200000.00,
            'discount' => 10.00,
            'category' => 'English',
            'sub_category' => 'Regular',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];

        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'English Regular Course - Business English',
            'description' => 'Master professional English for business settings. Learn business vocabulary, email writing, presentation skills, and meeting communication.',
            'features' => json_encode([
                'Business vocabulary',
                'Email & report writing',
                'Presentation skills',
                'Meeting communication',
                'Negotiation language',
                'Professional etiquette'
            ]),
            'facilities' => json_encode([
                'Air-conditioned classroom',
                'Multimedia learning tools',
                'Business English materials',
                'Case study resources',
                'Digital platform access'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Library access',
                'Business simulation exercises',
                'Certificate upon completion'
            ]),
            'registration_fee' => 500000.00,
            'tuition_fee' => 2800000.00,
            'discount' => 10.00,
            'category' => 'English',
            'sub_category' => 'Regular',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
        
        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'English Package Course - IELTS/TOEFL Preparation',
            'description' => 'Comprehensive test preparation package for IELTS and TOEFL exams. Includes intensive practice, mock tests, and strategies for achieving high scores.',
            'features' => json_encode([
                'IELTS & TOEFL strategies',
                'All sections covered',
                'Mock tests & scoring',
                'Time management skills',
                'Writing task practice',
                'Speaking interview prep'
            ]),
            'facilities' => json_encode([
                'Premium classroom facilities',
                'Computer lab for practice',
                'Complete test materials',
                'Digital learning platform',
                'Practice test center'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Library & resource center',
                'Unlimited mock tests',
                'Score prediction service',
                'Certificate of completion'
            ]),
            'registration_fee' => 750000.00,
            'tuition_fee' => 7000000.00,
            'discount' => 15.00,
            'category' => 'English',
            'sub_category' => 'Package',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];

        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'English Package Course - Academic English',
            'description' => 'Prepare for academic success with advanced English skills for university studies. Includes essay writing, research skills, and academic presentations.',
            'features' => json_encode([
                'Academic writing skills',
                'Research & citation',
                'Critical reading',
                'Academic presentations',
                'Seminar participation',
                'Thesis writing basics'
            ]),
            'facilities' => json_encode([
                'Premium classroom facilities',
                'Research library access',
                'Complete academic materials',
                'Digital learning platform',
                'Writing lab'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Academic resource center',
                'Writing feedback service',
                'University preparation',
                'Certificate of completion'
            ]),
            'registration_fee' => 750000.00,
            'tuition_fee' => 6500000.00,
            'discount' => 15.00,
            'category' => 'English',
            'sub_category' => 'Package',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
        
        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'English Private Course - Conversation Focus',
            'description' => 'Personalized English conversation practice with native or near-native speakers. Build confidence and fluency through intensive speaking practice.',
            'features' => json_encode([
                'Customized conversation topics',
                'Flexible scheduling',
                'One-on-one instruction',
                'Pronunciation correction',
                'Natural expression practice',
                'Cultural insights'
            ]),
            'facilities' => json_encode([
                'Private study room',
                'Conversation materials',
                'Recording equipment',
                'Digital resources',
                'Comfortable environment'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Flexible class hours',
                'Online/offline options',
                'Progress tracking',
                'Lifetime material access'
            ]),
            'registration_fee' => 300000.00,
            'tuition_fee' => 4000000.00,
            'discount' => 5.00,
            'category' => 'English',
            'sub_category' => 'Private',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
        
        $programs[] = [
            'id' => $this->generateUUID(),
            'title' => 'English Private Course - Business Executive',
            'description' => 'Premium English coaching for business executives. Focus on leadership communication, international business, and executive presence.',
            'features' => json_encode([
                'Executive communication',
                'Leadership language',
                'International business English',
                'Presentation mastery',
                'Negotiation skills',
                'Cross-cultural communication'
            ]),
            'facilities' => json_encode([
                'Executive study room',
                'Premium learning materials',
                'Business case studies',
                'Recording equipment',
                'Professional environment'
            ]),
            'extra_facilities' => json_encode([
                'Free Wi-Fi',
                'Ultra-flexible scheduling',
                'Online/offline/hybrid options',
                'Executive progress reports',
                'Lifetime VIP access'
            ]),
            'registration_fee' => 500000.00,
            'tuition_fee' => 8000000.00,
            'discount' => 5.00,
            'category' => 'English',
            'sub_category' => 'Private',
            'status' => 'active',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
        
        return $programs;
    }
}
