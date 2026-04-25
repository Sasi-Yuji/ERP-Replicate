<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHostelFeeStatus extends Migration
{
    public function up()
    {
        $this->forge->addColumn('hostel_allocation', [
            'fee_status' => [
                'type' => 'ENUM',
                'constraint' => ['Paid', 'Unpaid', 'Partial'],
                'default' => 'Unpaid',
                'after' => 'status'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('hostel_allocation', 'fee_status');
    }
}
