# アプリケーション名
模擬案件フリマアプリ

# 環境構築
Docker構築
　リポジトリをclone
　git clone　git@github.com:kochiyama-h/test3.git

dockerコンテナを立ち上げる
  docker-compose up -d

Application Keyの作成
　docker-compose exec app php artisan key:generate

マイグレーション
　docker-compose exec app php artisan migrate

シード作成
　docker-compose exec app php artisan db:seed


## 使用技術(実行環境)
-PHP: 8.0.2
Laravel: 9.19
MySQL8.0.32

## ER図
![er図ファイル drawio](https://github.com/user-attachments/assets/64c6f8fe-e505-493d-9e11-972258af5c9b)


## URL
　開発環境：http://localhost
  phpMyAdmin: http://localhost:8080
