# API設計書

## 1. エンドポイント一覧

### CSVインポート
- **URL**: `/api/import`
- **Method**: POST
- **Content-Type**: multipart/form-data

#### リクエスト
| パラメーター名 | 型     | 必須 | 説明 |
|------------|--------|------|------|
| file      | File   | Yes  | CSVファイル |

#### レスポンス
##### 成功時 (200 OK)
```json
{
    "message": "CSVデータをインポートしました！",
    "success": true
}
```

##### エラー時 (400 Bad Request)
```json
{
    "message": "エラーメッセージ",
    "success": false
}
```

## 2. バリデーションルール

### CSVファイル
- 拡張子: .csv
- 文字コード: UTF-8
- 最大サイズ: 2MB

### データ項目
- employee_id: 整数値、必須、ユニーク
- employee_name: 文字列、必須
- gender: 必須
- birthday: YYYY-MM-DD形式、必須
- email: メールアドレス形式、必須

## 3. エラーハンドリング
- 400: バリデーションエラー
- 413: ファイルサイズ超過
- 415: 不正なファイル形式
- 500: サーバーエラー
