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
        Schema::create('batiks', function (Blueprint $table) {
        
    $table->id();

    $table->bigInteger('api_id')->unique();

    $table->string('nama')->nullable();

    $table->text('keyword')->nullable();

    $table->text('deskripsi')->nullable();

    $table->string('kategori')->nullable();

    $table->string('warna')->nullable();

    $table->string('preview_image')->nullable();

    $table->json('costume_images')->nullable();

    $table->string('video')->nullable();

    $table->unsignedBigInteger('seed')->nullable();

    $table->timestamp('api_created_at')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batiks');
    }
    
};
