<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EmployeeImportTest extends TestCase
{
    use RefreshDatabase;  // テスト後にDBをリセット

    protected function setUp(): void
    {
        parent::setUp();
        // テスト前の準備
    }

    protected function tearDown(): void
    {
        // テスト後のクリーンアップ
        parent::tearDown();
    }

    public function test_can_import_valid_csv()
    {
        // テスト用CSVファイルの作成
        $csv = "employee_id,employee_name,gender,birthday,email\n";
        $csv .= "0001,山田太郎,男性,1990/01/01,yamada@example.com";

        $file = UploadedFile::fake()->createWithContent(
            'test.csv',
            $csv
        );

        // APIリクエストのテスト
        $response = $this->post('/api/import', [
            'csv_file' => $file
        ]);

        // レスポンスの検証
        $response->assertStatus(200)
            ->assertJson(['message' => 'CSVデータをインポートしました！']);

        // DBに保存されたことを確認
        $this->assertDatabaseHas('employees', [
            'employee_id' => '0001',
            'employee_name' => '山田太郎'
        ]);
    }

    public function test_rejects_invalid_file()
    {
        $file = UploadedFile::fake()->create('test.txt', 100);

        $response = $this->post('/api/import', [
            'csv_file' => $file
        ]);

        $response->assertStatus(400);
    }

    public function test_can_update_existing_employee()
    {
        // 既存のデータを作成
        $initialCsv = "employee_id,employee_name,gender,birthday,email\n";
        $initialCsv .= "0001,山田太郎,男性,1990/01/01,yamada@example.com";

        $initialFile = UploadedFile::fake()->createWithContent('initial.csv', $initialCsv);
        $this->post('/api/import', ['csv_file' => $initialFile]);

        // 更新用のデータ
        $updateCsv = "employee_id,employee_name,gender,birthday,email\n";
        $updateCsv .= "0001,山田花子,女性,1991/11/11,yamada.new@example.com";

        $updateFile = UploadedFile::fake()->createWithContent('update.csv', $updateCsv);

        // 更新を実行
        $response = $this->post('/api/import', ['csv_file' => $updateFile]);

        // データベースの検証
        $this->assertDatabaseHas('employees', [
            'employee_id' => '0001',
            'employee_name' => '山田花子',
            'gender' => '女性',
            'birthday' => '1991-11-11',
            'email' => 'yamada.new@example.com'
        ]);

        // レコードが1件のみ存在することを確認（重複がないこと）
        $this->assertEquals(1, \App\Models\Employee::count());
    }

    public function test_handles_invalid_date_format()
    {
        $csv = "employee_id,employee_name,gender,birthday,email\n";
        $csv .= "E001,山田太郎,男性,invalid-date,yamada@example.com";

        $file = UploadedFile::fake()->createWithContent('test.csv', $csv);

        $response = $this->post('/api/import', ['csv_file' => $file]);

        // 無効なデータは保存されていないことを確認
        $this->assertDatabaseMissing('employees', ['employee_id' => '0001']);
    }
}
