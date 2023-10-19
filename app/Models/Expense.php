<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Expense extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'expense';

    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'expense_category',
        'amount',
        'is_income',
        'date',
        'expense_name',
    ];
}
