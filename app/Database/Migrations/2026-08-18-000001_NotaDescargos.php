<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tabla nota_descargos: "Descargo de Aplazados".
 *
 * Cuando un estudiante se aplaza (csamarks.total_average < 51) en una materia,
 * el docente titular de esa materia debe dejar constancia de las gestiones
 * realizadas antes del aplazo (reunión con padres, estrategias aplicadas,
 * motivo del aplazo). Un registro de esta tabla es requisito para poder
 * consolidar las notas de la materia (Teacher::consolidate_notes()).
 *
 * Vive en el DBGroup 'tiquipaya' (misma base que csamarks, subject, t_student,
 * incidencia_compromisos), para poder hacer joins directos con esas tablas.
 */
class NotaDescargos extends Migration
{
    protected $DBGroup = 'tiquipaya';

    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'student_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'subject_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'teacher_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'section_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'phase_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            // Snapshots de texto: evitan depender de un join a la tabla
            // 'phase'/'settings', que vive en otro DBGroup ('default').
            'phase_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'gestion' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'nota_final' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            'reunion_padres' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            // Cuántas veces se reunió con los padres; solo tiene sentido si
            // reunion_padres = 1 (si es 0, se guarda en 0).
            'nro_reuniones' => [
                'type'       => 'SMALLINT',
                'constraint' => 5,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'estrategias_aplicadas' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'motivo_aplazo' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            // Opcional: acta/descargo firmado y escaneado, subido luego
            // siguiendo el mismo patrón que Teacher::upload_acta().
            'archivo_firmado' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['student_id', 'subject_id', 'phase_id'], false, true, 'uq_descargo_student_subject_phase');
        $this->forge->addKey('teacher_id');
        $this->forge->addKey('subject_id');
        $this->forge->createTable('nota_descargos');
    }

    public function down()
    {
        $this->forge->dropTable('nota_descargos');
    }
}
