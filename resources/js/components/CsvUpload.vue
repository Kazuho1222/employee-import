<template>
    <div class="m-4 flex flex-col items-center">
        <h2 class="text-lg font-semibold">CSV インポート</h2>

        <!-- ドラッグ＆ドロップエリア -->
        <div
            class="m-4 flex h-52 min-h-32 w-full max-w-4xl cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-100 text-center hover:bg-gray-200"
            @click="selectFile"
            @dragover.prevent="handleDragOver"
            @dragenter.prevent="drag = true"
            @dragleave.prevent="drag = false"
            @drop.prevent="handleDrop"
        >
            <p v-if="!drag">ここにCSVファイルをドラッグ＆ドロップするか、参照して選択します。</p>
            <p v-else>ドロップ中</p>
            <p v-if="file" class="font-bold text-blue-600">
                {{ file.name }}
                <button class="mt-4 rounded-lg bg-slate-500 px-4 py-2 text-white hover:bg-slate-600" @click.stop="clearFile">クリア</button>
            </p>

            <input type="file" class="hidden" ref="fileInput" @change="handleFileUpload" accept=".csv" />
        </div>
        <!-- ステータスメッセージ -->
        <p v-if="message" class="mt-2 text-sm" :class="{ 'text-green-600': success, 'text-red-600': !success }">
            {{ message }}
        </p>

        <!-- インポートボタン -->
        <div>
            <button
                class="mt-2 rounded-lg bg-blue-500 px-4 py-2 text-white hover:bg-blue-600 disabled:opacity-50"
                :class="{
                    'cursor-pointer': file && !success,
                    'cursor-not-allowed': !file || success,
                }"
                :disabled="!file || success"
                @click="uploadCsv"
            >
                インポート
            </button>
        </div>
    </div>
</template>

<script set lang="ts">
import axios from 'axios';

export default {
    data() {
        return {
            file: null as File | null,
            message: '',
            success: false,
            drag: false,
            isLoading: false,
        };
    },
    methods: {
        // ドラッグオーバーイベントの処理
        handleDragOver(event: DragEvent) {
            event.preventDefault(); // デフォルトの動作を防ぐ
            this.drag = true; // ドラッグ中の状態を設定
        },
        // ドラッグ＆ドロップでファイルを取得
        handleDrop(event: DragEvent): void {
            event.preventDefault();
            this.drag = false;
            this.success = false;

            if (!event) {
                return;
            }

            if (!event.dataTransfer) {
                return;
            }

            if (event.dataTransfer.files.length === 0) {
                return;
            }

            this.file = event.dataTransfer.files[0] as File;
        },
        // ファイル選択ボタン
        selectFile() {
            (this.$refs.fileInput as HTMLInputElement).click();
        },
        // input[type=file] でファイルを取得
        handleFileUpload(event: Event): void {
            const target = event.target as HTMLInputElement;
            if (target?.files?.[0]) {
                const file = target.files[0];
                if (!file.name.endsWith('.csv')) {
                    this.message = 'CSVファイルを選択してください';
                    this.success = false;
                    return;
                }
                this.file = file;
                this.success = false;
                this.message = '';
            }
        },

        // クリアファイルメソッド
        clearFile() {
            this.file = null;
            this.message = '';
            (this.$refs.fileInput as HTMLInputElement).value = '';
        },
        // CSVファイルをアップロード
        async uploadCsv() {
            if (!this.file) {
                this.message = 'ファイルを選択してください';
                this.success = false;
                return;
            }

            const formData = new FormData();
            formData.append('csv_file', this.file); // APIで受け取るフィールド名を "csv_file" に合わせる

            this.isLoading = true; // ローディング開始
            try {
                const response = await axios.post('http://127.0.0.1:8000/api/import', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' },
                });
                this.message = response.data.message;
                this.success = true;
            } catch (error: any) {
                this.message = error.response?.data?.error || 'インポートに失敗しました';
                this.success = false;
            } finally {
                this.isLoading = false; // ローディング終了
            }
        },
    },
};
</script>
