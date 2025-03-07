<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    protected $primaryKey = 'code';

    public function category() {
        return $this->belongsTo(Category::class, 'category_name');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'coa_code', 'code');
    }
}
