<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LedgerDetail extends Model
{
    use HasFactory;

    protected $table = "ledgerdetails";

    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }
}
