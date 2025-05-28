@extends('layouts.app') 
@section('css')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">

@endsection

@section('content')
<div class="chat-container">
    <!-- サイドバー -->
    <div class="sidebar">
        <div class="sidebar-header">
            @if($userRole === 'buyer')
                <h3>その他の取引</h3>
            @else
                <h3>商品一覧</h3>
            @endif
        </div>
        <div class="sidebar-content">
            @foreach($sidebarItems as $sidebarItem)
                <div class="sidebar-item {{ $sidebarItem->id === $item->id ? 'active' : '' }}">
                    <a href="{{ route('chat', $sidebarItem->id) }}">
                        {{ $sidebarItem->name }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- メインチャットエリア -->
    <div class="chat-main">
        <!-- ヘッダー -->
        <div class="chat-header">
            @if($partner)
                <div class="chat-partner">
                    <img src="{{ asset('storage/images/' . ($partner->image ?? 'default-avatar.png')) }}" alt="ユーザー画像" class="partner-avatar">
                    <span class="partner-name">「{{ $partner->name }}」さんとの取引画面</span>
                </div>
                
                @php
                    // 現在のユーザーが既に評価済みかチェック
                    $hasRated = \App\Models\Rating::where('rater_id', Auth::id())
                                                  ->where('rated_id', $partner->id)
                                                  ->where('item_id', $item->id)
                                                  ->exists();
                    
                    // 相手が評価済みかチェック（出品者が評価ボタンを表示する条件）
                    $partnerHasRated = \App\Models\Rating::where('rater_id', $partner->id)
                                                         ->where('rated_id', Auth::id())
                                                         ->where('item_id', $item->id)
                                                         ->exists();
                @endphp
                
                @if(!$hasRated)
                    @if($userRole === 'buyer')
                        <!-- 購入者は常に評価ボタンを表示 -->
                        <button class="complete-btn" onclick="openCompleteModal()">取引を完了する</button>
                    @elseif($userRole === 'seller' && $partnerHasRated)
                        <!-- 出品者は購入者が評価済みの場合のみ評価ボタンを表示 -->
                        <button class="complete-btn" onclick="openCompleteModal()">取引を完了する</button>
                    @endif
                @endif
            @endif
        </div>
        
        <!-- 商品情報 -->
        <div class="item-info">
            <div class="item-image">
                @if (filter_var($item->img, FILTER_VALIDATE_URL))
                    <img src="{{ $item->img }}" alt="商品画像">
                @else
                    <img src="{{ asset('storage/' . $item->img) }}" alt="商品画像">
                @endif
            </div>
            <div class="item-details">
                <h2 class="item-name">{{ $item->name }}</h2>
                <p class="item-price">¥{{ number_format($item->price) }}</p>
            </div>
        </div>
        
        <!-- チャットメッセージエリア -->
        <div class="chat-messages" id="chatMessages">
            @if($partner)
                @foreach($messages as $message)
                    <div class="message {{ $message->sender_id === Auth::id() ? 'sent' : 'received' }}">
                        @if($message->sender_id !== Auth::id())
                            <img src="{{ asset('storage/images/' . ($message->sender->image ?? 'default-avatar.png')) }}" alt="ユーザー画像" class="message-avatar">
                            <div class="message-info">
                                <span class="message-sender">{{ $message->sender->name }}</span>
                                <div class="message-content">
                                    {{ $message->message }}
                                    @if($message->image)
                                        <br>
                                        <img src="{{ asset('storage/' . $message->image) }}" alt="送信画像" class="message-image" onclick="openImageModal('{{ asset('storage/' . $message->image) }}')">
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="message-info">
                                <span class="message-sender">{{ $message->sender->name }}</span>
                                <div class="message-content">
                                    {{ $message->message }}
                                    @if($message->image)
                                        <br>
                                        <img src="{{ asset('storage/' . $message->image) }}" alt="送信画像" class="message-image" onclick="openImageModal('{{ asset('storage/' . $message->image) }}')">
                                    @endif
                                </div>
                                <div class="message-actions">
                                    <span class="edit-btn" data-id="{{ $message->id }}" data-message="{{ $message->message }}">編集</span>
                                    <form action="{{ route('chat.delete', $message->id) }}" method="POST" class="delete-form" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn">削除</button>
                                    </form>
                                </div>
                            </div>
                            <img src="{{ asset('storage/images/' . ($message->sender->image ?? 'default-avatar.png')) }}" alt="ユーザー画像" class="message-avatar">
                        @endif
                    </div>
                @endforeach
            @else
                <div class="no-messages">
                    <p>まだメッセージがありません。最初のメッセージを送信してください。</p>
                </div>
            @endif
        </div>
        
        <!-- メッセージ入力エリア -->
        <div class="message-input-area">
            <form action="{{ route('chat.send') }}" method="POST" enctype="multipart/form-data" class="message-form" novalidate>
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <input type="hidden" name="receiver_id" value="{{ $partner ? $partner->id : '' }}">
                
                <div class="input-container">
                    <input type="text" name="message" placeholder="取引メッセージを記入してください" class="message-input" required value="{{ old('message') }}" id="message-input" data-item-id="{{ $item->id }}">

                    <div class="form__error">
                        @error('message')
                        {{ $message }}
                        @enderror
                    </div>

                    <label for="image-upload" class="image-upload-btn">画像を追加</label>
                    <input type="file" id="image-upload" name="image" accept="image/*" style="display: none;">

                    <div class="form__error">
                        @error('image')
                        {{ $message }}
                        @enderror
                    </div>

                    <button type="submit" class="send-btn" {{ !$partner ? 'disabled' : '' }}>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 21L23 12L2 3V10L17 12L2 14V21Z" fill="currentColor"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 編集モーダル -->
<div id="editModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h3>メッセージを編集</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="editMessageId" name="message_id">
            <textarea id="editMessageText" name="message" rows="4" class="message-edit-textarea" required></textarea>
            <button type="submit" class="submit-edit-btn">更新</button>
            <button type="button" onclick="closeEditModal()">キャンセル</button>
        </form>
    </div>
</div>

@if($partner && !$hasRated && (($userRole === 'buyer') || ($userRole === 'seller' && $partnerHasRated)))
<!-- 取引完了モーダル -->
<div id="completeModal" class="modal">
    <div class="modal-content">
        <h3>取引が完了しました。</h3>
        <p>今回の取引相手はどうでしたか？</p>
        
        <form action="{{ route('transaction.complete') }}" method="POST" id="ratingForm">
            @csrf
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            <input type="hidden" name="partner_id" value="{{ $partner->id }}">
            
            <div class="rating-container">
                @csrf
                <input type="hidden" name="rated_id" value="{{ $partner->id }}">
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <input type="hidden" name="rating" id="rating-input" value="">
                
                <div class="stars">
                    <!-- 星の順序を逆にして配置（5→1の順） -->
                    <span class="star" data-rating="5">★</span>
                    <span class="star" data-rating="4">★</span>
                    <span class="star" data-rating="3">★</span>
                    <span class="star" data-rating="2">★</span>
                    <span class="star" data-rating="1">★</span>
                </div>
                
                <button type="submit" class="submit-rating-btn">送信する</button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
// 編集ボタンクリック時の処理
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', () => {
            const messageId = button.dataset.id;
            const messageText = button.dataset.message;

            // フォームのactionを動的に設定
            const form = document.getElementById('editForm');
            form.action = `/chat/messages/${messageId}/update`;
            
            // 隠しフィールドとテキストエリアに値を設定
            document.getElementById('editMessageId').value = messageId;
            document.getElementById('editMessageText').value = messageText;

            // モーダルを表示
            document.getElementById('editModal').style.display = 'flex';
        });
    });
});

