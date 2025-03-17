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
        Schema::table('incoming_letters', function (Blueprint $table) {
            $table->foreignId('related_outgoing_letter_id')->nullable()->after('receiver_user_id')
                ->constrained('outgoing_letters')->onDelete('set null');
            $table->string('status')->default('received')->after('related_outgoing_letter_id');
            $table->text('approval_notes')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incoming_letters', function (Blueprint $table) {
            $table->dropForeign(['related_outgoing_letter_id']);
            $table->dropColumn(['related_outgoing_letter_id', 'status', 'approval_notes']);
        });
    }
};
