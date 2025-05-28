<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\ChatMessage;
use App\Models\User;
use App\Http\Requests\ChatRequest;

class ChatController extends Controller
{
    /**
     * チャット画面を表示
     */
    public function show($itemId)
    {
        $user = Auth::user();
        $item = Item::with(['user', 'purchases.user'])->findOrFail($itemId);
        
        // ユーザーの役割を判定（出品者 or 購入者）
        $userRole = $this->getUserRole($user, $item);
        
        if (!$userRole) {
            abort(403, 'この取引にアクセスする権限がありません。');
        }
        
        // 取引相手を取得
        $partner = $this->getPartner($user, $item, $userRole);
        
        // サイドバーに表示するアイテムを取得
        $sidebarItems = $this->getSidebarItems($user, $userRole);
        
        // チャットメッセージを取得
        $messages = collect();
        if ($partner) {
            $messages = ChatMessage::where('item_id', $itemId)
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get();
            
            // 未読メッセージを既読にする
            ChatMessage::where('item_id', $itemId)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }
        
        return view('chat', compact('item', 'partner', 'userRole', 'sidebarItems', 'messages'));
    }
    
    /**
     * メッセージを送信
     */
    public function send(ChatRequest $request)
{
    $user = Auth::user();
    $item = Item::with(['user', 'purchases.user'])->findOrFail($request->item_id);
    
    // ユーザーがこの取引に参加しているかチェック
    $userRole = $this->getUserRole($user, $item);
    if (!$userRole) {
        abort(403, 'この取引にアクセスする権限がありません。');
    }
    
    // 取引相手が存在するかチェック
    $partner = $this->getPartner($user, $item, $userRole);
    if (!$partner) {
        return redirect()->route('chat', $request->item_id)
            ->with('error', '取引相手が見つからないため、メッセージを送信できません。');
    }
    
    // メッセージを保存
    $chatMessage = new ChatMessage();
    $chatMessage->item_id = $request->item_id;
    $chatMessage->sender_id = $user->id;
    $chatMessage->receiver_id = $request->receiver_id;
    $chatMessage->message = $request->message;
    $chatMessage->is_read = false;
    
    // 画像がアップロードされた場合の処理
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('chat_images', 'public');
        $chatMessage->image = $imagePath;
    }
    
    $chatMessage->save();
    
    return redirect()->route('chat', $request->item_id)->with('success', 'メッセージを送信しました。');
}
    
    /**
     * ユーザーの役割を判定（出品者 or 購入者）
     */
    private function getUserRole($user, $item)
    {
        // 出品者の場合
        if ($item->user_id === $user->id) {
            return 'seller';
        }
        
        // 購入者の場合（購入データが存在する場合）
        if ($item->purchases && $item->purchases->user_id === $user->id) {
            return 'buyer';
        }
        
        return null;
    }
    
    /**
     * 取引相手を取得
     */
    private function getPartner($user, $item, $userRole)
    {
        if ($userRole === 'seller') {
            // 出品者の場合、購入者を取得
            return $item->purchases ? $item->purchases->user : null;
        } else {
            // 購入者の場合、出品者を取得
            return $item->user;
        }
    }
    
    /**
     * サイドバーに表示するアイテムを取得（取引中の商品のみ）
     */
    private function getSidebarItems($user, $userRole)
    {
        if ($userRole === 'seller') {
            // 出品者の場合：出品した商品で購入者がいるもの（取引中）
            return Item::where('user_id', $user->id)
                ->whereHas('purchases', function($query) {
                    $query->where('status', '!=', 'completed'); // 取引完了していないもの
                })
                ->get();
        } else {
            // 購入者の場合：購入した商品で取引中のもの
            return Item::whereHas('purchases', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('status', '!=', 'completed'); // 取引完了していないもの
            })->get();
        }
    }

    public function update(Request $request, $id)
    {
        $message = ChatMessage::findOrFail($id);

        // 自分のメッセージ以外は編集できない
        if ($message->sender_id !== auth()->id()) {
            abort(403, '無許可の操作です');
        }

        $message->message = $request->message;
        $message->save();

       return redirect()->back();
    }

    public function destroy($id)
    {
        $message = ChatMessage::findOrFail($id);

        if ($message->sender_id !== auth()->id()) {
            abort(403, '無許可の操作です');
        }

        $message->delete();

        return redirect()->back(); 
    }
}