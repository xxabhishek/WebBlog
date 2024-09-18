<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'content', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function scopeSearch($query, $term)
    {
        return $query->where('title', 'LIKE', "%{$term}%")
            ->orWhere('content', 'LIKE', "%{$term}%");
    }
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,  // Generate fake title
            'content' => $this->faker->paragraph,  // Generate fake content
        ];
    }
}
