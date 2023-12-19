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
        Schema::create('oil_task', function (Blueprint $table) {
            $table->id();
            $table->string('Judul', 45);
            $table->text('Isi');
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
        Schema::dropIfExists('oil_task');
    }
};
