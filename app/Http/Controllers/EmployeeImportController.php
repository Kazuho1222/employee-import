<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Employee;
use Carbon\Carbon;
use Exception;

class EmployeeImportController extends Controller
{
    public function import(Request $request)
    {
        // ① ファイルのバリデーション
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => '無効なファイル形式です。CSVファイルを選択してください。'], 400);
        }

        // ② CSVを取得
        $file = $request->file('csv_file');
        if (!$file) {
            return response()->json(['error' => 'ファイルが見つかりません。'], 400);
        }
        $path = $file->getRealPath();

        // ③ CSVを配列として取得
        $data = array_map('str_getcsv', file($path));

        // ④ データの整形・保存
        foreach ($data as $row) {
            if (count($row) !== 5) {
                continue; // 現在の行をスキップ
            }

            // 日付の変換を行う
            try {
                $birthday = Carbon::createFromFormat('Y/m/d', $row[3])->format('Y-m-d');
            } catch (Exception $e) {
                continue; // 日付が無効な場合の処理
            }

            // employee_id でレコードを検索
            $employee = Employee::where('employee_id', $row[0])->first();

            if ($employee) {
                // レコードが存在する場合は更新
                $employee->update([
                    'employee_name' => $row[1],
                    'gender' => $row[2],
                    'birthday' => $birthday,
                    'email' => $row[4],
                    'updated_at' => now(), // 更新日時のみ設定
                ]);
            } else {
                // レコードが存在しない場合は新規挿入
                Employee::create([
                    'employee_id' => $row[0],
                    'employee_name' => $row[1],
                    'gender' => $row[2],
                    'birthday' => $birthday,
                    'email' => $row[4],
                    'created_at' => now(), // 新規作成時に作成日時を設定
                    'updated_at' => now(), // 新規作成時に更新日時も設定
                ]);
            }
        }

        return response()->json(['message' => 'CSVデータをインポートしました！'], 200);
    }
}
