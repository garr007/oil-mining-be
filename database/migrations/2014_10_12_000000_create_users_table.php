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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique()->default(DB::raw('(UUID())'));
            $table->boolean('is_admin')->default(false);

            $table->string('first_name', 30);
            $table->string('last_name', 30)->nullable();
            $table->string('email', 255)->unique();
            $table->string('password', 255);

            $table->timestamps();

            // emp details
            $table->string('religion', 20)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('social_number', 16)->nullable();
            $table->string('img', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
