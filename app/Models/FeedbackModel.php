<?php

namespace App\Models;

use CodeIgniter\Model;

class FeedbackModel extends Model
{
    protected $table = 'system_feedback';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'user_type', 'comment', 'url', 'created_at'];

    public function registerFeedback($userId, $userType, $comment, $url)
    {
        return $this->insert([
            'user_id' => $userId,
            'user_type' => $userType,
            'comment' => $comment,
            'url' => $url,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getAllFeedback()
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }
}
