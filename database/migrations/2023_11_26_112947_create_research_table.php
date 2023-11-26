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
        Schema::create('research', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique()->default(DB::raw('(UUID())'));

            $table->timestamps();
            $table->unsignedBigInteger('research_category_id')->nullable();
            $table->char('code', 6)->unique();
            $table->string('name', 200);
            $table->text('description');
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ["ongoing", "completed", "delayed", "at risk"]);
            $table->string('doc', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research');
    }
};
