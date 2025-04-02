# 開発環境セットアップ手順書

## 1. 必要条件
- PHP 8.4.5以上
- Node.js 20.9.0以上
- PostgreSQL 14.17以上
- Composer 2.2.6以上
- npm 10.1.0以上

## 2. 環境構築手順

### 2.1. リポジトリのクローン
```bash
git clone [repository-url]
cd employee-import
```

### 2.2. 依存パッケージのインストール
```bash
# PHP依存パッケージのインストール
composer install

# Node.js依存パッケージのインストール
npm install
```

### 2.3. 環境設定
1. `.env`ファイルの作成
```bash
cp .env.example .env
```
2. `.env` ファイルを開き、必要な環境変数を設定します。

3. アプリケーションキーの生成
```bash
php artisan key:generate
```

4. データベース設定
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=employee_import
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 2.4. データベースのセットアップ
```bash
# データベースの作成
createdb employee_import

# マイグレーションの実行
php artisan migrate
```

### 2.5. 開発サーバーの起動
```bash
# バックエンドサーバー
php artisan serve

# フロントエンド開発サーバー
npm run dev
```

## 3. テスト環境のセットアップ

### 3.1. テスト用データベースの作成
```bash
createdb employee_import_test
```

### 3.2. テスト用環境設定
1. `.env.testing`ファイルの作成
```bash
cp .env.example .env.testing
```

2. テスト用データベース設定
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=employee_import_test
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3.3. テストの実行
```bash
# PHPUnitテスト
php artisan test

# Vue.jsテスト
npm run test
```

## 4. 開発ツールのセットアップ

### 4.1. VS Code設定
1. 推奨拡張機能のインストール
   - PHP Intelephense
   - Vue Language Features
   - Tailwind CSS IntelliSense
   - ESLint
   - Prettier

2. 設定ファイルの配置
```bash
cp .vscode/settings.example.json .vscode/settings.json
```

### 4.2. Git設定
```bash
# Git Hooksのセットアップ
composer run-script post-root-package-install
```

## 5. トラブルシューティング

### 5.1. よくある問題と解決方法
1. データベース接続エラー
   - PostgreSQLサービスが起動しているか確認
   - データベース名、ユーザー名、パスワードが正しいか確認

2. 依存パッケージのインストールエラー
   - Composerのキャッシュクリア: `composer clear-cache`
   - npmのキャッシュクリア: `npm cache clean --force`

3. テスト実行エラー
   - テスト用データベースが存在するか確認
   - マイグレーションが最新か確認: `php artisan migrate:fresh --env=testing`
