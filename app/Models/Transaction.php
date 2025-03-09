<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $fillable = ['coa_code', 'description', 'debit', 'credit', 'user_id', 'date'];
    public function chartOfAccount() {
        return $this->belongsTo(ChartOfAccount::class, 'coa_code');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
