# デプロイ手順書

## 1. デプロイ環境の要件
- PHP 8.4.5以上
- Node.js 20.9.0以上
- PostgreSQL 14.17以上
- Nginx 1.20以上
- SSL証明書（本番環境）

## 2. デプロイ手順

### 2.1. サーバー準備
1. システムパッケージの更新
```bash
sudo apt update
sudo apt upgrade -y
```

2. 必要なパッケージのインストール
```bash
sudo apt install -y nginx postgresql postgresql-contrib php8.2-fpm php8.2-pgsql php8.2-xml php8.2-curl php8.2-mbstring php8.2-zip php8.2-gd nodejs npm
```

3. Composerのインストール
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 2.2. アプリケーションのデプロイ
1. アプリケーションディレクトリの作成
```bash
sudo mkdir -p /var/www/employee-import
sudo chown -R $USER:$USER /var/www/employee-import
```

2. アプリケーションのクローン
```bash
cd /var/www/employee-import
git clone [repository-url] .
```

3. 依存パッケージのインストール
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

4. 環境設定
```bash
cp .env.example .env
php artisan key:generate
```

5. データベース設定
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=employee_import
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. データベースのセットアップ
```bash
sudo -u postgres createdb employee_import
php artisan migrate
```

### 2.3. Nginx設定
1. 設定ファイルの作成
```bash
sudo nano /etc/nginx/sites-available/employee-import
```

2. 設定内容
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/employee-import/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

3. シンボリックリンクの作成
```bash
sudo ln -s /etc/nginx/sites-available/employee-import /etc/nginx/sites-enabled/
```

4. Nginxの設定テストと再起動
```bash
sudo nginx -t
sudo systemctl restart nginx
```

### 2.4. SSL設定（本番環境）
1. Certbotのインストール
```bash
sudo apt install -y certbot python3-certbot-nginx
```

2. SSL証明書の取得
```bash
sudo certbot --nginx -d your-domain.com
```

## 3. デプロイ後の確認事項

### 3.1. アプリケーションの動作確認
1. ブラウザでアクセス
   - https://your-domain.com にアクセス
   - 正常に表示されることを確認

2. ファイルアップロード機能の確認
   - CSVファイルのアップロード
   - データのインポート
   - エラーハンドリング

### 3.2. ログの確認
```bash
# Nginxログ
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/nginx/error.log

# Laravelログ
tail -f /var/www/employee-import/storage/logs/laravel.log
```

## 4. メンテナンス手順

### 4.1. アプリケーションの更新
```bash
cd /var/www/employee-import
git pull
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4.2. バックアップ
```bash
# データベースバックアップ
pg_dump employee_import > backup_$(date +%Y%m%d).sql

# アプリケーションファイルのバックアップ
tar -czf app_backup_$(date +%Y%m%d).tar.gz /var/www/employee-import
```
