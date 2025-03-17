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
        Schema::create('incoming_letters', function (Blueprint $table) {
            $table->id();
            $table->string('letter_number');
            $table->date('letter_date');
            $table->date('received_date');
            $table->string('sender');
            $table->string('subject');
            $table->text('description');
            $table->string('attachment')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('sender_village_id')->constrained('villages')->onDelete('cascade');
            $table->foreignId('receiver_village_id')->constrained('villages')->onDelete('cascade');
            $table->foreignId('receiver_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('classification_id')->constrained('letter_classifications')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('village_id')->constrained('villages')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_letters');
    }
};
