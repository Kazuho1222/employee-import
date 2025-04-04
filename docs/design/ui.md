# UI設計書

## 1. 画面構成

### CSVインポート画面
- タイトル: "従業員データインポート"
- 説明文: "CSVファイルを選択して従業員データをインポートしてください。"

#### コンポーネント
1. ファイル選択エリア
   - ドラッグ&ドロップ対応
   - クリックでファイル選択
   - 選択可能なファイル形式: CSV
   - 最大ファイルサイズ: 2MB

2. 選択ファイル表示
   - ファイル名表示
   - クリアボタン
   - ファイル選択状態に応じた表示/非表示

3. インポートボタン
   - ファイル未選択時: 無効化（青）
   - ファイル選択時: 有効化（青）
   - インポート完了時: 無効化（青）

4. メッセージ表示エリア
   - 成功メッセージ: 緑色
   - エラーメッセージ: 赤色

## 2. インタラクション

### ファイル選択時
1. ファイル選択ダイアログ表示
2. 選択後、ファイル名表示
3. インポートボタン有効化

### インポート実行時
1. インポートボタン無効化
2. 処理中表示
3. 完了後、成功メッセージ表示

### エラー発生時
1. エラーメッセージ表示
2. インポートボタン有効化
3. ファイル選択状態維持

## 3. スタイル

### カラーパレット
- プライマリー: #3B82F6（青）
- 成功: #22C55E（緑）
- エラー: #EF4444（赤）

### フォント
- 日本語: Noto Sans JP
- 英数字: Inter

### レイアウト
- 最大幅: 896px
- 中央寄せ
- レスポンシブ対応
