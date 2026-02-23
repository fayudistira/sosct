<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLanguageFieldsToPrograms extends Migration
{
    public function up()
    {
        $fields = [
            'language' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'discount',
            ],
            'language_level' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'language',
            ],
        ];

        $this->forge->addColumn('programs', $fields);

        // Update existing programs: set language based on category
        $db = \Config\Database::connect();
        $builder = $db->table('programs');

        // Map category to language
        $categoryLanguageMap = [
            'Mandarin' => 'Mandarin',
            'Japanese' => 'Japanese',
            'Korean' => 'Korean',
            'German' => 'German',
            'English' => 'English',
        ];

        foreach ($categoryLanguageMap as $category => $language) {
            $builder->where('category', $category)
                ->update(['language' => $language]);
        }

        // Set language_level based on sub_category for existing programs
        $subCategoryLevelMap = [
            'HSK 1' => 'Beginner',
            'HSK 2' => 'Beginner',
            'HSK 3' => 'Intermediate',
            'HSK 4' => 'Intermediate',
            'HSK 5' => 'Advanced',
            'HSK 6' => 'Advanced',
            'Paket' => 'All Levels',
            'Privat' => 'All Levels',
            'Standard' => 'Beginner',
        ];

        foreach ($subCategoryLevelMap as $subCategory => $level) {
            $builder->where('sub_category', $subCategory)
                ->update(['language_level' => $level]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('programs', ['language', 'language_level']);
    }
}
