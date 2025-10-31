<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('category')->nullable();
            $table->string('isbn')->unique();
            $table->integer('total_copies');
            $table->integer('available_copies');
            $table->string('shelf_number', 20);
            $table->string('row_number', 20);
            $table->string('sub_col_number', 20);
            $table->longText('barcode')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
