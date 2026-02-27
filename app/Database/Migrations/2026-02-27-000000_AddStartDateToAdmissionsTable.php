<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStartDateToAdmissionsTable extends Migration
{
    public function up()
    {
        $fields = [
            'start_date' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Course start date for the student',
            ],
        ];
        
        $this->forge->addColumn('admissions', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('admissions', 'start_date');
    }
}
