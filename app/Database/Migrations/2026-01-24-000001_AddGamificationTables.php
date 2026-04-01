<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGamificationTables extends Migration
{
    public function up()
    {
        // Table: behavior_types
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'icon' => [
                'type' => 'VARCHAR',
                'constraint' => '50', // e.g., 'fa-book', 'bi-emoji-smile'
            ],
            'points' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0, // Can be positive or negative
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['positive', 'negative'],
                'default' => 'negative',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('behavior_types');

        // Seed default behaviors
        $data = [
            [
                'name' => 'No presentar tarea',
                'icon' => 'fa-times-circle',
                'points' => -5,
                'type' => 'negative',
            ],
            [
                'name' => 'Comer en clase',
                'icon' => 'fa-hamburger',
                'points' => -5,
                'type' => 'negative',
            ],
            [
                'name' => 'Indisciplina o ruido',
                'icon' => 'fa-volume-up',
                'points' => -5,
                'type' => 'negative',
            ],
            [
                'name' => 'Uso de celular',
                'icon' => 'fa-mobile-alt',
                'points' => -5,
                'type' => 'negative',
            ],
            [
                'name' => 'Participación positiva',
                'icon' => 'fa-star',
                'points' => 5,
                'type' => 'positive',
            ],
        ];
        $this->db->table('behavior_types')->insertBatch($data);

        // Table: daily_scores
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'student_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'date_id' => [ // Assuming date_id exists in your Dates system or just using DATE type if simpler, but following project pattern
                'type' => 'INT',
                'constraint' => 11,
            ],
            'subject_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'score' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 100,
            ],
            'updated_at datetime default current_timestamp on update current_timestamp',
        ]);
        $this->forge->addKey('id', true);
        // $this->forge->addForeignKey('student_id', 'student', 'student_id', 'CASCADE', 'CASCADE'); // Assuming student table exists
        $this->forge->createTable('daily_scores');

        // Table: behavior_log
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'student_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'behavior_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'subject_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'date_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('behavior_log');
    }

    public function down()
    {
        $this->forge->dropTable('behavior_log');
        $this->forge->dropTable('daily_scores');
        $this->forge->dropTable('behavior_types');
    }
}
