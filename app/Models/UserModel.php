<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama',
        'email',
        'password',
        'role',
        'is_verified',
        'saldo',
        'bio',
        'last_seen_at',
    ];
}