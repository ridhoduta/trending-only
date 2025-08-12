<?php

namespace App\Models;

use CodeIgniter\Model;

class PenulisModel extends Model
{
    protected $table = 'tb_users';
    protected $primaryKey = 'id_user';
    protected $allowedFields = ['nama_lengkap', 'email', 'password', 'role', 'foto'];
}
