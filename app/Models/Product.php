<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'p_category',
        'p_subcategory',
        'name',
        'price',
        'p_color',
    ];

    public function Category()
    {
       return $this->belongsTo(Category::class,'p_category','id');
    }
    public function Subcategory()
    {
       return $this->belongsTo(Subcategory::class,'p_subcategory','id');
    }
}