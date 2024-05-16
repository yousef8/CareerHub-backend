<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'job_id', 'resume_path', 'status'
    ];
    public $incrementing = true;
    public function applicant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
