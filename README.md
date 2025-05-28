# アプリケーション名
模擬案件フリマアプリ

# 環境構築

Docker構築


リポジトリをclone
　git clone　git@github.com:kochiyama-h/test3.git

dockerコンテナを立ち上げる
  docker-compose up -d --build

phpコンテナに移動
　docker-compose exec php bash

依存ライブラリのインストール
  composer install

「.env.example」ファイルを 「.env」ファイルに命名を変更。

.envに以下の環境変数を追加

  DB_CONNECTION=mysql

  DB_HOST=mysql

  DB_PORT=3306

  DB_DATABASE=laravel_db

  DB_USERNAME=laravel_user

  DB_PASSWORD=laravel_pass

Application Keyの作成 
　php artisan key:generate

マイグレーション
 　php artisan migrate

シード作成
 　php artisan db:seed

シンボリックリンクを作成
 　php artisan storage:link

## 使用技術(実行環境)
PHP:7.4.9


Laravel: 8.83.27


MySQL:8.0.26

## ER図
![er図ファイル drawio](https://github.com/user-attachments/assets/64c6f8fe-e505-493d-9e11-972258af5c9b)


## URL
　開発環境：http://localhost
  phpMyAdmin: http://localhost:8080


## ダミーデータ情報

ユーザー１
name aaa
email aaa@aaa
password aaaaaaaa
itemsテーブルのダミーデータ id１～id５を出品


ユーザー2
name bbb
email bbb@bbb
password bbbbbbbb
itemsテーブルのダミーデータid６～id１０を出品

ユーザー3
name ccc
email ccc@ccc
password cccccccc


