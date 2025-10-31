<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id('member_id');              // Primary key
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('roll_no');
            $table->string('year');
            $table->string('major');
            $table->text('qr_code')->nullable(); // QR code string path or data
            $table->boolean('is_admin')->default(false);
            $table->date('registration_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
