<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    public function bank_relation(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank', 'id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getType()
    {
        if ($this->type == '2') {
            return 'Partial';
        }

        if ($this->type == '1') {
            return 'Paid';
        }

        if ($this->type == '3') {
            return 'Return';
        }

        if ($this->type == '4') {
            //vendor add cash
            return '';
        }

        return 'Unpaid';
    }
    public function getColor()
    {
        if ($this->type == '2') {
            return 'blue';
        }

        if ($this->type == '1') {
            return 'black';
        }

        if ($this->type == '3') {
            return 'red';
        }

        if ($this->type == '4') {
            //vendor add cash
            return 'black';
        }
        if ($this->type == '0') {
            //vendor add cash
            return 'green';
        }

        return 'black';
    }

    public function ledger()
    {
        return $this->belongsTo(Ledger::class, 'created_at', 'created_at');
    }
}
