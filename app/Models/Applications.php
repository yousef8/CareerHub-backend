<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applications extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'job_id', 'resume_path', 'status'
    ];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function job()
    {
        return $this->belongsToMany(JobPost::class);
    }
}
