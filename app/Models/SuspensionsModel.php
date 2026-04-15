<?php
namespace App\Models;
use CodeIgniter\Model;
class SuspensionsModel extends Model
{
    protected $DBGroup       = 'asistencia';
    protected $table         = 'suspensions';
    protected $primaryKey    = 'suspension_id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $allowedFields = [
        'student_id',
        'type',
        'reason',
        'date_start',
        'date_end',
        'date',
        'period_id',
        'created_at',
        'created_by',
    ];
    // CREATE
    public function insertSuspension(array $datos): int
    {
        $this->db->table($this->table)->insert($datos);
        return (int) $this->db->insertID();
    }
    // READ
    public function getSuspension(int $suspension_id): array
    {
        $row = $this->db->table($this->table)
            ->where('suspension_id', $suspension_id)
            ->get()->getRowArray();
        return $row ?? [];
    }
    public function getSuspensionsByStudent(int $student_id): array
    {
        return $this->db->table($this->table)
            ->where('student_id', $student_id)
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();
    }
    public function getSuspensionsByType(int $type): array
    {
        return $this->db->table($this->table)
            ->where('type', $type)
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();
    }
    public function listarSuspensiones(): array
    {
        $sql = "SELECT s.*,
                    CONCAT(e.lastname, ' ', e.lastname2, ' ', e.name) AS student,
                    sec.nick_name AS seccion,
                    p.periodo AS periodo_nombre
                FROM suspensions s
                INNER JOIN t_student e   ON e.student_id = s.student_id
                INNER JOIN section sec   ON sec.section_id = e.section_id
                LEFT  JOIN periodo p     ON p.periodo_id = s.period_id
                ORDER BY s.created_at DESC";
        return $this->db->query($sql)->getResultArray();
    }
    public function listarPorSeccion(int $section_id): array
    {
        $sql = "SELECT s.*,
                    CONCAT(e.lastname, ' ', e.lastname2, ' ', e.name) AS student,
                    p.periodo AS periodo_nombre
                FROM suspensions s
                INNER JOIN t_student e ON e.student_id = s.student_id
                LEFT  JOIN periodo p   ON p.periodo_id = s.period_id
                WHERE e.section_id = ?
                ORDER BY s.created_at DESC";
        return $this->db->query($sql, [$section_id])->getResultArray();
    }
    public function listarPorFechas(string $fechaIni, string $fechaFin): array
    {
        $sql = "SELECT s.*,
                    CONCAT(e.lastname, ' ', e.lastname2, ' ', e.name) AS student,
                    sec.nick_name AS seccion
                FROM suspensions s
                INNER JOIN t_student e ON e.student_id = s.student_id
                INNER JOIN section sec ON sec.section_id = e.section_id
                WHERE s.type = 1
                  AND s.date_start <= ? AND s.date_end >= ?
                ORDER BY s.date_start ASC";
        return $this->db->query($sql, [$fechaFin, $fechaIni])->getResultArray();
    }
    public function tienesSuspensionDia(int $student_id, string $fecha): bool
    {
        $row = $this->db->query(
            "SELECT suspension_id FROM suspensions
             WHERE student_id = ? AND type = 1
               AND date_start <= ? AND date_end >= ?
             LIMIT 1",
            [$student_id, $fecha, $fecha]
        )->getRow();
        return $row !== null;
    }
    public function tienesSuspensionPeriodo(int $student_id, string $fecha, int $period_id): bool
    {
        $row = $this->db->query(
            "SELECT suspension_id FROM suspensions
             WHERE student_id = ? AND type = 2
               AND date = ? AND period_id = ?
             LIMIT 1",
            [$student_id, $fecha, $period_id]
        )->getRow();
        return $row !== null;
    }
    // UPDATE
    public function updateSuspension(array $datos, int $suspension_id): bool
    {
        return $this->db->table($this->table)
            ->set($datos)
            ->where('suspension_id', $suspension_id)
            ->update();
    }
    // DELETE
    public function deleteSuspension(int $suspension_id): bool
    {
        return $this->db->table($this->table)
            ->where('suspension_id', $suspension_id)
            ->delete();
    }
    public function deletePorEstudiante(int $student_id): bool
    {
        return $this->db->table($this->table)
            ->where('student_id', $student_id)
            ->delete();
    }
}