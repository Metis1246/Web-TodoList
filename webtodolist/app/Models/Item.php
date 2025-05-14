<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
        'name',
        'description',
        'image_url',
        'status'
    ];

    protected $attributes = [
        'status' => 'กำลังดำเนินการ'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
}
