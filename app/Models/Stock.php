<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function expenseByCreatedAt()
    {
        return $this->hasOne(Expense::class, 'created_at', 'created_at');
    }

    public function ledgerByCreatedAt()
    {
        return $this->hasOne(Ledger::class, 'created_at', 'created_at');
    }

    public function ledgerDetailByCreatedAt()
    {
        return $this->hasOne(LedgerDetail::class, 'created_at', 'created_at');
    }
}
