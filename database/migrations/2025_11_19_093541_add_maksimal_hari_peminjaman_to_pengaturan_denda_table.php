<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pengaturan_denda', function (Blueprint $table) {
            $table->integer('maksimal_hari_peminjaman')->default(7)->after('masa_tenggang');
            // Ganti nama kolom batas_waktu_peminjaman menjadi masa_tenggang untuk konsistensi
        });
    }

    public function down()
    {
        Schema::table('pengaturan_denda', function (Blueprint $table) {
            $table->dropColumn('masa_tenggang');
        });
    }
};