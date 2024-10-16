<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'title',
        'category',
        'price',
        'serial',
        'certificate',
        'code_manufactur',
        'created_by',
        'edited_by',
        'branch_id'
    ];
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
