# アプリケーション名

coachtech フリマ

## 環境構築

### Docker ビルド

- git clone git@github.com:Shuta0105/coachtech-freemarket.git
- docker-compose up -d --build

### Laravel 環境構築

- docker-compose exec php bash
- composer install
- cp .env.example .env
- php artisan key:generate
- cp .env.example .env.testing
- php artisan key:generate --env=testing

以下、「env ファイルの編集」実行後

- php artisan config:clear
- php artisan migrate --env=testing
- php artisan migrate --seed
- php artisan storage:link
- php artisan queue:work（起動したまま）

### env ファイルの編集

#### .env の変更箇所

- DB_DATABASE=laravel_db
- DB_USERNAME=laravel_user
- DB_PASSOWRD=laravel_pass

#### .env.testing の変更箇所

- APP_ENV=test
- DB_HOST=mysql_testing
- DB_DATABASE=demo_test
- DB_USERNAME=laravel_user
- DB_PASSOWRD=laravel_pass

#### .env.testing 変更後

- cp .env.testing .env.dusk.local

#### .env.dusk.local 変更箇所

- APP_URL=http://nginx

「Laravel 環境構築」へ戻る

## 外部サービス

- Stripe
- Mailhog

### Stripe

本環境は Stripe で決済を行っています。Stripe の APIKey は各自で.env ファイルに設定してください。

- STRIPE_KEY
- STRIPE_SECRET

### Mailhog

- http://localhost:8025/
- 初回登録時と初回ログイン時にメール認証必須
- 出品後に出品者に対してメール通知
- 購入後に購入者と出品者に対してメール通知

## ユーザー情報

- ダミーユーザーのパスワードは全て「password」

## テスト実行方法

tests/Feature ディレクトリのファイルに対して

- vendor/bin/phpunit tests/Feature/xxx.php

tests/Browser ディレクトリのファイルに対して

- php artisan dusk tests/Browser/xxx.php

## 開発環境

- トップ画面：http://localhost/
- ユーザー登録：http://localhost/register
- phpMyAdmin：http://localhost:8080/
- mailhog：http://localhost:8025/

## 使用技術（実行環境）

- PHP 8.1-fpm
- Laravel 8.83.29
- MySQL 8.0.26
- nginx 1.21.1

## ER 図

![ER図](./docs/er.drawio.png)
