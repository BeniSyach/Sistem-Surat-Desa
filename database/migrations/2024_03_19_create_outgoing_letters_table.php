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
        Schema::create('outgoing_letters', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('letter_number')->nullable(); // Diisi oleh Umum Desa setelah disetujui
            $table->date('letter_date');
            $table->string('subject');
            $table->text('content');
            $table->string('attachment')->nullable();
            
            // Classification & Department
            $table->foreignId('classification_id')->constrained('letter_classifications');
            $table->foreignId('department_id')
                    ->nullable()
                    ->constrained('departments')
                    ->onDelete('set null'); 
            
            // Village Information
            $table->foreignId('village_id')->constrained('villages');
            
            // User Information
            $table->foreignId('created_by')->constrained('users'); // Kasi yang membuat
            $table->foreignId('signer_id')->constrained('users'); // Kades yang menandatangani
            
            // Approval & Processing
            $table->enum('status', [
                'draft',
                'pending_sekdes',
                'approved_sekdes',
                'rejected_sekdes',
                'pending_kades',
                'approved_kades',
                'rejected_kades',
                'pending_process',
                'processed'
            ])->default('draft');
            
            // Tracking Timestamps
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('sekdes_approved_at')->nullable();
            $table->foreignId('sekdes_approved_by')->nullable()->constrained('users');
            $table->text('sekdes_notes')->nullable();
            
            $table->timestamp('kades_approved_at')->nullable();
            $table->foreignId('kades_approved_by')->nullable()->constrained('users');
            $table->text('kades_notes')->nullable();
            
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->binary('qr_code')->nullable();
            
            // Rejection Information
            $table->text('rejection_reason')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outgoing_letters');
    }
}; 