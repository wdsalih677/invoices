<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = ["product_name","description","section_id"];

    use HasFactory;


    public function section()
    {
        return $this->belongsTo(section::class, 'section_id');
    }

}
