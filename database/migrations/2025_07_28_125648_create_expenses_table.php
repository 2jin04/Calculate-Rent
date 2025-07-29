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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('created_by');
            $table->string('title');
            $table->string('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('paid_by_user_id')->nullable();
            $table->json('members_share')->nullable();
            $table->string('image_path')->nullable(); // ảnh hóa đơn
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('paid_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
