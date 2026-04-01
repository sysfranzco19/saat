<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Parents extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'parent_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'lastname' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'lastname2' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'birthday' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'place_birth' => [
                'type'       => 'INT',
                'constraint' => '5',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'level' => [
                'type'       => 'INT',
                'constraint' => '5',
            ],
            'employee_id' => [
                'type'       => 'INT',
                'constraint' => '11',
            ],
            
        ]);
        $this->forge->addKey('admin_id', true);
        $this->forge->createTable('admin');
    }

    public function down()
    {
        $this->forge->dropTable('parent');
    }
}
