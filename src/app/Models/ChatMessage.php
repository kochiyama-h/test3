<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'image'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * メッセージが属する商品
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * メッセージの送信者
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * メッセージの受信者
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}