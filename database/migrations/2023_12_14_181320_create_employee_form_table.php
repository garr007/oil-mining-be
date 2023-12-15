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
        Schema::create('employee_form', function (Blueprint $table) {
            $table->id();
            $table->enum('Riwayat_Penyakit_Jantung', ['Iya', 'Tidak']);
            $table->string('Gejala_Penyakit_Jantung', 255);
            $table->enum('Penurunan_Kinerja_Fisik', ['Iya', 'Tidak']);
            $table->enum('Gangguan_Pendengaran', ['Iya', 'Tidak']);
            $table->string('Gejala_Gangguan_Pendengaran', 255);
            $table->enum('Pelindung_Pendengaran', ['Iya', 'Tidak']);
            $table->enum('Kecelakaan_Keamanan', ['Iya', 'Tidak']);
            $table->string('Jelaskan_Ancaman_Keamanan', 255);
            $table->string('Aktivitas_Pekerjaan', 255);
            $table->string('Laporan_Tambahan', 255);
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('personal_id');

            $table->foreign('personal_Id')->references('id')->on('personal_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form');
    }
};
