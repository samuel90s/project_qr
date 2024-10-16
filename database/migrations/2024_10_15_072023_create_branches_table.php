<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id(); // ID cabang
            $table->string('name'); // Nama cabang
            $table->string('location')->nullable(); // Lokasi cabang (opsional)
            $table->string('contact_number')->nullable(); // Nomor kontak (opsional)
            $table->timestamps(); // Kolom timestamps untuk created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('branches'); // Hapus tabel jika migration di-reverse
    }
}
