<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;
    protected $table = 'subcategories';

    public function Category()
    {
       return $this->belongsTo(Category::class,'category','id')->orderBy('name');
    }
}
