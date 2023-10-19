<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Categories extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'categories';

    // protected $fillable = ['category_name','image'];
    
    protected $fillable = ['category_name'];

}
