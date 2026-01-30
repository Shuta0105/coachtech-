<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'item_id',
        'paymethod',
        'post_code',
        'address',
        'building',
        'status',
    ];
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
