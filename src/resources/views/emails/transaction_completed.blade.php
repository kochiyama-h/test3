<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>取引完了のお知らせ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }
        .item-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .price {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>取引完了のお知らせ</h1>
    </div>
    
    <div class="content">
        <p>{{ $sellerName }}様</p>
        
        <p>いつもご利用いただきありがとうございます。</p>
        
        <p>以下の商品の取引が完了いたしました。</p>
        
        <div class="item-info">
            <h3>商品情報</h3>
            <p><strong>商品名：</strong>{{ $itemName }}</p>
            <p><strong>販売価格：</strong><span class="price">¥{{ number_format($itemPrice) }}</span></p>
            <p><strong>購入者：</strong>{{ $buyerName }}様</p>
        </div>
        
        <p>購入者様から取引完了の評価をいただきました。</p>
        
        <p>今後ともよろしくお願いいたします。</p>
    </div>
    
    <div class="footer">
        <p>このメールは自動送信されています。</p>
        <p>ご不明な点がございましたら、サポートまでお問い合わせください。</p>
    </div>
</body>
</html>