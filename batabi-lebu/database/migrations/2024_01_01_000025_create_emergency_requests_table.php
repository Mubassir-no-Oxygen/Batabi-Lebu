<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * FUTURE FEATURE: Emergency support requests from farmers.
     * (e.g., weather damage, pest infestation, financial aid)
     */
    public function up(): void
    {
        Schema::create('emergency_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('farmers')->onDelete('cascade');
            $table->enum('type', ['weather', 'pest', 'finance', 'other'])->default('other');
            $table->text('description');
            $table->string('location')->nullable();
            $table->enum('status', ['open', 'in_review', 'resolved'])->default('open');
            $table->text('admin_note')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_requests');
    }
};
