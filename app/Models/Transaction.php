<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_number',
        'queue_number',
        'table_id',
        'user_id',
        'total_amount',
        'paid_amount',
        'change_amount',
        'payment_method',
        'status',
        'transaction_date',
        'discount_type',
        'discount_value',
        'discount_amount',
        'voided_by',
        'voided_at',
        'void_reason',

    ];
    protected $casts = ['transaction_date' => 'datetime'];
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
    public function table()
    {
        return $this->belongsTo(Table::class);
    }
    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function voider()
    {
        return $this->belongsTo(User::class, 'voided_by');
    }
}
