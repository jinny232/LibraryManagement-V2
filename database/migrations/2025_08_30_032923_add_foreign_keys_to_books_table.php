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
        Schema::table('books', function (Blueprint $table) {
            // Drop the old columns
            $table->dropColumn(['category', 'shelf_number', 'row_number', 'sub_col_number']);

            // Add the new foreign key columns
            $table->foreignId('category_id')->nullable()->after('author')->constrained();
            $table->foreignId('shelf_id')->nullable()->after('isbn')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Drop the new foreign key columns
            $table->dropConstrainedForeignId('category_id');
            $table->dropConstrainedForeignId('shelf_id');

            // Add the old columns back
            $table->string('category')->nullable();
            $table->string('shelf_number')->nullable();
            $table->string('row_number')->nullable();
            $table->string('sub_col_number')->nullable();
        });
    }
};
