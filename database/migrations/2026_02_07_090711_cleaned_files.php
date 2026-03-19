<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cleaned_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('original_name');
            $table->string('clean_name');
            $table->string('file_type')->nullable();
            $table->unsignedBigInteger('original_size')->default(0);
            $table->unsignedBigInteger('clean_size')->default(0);
            $table->json('metadata_removed')->nullable();
            $table->boolean('converted')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cleaned_files');
    }
};