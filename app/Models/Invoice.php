<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public function sub_invoices()
    {
        return $this->hasMany(Subinvoice::class, 'invoice_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Vendor::class, 'customer_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank', 'id');
    }
}
