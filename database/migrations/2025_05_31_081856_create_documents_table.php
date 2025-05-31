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
      Schema::create('documents', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('language'); // 'ar' or 'fr'
    $table->date('publish_date');
    $table->string('category')->nullable();
    $table->string('filename'); // Original uploaded filename
    $table->json('indexed_data'); // Extracted content from PDF
    $table->timestamps();
    $table->date('date')->nullable();
    $table->string('file_path');
    $table->text('summary')->nullable(); // ← Add this
    $table->text('content');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
