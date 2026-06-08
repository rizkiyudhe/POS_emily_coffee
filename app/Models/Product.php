<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'sku', 'price', 'stock',  'description', 'is_active', 'photo'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
