## テスト概要

### テスト対象
- CSVインポート機能
  - ファイルアップロード
  - データ検証
  - DB保存処理

### テスト実行方法
```bash
# テスト用データベースの作成
php artisan db:create --env=testing

# マイグレーションの実行
php artisan migrate --env=testing

# ローカルでのテスト実行
php artisan test

# 特定のテストのみ実行
php artisan test --filter=EmployeeImportTest
```

### 既知の課題
- [ ] 大容量ファイルのテスト
- [ ] 文字コード関連のテスト
