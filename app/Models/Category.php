<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'name';
    protected $keyType = 'string';

    protected $fillable = ['name', 'type', 'user_id'];

    public function chartOfAccounts() {
        return $this->hasMany(ChartOfAccount::class, 'category_name');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
