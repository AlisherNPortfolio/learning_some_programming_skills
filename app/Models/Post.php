<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body'];

    protected $casts = [
        'body' => 'array'
    ];

    //    protected $appends = [
    //        'title_upper_case'
    //    ];

    // public function getTitleUpperCaseAttribute()
    // {
    //     return strtoupper($this->title);
    // }

    // public function setTitleAttribute($value)
    // {
    //     $this->attributes['title'] = strtolower($value);
    // }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_post_pivot', 'post_id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }
}
