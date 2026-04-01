<?php

namespace App\Models;

use CodeIgniter\Model;

class ScoreModel extends Model
{
    protected $table = 'daily_scores';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['student_id', 'date_id', 'subject_id', 'score', 'period'];

    /**
     * Get cumulative score for a student in a subject up to a specific date (or the end of the phase)
     */
    public function getDailyScore($studentId, $dateId, $subjectId, $period = 1)
    {
        // 1. Get the date of the dateId to filter logs
        $asistenciaDb = \Config\Database::connect('asistencia');
        $dateInfo = $asistenciaDb->table('attendance_dates')->where('date_id', $dateId)->get()->getRowArray();

        if (!$dateInfo) {
            return 100; // Fallback
        }

        $phaseId = $dateInfo['phase_id'];
        $dateClass = $dateInfo['date_class'];

        // 2. Sum all points from behavior_log for this student, subject and phase UP TO this date (inclusive)
        $db = \Config\Database::connect();
        $builder = $db->table('behavior_log bl');
        $builder->selectSum('bt.points');
        $builder->join('behavior_types bt', 'bt.id = bl.behavior_type_id');
        $builder->join($asistenciaDb->database . '.attendance_dates ad', 'ad.date_id = bl.date_id');

        $builder->where('bl.student_id', $studentId);
        $builder->where('bl.subject_id', $subjectId);
        $builder->where('ad.phase_id', $phaseId);

        // Cumulative means all points until this date or "moment"
        // Since we are in a daily record, we include everything until this date_class
        $builder->where('ad.date_class <=', $dateClass);

        $result = $builder->get()->getRowArray();
        $totalPoints = (int) ($result['points'] ?? 0);

        $finalScore = 100 + $totalPoints;

        if ($finalScore < 0)
            $finalScore = 0;

        // 3. Sync with daily_scores table (for legacy/cache/reporting purposes if needed)
        // We'll update the record for THIS date_id so it reflects the cumulative score at this point
        if (!empty($period) && $period !== '0') {
            $record = $this->where([
                'student_id' => $studentId,
                'date_id' => $dateId,
                'subject_id' => $subjectId,
                'period' => $period
            ])->first();

            if (!$record) {
                $this->insert([
                    'student_id' => $studentId,
                    'date_id' => $dateId,
                    'subject_id' => $subjectId,
                    'period' => $period,
                    'score' => $finalScore
                ]);
            } else {
                $this->where('id', $record['id'])->set(['score' => $finalScore])->update();
            }
        }

        return $finalScore;
    }

    /**
     * Update score (This is now technically a no-op or a trigger to recalculate, 
     * but we keep it for compatibility with the AJAX calls)
     */
    public function updateScore($studentId, $dateId, $subjectId, $pointsDelta, $period = 1)
    {
        // Since getDailyScore now calculates based on logs, we just need to return 
        // the new calculation after the log has presumably been added/removed.
        return $this->getDailyScore($studentId, $dateId, $subjectId, $period);
    }
    /**
     * Get all daily scores for a student in a subject, optionally filtered by phase
     */
    public function getSubjectScores($studentId, $subjectId, $phaseId = null)
    {
        $builder = $this->builder();
        $builder->select('daily_scores.*');
        $builder->where('daily_scores.student_id', $studentId);
        $builder->where('daily_scores.subject_id', $subjectId);

        if ($phaseId) {
            $asistenciaDb = \Config\Database::connect('asistencia');
            $dateIds = $asistenciaDb->table('attendance_dates')
                ->select('date_id')
                ->where('phase_id', $phaseId)
                ->get()
                ->getResultArray();
            $ids = array_column($dateIds, 'date_id');

            if (empty($ids)) {
                return [];
            }
            $builder->whereIn('daily_scores.date_id', $ids);
        }

        return $builder->get()->getResultArray();
    }
}
