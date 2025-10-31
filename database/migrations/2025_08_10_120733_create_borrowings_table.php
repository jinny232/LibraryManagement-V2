<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id('borrow_id');
$table->unsignedBigInteger('member_id');
$table->foreign('member_id')->references('member_id')->on('members')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->date('borrow_date')->nullable();
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['pending','borrowed', 'returned', 'late'])->default('borrowed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
