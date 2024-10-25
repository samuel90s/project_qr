<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsEditedToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_edited')->default(false); // Menandakan apakah produk sudah di-edit
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_edited'); // Menghapus kolom jika migrasi dibatalkan
        });
    }
}
