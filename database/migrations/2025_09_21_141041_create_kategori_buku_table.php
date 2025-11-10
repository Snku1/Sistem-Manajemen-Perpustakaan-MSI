<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('pengarang');
            $table->string('penerbit')->nullable();
            $table->year('tahun_terbit')->nullable();
            $table->string('isbn')->nullable();
            $table->unsignedBigInteger('kategori_id');
            $table->unsignedBigInteger('rak_id');
            $table->integer('stok')->default(0);
            $table->timestamps();

            $table->foreign('kategori_id')->references('id')->on('kategori_buku')->onDelete('cascade');
            $table->foreign('rak_id')->references('id')->on('rak_buku')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
