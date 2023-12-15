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
        Schema::create('personal_data', function (Blueprint $table) {
            $table->id();
            $table->string('Lokasi_Pekerjaan', 255);
            $table->string('Kontak_Darurat', 255);
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('employee_id');

            $table->foreign('employee_id')->references('id')->on('employee_statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_data');
    }
};
