<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'rater_id',
        'rated_id',
        'rater_type',
        'rating',
        'comment',
    ];

    /**
     * 評価する人（評価者）
     */
    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_id');
    }

    /**
     * 評価される人（被評価者）
     */
    public function rated()
    {
        return $this->belongsTo(User::class, 'rated_id');
    }

    /**
     * 取引
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * 指定ユーザーの評価平均を取得（四捨五入）
     */
    public static function getAverageRating($userId)
    {
        $average = self::where('rated_id', $userId)->avg('rating');
        
        if ($average === null) {
            return null; // 評価がない場合はnullを返す
        }
        
        return round($average); // 四捨五入
    }

    /**
     * 指定ユーザーの評価数を取得
     */
    public static function getRatingCount($userId)
    {
        return self::where('rated_id', $userId)->count();
    }
}