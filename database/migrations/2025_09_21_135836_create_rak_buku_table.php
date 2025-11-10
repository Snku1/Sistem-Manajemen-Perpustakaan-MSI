<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rak_buku', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // Kode rak, misalnya R01, R02
            $table->string('nama'); // Nama rak, misalnya Rak Novel
            $table->string('lokasi')->nullable(); // Lokasi fisik rak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rak_buku');
    }
};
