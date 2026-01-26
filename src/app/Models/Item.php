<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'price', 'brand', 'detail', 'user_id', 'condition_id', 'img'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }
    public function order()
    {
        return $this->hasOne(Order::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function likedBy(?User $user)
    {
        if (!$user) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'item_categories',
            'item_id',
            'category_id'
        );
    }
}
