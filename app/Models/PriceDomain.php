<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceDomain extends Model
{
    use HasFactory;
    protected $table = 'priceDomain';
    protected $guarded = [];
    public $timestamps = true;
}
