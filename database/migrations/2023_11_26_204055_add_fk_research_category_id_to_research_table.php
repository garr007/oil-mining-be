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
        Schema::table('research', function (Blueprint $table) {
            $table->foreign('research_category_id')->references('id')->on('research_categories')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('research', function (Blueprint $table) {
            $table->dropForeign(['research_category_id']);
        });
    }
};
