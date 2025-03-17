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
            $table->foreignId('sekdes_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('kades_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('sekdes_approved_at')->nullable();
            $table->timestamp('kades_approved_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->string('rejection_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incoming_letters', function (Blueprint $table) {
            $table->dropForeign(['sekdes_id']);
            $table->dropForeign(['kades_id']);
            $table->dropColumn([
                'sekdes_id',
                'kades_id',
                'sekdes_approved_at',
                'kades_approved_at',
                'submitted_at',
                'rejection_reason',
            ]);
        });
    }
};
