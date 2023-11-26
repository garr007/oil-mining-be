<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_certs', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique()->default(DB::raw('(UUID())'));

            $table->unsignedBigInteger('employee_id');
            $table->string('code', 20)->unique();
            $table->date('date');
            $table->date('exp_date')->nullable();
            $table->string('type', 50);
            $table->string('cert', 255)->comment('file name');

            $table->foreign('employee_id')->references('user_id')->on('employees')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_certs');
    }
};
