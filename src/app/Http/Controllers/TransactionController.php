<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Rating;
use App\Mail\TransactionCompletedMail;

class TransactionController extends Controller
{
    /**
     * 取引を完了し、評価を保存
     */
    public function complete(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'partner_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5'
        ]);
        
        $user = Auth::user();
        $item = Item::with('purchases')->findOrFail($request->item_id);
        $partner = User::findOrFail($request->partner_id);
        
        // ユーザーがこの取引に参加しているかチェック
        $userRole = $this->getUserRole($user, $item);
        if (!$userRole) {
            abort(403, 'この取引にアクセスする権限がありません。');
        }
        
        // 既に評価済みかチェック
        $existingRating = Rating::where('rater_id', $user->id)
                                ->where('rated_id', $partner->id)
                                ->where('item_id', $item->id)
                                ->first();
        
        if ($existingRating) {
            return redirect()->back()->with('error', '既に評価済みです。');
        }
        
        DB::transaction(function () use ($request, $user, $item, $partner) {
            // ユーザーの役割を取得
            $userRole = $this->getUserRole($user, $item);
            
            // 評価を保存
            $rating = new Rating();
            $rating->rater_id = $user->id;
            $rating->rated_id = $partner->id;
            $rating->item_id = $item->id;
            $rating->rating = $request->rating;
            $rating->save();
            
            // 評価された人の平均評価を更新
            $this->updateUserRating($partner);
            
            // 購入者が取引を完了した場合、出品者にメール送信
            if ($userRole === 'buyer') {
                // 出品者を取得
                $seller = $item->user;
                
                // 出品者にメール送信
                Mail::to($seller->email)->send(new TransactionCompletedMail($seller, $user, $item));
            }
        });
        
        return redirect()->route('profile', ['tab' => 'trading']);
    }
    
    /**
     * ユーザーの役割を判定
     */
    private function getUserRole($user, $item)
    {
        // 出品者の場合
        if ($item->user_id === $user->id) {
            return 'seller';
        }
        
        // 購入者の場合
        if ($item->purchases && $item->purchases->user_id === $user->id) {
            return 'buyer';
        }
        
        return null;
    }
    
    /**
     * ユーザーの平均評価を更新
     */
    private function updateUserRating($user)
    {
        $averageRating = Rating::where('rated_id', $user->id)->avg('rating');
        
        if ($averageRating) {
            $user->update(['rating' => round($averageRating)]);
        }
    }
}