// 編集モーダルを閉じる関数
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// 取引完了モーダル制御
function openCompleteModal() {
    document.getElementById('completeModal').style.display = 'flex';
}

function closeCompleteModal() {
    document.getElementById('completeModal').style.display = 'none';
}

// 星評価システム
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating-input');
    let selectedRating = 0;

    if (stars.length > 0) {
        stars.forEach(star => {
            // クリック時の処理
            star.addEventListener('click', function() {
                selectedRating = parseInt(this.dataset.rating);
                ratingInput.value = selectedRating;
                
                // 選択された評価に応じて星を色付け
                updateStarDisplay();
            });

            // ホバー時の処理
            star.addEventListener('mouseenter', function() {
                const hoverRating = parseInt(this.dataset.rating);
                highlightStars(hoverRating);
            });
        });

        // 星エリアから離れた時の処理
        document.querySelector('.stars').addEventListener('mouseleave', function() {
            updateStarDisplay();
        });

        // 星の表示を更新する関数
        function updateStarDisplay() {
            stars.forEach(star => {
                const starRating = parseInt(star.dataset.rating);
                if (starRating <= selectedRating) {
                    star.classList.add('filled');
                } else {
                    star.classList.remove('filled');
                }
            });
        }

        // ホバー時の星のハイライト
        function highlightStars(rating) {
            stars.forEach(star => {
                const starRating = parseInt(star.dataset.rating);
                if (starRating <= rating) {
                    star.style.color = '#ffd700';
                } else {
                    star.style.color = '#ddd';
                }
            });
        }
    }
});

// モーダル外クリックで閉じる
window.onclick = function(event) {
    const editModal = document.getElementById('editModal');
    const completeModal = document.getElementById('completeModal');
    
    if (event.target === editModal) {
        closeEditModal();
    }
    if (event.target === completeModal) {
        closeCompleteModal();
    }
}

// チャットメッセージを最下部にスクロール
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chatMessages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});

// メッセージ下書き保存機能
document.addEventListener('DOMContentLoaded', function() {
    const messageInput = document.getElementById('message-input');
    if (messageInput) {
        const itemId = messageInput.getAttribute('data-item-id');
        const storageKey = 'chat_draft_' + itemId;
        
        // ページ読み込み時に保存された下書きを復元
        const savedDraft = localStorage.getItem(storageKey);
        if (savedDraft && !messageInput.value) {
            messageInput.value = savedDraft;
        }
        
        // 入力内容をリアルタイムで保存
        messageInput.addEventListener('input', function() {
            if (this.value.trim()) {
                localStorage.setItem(storageKey, this.value);
            } else {
                localStorage.removeItem(storageKey);
            }
        });
        
        // フォーム送信時に下書きを削除
        const form = document.querySelector('.message-form');
        if (form) {
            form.addEventListener('submit', function() {
                localStorage.removeItem(storageKey);
            });
        }
    }
});
</script>
@endsection