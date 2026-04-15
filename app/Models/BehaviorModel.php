<?php

namespace App\Models;

use CodeIgniter\Model;

class BehaviorModel extends Model
{
    protected $table = 'behavior_types';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['name', 'icon', 'points', 'type'];

    /**
     * Get all behaviors grouped by type (positive/negative)
     */
    public function getBehaviors()
    {
        $behaviors = $this->findAll();
        $grouped = [
            'positive' => [],
            'negative' => [],
            'neutral' => []
        ];

        foreach ($behaviors as $b) {
            $grouped[$b['type']][] = $b;
        }

        return $grouped;
    }

    /**
     * Log a student behavior occurrence
     */
    public function logBehavior($studentId, $behaviorTypeId, $subjectId, $dateId, $observation = null, $period = 1)
    {
        // Force Timezone for accurate logging
        date_default_timezone_set('America/La_Paz');

        $db = \Config\Database::connect();
        $builder = $db->table('behavior_log');

        return $builder->insert([
            'student_id' => $studentId,
            'behavior_type_id' => $behaviorTypeId,
            'subject_id' => $subjectId,
            'date_id' => $dateId,
            'period' => $period,
            'observation' => $observation,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Fetch daily logistics (Nurse/Bathroom) for a student, possibly from other subjects.
     */
    public function getDailyLogistics($studentId, $date)
    {
        $db = \Config\Database::connect();

        // Get database names dynamically
        $dbDefault = $db->database;
        $dbAsist = \Config\Database::connect('asistencia')->database;
        $dbTiqui = \Config\Database::connect('tiquipaya')->database;

        $builder = $db->table('behavior_log bl');
        $builder->select('bl.created_at, bl.subject_id, bt.name as behavior_name, bt.icon, s.name as subject_name');

        $builder->join('behavior_types bt', 'bt.id = bl.behavior_type_id');
        $builder->join($dbAsist . '.attendance_dates ad', 'ad.date_id = bl.date_id');
        $builder->join($dbTiqui . '.subject s', 's.subject_id = bl.subject_id');

        $builder->where('bl.student_id', $studentId);
        $builder->where('ad.date_class', $date);
        $builder->whereIn('bl.behavior_type_id', [10, 11]); // Hardcoded IDs for Enfermería and Baño

        $builder->orderBy('bl.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function getStudentLog($student_id, $date_id = null, $subject_id = null, $phase_id = null)
    {
        $db = \Config\Database::connect();

        $sql = "SELECT bl.id, bl.student_id, bl.behavior_type_id, bl.subject_id, bl.period,
                       bl.date_id, bl.created_at, bl.observation,
                       bt.name, bt.points, bt.icon, bt.type,
                       s.name AS subject_name
                FROM behavior_log bl
                INNER JOIN behavior_types bt ON bt.id = bl.behavior_type_id
                LEFT JOIN subject s ON s.subject_id = bl.subject_id
                WHERE bl.student_id = ?";
        $params = [$student_id];

        if ($date_id) {
            $sql .= " AND bl.date_id = ?";
            $params[] = $date_id;
        }
        if ($subject_id) {
            $sql .= " AND bl.subject_id = ?";
            $params[] = $subject_id;
        }
        if ($phase_id) {
            $asistenciaDb = \Config\Database::connect('asistencia');
            $dateIds = $asistenciaDb->table('attendance_dates')
                ->select('date_id')
                ->where('phase_id', $phase_id)
                ->get()
                ->getResultArray();
            $ids = array_column($dateIds, 'date_id');
            if (!empty($ids)) {
                $sql .= " AND bl.date_id IN (" . implode(',', array_map('intval', $ids)) . ")";
            }
        }

        $sql .= " ORDER BY bl.created_at DESC";
        return $db->query($sql, $params)->getResultArray();
    }

    public function deleteLog($id)
    {
        $db = \Config\Database::connect();
        return $db->table('behavior_log')->where('id', $id)->delete();
    }

    public function updateObservation($id, $observation)
    {
        $db = \Config\Database::connect();
        return $db->table('behavior_log')->where('id', $id)->update(['observation' => $observation]);
    }

    /**
     * Get all behavior logs for a section in a specific phase
     */
    public function getSectionBehaviorLog($section_id, $phase_id)
    {
        $db = \Config\Database::connect();
        $dbTiqui = \Config\Database::connect('tiquipaya')->database;
        $dbAsist = \Config\Database::connect('asistencia')->database;

        $builder = $db->table('behavior_log bl');
        $builder->select('bl.*, bt.name as behavior_name, bt.points, bt.icon, bt.type, s.name as subject_name, st.name as student_name, st.lastname as student_lastname, st.lastname2 as student_lastname2');
        $builder->join('behavior_types bt', 'bt.id = bl.behavior_type_id');
        $builder->join($dbTiqui . '.subject s', 's.subject_id = bl.subject_id');
        $builder->join($dbTiqui . '.t_student st', 'st.student_id = bl.student_id');
        $builder->join($dbAsist . '.attendance_dates ad', 'ad.date_id = bl.date_id');

        $builder->where('st.section_id', $section_id);
        $builder->where('ad.phase_id', $phase_id);

        $builder->orderBy('bl.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }
}
