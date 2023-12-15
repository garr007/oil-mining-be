<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('oil_assets', function (Blueprint $table) {
            $table->id();
            $table->string('Nama_Aset', 255);
            $table->string('Jenis_Aset', 50);
            $table->string('Status_Aset', 20);
            $table->string('Riwayat Status');
            $table->text('Keterangan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oil_assets');
    }
};
