<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengaturan_denda', function (Blueprint $table) {
            $table->id();
            $table->decimal('denda_per_hari', 10, 2)->default(10000);
            $table->integer('masa_tenggang')->default(3); // dalam hari
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // Insert default data
        DB::table('pengaturan_denda')->insert([
            'denda_per_hari' => 10000,
            'masa_tenggang' => 3,
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('pengaturan_denda');
    }
};