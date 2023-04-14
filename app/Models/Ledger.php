<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function ledger_details()
    {
        return $this->hasMany(LedgerDetail::class);
    }
}
