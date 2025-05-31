Schema::create('documents', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('language'); // 'ar' or 'fr'
    $table->date('publish_date');
    $table->string('category')->nullable();
    $table->string('filename'); // Original uploaded filename
    $table->json('indexed_data'); // Extracted content from PDF
    $table->timestamps();
});

