<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'ci_admin';
    protected $primaryKey = 'admin_id';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'email',
        'password',
        'admin_role_id',
        'is_verify',
        'token',
        'is_active',
        'is_supper',
        'profile_image',
        'firstname',
        'lastname',
        'password_reset_code'
    ];

    public function role()
    {
        return $this->belongsTo(AdminRole::class, 'admin_role_id');
    }
}
