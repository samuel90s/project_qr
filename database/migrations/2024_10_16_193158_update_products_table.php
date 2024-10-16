<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsTable extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Pastikan kolom ini belum ada sebelum menambahkannya
            if (!Schema::hasColumn('products', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('code_manufactur');
            }
            if (!Schema::hasColumn('products', 'edited_by')) {
                $table->unsignedBigInteger('edited_by')->nullable()->after('created_by');
            }
            // Hapus 'edited_at' jika tidak dibutuhkan
            // Jika Anda ingin tetap menambahkannya, uncomment baris berikut:
            // if (!Schema::hasColumn('products', 'edited_at')) {
            //     $table->timestamp('edited_at')->nullable()->after('edited_by');
            // }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Menghapus kolom yang ditambahkan saat rollback
            $table->dropColumn('created_by');
            $table->dropColumn('edited_by');
            // Uncomment baris berikut jika ingin menghapus 'edited_at' saat rollback
            // $table->dropColumn('edited_at');
        });
    }
}
