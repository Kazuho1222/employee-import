# データベース設計書

## 1. テーブル定義

### employees テーブル
| カラム名        | 型         | NULL | デフォルト | 説明 |
|---------------|-----------|------|-----------|------|
| id            | bigint    | NO   | -         | 主キー |
| employee_id   | integer   | NO   | -         | 社員番号（ユニーク） |
| employee_name | text      | NO   | -         | 従業員名 |
| gender        | text      | NO   | -         | 性別 |
| birthday      | date      | NO   | -         | 生年月日 |
| email         | text      | NO   | -         | メールアドレス |
| created_at    | timestamp | NO   | CURRENT_TIMESTAMP | 作成日時 |
| updated_at    | timestamp | NO   | CURRENT_TIMESTAMP | 更新日時 |

## 2. インデックス
- PRIMARY KEY (id)
- UNIQUE INDEX (employee_id)

## 3. 制約条件
- employee_id: NOT NULL, UNIQUE
- employee_name: NOT NULL
- gender: NOT NULL, CHECK (gender IN ('M', 'F'))
- birthday: NOT NULL
- email: NOT NULL, CHECK (email ~* '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$')
