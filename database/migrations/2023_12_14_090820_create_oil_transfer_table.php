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
        Schema::create('oil_transfer', function (Blueprint $table) {
            $table->id();
            $table->dateTime('Tanggal_Pindah');
            $table->string('Lokasi_Pindah', 255);
            $table->string('Lokasi_Tujuan', 255);
            $table->text('Keterangan');
            $table->timestamps();
            $table->unsignedBigInteger('oil_assets_id');
            $table->softDeletes();
            $table->foreign('oil_assets_id')->references('id')->on('oil_assets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oil_transfer');
    }
};
