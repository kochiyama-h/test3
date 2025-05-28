<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'image',
        'password',
        'postal_code',
        'address',
        'building',
        'rating'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'rating' => 'float'
    ];

    public function likedItems()
    {
        return $this->belongsToMany(Item::class, 'likes','user_id', 'item_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class); 
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function hasLiked($itemId)
    {
        return $this->likes()->where('item_id', $itemId)->exists();
    }

    /**
     * このユーザーが受けた評価
     */
    public function receivedRatings()
    {
        return $this->hasMany(Rating::class, 'rated_id');
    }

    /**
     * このユーザーがした評価
     */
    public function givenRatings()
    {
        return $this->hasMany(Rating::class, 'rater_id');
    }

    /**
     * 評価の平均値を取得（四捨五入）
     */
    public function getAverageRatingAttribute()
    {
        $average = $this->receivedRatings()->avg('rating');
        return $average ? round($average) : null;
    }

    /**
     * 評価数を取得
     */
    public function getRatingCountAttribute()
    {
        return $this->receivedRatings()->count();
    }

    /**
     * 評価があるかどうかを確認
     */
    public function hasRatings()
    {
        return $this->rating_count > 0;
    }

    /**
     * 評価の星を表示用に取得
     */
    public function getRatingStarsAttribute()
    {
        if (!$this->hasRatings()) {
            return null;
        }
        
        $rating = $this->average_rating;
        return str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
    }
}