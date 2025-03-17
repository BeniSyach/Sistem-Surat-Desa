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
            $table->enum('confidentiality', ['biasa', 'rahasia', 'umum'])->default('biasa')->after('classification_id');
        });

        Schema::table('outgoing_letters', function (Blueprint $table) {
            $table->enum('confidentiality', ['biasa', 'rahasia', 'umum'])->default('biasa')->after('classification_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incoming_letters', function (Blueprint $table) {
            $table->dropColumn('confidentiality');
        });

        Schema::table('outgoing_letters', function (Blueprint $table) {
            $table->dropColumn('confidentiality');
        });
    }
};
