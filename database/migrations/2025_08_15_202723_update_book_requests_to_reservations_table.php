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
        // First, rename the table
        Schema::rename('book_requests', 'reservations');

        // Then, modify the columns
        Schema::table('reservations', function (Blueprint $table) {
            // Remove old columns
            $table->dropColumn(['requester_name', 'requester_email', 'requester_phone']);

            // Add new, more structured columns
            $table->foreignId('member_id')->constrained('members', 'member_id')->after('book_id');
            $table->string('status')->default('pending')->after('member_id');
            $table->timestamp('reservation_date')->nullable()->after('status');
            $table->timestamp('expiration_date')->nullable()->after('reservation_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Add old columns back
            $table->string('requester_name');
            $table->string('requester_email');
            $table->string('requester_phone')->nullable();

            // Drop new columns
            $table->dropForeign(['member_id']);
            $table->dropColumn(['member_id', 'status', 'reservation_date', 'expiration_date']);
        });

        // Finally, rename the table back
        Schema::rename('reservations', 'book_requests');
    }
};
