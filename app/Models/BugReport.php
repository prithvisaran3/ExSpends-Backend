<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class BugReport extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'bug_report';

    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'title',
        'description',
    ];
}
