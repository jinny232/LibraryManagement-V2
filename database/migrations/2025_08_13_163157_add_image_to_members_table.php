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
        Schema::table('members', function (Blueprint $table) {
            // Adds a new 'image' column to the 'members' table.
            // It is nullable, meaning a member does not have to have an image.
            // We've set it to be a string to store the file path.
            $table->string('image')->nullable()->after('roll_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // Drops the 'image' column from the 'members' table.
            // This is for rolling back the migration if needed.
            $table->dropColumn('image');
        });
    }
};

