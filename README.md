# アプリケーション名
模擬案件フリマアプリ

# 環境構築

Docker構築


リポジトリをclone
　git clone　git@github.com:kochiyama-h/test3.git

dockerコンテナを立ち上げる
  docker-compose up -d

phpコンテナに移動
　docker-compose exec php bash

依存ライブラリのインストール
  composer install

.env ファイルを作成
  cp .env.example .env

Application Keyの作成
　php artisan key:generate

マイグレーション
　php artisan migrate

（エラーが出る場合は、.envファイルを下記のように編集してください）

<img width="261" alt="image" src="https://github.com/user-attachments/assets/0c77b5ee-1370-4c2f-a3b6-67caae13c8c2" />



シード作成
　php artisan db:seed

シンボリックリンクを作成
　php artisan storage:link


PHPunitテスト

　MySQLログイン後
　 CREATE DATABASE demo_test;

　phpコンテナ上
　　php artisan migrate --env=testing

　テスト実行
　　 ./vendor/bin/phpunit


## 使用技術(実行環境)
PHP:7.4.9


Laravel: 8.83.27


MySQL:8.0.26

## ER図
![er図ファイル drawio](https://github.com/user-attachments/assets/64c6f8fe-e505-493d-9e11-972258af5c9b)


## URL
　開発環境：http://localhost
  phpMyAdmin: http://localhost:8080
