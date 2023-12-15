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
        Schema::create('oil_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('Jenis_Minyak', 45);
            $table->decimal('Jumlah', 10, 2);
            $table->dateTime('Tanggal_Masuk');
            $table->dateTime('Tanggal_Keluar');
            $table->string('Lokasi_Penyimpanan', 100);
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
