<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPasswordReset extends Model
{
    use HasFactory;
    protected $table = "admin_password_reset";

    public function getAdmin()
    {
        return $this->belongsTo(Admin::class, 'email', 'admin_email');
    }
}
