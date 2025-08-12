<?php

namespace App\Models;
use CodeIgniter\Model;

class UserActivityModel extends Model
{
    protected $table = 'tb_user_activities';
    protected $primaryKey = 'id_activity';
    protected $allowedFields = [
        'session_id', 
        'id_artikel', 
        'activity_type', 
        'activity_value', 
        'user_agent', 
        'referrer'
    ];
}
