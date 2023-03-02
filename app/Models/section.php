<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class section extends Model
{
    protected $table = 'sections';

    protected $fillable = ["section_name","description","created_by"];
    
    use HasFactory;
}
